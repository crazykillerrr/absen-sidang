<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PihakSidangService;
use App\Services\JadwalSidangService;
use Illuminate\Http\Request;

class PihakSidangController extends Controller
{
    protected $pihakService;
    protected $jadwalService;

    // Daftar status pihak sesuai ketentuan
    protected $statuses = [
        'Penggugat',
        'Tergugat',
        'Penggugat II Intervensi',
        'Tergugat II Intervensi',
        'Saksi Penggugat',
        'Saksi Tergugat',
        'Saksi Penggugat II Intervensi',
        'Saksi Tergugat II Intervensi',
        'Ahli Penggugat',
        'Ahli Tergugat',
        'Ahli Penggugat II Intervensi',
        'Ahli Tergugat II Intervensi',
    ];

    public function __construct(PihakSidangService $pihakService, JadwalSidangService $jadwalService)
    {
        $this->pihakService = $pihakService;
        $this->jadwalService = $jadwalService;
    }

    public function index(int $jadwalSidangId)
    {
        $jadwal = $this->jadwalService->findWith($jadwalSidangId, ['perkara', 'ruangSidang']);
        $pihaks = \App\Models\PihakSidang::where('jadwal_sidang_id', $jadwalSidangId)
            ->with('kehadiran')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.pihak_sidang.index', compact('jadwal', 'pihaks'));
    }

    public function create(int $jadwalSidangId)
    {
        $jadwal = $this->jadwalService->findWith($jadwalSidangId, ['perkara']);
        $statuses = $this->statuses;
        return view('admin.pihak_sidang.create', compact('jadwal', 'statuses'));
    }

    public function store(Request $request, int $jadwalSidangId)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20|regex:/^[0-9]+$/',
            'status_pihak' => 'required|string|in:' . implode(',', $this->statuses),
        ], [
            'nama.required' => 'Nama Pihak wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'status_pihak.required' => 'Status Pihak wajib dipilih.',
            'status_pihak.in' => 'Status Pihak tidak valid.',
        ]);

        $validated['jadwal_sidang_id'] = $jadwalSidangId;

        $this->pihakService->create($validated);

        return redirect()->route('admin.pihak-sidang.index', $jadwalSidangId)
            ->with('success', 'Pihak Berperkara berhasil ditambahkan ke jadwal sidang.');
    }

    public function edit(int $id)
    {
        $pihak = $this->pihakService->findWith($id, ['jadwalSidang.perkara']);
        $statuses = $this->statuses;
        return view('admin.pihak_sidang.edit', compact('pihak', 'statuses'));
    }

    public function update(Request $request, int $id)
    {
        $pihak = $this->pihakService->find($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20|regex:/^[0-9]+$/',
            'status_pihak' => 'required|string|in:' . implode(',', $this->statuses),
        ], [
            'nama.required' => 'Nama Pihak wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'status_pihak.required' => 'Status Pihak wajib dipilih.',
            'status_pihak.in' => 'Status Pihak tidak valid.',
        ]);

        $this->pihakService->update($id, $validated);

        return redirect()->route('admin.pihak-sidang.index', $pihak->jadwal_sidang_id)
            ->with('success', 'Data Pihak Berperkara berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $pihak = $this->pihakService->find($id);
        $jadwalSidangId = $pihak->jadwal_sidang_id;
        
        $this->pihakService->delete($id);

        return redirect()->route('admin.pihak-sidang.index', $jadwalSidangId)
            ->with('success', 'Pihak Berperkara berhasil dihapus dari jadwal sidang.');
    }
}
