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
        // 1. Hitung jumlah pihak yang wajib hadir pada jadwal sidang tersebut
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

            // Ambil jadwal sidang lengkap beserta relasi perkara, majelis hakim, panitera pengganti, dan ruang sidang
            $jadwal = $this->jadwalRepository->findWith($jadwalSidangId, [
                'perkara.hakims',
                'perkara.paniteraPenggantis',
                'ruangSidang'
            ]);

            $perkara = $jadwal->perkara;
            if (!$perkara) {
                Log::error("AttendanceValidationService: Perkara tidak ditemukan pada Jadwal #{$jadwalSidangId}");
                return false;
            }

            // Ambil seluruh Hakim pada perkara tersebut
            $hakims = $perkara->hakims;
            // Ambil Panitera Pengganti pada perkara tersebut
            $pps = $perkara->paniteraPenggantis;

            // Kumpulkan alamat email
            $emails = [];
            foreach ($hakims as $hakim) {
                if (!empty($hakim->email)) {
                    $emails[] = $hakim->email;
                }
            }
            foreach ($pps as $pp) {
                if (!empty($pp->email)) {
                    $emails[] = $pp->email;
                }
            }

            // Hapus duplikat email jika ada
            $emails = array_unique($emails);

            if (empty($emails)) {
                Log::warning("AttendanceValidationService: Tidak ditemukan alamat email untuk Hakim/PP pada perkara nomor {$perkara->nomor_perkara}");
                return false;
            }

            // Format Waktu Sidang
            $tanggal = $jadwal->tanggal_sidang instanceof Carbon 
                ? $jadwal->tanggal_sidang->format('d-m-Y') 
                : Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');
            
            $waktu = $tanggal . ' Pukul ' . substr($jadwal->jam_sidang, 0, 5) . ' WIB';

            // Kirim notifikasi melalui Laravel Mail
            $statusKirim = false;
            try {
                Mail::to($emails)->send(new NotifikasiSidangMail($jadwal, $perkara, $waktu));
                Log::info("AttendanceValidationService: Notifikasi Email berhasil dikirim ke: " . implode(', ', $emails));
                $statusKirim = true;
            } catch (\Exception $e) {
                Log::error("AttendanceValidationService: Exception saat mengirim Email ke " . implode(', ', $emails) . ". Error: " . $e->getMessage());
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
