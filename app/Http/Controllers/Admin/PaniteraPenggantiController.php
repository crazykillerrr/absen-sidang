<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaniteraPenggantiService;
use Illuminate\Http\Request;

class PaniteraPenggantiController extends Controller
{
    protected $ppService;

    public function __construct(PaniteraPenggantiService $ppService)
    {
        $this->ppService = $ppService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\PaniteraPengganti::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_whatsapp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pps = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.panitera_pengganti.index', compact('pps'));
    }

    public function create()
    {
        return view('admin.panitera_pengganti.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20|regex:/^[0-9]+$/',
            'email' => 'nullable|email|max:255',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $this->ppService->create($validated);

        return redirect()->route('admin.panitera-pengganti.index')->with('success', 'Data Panitera Pengganti berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $pp = $this->ppService->find($id);
        return view('admin.panitera_pengganti.edit', compact('pp'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_whatsapp' => 'required|string|max:20|regex:/^[0-9]+$/',
            'email' => 'nullable|email|max:255',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $this->ppService->update($id, $validated);

        return redirect()->route('admin.panitera-pengganti.index')->with('success', 'Data Panitera Pengganti berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->ppService->delete($id);
        return redirect()->route('admin.panitera-pengganti.index')->with('success', 'Data Panitera Pengganti berhasil dihapus (Soft Delete).');
    }
}
