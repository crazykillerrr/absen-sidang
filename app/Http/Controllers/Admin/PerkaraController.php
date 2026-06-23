<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PerkaraService;
use App\Models\Hakim;
use App\Models\PaniteraPengganti;
use Illuminate\Http\Request;

class PerkaraController extends Controller
{
    protected $perkaraService;

    public function __construct(PerkaraService $perkaraService)
    {
        $this->perkaraService = $perkaraService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Perkara::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Optimasi query dengan Eager Loading
        $perkaras = $query->with(['hakims', 'paniteraPenggantis'])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.perkara.index', compact('perkaras'));
    }

    public function create()
    {
        $hakims = Hakim::orderBy('nama', 'asc')->get();
        $pps = PaniteraPengganti::orderBy('nama', 'asc')->get();
        return view('admin.perkara.create', compact('hakims', 'pps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_perkara' => 'required|string|max:255|unique:perkara,nomor_perkara',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string',
            'ketua_majelis' => 'required|exists:hakim,id',
            'hakim_anggota' => 'required|array|min:1',
            'hakim_anggota.*' => 'exists:hakim,id',
            'panitera_pengganti' => 'required|exists:panitera_pengganti,id',
        ], [
            'nomor_perkara.required' => 'Nomor Perkara wajib diisi.',
            'nomor_perkara.unique' => 'Nomor Perkara sudah terdaftar.',
            'tahun.required' => 'Tahun wajib diisi.',
            'ketua_majelis.required' => 'Ketua Majelis wajib dipilih.',
            'hakim_anggota.required' => 'Hakim Anggota wajib dipilih minimal 1 orang.',
            'panitera_pengganti.required' => 'Panitera Pengganti wajib dipilih.',
        ]);

        $perkaraData = [
            'nomor_perkara' => $validated['nomor_perkara'],
            'tahun' => $validated['tahun'],
            'keterangan' => $validated['keterangan'] ?? '',
        ];

        $this->perkaraService->createPerkara(
            $perkaraData,
            $validated['ketua_majelis'],
            $validated['hakim_anggota'],
            $validated['panitera_pengganti']
        );

        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil ditambahkan beserta Majelis Hakim & PP.');
    }

    public function show(int $id)
    {
        $perkara = $this->perkaraService->findWith($id, [
            'hakims',
            'paniteraPenggantis',
            'jadwalSidangs.ruangSidang'
        ]);

        return view('admin.perkara.show', compact('perkara'));
    }

    public function edit(int $id)
    {
        $perkara = $this->perkaraService->findWith($id, ['hakims', 'paniteraPenggantis']);
        $hakims = Hakim::orderBy('nama', 'asc')->get();
        $pps = PaniteraPengganti::orderBy('nama', 'asc')->get();

        // Cari ID Ketua Majelis dan Hakim Anggota
        $ketuaMajelisId = null;
        $hakimAnggotaIds = [];
        foreach ($perkara->hakims as $hakim) {
            if ($hakim->pivot->jabatan === 'Ketua Majelis') {
                $ketuaMajelisId = $hakim->id;
            } else {
                $hakimAnggotaIds[] = $hakim->id;
            }
        }

        $currentPpId = $perkara->paniteraPenggantis->first() ? $perkara->paniteraPenggantis->first()->id : null;

        return view('admin.perkara.edit', compact('perkara', 'hakims', 'pps', 'ketuaMajelisId', 'hakimAnggotaIds', 'currentPpId'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nomor_perkara' => "required|string|max:255|unique:perkara,nomor_perkara,{$id}",
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string',
            'ketua_majelis' => 'required|exists:hakim,id',
            'hakim_anggota' => 'required|array|min:1',
            'hakim_anggota.*' => 'exists:hakim,id',
            'panitera_pengganti' => 'required|exists:panitera_pengganti,id',
        ], [
            'nomor_perkara.required' => 'Nomor Perkara wajib diisi.',
            'nomor_perkara.unique' => 'Nomor Perkara sudah terdaftar.',
            'tahun.required' => 'Tahun wajib diisi.',
            'ketua_majelis.required' => 'Ketua Majelis wajib dipilih.',
            'hakim_anggota.required' => 'Hakim Anggota wajib dipilih minimal 1 orang.',
            'panitera_pengganti.required' => 'Panitera Pengganti wajib dipilih.',
        ]);

        $perkaraData = [
            'nomor_perkara' => $validated['nomor_perkara'],
            'tahun' => $validated['tahun'],
            'keterangan' => $validated['keterangan'] ?? '',
        ];

        $this->perkaraService->updatePerkara(
            $id,
            $perkaraData,
            $validated['ketua_majelis'],
            $validated['hakim_anggota'],
            $validated['panitera_pengganti']
        );

        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->perkaraService->delete($id);
        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil dihapus.');
    }
}
