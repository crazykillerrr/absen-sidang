<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Perkara;
use App\Exports\KehadiranExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Dapatkan query kehadiran dengan filter yang diterapkan
     */
    private function getFilterQuery(Request $request)
    {
        $query = Kehadiran::with([
            'pihakSidang.jadwalSidang.perkara',
            'pihakSidang.jadwalSidang.ruangSidang'
        ]);

        $query->whereHas('pihakSidang.jadwalSidang', function ($q) use ($request) {
            if ($request->filled('tanggal_awal')) {
                $q->whereDate('tanggal_sidang', '>=', $request->input('tanggal_awal'));
            }
            if ($request->filled('tanggal_akhir')) {
                $q->whereDate('tanggal_sidang', '<=', $request->input('tanggal_akhir'));
            }
            if ($request->filled('perkara_id')) {
                $q->where('perkara_id', $request->input('perkara_id'));
            }
            if ($request->filled('agenda_sidang')) {
                $q->where('agenda_sidang', $request->input('agenda_sidang'));
            }
        });

        return $query;
    }

    public function index(Request $request)
    {
        $perkaras = Perkara::orderBy('nomor_perkara', 'asc')->get();
        
        $agendas = [
            'Dismissal',
            'Pemeriksaan Persiapan',
            'Pemeriksaan Bukti Surat',
            'Pemeriksaan Bukti Saksi',
            'Pemeriksaan Bukti Ahli',
            'Eksekusi'
        ];

        $query = $this->getFilterQuery($request);
        $kehadirans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact('perkaras', 'agendas', 'kehadirans'));
    }

    /**
     * Export Laporan Kehadiran ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = $this->getFilterQuery($request);
        $kehadirans = $query->orderBy('created_at', 'asc')->get();
        
        // Membuka file PDF dengan layout landscape agar muat kolom lebar
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('kehadirans'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Kehadiran_Sidang_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export Laporan Kehadiran ke Excel
     */
    public function exportExcel(Request $request)
    {
        $query = $this->getFilterQuery($request);
        return Excel::download(new KehadiranExport($query), 'Laporan_Kehadiran_Sidang_' . date('Ymd_His') . '.xlsx');
    }
}
