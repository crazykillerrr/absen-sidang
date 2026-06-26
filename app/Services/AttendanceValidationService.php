<?php

namespace App\Services;

use App\Repositories\Contracts\JadwalSidangRepositoryInterface;
use App\Repositories\Contracts\NotifikasiRepositoryInterface;
use App\Models\PihakSidang;
use App\Models\Notifikasi;
use App\Mail\NotifikasiSidangMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceValidationService
{
    protected $jadwalRepository;
    protected $notifikasiRepository;

    public function __construct(
        JadwalSidangRepositoryInterface $jadwalRepository,
        NotifikasiRepositoryInterface $notifikasiRepository
    ) {
        $this->jadwalRepository = $jadwalRepository;
        $this->notifikasiRepository = $notifikasiRepository;
    }

    /**
     * Validasi kehadiran pihak sidang, jika lengkap kirim notifikasi Email ke Majelis Hakim & PP
     *
     * @param int $jadwalSidangId
     * @return bool Status pengiriman notifikasi
     */
    public function validateAndNotify(int $jadwalSidangId): bool
    {
        return false; // Dinonaktifkan karena tidak ada pihak terdaftar (absen mandiri murni)
        $totalWajibHadir = PihakSidang::where('jadwal_sidang_id', $jadwalSidangId)->count();
        if ($totalWajibHadir === 0) {
            Log::warning("AttendanceValidationService: Jadwal #{$jadwalSidangId} tidak memiliki pihak sidang wajib hadir.");
            return false;
        }

        // 2. Hitung jumlah pihak yang sudah hadir (punya catatan di tabel kehadiran)
        $totalSudahHadir = PihakSidang::where('jadwal_sidang_id', $jadwalSidangId)
            ->whereHas('kehadiran')
            ->count();

        Log::info("AttendanceValidationService: Validasi Jadwal #{$jadwalSidangId}. Wajib: {$totalWajibHadir}, Hadir: {$totalSudahHadir}");

        // 3. Bandingkan hasilnya
        if ($totalSudahHadir === $totalWajibHadir) {
            // Notifikasi hanya boleh dikirim satu kali untuk setiap jadwal sidang
            $sudahTerkirim = Notifikasi::where('jadwal_sidang_id', $jadwalSidangId)
                ->where('jenis', 'Email')
                ->where('status_kirim', 'terkirim')
                ->exists();

            if ($sudahTerkirim) {
                Log::info("AttendanceValidationService: Notifikasi untuk Jadwal #{$jadwalSidangId} sudah pernah dikirim sebelumnya. Skip kirim ulang.");
                return true;
            }

            // Ambil jadwal sidang lengkap beserta relasi perkara dan ruang sidang
            $jadwal = $this->jadwalRepository->findWith($jadwalSidangId, [
                'perkara',
                'ruangSidang'
            ]);

            $perkara = $jadwal->perkara;
            if (!$perkara) {
                Log::error("AttendanceValidationService: Perkara tidak ditemukan pada Jadwal #{$jadwalSidangId}");
                return false;
            }

            // Ambil seluruh pihak sidang yang terdaftar dan memiliki email
            $pihaks = PihakSidang::where('jadwal_sidang_id', $jadwalSidangId)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();

            $emails = $pihaks->pluck('email')->unique()->toArray();

            if (empty($emails)) {
                Log::warning("AttendanceValidationService: Tidak ditemukan alamat email pihak untuk persidangan nomor {$perkara->nomor_perkara}");
                return false;
            }

            // Format Waktu Sidang
            $tanggal = $jadwal->tanggal_sidang instanceof Carbon 
                ? $jadwal->tanggal_sidang->format('d-m-Y') 
                : Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');
            
            $waktu = $tanggal . ' Pukul ' . substr($jadwal->jam_sidang, 0, 5) . ' WIB';

            // Kirim notifikasi melalui Laravel Mail secara individual
            $statusKirim = false;
            $emailSukses = [];
            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new NotifikasiSidangMail($jadwal, $perkara, $waktu));
                    Log::info("AttendanceValidationService: Notifikasi Email berhasil dikirim ke: " . $email);
                    $emailSukses[] = $email;
                } catch (\Exception $e) {
                    Log::error("AttendanceValidationService: Exception saat mengirim Email ke " . $email . ". Error: " . $e->getMessage());
                }
            }

            if (count($emailSukses) > 0) {
                $statusKirim = true;
                Log::info("AttendanceValidationService: Selesai mengirim email. Berhasil ke: " . implode(', ', $emailSukses));
            }

            // Simpan log notifikasi
            $this->notifikasiRepository->create([
                'jadwal_sidang_id' => $jadwalSidangId,
                'jenis' => 'Email',
                'status_kirim' => $statusKirim ? 'terkirim' : 'gagal',
                'waktu_kirim' => Carbon::now()
            ]);

            return $statusKirim;
        }

        return false;
    }
}
