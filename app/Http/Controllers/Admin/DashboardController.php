<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\JadwalSidang;
use App\Models\Kehadiran;
use App\Models\Notifikasi;
use App\Services\JadwalSidangService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $jadwalService;

    public function __construct(JadwalSidangService $jadwalService)
    {
        $this->jadwalService = $jadwalService;
    }

    public function index()
    {
        $today = Carbon::today();

        // 1. Total Perkara
        $totalPerkara = Perkara::count();

        // 2. Total Jadwal Sidang Hari Ini
        $totalJadwalHariIni = JadwalSidang::whereDate('tanggal_sidang', $today)->count();

        // 3. Total Kehadiran Hari Ini
        $totalKehadiranHariIni = Kehadiran::whereDate('waktu_hadir', $today)->count();

        // 4. Total Sidang Berjalan Hari Ini (yang sudah diisi kehadiran pihak)
        $todaySchedules = JadwalSidang::whereDate('tanggal_sidang', $today)
            ->withCount('pihakSidangs')->get();

        $totalSidangBerjalan = $todaySchedules->filter(function ($j) {
            return $j->pihak_sidangs_count > 0;
        })->count();

        // 5. Total Notifikasi Terkirim
        $totalNotifikasiTerkirim = Notifikasi::where('status_kirim', 'terkirim')->count();

        // --- Tabel Sidang Hari Ini ---
        $sidangHariIni = $this->jadwalService->getTodaySchedules();

        // --- Daftar Kehadiran Terbaru ---
        $kehadiranTerbaru = Kehadiran::with([
                'pihakSidang.jadwalSidang.perkara',
                'pihakSidang.jadwalSidang.ruangSidang'
            ])
            ->orderBy('waktu_hadir', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPerkara',
            'totalJadwalHariIni',
            'totalKehadiranHariIni',
            'totalSidangBerjalan',
            'totalNotifikasiTerkirim',
            'sidangHariIni',
            'kehadiranTerbaru'
        ));
    }

    public function getDashboardData()
    {
        $today = Carbon::today();

        // 1. Total Kehadiran Hari Ini
        $totalKehadiranHariIni = Kehadiran::whereDate('waktu_hadir', $today)->count();

        // 2. Total Sidang Berjalan Hari Ini (yang sudah diisi kehadiran pihak)
        $todaySchedules = JadwalSidang::whereDate('tanggal_sidang', $today)
            ->withCount('pihakSidangs')->get();

        $totalSidangBerjalan = $todaySchedules->filter(function ($j) {
            return $j->pihak_sidangs_count > 0;
        })->count();

        // 3. Tabel Sidang Hari Ini
        $sidangHariIni = $this->jadwalService->getTodaySchedules()->map(function ($sidang) {
            $totalHadir = $sidang->pihakSidangs->count();
            
            return [
                'id' => $sidang->id,
                'jam_sidang' => substr($sidang->jam_sidang, 0, 5),
                'jenis_sidang' => $sidang->jenis_sidang,
                'perkara_id' => $sidang->perkara_id,
                'nomor_perkara' => $sidang->perkara->nomor_perkara,
                'agenda_sidang' => $sidang->agenda_sidang,
                'ruang_sidang' => $sidang->ruangSidang->nama_ruang,
                'total_hadir' => $totalHadir,
                'perkara_show_route' => route('admin.perkara.show', $sidang->perkara_id),
                'pihak_sidang_route' => route('admin.pihak-sidang.index', $sidang->id),
                'panggil_route' => route('admin.jadwal-sidang.panggil', $sidang->id)
            ];
        });

        // 4. Daftar Kehadiran Terbaru
        $kehadiranTerbaru = Kehadiran::with([
                'pihakSidang.jadwalSidang.perkara',
                'pihakSidang.jadwalSidang.ruangSidang'
            ])
            ->orderBy('waktu_hadir', 'desc')
            ->take(5)
            ->get()
            ->map(function ($k) {
                $pihak = $k->pihakSidang;
                $jadwal = $pihak ? $pihak->jadwalSidang : null;
                $perkara = $jadwal ? $jadwal->perkara : null;
                $ruang = $jadwal ? $jadwal->ruangSidang : null;

                return [
                    'waktu_hadir' => $k->waktu_hadir->format('H:i') . ' WIB',
                    'tanggal_hadir' => $k->waktu_hadir->translatedFormat('d-m-Y'),
                    'pihak_nama' => $pihak ? $pihak->nama : '-',
                    'pihak_status' => $pihak ? $pihak->status_pihak : '-',
                    'nomor_perkara' => $perkara ? $perkara->nomor_perkara : '-',
                    'perkara_show_route' => $perkara ? route('admin.perkara.show', $perkara->id) : '#',
                    'agenda_sidang' => $jadwal ? $jadwal->agenda_sidang : '-',
                    'ruang_sidang' => $ruang ? $ruang->nama_ruang : '-',
                    'status_hadir' => $k->status_hadir,
                ];
            });

        return response()->json([
            'totalKehadiranHariIni' => $totalKehadiranHariIni,
            'totalSidangBerjalan' => $totalSidangBerjalan,
            'sidangHariIni' => $sidangHariIni,
            'kehadiranTerbaru' => $kehadiranTerbaru
        ]);
    }
}
