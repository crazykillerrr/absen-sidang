<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Perkara;
use App\Models\RuangSidang;
use App\Models\JadwalSidang;
use App\Models\PihakSidang;
use App\Models\Kehadiran;
use App\Services\AttendanceValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiSidangMail;
use Tests\TestCase;

class AttendanceRealtimeAndEmailTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $ruang;
    protected $perkara;
    protected $jadwal;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->ruang = RuangSidang::create([
            'nama_ruang' => 'Ruang Sidang Utama',
            'jenis_ruang' => 'Ruang Sidang Utama',
        ]);

        $this->perkara = Perkara::create([
            'nomor_perkara' => '123/G/2026/PTUN.BL',
            'tahun' => 2026,
        ]);

        $this->jadwal = JadwalSidang::create([
            'perkara_id' => $this->perkara->id,
            'ruang_sidang_id' => $this->ruang->id,
            'tanggal_sidang' => '2026-06-25',
            'jam_sidang' => '09:00:00',
            'agenda_sidang' => 'Pemeriksaan Persiapan',
            'jenis_sidang' => 'Terbuka',
            'status_sidang' => 'menunggu',
        ]);
    }

    /**
     * Test real-time attendance data JSON API.
     */
    public function test_get_attendance_data_returns_correct_json(): void
    {
        $pihak = PihakSidang::create([
            'jadwal_sidang_id' => $this->jadwal->id,
            'nama' => 'Budi Santoso',
            'nomor_hp' => '081234567890',
            'status_pihak' => 'Penggugat',
            'email' => 'budi@example.com',
        ]);

        Kehadiran::create([
            'pihak_sidang_id' => $pihak->id,
            'waktu_hadir' => '2026-06-25 09:05:00',
            'status_hadir' => 'hadir',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.pihak-sidang.data', $this->jadwal->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'pihaks' => [
                '*' => ['id', 'nama', 'status_pihak', 'nomor_hp', 'kehadiran', 'kehadiran_time']
            ]
        ]);
        $this->assertEquals('Budi Santoso', $response->json('pihaks.0.nama'));
        $this->assertTrue($response->json('pihaks.0.kehadiran'));
        $this->assertEquals('09:05', $response->json('pihaks.0.kehadiran_time'));
    }

    /**
     * Test real-time dashboard data JSON API.
     */
    public function test_get_dashboard_data_returns_correct_json(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.dashboard.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'totalKehadiranHariIni',
            'totalSidangBerjalan',
            'sidangHariIni',
            'kehadiranTerbaru',
        ]);
    }

    /**
     * Test AttendanceValidationService sends email to all parties (not just saksi/ahli).
     */
    public function test_panggil_sends_email_to_all_checked_in_parties(): void
    {
        Mail::fake();

        // 1. Tambah Penggugat dengan email
        $pihak1 = PihakSidang::create([
            'jadwal_sidang_id' => $this->jadwal->id,
            'nama' => 'Budi Santoso',
            'nomor_hp' => '081234567890',
            'status_pihak' => 'Penggugat',
            'email' => 'budi@example.com',
        ]);

        // 2. Tambah Tergugat dengan email
        $pihak2 = PihakSidang::create([
            'jadwal_sidang_id' => $this->jadwal->id,
            'nama' => 'Kementerian Kehutanan',
            'nomor_hp' => '081234567891',
            'status_pihak' => 'Tergugat',
            'email' => 'kemen@example.com',
        ]);

        // Catat kehadiran keduanya
        Kehadiran::create([
            'pihak_sidang_id' => $pihak1->id,
            'waktu_hadir' => '2026-06-25 09:05:00',
            'status_hadir' => 'hadir',
        ]);

        Kehadiran::create([
            'pihak_sidang_id' => $pihak2->id,
            'waktu_hadir' => '2026-06-25 09:06:00',
            'status_hadir' => 'hadir',
        ]);

        // Kirim post request ke rute panggil
        $response = $this->actingAs($this->user)
            ->post(route('admin.jadwal-sidang.panggil', $this->jadwal->id));

        $response->assertStatus(302);

        // Pastikan email panggilan terkirim ke budi@example.com dan kemen@example.com
        Mail::assertSent(\App\Mail\PanggilanSidangMail::class, function ($mail) {
            return $mail->hasTo('budi@example.com');
        });

        Mail::assertSent(\App\Mail\PanggilanSidangMail::class, function ($mail) {
            return $mail->hasTo('kemen@example.com');
        });

        Mail::assertSent(\App\Mail\PanggilanSidangMail::class, 2);
    }

    /**
     * Test real-time laporan data JSON API.
     */
    public function test_get_laporan_data_returns_correct_json(): void
    {
        $pihak = PihakSidang::create([
            'jadwal_sidang_id' => $this->jadwal->id,
            'nama' => 'Budi Santoso',
            'nomor_hp' => '081234567890',
            'status_pihak' => 'Penggugat',
            'email' => 'budi@example.com',
        ]);

        Kehadiran::create([
            'pihak_sidang_id' => $pihak->id,
            'waktu_hadir' => '2026-06-25 09:05:00',
            'status_hadir' => 'hadir',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.laporan.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'items',
            'has_pages',
            'pagination_links',
        ]);
        $this->assertNotEmpty($response->json('items'));
        $this->assertEquals('Budi Santoso', $response->json('items.0.pihak_nama'));
    }
}
