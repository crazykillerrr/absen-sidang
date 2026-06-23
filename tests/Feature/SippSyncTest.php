<?php

namespace Tests\Feature;

use App\Models\JadwalSidang;
use App\Models\Perkara;
use App\Models\RuangSidang;
use App\Models\SinkronisasiLog;
use App\Services\SippSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SippSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_sipp_sync_service_parses_html_correctly(): void
    {
        $mockHtml = '
        <html>
        <body>
            <table id="tablePerkaraAll">
                <tbody>
                    <tr>
                        <td>No</td>
                        <td>Tanggal Sidang</td>
                        <td>Nomor Perkara</td>
                        <td>Sidang Keliling</td>
                        <td>Ruangan</td>
                        <td>Agenda</td>
                        <td>Detil</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Senin, 22 Jun. 2026 <br> 09:30:00</td>
                        <td>120/G/2026/PTUN.BL</td>
                        <td>Tidak</td>
                        <td>Ruang Sidang Utama</td>
                        <td>Pemeriksaan Bukti Surat</td>
                        <td>Link</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Selasa, 23 Juni 2026 <br> 10:15</td>
                        <td>121/G/2026/PTUN.BL</td>
                        <td>Tidak</td>
                        <td>Ruang Sidang Elektronik</td>
                        <td>Pemeriksaan Bukti Saksi</td>
                        <td>Link</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';

        $service = new SippSyncService();
        $count = $service->sync($mockHtml);

        $this->assertEquals(2, $count);

        // Verify Perkara creation
        $this->assertDatabaseHas('perkara', [
            'nomor_perkara' => '120/G/2026/PTUN.BL',
            'tahun' => 2026
        ]);
        $this->assertDatabaseHas('perkara', [
            'nomor_perkara' => '121/G/2026/PTUN.BL',
            'tahun' => 2026
        ]);

        // Verify RuangSidang creation
        $this->assertDatabaseHas('ruang_sidang', [
            'nama_ruang' => 'Ruang Sidang Utama'
        ]);
        $this->assertDatabaseHas('ruang_sidang', [
            'nama_ruang' => 'Ruang Sidang Elektronik',
            'jenis_ruang' => 'Ruang Sidang Elektronik'
        ]);

        // Verify JadwalSidang creation
        $this->assertDatabaseHas('jadwal_sidang', [
            'agenda_sidang' => 'Pemeriksaan Bukti Surat',
            'tanggal_sidang' => '2026-06-22',
            'jam_sidang' => '09:30:00',
            'jenis_sidang' => 'Offline',
            'sumber_data' => 'SIPP'
        ]);
        $this->assertDatabaseHas('jadwal_sidang', [
            'agenda_sidang' => 'Pemeriksaan Bukti Saksi',
            'tanggal_sidang' => '2026-06-23',
            'jam_sidang' => '10:15:00',
            'jenis_sidang' => 'Online',
            'sumber_data' => 'SIPP'
        ]);

        // Verify SinkronisasiLog creation
        $this->assertDatabaseHas('sinkronisasi_log', [
            'jumlah_data' => 2,
            'status' => 'berhasil'
        ]);

        // Test running sync again prevents duplicates
        $count2 = $service->sync($mockHtml);
        $this->assertEquals(2, $count2);

        // Count in DB should still be 2
        $this->assertEquals(2, JadwalSidang::count());
    }

    public function test_sipp_sync_logs_failure_on_exception(): void
    {
        // Fake SIPP URL to return 500 server error
        \Illuminate\Support\Facades\Http::fake([
            'https://sipp.ptun-bandarlampung.go.id/*' => \Illuminate\Support\Facades\Http::response('Error', 500),
        ]);

        $service = new SippSyncService();

        try {
            $service->sync(); // No parameters will trigger HTTP requests
            $this->fail("Expected exception was not thrown.");
        } catch (\Exception $e) {
            // Verify SinkronisasiLog creation with failure status
            $this->assertDatabaseHas('sinkronisasi_log', [
                'jumlah_data' => 0,
                'status' => 'gagal'
            ]);
        }
    }

    public function test_sipp_sync_service_crawls_7_days_correctly(): void
    {
        $mockHtml = '
        <html>
        <body>
            <table id="tablePerkaraAll">
                <tbody>
                    <tr>
                        <td>No</td>
                        <td>Tanggal Sidang</td>
                        <td>Nomor Perkara</td>
                        <td>Sidang Keliling</td>
                        <td>Ruangan</td>
                        <td>Agenda</td>
                        <td>Detil</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Senin, 22 Jun. 2026 <br> 09:30:00</td>
                        <td>120/G/2026/PTUN.BL</td>
                        <td>Tidak</td>
                        <td>Ruang Sidang Utama</td>
                        <td>Pemeriksaan Bukti Surat</td>
                        <td>Link</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';

        // Fake the 7 days HTTP requests
        \Illuminate\Support\Facades\Http::fake([
            'https://sipp.ptun-bandarlampung.go.id/*' => \Illuminate\Support\Facades\Http::response($mockHtml, 200),
        ]);

        $service = new SippSyncService();
        $count = $service->sync(); // This calls the faked HTTP requests 7 times

        $this->assertEquals(7, $count);
        $this->assertEquals(1, JadwalSidang::count());
        
        $this->assertDatabaseHas('sinkronisasi_log', [
            'jumlah_data' => 7,
            'status' => 'berhasil'
        ]);
    }

    public function test_sipp_sync_service_tolerant_to_partial_failures(): void
    {
        $mockHtml = '
        <html>
        <body>
            <table id="tablePerkaraAll">
                <tbody>
                    <tr>
                        <td>No</td>
                        <td>Tanggal Sidang</td>
                        <td>Nomor Perkara</td>
                        <td>Sidang Keliling</td>
                        <td>Ruangan</td>
                        <td>Agenda</td>
                        <td>Detil</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Senin, 22 Jun. 2026 <br> 09:30:00</td>
                        <td>120/G/2026/PTUN.BL</td>
                        <td>Tidak</td>
                        <td>Ruang Sidang Utama</td>
                        <td>Pemeriksaan Bukti Surat</td>
                        <td>Link</td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>';

        // Fake 5 dates to succeed, and 2 dates to fail
        $today = now();
        $urls = [];
        for ($i = 0; $i < 7; $i++) {
            $dateStr = $today->copy()->addDays($i)->format('d/m/Y');
            $urls["https://sipp.ptun-bandarlampung.go.id/list_jadwal_sidang/search/1/{$dateStr}"] = 
                ($i === 1 || $i === 3) 
                    ? \Illuminate\Support\Facades\Http::response('Error', 500) 
                    : \Illuminate\Support\Facades\Http::response($mockHtml, 200);
        }

        \Illuminate\Support\Facades\Http::fake($urls);

        $service = new SippSyncService();
        $count = $service->sync(); // Syncs the next 7 days

        // 5 successful requests of 1 schedule each
        $this->assertEquals(5, $count);
        $this->assertEquals(1, JadwalSidang::count());

        $this->assertDatabaseHas('sinkronisasi_log', [
            'jumlah_data' => 5,
            'status' => 'berhasil'
        ]);

        $log = SinkronisasiLog::orderBy('created_at', 'desc')->first();
        $this->assertStringContainsString('Gagal pada tanggal:', $log->keterangan);
    }
}
