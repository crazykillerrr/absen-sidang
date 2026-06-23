<?php

namespace App\Services;

use App\Models\JadwalSidang;
use App\Models\Perkara;
use App\Models\RuangSidang;
use App\Models\SinkronisasiLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class SippSyncService
{
    /**
     * Synchronize schedules from SIPP PTUN Bandar Lampung.
     *
     * @param string|null $html Optional HTML content to parse (useful for testing/offline sync).
     * @return int Number of synchronized schedules.
     * @throws \Exception
     */
    public function sync(?string $html = null): int
    {
        $startTime = now();
        $syncedCount = 0;

        try {
            if ($html !== null) {
                // If HTML content is provided directly, parse it once (e.g. in testing)
                $syncedCount = $this->parseAndStoreHtml($html);
                
                // Write successful log
                SinkronisasiLog::create([
                    'waktu_sinkronisasi' => $startTime,
                    'jumlah_data' => $syncedCount,
                    'status' => 'berhasil',
                    'keterangan' => "Berhasil sinkronisasi {$syncedCount} jadwal sidang dari konten HTML (Testing).",
                ]);
                
                return $syncedCount;
            }

            // Otherwise, fetch schedules for the next 10 days
            $failedDates = [];
            $jar = new \GuzzleHttp\Cookie\CookieJar();
            for ($i = 0; $i < 10; $i++) {
                $dateStr = now()->addDays($i)->format('d/m/Y');
                $url = "https://sipp.ptun-bandarlampung.go.id/list_jadwal_sidang/search/1/{$dateStr}";
                
                try {
                    // Increased timeout to 30 seconds for reliability
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
                    ])->withOptions([
                        'cookies' => $jar
                    ])->timeout(30)->get($url);

                    if (!$response->successful()) {
                        throw new \Exception("Status code: " . $response->status());
                    }

                    $dayHtml = $response->body();
                    $syncedCount += $this->parseAndStoreHtml($dayHtml, $jar, $url);
                } catch (\Exception $dateEx) {
                    Log::warning("SippSyncService: Gagal sinkronisasi tanggal {$dateStr}. Error: " . $dateEx->getMessage());
                    $failedDates[] = $dateStr;
                }
            }

            // If ALL 10 days failed, throw a general exception to log as a failure
            if (count($failedDates) === 10) {
                throw new \Exception("Gagal menghubungi SIPP untuk seluruh 10 hari pencarian.");
            }

            // Log details of success and any failed dates
            $keterangan = "Berhasil sinkronisasi {$syncedCount} jadwal sidang dari SIPP untuk 10 hari ke depan.";
            if (!empty($failedDates)) {
                $keterangan .= " (Gagal pada tanggal: " . implode(', ', $failedDates) . ")";
            }

            // Write successful log
            SinkronisasiLog::create([
                'waktu_sinkronisasi' => $startTime,
                'jumlah_data' => $syncedCount,
                'status' => 'berhasil',
                'keterangan' => $keterangan,
            ]);

            return $syncedCount;

        } catch (\Exception $e) {
            Log::error("SippSyncService Error: " . $e->getMessage());

            // Write failed log
            SinkronisasiLog::create([
                'waktu_sinkronisasi' => $startTime,
                'jumlah_data' => 0,
                'status' => 'gagal',
                'keterangan' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Parse schedule HTML table and store in the database.
     *
     * @param string $html
     * @return int Number of parsed and stored rows.
     */
    public function parseAndStoreHtml(string $html, ?\GuzzleHttp\Cookie\CookieJar $jar = null, ?string $refererUrl = null): int
    {
        if (empty(trim($html))) {
            return 0;
        }

        $crawler = new Crawler($html);
        $table = $crawler->filter('#tablePerkaraAll');

        if ($table->count() === 0) {
            return 0;
        }

        $rows = $table->filter('tbody tr');
        $syncedCount = 0;

        $rows->each(function (Crawler $row, $index) use (&$syncedCount, $jar, $refererUrl) {
            // Skip the header row (typically the first row)
            if ($index === 0) {
                return;
            }

            $cols = $row->filter('td');

            // Skip rows that don't match the expected column count
            if ($cols->count() < 6) {
                return;
            }

            $tanggalRaw = trim($cols->eq(1)->text());
            $nomorPerkara = trim($cols->eq(2)->text());
            $ruangRaw = trim($cols->eq(4)->text());
            $agenda = trim($cols->eq(5)->text());

            // Skip if the row contains "Data Tidak diTemukan"
            if (stripos($tanggalRaw, 'tidak ditemukan') !== false || stripos($nomorPerkara, 'tidak ditemukan') !== false) {
                return;
            }

            if (empty($nomorPerkara) || strpos($nomorPerkara, '/') === false) {
                return;
            }

            // Parse Date & Time
            [$date, $time] = $this->parseSippDate($tanggalRaw);

            // 1. Resolve Perkara (Find or Create)
            $perkara = Perkara::where('nomor_perkara', $nomorPerkara)->first();
            if (!$perkara) {
                $tahun = $this->extractYearFromCaseNumber($nomorPerkara);
                
                $perkara = Perkara::create([
                    'nomor_perkara' => $nomorPerkara,
                    'tahun' => $tahun,
                    'keterangan' => 'Sinkronisasi Otomatis dari SIPP PTUN Bandar Lampung',
                ]);
            }

            // 2. Resolve RuangSidang (Find or Create)
            $ruangSidang = RuangSidang::where('nama_ruang', 'like', $ruangRaw)->first();
            if (!$ruangSidang) {
                $ruangSidang = RuangSidang::create([
                    'nama_ruang' => $ruangRaw,
                    'jenis_ruang' => strpos(strtolower($ruangRaw), 'elektronik') !== false ? 'Ruang Sidang Elektronik' : 'Ruang Sidang Utama',
                ]);
            }

            // Determine jenis sidang (Online if electronic room, else Offline)
            $jenisSidang = strpos(strtolower($ruangRaw), 'elektronik') !== false ? 'Online' : 'Offline';

            // Extract details from details page if available
            $jenisPerkara = null;
            $pihak = null;
            $sidangKeliling = $cols->count() >= 4 ? trim($cols->eq(3)->text()) : 'Tidak';

            if ($jar && $refererUrl && $cols->count() >= 7) {
                $detailLink = $cols->eq(6)->filter('a');
                if ($detailLink->count() > 0) {
                    $onclick = $detailLink->attr('onclick');
                    if (preg_match("/detilSidang\('([^']+)'\)/", $onclick, $matches)) {
                        $idPerkara = $matches[1];
                        $detailUrl = "https://sipp.ptun-bandarlampung.go.id/detil_jadwal_sidang/{$idPerkara}";

                        try {
                            $detailResponse = Http::withHeaders([
                                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                                'X-Requested-With' => 'XMLHttpRequest',
                                'Referer' => $refererUrl
                            ])->withOptions([
                                'cookies' => $jar,
                                'allow_redirects' => true,
                            ])->timeout(10)->get($detailUrl);

                            if ($detailResponse->successful()) {
                                $detailHtml = $detailResponse->body();
                                $detailCrawler = new Crawler($detailHtml);
                                
                                $details = [];
                                $detailCrawler->filter('#infoPerkara tr')->each(function (Crawler $dRow) use (&$details) {
                                    $dCols = $dRow->filter('td');
                                    if ($dCols->count() >= 2) {
                                        $label = trim($dCols->eq(0)->text());
                                        $val = trim($dCols->eq(1)->text());
                                        $details[$label] = $val;
                                    }
                                });

                                $jenisPerkara = $details['Jenis Perkara'] ?? null;
                                $pihak = $details['Pihak'] ?? null;
                                $sidangKeliling = $details['Sidang Keliling'] ?? $sidangKeliling;
                            }
                        } catch (\Exception $ex) {
                            Log::warning("SippSyncService: Gagal sinkronisasi detail jadwal $nomorPerkara. Error: " . $ex->getMessage());
                        }
                    }
                }
            }

            // 3. Save JadwalSidang
            JadwalSidang::updateOrCreate(
                [
                    'perkara_id' => $perkara->id,
                    'tanggal_sidang' => $date,
                    'agenda_sidang' => $agenda,
                ],
                [
                    'ruang_sidang_id' => $ruangSidang->id,
                    'jam_sidang' => $time,
                    'jenis_sidang' => $jenisSidang,
                    'sumber_data' => 'SIPP',
                    'terakhir_sinkron' => now(),
                    'jenis_perkara' => $jenisPerkara,
                    'pihak' => $pihak,
                    'sidang_keliling' => $sidangKeliling,
                ]
            );

            $syncedCount++;
        });

        return $syncedCount;
    }

    /**
     * Parse Indonesian formatted date and time into SQL format.
     *
     * @param string $dateStr Raw date string (e.g. "Senin, 22 Jun. 2026", "22 Juni 2026")
     * @return array [string $date, string $time]
     */
    public function parseSippDate(string $dateStr): array
    {
        $dateStr = str_replace(["\r", "\n", "\t"], ' ', $dateStr);
        $dateStr = preg_replace('/\s+/', ' ', $dateStr);

        // Extract time (e.g., 09:00:00 or 09:00)
        $time = '09:00:00';
        if (preg_match('/(\d{2}[:\.]\d{2}([:\.]\d{2})?)/', $dateStr, $timeMatches)) {
            $timeStr = str_replace('.', ':', $timeMatches[1]);
            if (strlen($timeStr) === 5) {
                $time = $timeStr . ':00';
            } else {
                $time = $timeStr;
            }
            $dateStr = str_replace($timeMatches[1], '', $dateStr);
        }

        // Strip day names
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Jum\'at'];
        foreach ($days as $day) {
            $dateStr = preg_replace('/' . $day . ',\s*/i', '', $dateStr);
            $dateStr = preg_replace('/' . $day . '\s+/i', '', $dateStr);
        }

        $dateStr = trim($dateStr, " ,");

        // Map Indonesian months to numeric format
        $months = [
            'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
            'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
            'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12',
            'jan.' => '01', 'feb.' => '02', 'mar.' => '03', 'apr.' => '04',
            'jun.' => '06', 'jul.' => '07', 'agu.' => '08', 'sep.' => '09',
            'okt.' => '10', 'nov.' => '11', 'des.' => '12',
            'jan' => '01', 'feb' => '02', 'mar' => '03', 'apr' => '04',
            'jun' => '06', 'jul' => '07', 'agu' => '08', 'sep' => '09',
            'okt' => '10', 'nov' => '11', 'des' => '12',
        ];

        $lowerDate = strtolower($dateStr);
        foreach ($months as $mName => $mVal) {
            if (strpos($lowerDate, $mName) !== false) {
                $dateStr = str_ireplace($mName, $mVal, $dateStr);
                break;
            }
        }

        $dateStr = preg_replace('/\s+/', '-', $dateStr);
        $dateStr = str_replace('/', '-', $dateStr);

        try {
            $date = Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            $date = Carbon::today()->format('Y-m-d');
        }

        return [$date, $time];
    }

    /**
     * Extract the year from a case number.
     *
     * @param string $caseNumber (e.g. "120/G/2026/PTUN.JKT")
     * @return int Year of the case.
     */
    private function extractYearFromCaseNumber(string $caseNumber): int
    {
        $parts = explode('/', $caseNumber);
        $tahun = intval(date('Y'));

        foreach ($parts as $part) {
            $part = trim($part);
            if (is_numeric($part) && strlen($part) === 4) {
                $tahun = intval($part);
                break;
            }
        }

        return $tahun;
    }
}
