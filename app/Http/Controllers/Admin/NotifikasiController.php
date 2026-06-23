<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    protected $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Notifikasi::query();

        if ($request->filled('status')) {
            $query->where('status_kirim', $request->input('status'));
        }

        $notifikasis = $query->with(['jadwalSidang.perkara', 'jadwalSidang.ruangSidang'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.notifikasi.index', compact('notifikasis'));
    }
}
