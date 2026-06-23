<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalSidang;
use App\Models\SinkronisasiLog;
use App\Services\SippSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SippController extends Controller
{
    /**
     * Display the SIPP integration dashboard.
     */
    public function index(Request $request): View
    {
        $lastLog = SinkronisasiLog::orderBy('waktu_sinkronisasi', 'desc')->first();
        $totalSchedules = JadwalSidang::where('sumber_data', 'SIPP')->count();
        
        // Paginate SIPP-sourced schedules
        $schedules = JadwalSidang::where('sumber_data', 'SIPP')
            ->with(['perkara', 'ruangSidang'])
            ->orderBy('tanggal_sidang', 'desc')
            ->orderBy('jam_sidang', 'asc')
            ->paginate(10, ['*'], 'schedules_page');

        // Paginate sync logs
        $logs = SinkronisasiLog::orderBy('waktu_sinkronisasi', 'desc')
            ->paginate(10, ['*'], 'logs_page');

        return view('admin.integrasi_sipp.index', compact('lastLog', 'totalSchedules', 'schedules', 'logs'));
    }

    /**
     * Trigger manual synchronization.
     */
    public function syncNow(SippSyncService $service): RedirectResponse
    {
        try {
            $count = $service->sync();
            return redirect()
                ->route('admin.integrasi-sipp.index')
                ->with('success', "Sinkronisasi berhasil! Menambahkan/memperbarui {$count} jadwal sidang.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.integrasi-sipp.index')
                ->with('error', "Gagal melakukan sinkronisasi: " . $e->getMessage());
        }
    }
}
