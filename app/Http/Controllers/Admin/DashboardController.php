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

        // 4. Total Sidang Lengkap Hari Ini
        $todaySchedules = JadwalSidang::whereDate('tanggal_sidang', $today)
            ->withCount([
                'pihakSidangs',
                'pihakSidangs as pihak_hadir_count' => function ($q) {
                    $q->whereHas('kehadiran');
                }
            ])->get();

        $totalSidangLengkap = $todaySchedules->filter(function ($j) {
            return $j->pihak_sidangs_count > 0 && $j->pihak_hadir_count === $j->pihak_sidangs_count;
        })->count();

        // 5. Total Notifikasi Terkirim
        $totalNotifikasiTerkirim = Notifikasi::where('status_kirim', 'terkirim')->count();

        // --- Data Grafik 1: Kehadiran per Hari (7 Hari Terakhir) ---
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = Carbon::today()->subDays($i)->toDateString();
        }

        $kehadiranPerHariData = [];
        $kehadiranPerHariLabels = [];
        foreach ($last7Days as $date) {
            $count = Kehadiran::whereDate('waktu_hadir', $date)->count();
            $kehadiranPerHariData[] = $count;
            $kehadiranPerHariLabels[] = Carbon::parse($date)->translatedFormat('d M');
        }

        // --- Data Grafik 2: Kehadiran per Agenda Sidang ---
        $agendaData = DB::table('kehadiran')
            ->join('pihak_sidang', 'kehadiran.pihak_sidang_id', '=', 'pihak_sidang.id')
            ->join('jadwal_sidang', 'pihak_sidang.jadwal_sidang_id', '=', 'jadwal_sidang.id')
            ->select('jadwal_sidang.agenda_sidang', DB::raw('count(kehadiran.id) as total'))
            ->groupBy('jadwal_sidang.agenda_sidang')
            ->get();

        $agendaLabels = $agendaData->pluck('agenda_sidang')->toArray();
        $agendaTotals = $agendaData->pluck('total')->toArray();

        // --- Tabel Sidang Hari Ini ---
        $sidangHariIni = $this->jadwalService->getTodaySchedules();

        return view('admin.dashboard', compact(
            'totalPerkara',
            'totalJadwalHariIni',
            'totalKehadiranHariIni',
            'totalSidangLengkap',
            'totalNotifikasiTerkirim',
            'kehadiranPerHariLabels',
            'kehadiranPerHariData',
            'agendaLabels',
            'agendaTotals',
            'sidangHariIni'
        ));
    }
}
