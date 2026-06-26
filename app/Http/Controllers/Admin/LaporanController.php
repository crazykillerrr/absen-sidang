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
        $query = Kehadiran::select('kehadiran.*')
            ->join('pihak_sidang', 'kehadiran.pihak_sidang_id', '=', 'pihak_sidang.id')
            ->join('jadwal_sidang', 'pihak_sidang.jadwal_sidang_id', '=', 'jadwal_sidang.id')
            ->join('perkara', 'jadwal_sidang.perkara_id', '=', 'perkara.id')
            ->whereNull('pihak_sidang.deleted_at')
            ->whereNull('jadwal_sidang.deleted_at')
            ->whereNull('perkara.deleted_at')
            ->with([
                'pihakSidang.jadwalSidang.perkara',
                'pihakSidang.jadwalSidang.ruangSidang'
            ]);

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('jadwal_sidang.tanggal_sidang', '>=', $request->input('tanggal_awal'));
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('jadwal_sidang.tanggal_sidang', '<=', $request->input('tanggal_akhir'));
        }
        if ($request->filled('perkara_id')) {
            $query->where('jadwal_sidang.perkara_id', $request->input('perkara_id'));
        }
        if ($request->filled('agenda_sidang')) {
            $query->where('jadwal_sidang.agenda_sidang', $request->input('agenda_sidang'));
        }

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
        $kehadirans = $query->orderBy('perkara.nomor_perkara', 'asc')
            ->orderBy('kehadiran.waktu_hadir', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.laporan.index', compact('perkaras', 'agendas', 'kehadirans'));
    }

    /**
     * Export Laporan Kehadiran ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = $this->getFilterQuery($request);
        $kehadirans = $query->orderBy('perkara.nomor_perkara', 'asc')
            ->orderBy('kehadiran.waktu_hadir', 'asc')
            ->get();
        
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
        $query = $this->getFilterQuery($request)
            ->orderBy('perkara.nomor_perkara', 'asc')
            ->orderBy('kehadiran.waktu_hadir', 'asc');
        return Excel::download(new KehadiranExport($query), 'Laporan_Kehadiran_Sidang_' . date('Ymd_His') . '.xlsx');
    }

    public function getLaporanData(Request $request)
    {
        $query = $this->getFilterQuery($request);
        $kehadirans = $query->orderBy('perkara.nomor_perkara', 'asc')
            ->orderBy('kehadiran.waktu_hadir', 'desc')
            ->paginate(15);

        $items = collect($kehadirans->items())->map(function ($kehadiran, $index) use ($kehadirans) {
            $pihak = $kehadiran->pihakSidang;
            $jadwal = $pihak ? $pihak->jadwalSidang : null;
            $perkara = $jadwal ? $jadwal->perkara : null;

            $tanggal = $jadwal ? ($jadwal->tanggal_sidang instanceof \Carbon\Carbon 
                ? $jadwal->tanggal_sidang->format('d-m-Y') 
                : \Carbon\Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y')) : '-';

            return [
                'no' => $kehadirans->firstItem() + $index,
                'nomor_perkara' => $perkara ? $perkara->nomor_perkara : '-',
                'agenda_sidang' => $jadwal ? $jadwal->agenda_sidang : '-',
                'tanggal_sidang' => $tanggal,
                'jam_sidang' => $jadwal ? substr($jadwal->jam_sidang, 0, 5) . ' WIB' : '-',
                'pihak_nama' => $pihak ? $pihak->nama : '-',
                'pihak_status' => $pihak ? $pihak->status_pihak : '-',
                'pihak_nomor_hp' => $pihak ? $pihak->nomor_hp : '-',
                'waktu_hadir' => $kehadiran->waktu_hadir->format('H:i') . ' WIB',
                'status_hadir' => $kehadiran->status_hadir,
            ];
        });

        return response()->json([
            'items' => $items,
            'has_pages' => $kehadirans->hasPages(),
            'pagination_links' => (string) $kehadirans->links(),
        ]);
    }
}
