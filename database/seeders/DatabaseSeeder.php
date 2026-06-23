<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Hakim;
use App\Models\PaniteraPengganti;
use App\Models\RuangSidang;
use App\Models\Perkara;
use App\Models\MajelisHakim;
use App\Models\PenugasanPp;
use App\Models\JadwalSidang;
use App\Models\PihakSidang;
use App\Models\Kehadiran;
use App\Models\QrCode;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Admin
        User::create([
            'name' => 'Administrator PTUN',
            'email' => 'admin@ptun.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Hakim
        $hakims = [
            ['nama' => 'Dr. H. Ahmad Sahuri, S.H., M.H.', 'nomor_whatsapp' => '087788766915', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Maria Ulfah, S.H., M.Hum.', 'nomor_whatsapp' => '087788766915', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Bambang Setiawan, S.H.', 'nomor_whatsapp' => '087788766915', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Riska Amelia, S.H., M.H.', 'nomor_whatsapp' => '087788766915', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Hendra Wijaya, S.H.', 'nomor_whatsapp' => '087788766915', 'email' => 'ddzulfajri@gmail.com'],
        ];
        $hakimModels = [];
        foreach ($hakims as $h) {
            $hakimModels[] = Hakim::create($h);
        }

        // 3. Panitera Pengganti
        $pps = [
            ['nama' => 'Syamsul Bahri, S.H.', 'nomor_whatsapp' => '085234567811', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Fitriani, S.H.', 'nomor_whatsapp' => '085234567812', 'email' => 'ddzulfajri@gmail.com'],
            ['nama' => 'Taufik Hidayat, S.H.', 'nomor_whatsapp' => '085234567813', 'email' => 'ddzulfajri@gmail.com'],
        ];
        $ppModels = [];
        foreach ($pps as $p) {
            $ppModels[] = PaniteraPengganti::create($p);
        }

        // 4. Ruang Sidang
        $ruangs = [
            ['nama_ruang' => 'Ruang Sidang Utama', 'jenis_ruang' => 'Ruang Sidang Utama'],
            ['nama_ruang' => 'Ruang Sidang Elektronik', 'jenis_ruang' => 'Ruang Sidang Elektronik'],
            ['nama_ruang' => 'Ruang Pemeriksaan Persiapan', 'jenis_ruang' => 'Ruang Pemeriksaan Persiapan'],
        ];
        $ruangModels = [];
        foreach ($ruangs as $r) {
            $ruangModels[] = RuangSidang::create($r);
        }

        // 5. Perkara
        $perkaras = [
            ['nomor_perkara' => '120/G/2026/PTUN.JKT', 'tahun' => 2026, 'keterangan' => 'Sengketa pemberhentian tidak dengan hormat PNS Pemerintah Provinsi DKI Jakarta.'],
            ['nomor_perkara' => '121/G/2026/PTUN.JKT', 'tahun' => 2026, 'keterangan' => 'Gugatan pembatalan sertifikat hak milik tanah di Jakarta Barat.'],
            ['nomor_perkara' => '122/G/2026/PTUN.JKT', 'tahun' => 2026, 'keterangan' => 'Sengketa perizinan lingkungan pembangunan apartemen mewah.'],
            ['nomor_perkara' => '123/G/2026/PTUN.JKT', 'tahun' => 2026, 'keterangan' => 'Sengketa pemilihan kepala desa serentak kabupaten Kepulauan Seribu.'],
            ['nomor_perkara' => '124/G/2026/PTUN.JKT', 'tahun' => 2026, 'keterangan' => 'Gugatan keterbukaan informasi publik dokumen tata ruang.'],
        ];
        $perkaraModels = [];
        foreach ($perkaras as $pk) {
            $perkaraModels[] = Perkara::create($pk);
        }

        // 6. Majelis Hakim & PP Assignment
        // Perkara 1: Hakim 1 (Ketua), Hakim 2 (Anggota), Hakim 3 (Anggota), PP 1
        MajelisHakim::create(['perkara_id' => $perkaraModels[0]->id, 'hakim_id' => $hakimModels[0]->id, 'jabatan' => 'Ketua Majelis']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[0]->id, 'hakim_id' => $hakimModels[1]->id, 'jabatan' => 'Hakim Anggota']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[0]->id, 'hakim_id' => $hakimModels[2]->id, 'jabatan' => 'Hakim Anggota']);
        PenugasanPp::create(['perkara_id' => $perkaraModels[0]->id, 'panitera_pengganti_id' => $ppModels[0]->id]);

        // Perkara 2: Hakim 2 (Ketua), Hakim 3 (Anggota), Hakim 4 (Anggota), PP 2
        MajelisHakim::create(['perkara_id' => $perkaraModels[1]->id, 'hakim_id' => $hakimModels[1]->id, 'jabatan' => 'Ketua Majelis']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[1]->id, 'hakim_id' => $hakimModels[2]->id, 'jabatan' => 'Hakim Anggota']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[1]->id, 'hakim_id' => $hakimModels[3]->id, 'jabatan' => 'Hakim Anggota']);
        PenugasanPp::create(['perkara_id' => $perkaraModels[1]->id, 'panitera_pengganti_id' => $ppModels[1]->id]);

        // Perkara 3: Hakim 3 (Ketua), Hakim 4 (Anggota), Hakim 5 (Anggota), PP 3
        MajelisHakim::create(['perkara_id' => $perkaraModels[2]->id, 'hakim_id' => $hakimModels[2]->id, 'jabatan' => 'Ketua Majelis']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[2]->id, 'hakim_id' => $hakimModels[3]->id, 'jabatan' => 'Hakim Anggota']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[2]->id, 'hakim_id' => $hakimModels[4]->id, 'jabatan' => 'Hakim Anggota']);
        PenugasanPp::create(['perkara_id' => $perkaraModels[2]->id, 'panitera_pengganti_id' => $ppModels[2]->id]);

        // Perkara 4: Hakim 4 (Ketua), Hakim 5 (Anggota), Hakim 1 (Anggota), PP 1
        MajelisHakim::create(['perkara_id' => $perkaraModels[3]->id, 'hakim_id' => $hakimModels[3]->id, 'jabatan' => 'Ketua Majelis']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[3]->id, 'hakim_id' => $hakimModels[4]->id, 'jabatan' => 'Hakim Anggota']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[3]->id, 'hakim_id' => $hakimModels[0]->id, 'jabatan' => 'Hakim Anggota']);
        PenugasanPp::create(['perkara_id' => $perkaraModels[3]->id, 'panitera_pengganti_id' => $ppModels[0]->id]);

        // Perkara 5: Hakim 5 (Ketua), Hakim 1 (Anggota), Hakim 2 (Anggota), PP 2
        MajelisHakim::create(['perkara_id' => $perkaraModels[4]->id, 'hakim_id' => $hakimModels[4]->id, 'jabatan' => 'Ketua Majelis']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[4]->id, 'hakim_id' => $hakimModels[0]->id, 'jabatan' => 'Hakim Anggota']);
        MajelisHakim::create(['perkara_id' => $perkaraModels[4]->id, 'hakim_id' => $hakimModels[1]->id, 'jabatan' => 'Hakim Anggota']);
        PenugasanPp::create(['perkara_id' => $perkaraModels[4]->id, 'panitera_pengganti_id' => $ppModels[1]->id]);

        // 7. Jadwal Sidang
        $today = Carbon::today();
        
        // Jadwal Hari Ini
        $jadwal1 = JadwalSidang::create([
            'perkara_id' => $perkaraModels[0]->id,
            'ruang_sidang_id' => $ruangModels[0]->id,
            'agenda_sidang' => 'Pemeriksaan Bukti Saksi',
            'tanggal_sidang' => $today->toDateString(),
            'jam_sidang' => '09:00:00',
            'jenis_sidang' => 'Offline'
        ]);

        $jadwal2 = JadwalSidang::create([
            'perkara_id' => $perkaraModels[1]->id,
            'ruang_sidang_id' => $ruangModels[1]->id,
            'agenda_sidang' => 'Pemeriksaan Bukti Surat',
            'tanggal_sidang' => $today->toDateString(),
            'jam_sidang' => '10:30:00',
            'jenis_sidang' => 'Online'
        ]);

        $jadwal3 = JadwalSidang::create([
            'perkara_id' => $perkaraModels[2]->id,
            'ruang_sidang_id' => $ruangModels[2]->id,
            'agenda_sidang' => 'Pemeriksaan Persiapan',
            'tanggal_sidang' => $today->toDateString(),
            'jam_sidang' => '13:00:00',
            'jenis_sidang' => 'Offline'
        ]);

        // Jadwal Besok
        $jadwal4 = JadwalSidang::create([
            'perkara_id' => $perkaraModels[3]->id,
            'ruang_sidang_id' => $ruangModels[0]->id,
            'agenda_sidang' => 'Dismissal',
            'tanggal_sidang' => Carbon::tomorrow()->toDateString(),
            'jam_sidang' => '09:00:00',
            'jenis_sidang' => 'Offline'
        ]);

        // Jadwal Kemarin
        $jadwal5 = JadwalSidang::create([
            'perkara_id' => $perkaraModels[4]->id,
            'ruang_sidang_id' => $ruangModels[0]->id,
            'agenda_sidang' => 'Pemeriksaan Bukti Ahli',
            'tanggal_sidang' => Carbon::yesterday()->toDateString(),
            'jam_sidang' => '10:00:00',
            'jenis_sidang' => 'Offline'
        ]);

        // 8. Pihak Sidang
        // Pihak Jadwal 1 (Hari ini, Perkara 1) - belum absen
        $pihak1_1 = PihakSidang::create(['jadwal_sidang_id' => $jadwal1->id, 'nama' => 'Supriyanto (Penggugat)', 'nomor_hp' => '081299990001', 'status_pihak' => 'Penggugat']);
        $pihak1_2 = PihakSidang::create(['jadwal_sidang_id' => $jadwal1->id, 'nama' => 'Kepala BKD Provinsi DKI Jakarta', 'nomor_hp' => '081299990002', 'status_pihak' => 'Tergugat']);
        $pihak1_3 = PihakSidang::create(['jadwal_sidang_id' => $jadwal1->id, 'nama' => 'Drs. H. Mulyono (Saksi)', 'nomor_hp' => '081299990003', 'status_pihak' => 'Saksi Penggugat']);

        // Pihak Jadwal 2 (Hari ini, Perkara 2)
        $pihak2_1 = PihakSidang::create(['jadwal_sidang_id' => $jadwal2->id, 'nama' => 'PT. Tanah Makmur (Penggugat)', 'nomor_hp' => '081299990004', 'status_pihak' => 'Penggugat']);
        $pihak2_2 = PihakSidang::create(['jadwal_sidang_id' => $jadwal2->id, 'nama' => 'Kepala Kantor Pertanahan Jakbar', 'nomor_hp' => '081299990005', 'status_pihak' => 'Tergugat']);

        // Pihak Jadwal 3 (Hari ini, Perkara 3)
        $pihak3_1 = PihakSidang::create(['jadwal_sidang_id' => $jadwal3->id, 'nama' => 'Wahana Lingkungan Hidup (Penggugat)', 'nomor_hp' => '081299990006', 'status_pihak' => 'Penggugat']);
        $pihak3_2 = PihakSidang::create(['jadwal_sidang_id' => $jadwal3->id, 'nama' => 'Gubernur DKI Jakarta (Tergugat)', 'nomor_hp' => '081299990007', 'status_pihak' => 'Tergugat']);

        // Pihak Jadwal 5 (Kemarin, Perkara 5) - Sudah hadir semua
        $pihak5_1 = PihakSidang::create(['jadwal_sidang_id' => $jadwal5->id, 'nama' => 'H. Sunarto (Penggugat)', 'nomor_hp' => '081299990008', 'status_pihak' => 'Penggugat']);
        $pihak5_2 = PihakSidang::create(['jadwal_sidang_id' => $jadwal5->id, 'nama' => 'Kepala Desa Sukamaju (Tergugat)', 'nomor_hp' => '081299990009', 'status_pihak' => 'Tergugat']);

        // 9. Kehadiran
        // Catat kehadiran Jadwal 5 (kemarin) yang sudah lengkap
        Kehadiran::create(['pihak_sidang_id' => $pihak5_1->id, 'waktu_hadir' => Carbon::yesterday()->setTime(9, 45, 0), 'status_hadir' => 'hadir']);
        Kehadiran::create(['pihak_sidang_id' => $pihak5_2->id, 'waktu_hadir' => Carbon::yesterday()->setTime(9, 55, 0), 'status_hadir' => 'hadir']);

        // 10. QR Codes
        QrCode::create(['kode' => 'QR-SATPAM', 'lokasi' => 'Pos Satpam']);
        QrCode::create(['kode' => 'QR-TUNGGU', 'lokasi' => 'Ruang Tunggu']);

        // 11. Notifikasi
        // Log Notifikasi terkirim untuk Jadwal 5 (Kemarin) karena sudah hadir lengkap
        Notifikasi::create([
            'jadwal_sidang_id' => $jadwal5->id,
            'jenis' => 'Email',
            'status_kirim' => 'terkirim',
            'waktu_kirim' => Carbon::yesterday()->setTime(9, 55, 5)
        ]);
    }
}
