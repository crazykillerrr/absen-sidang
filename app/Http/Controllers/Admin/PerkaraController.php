<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PerkaraService;
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

        $perkaras = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.perkara.index', compact('perkaras'));
    }

    public function create()
    {
        return view('admin.perkara.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_perkara' => 'required|string|max:255|unique:perkara,nomor_perkara',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_perkara.required' => 'Nomor Perkara wajib diisi.',
            'nomor_perkara.unique' => 'Nomor Perkara sudah terdaftar.',
            'tahun.required' => 'Tahun wajib diisi.',
        ]);

        $perkaraData = [
            'nomor_perkara' => $validated['nomor_perkara'],
            'tahun' => $validated['tahun'],
            'keterangan' => $validated['keterangan'] ?? '',
        ];

        $this->perkaraService->createPerkara($perkaraData);

        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $perkara = $this->perkaraService->findWith($id, [
            'jadwalSidangs.ruangSidang'
        ]);

        return view('admin.perkara.show', compact('perkara'));
    }

    public function edit(int $id)
    {
        $perkara = $this->perkaraService->find($id);

        return view('admin.perkara.edit', compact('perkara'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nomor_perkara' => "required|string|max:255|unique:perkara,nomor_perkara,{$id}",
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_perkara.required' => 'Nomor Perkara wajib diisi.',
            'nomor_perkara.unique' => 'Nomor Perkara sudah terdaftar.',
            'tahun.required' => 'Tahun wajib diisi.',
        ]);

        $perkaraData = [
            'nomor_perkara' => $validated['nomor_perkara'],
            'tahun' => $validated['tahun'],
            'keterangan' => $validated['keterangan'] ?? '',
        ];

        $this->perkaraService->updatePerkara($id, $perkaraData);

        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->perkaraService->delete($id);
        return redirect()->route('admin.perkara.index')->with('success', 'Data Perkara berhasil dihapus.');
    }
}
