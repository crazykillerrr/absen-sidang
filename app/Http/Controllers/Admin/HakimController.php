<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HakimService;
use Illuminate\Http\Request;

class HakimController extends Controller
{
    protected $hakimService;

    public function __construct(HakimService $hakimService)
    {
        $this->hakimService = $hakimService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Hakim::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_whatsapp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $hakims = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.hakim.index', compact('hakims'));
    }

    public function create()
    {
        return view('admin.hakim.create');
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

        $this->hakimService->create($validated);

        return redirect()->route('admin.hakim.index')->with('success', 'Data Hakim berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $hakim = $this->hakimService->find($id);
        return view('admin.hakim.edit', compact('hakim'));
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

        $this->hakimService->update($id, $validated);

        return redirect()->route('admin.hakim.index')->with('success', 'Data Hakim berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->hakimService->delete($id);
        return redirect()->route('admin.hakim.index')->with('success', 'Data Hakim berhasil dihapus (Soft Delete).');
    }
}
