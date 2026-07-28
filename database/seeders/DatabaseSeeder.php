<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\RuangSidang;
use App\Models\Perkara;
use App\Models\JadwalSidang;
use App\Models\QrCode;
use App\Models\Notifikasi;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database without wiping existing data.
     */
    public function run(): void
    {
        // 1. User Admin
        User::firstOrCreate(
            ['email' => 'admin@ptun.go.id'],
            [
                'name' => 'Administrator PTUN',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Ruang Sidang
        $ruangs = [
            ['nama_ruang' => 'Ruang Sidang Utama', 'jenis_ruang' => 'Ruang Sidang Utama'],
            ['nama_ruang' => 'Ruang Sidang Elektronik', 'jenis_ruang' => 'Ruang Sidang Elektronik'],
            ['nama_ruang' => 'Ruang Pemeriksaan Persiapan', 'jenis_ruang' => 'Ruang Pemeriksaan Persiapan'],
        ];
        $ruangModels = [];
        foreach ($ruangs as $r) {
            $ruangModels[] = RuangSidang::firstOrCreate(['nama_ruang' => $r['nama_ruang']], $r);
        }

        // 3. Perkara
        $perkaras = [
            ['nomor_perkara' => '120/G/2026/PTUN.BDL', 'tahun' => 2026, 'keterangan' => 'Sengketa pemberhentian tidak dengan hormat PNS Pemerintah Provinsi DKI Jakarta.'],
            ['nomor_perkara' => '121/G/2026/PTUN.BDL', 'tahun' => 2026, 'keterangan' => 'Gugatan pembatalan sertifikat hak milik tanah di Jakarta Barat.'],
            ['nomor_perkara' => '122/G/2026/PTUN.BDL', 'tahun' => 2026, 'keterangan' => 'Sengketa perizinan lingkungan pembangunan apartemen mewah.'],
            ['nomor_perkara' => '123/G/2026/PTUN.BDL', 'tahun' => 2026, 'keterangan' => 'Sengketa pemilihan kepala desa serentak kabupaten Kepulauan Seribu.'],
            ['nomor_perkara' => '124/G/2026/PTUN.BDL', 'tahun' => 2026, 'keterangan' => 'Gugatan keterbukaan informasi publik dokumen tata ruang.'],
        ];
        $perkaraModels = [];
        foreach ($perkaras as $pk) {
            $perkaraModels[] = Perkara::firstOrCreate(['nomor_perkara' => $pk['nomor_perkara']], $pk);
        }

        // 4. Jadwal Sidang
        $today = Carbon::today();
        
        // Jadwal Hari Ini
        $jadwal1 = JadwalSidang::firstOrCreate(
            [
                'perkara_id' => $perkaraModels[0]->id,
                'agenda_sidang' => 'Pemeriksaan Bukti Saksi',
            ],
            [
                'ruang_sidang_id' => $ruangModels[0]->id,
                'tanggal_sidang' => $today->toDateString(),
                'jam_sidang' => '09:00:00',
                'jenis_sidang' => 'Offline'
            ]
        );

        $jadwal2 = JadwalSidang::firstOrCreate(
            [
                'perkara_id' => $perkaraModels[1]->id,
                'agenda_sidang' => 'Pemeriksaan Bukti Surat',
            ],
            [
                'ruang_sidang_id' => $ruangModels[1]->id,
                'tanggal_sidang' => $today->toDateString(),
                'jam_sidang' => '10:30:00',
                'jenis_sidang' => 'Online'
            ]
        );

        $jadwal3 = JadwalSidang::firstOrCreate(
            [
                'perkara_id' => $perkaraModels[2]->id,
                'agenda_sidang' => 'Pemeriksaan Persiapan',
            ],
            [
                'ruang_sidang_id' => $ruangModels[2]->id,
                'tanggal_sidang' => $today->toDateString(),
                'jam_sidang' => '13:00:00',
                'jenis_sidang' => 'Offline'
            ]
        );

        // Jadwal Besok
        $jadwal4 = JadwalSidang::firstOrCreate(
            [
                'perkara_id' => $perkaraModels[3]->id,
                'agenda_sidang' => 'Dismissal',
            ],
            [
                'ruang_sidang_id' => $ruangModels[0]->id,
                'tanggal_sidang' => Carbon::tomorrow()->toDateString(),
                'jam_sidang' => '09:00:00',
                'jenis_sidang' => 'Offline'
            ]
        );

        // Jadwal Kemarin
        $jadwal5 = JadwalSidang::firstOrCreate(
            [
                'perkara_id' => $perkaraModels[4]->id,
                'agenda_sidang' => 'Pemeriksaan Bukti Ahli',
            ],
            [
                'ruang_sidang_id' => $ruangModels[0]->id,
                'tanggal_sidang' => Carbon::yesterday()->toDateString(),
                'jam_sidang' => '10:00:00',
                'jenis_sidang' => 'Offline'
            ]
        );

        // 5. QR Codes
        QrCode::firstOrCreate(['kode' => 'QR-SATPAM'], ['lokasi' => 'Pos Satpam']);
        QrCode::firstOrCreate(['kode' => 'QR-TUNGGU'], ['lokasi' => 'Ruang Tunggu']);

        // 6. Notifikasi
        Notifikasi::firstOrCreate(
            [
                'jadwal_sidang_id' => $jadwal5->id,
                'jenis' => 'Email',
            ],
            [
                'status_kirim' => 'terkirim',
                'waktu_kirim' => Carbon::yesterday()->setTime(9, 55, 5)
            ]
        );
    }
}
