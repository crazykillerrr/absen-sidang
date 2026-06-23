<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RuangSidangService;
use Illuminate\Http\Request;

class RuangSidangController extends Controller
{
    protected $ruangService;

    public function __construct(RuangSidangService $ruangService)
    {
        $this->ruangService = $ruangService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\RuangSidang::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_ruang', 'like', "%{$search}%")
                  ->orWhere('jenis_ruang', 'like', "%{$search}%");
            });
        }

        $ruangs = $query->orderBy('nama_ruang', 'asc')->paginate(10)->withQueryString();

        return view('admin.ruang_sidang.index', compact('ruangs'));
    }

    public function create()
    {
        $jenisRuangs = [
            'Ruang Sidang Utama',
            'Ruang Sidang Elektronik',
            'Ruang Pemeriksaan Persiapan'
        ];
        return view('admin.ruang_sidang.create', compact('jenisRuangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'jenis_ruang' => 'required|string|in:Ruang Sidang Utama,Ruang Sidang Elektronik,Ruang Pemeriksaan Persiapan',
        ], [
            'nama_ruang.required' => 'Nama Ruang wajib diisi.',
            'jenis_ruang.required' => 'Jenis Ruang wajib dipilih.',
            'jenis_ruang.in' => 'Jenis Ruang tidak valid.',
        ]);

        $this->ruangService->create($validated);

        return redirect()->route('admin.ruang-sidang.index')->with('success', 'Data Ruang Sidang berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $ruang = $this->ruangService->find($id);
        $jenisRuangs = [
            'Ruang Sidang Utama',
            'Ruang Sidang Elektronik',
            'Ruang Pemeriksaan Persiapan'
        ];
        return view('admin.ruang_sidang.edit', compact('ruang', 'jenisRuangs'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'jenis_ruang' => 'required|string|in:Ruang Sidang Utama,Ruang Sidang Elektronik,Ruang Pemeriksaan Persiapan',
        ], [
            'nama_ruang.required' => 'Nama Ruang wajib diisi.',
            'jenis_ruang.required' => 'Jenis Ruang wajib dipilih.',
            'jenis_ruang.in' => 'Jenis Ruang tidak valid.',
        ]);

        $this->ruangService->update($id, $validated);

        return redirect()->route('admin.ruang-sidang.index')->with('success', 'Data Ruang Sidang berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->ruangService->delete($id);
        return redirect()->route('admin.ruang-sidang.index')->with('success', 'Data Ruang Sidang berhasil dihapus (Soft Delete).');
    }
}
