<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JadwalSidangService;
use App\Models\Perkara;
use App\Models\RuangSidang;
use Illuminate\Http\Request;

class JadwalSidangController extends Controller
{
    protected $jadwalService;

    public function __construct(JadwalSidangService $jadwalService)
    {
        $this->jadwalService = $jadwalService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\JadwalSidang::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('perkara', function ($q) use ($search) {
                $q->where('nomor_perkara', 'like', "%{$search}%");
            })->orWhere('agenda_sidang', 'like', "%{$search}%")
              ->orWhere('jenis_sidang', 'like', "%{$search}%")
              ->orWhereHas('ruangSidang', function ($q) use ($search) {
                  $q->where('nama_ruang', 'like', "%{$search}%");
              });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_sidang', $request->input('tanggal'));
        }

        $jadwals = $query->with(['perkara', 'ruangSidang', 'pihakSidangs'])
            ->orderBy('tanggal_sidang', 'desc')
            ->orderBy('jam_sidang', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jadwal_sidang.index', compact('jadwals'));
    }

    public function create()
    {
        $perkaras = Perkara::orderBy('nomor_perkara', 'asc')->get();
        $ruangs = RuangSidang::orderBy('nama_ruang', 'asc')->get();
        
        $agendas = [
            'Dismissal',
            'Pemeriksaan Persiapan',
            'Pemeriksaan Bukti Surat',
            'Pemeriksaan Bukti Saksi',
            'Pemeriksaan Bukti Ahli',
            'Eksekusi'
        ];

        return view('admin.jadwal_sidang.create', compact('perkaras', 'ruangs', 'agendas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perkara_id' => 'required|exists:perkara,id',
            'ruang_sidang_id' => 'required|exists:ruang_sidang,id',
            'agenda_sidang' => 'required|string|max:255',
            'tanggal_sidang' => 'required|date',
            'jam_sidang' => 'required',
            'jenis_sidang' => 'required|string|in:Offline,Online',
        ], [
            'perkara_id.required' => 'Nomor Perkara wajib dipilih.',
            'ruang_sidang_id.required' => 'Ruang Sidang wajib dipilih.',
            'agenda_sidang.required' => 'Agenda Sidang wajib diisi.',
            'tanggal_sidang.required' => 'Tanggal Sidang wajib diisi.',
            'tanggal_sidang.date' => 'Format tanggal tidak valid.',
            'jam_sidang.required' => 'Jam Sidang wajib diisi.',
            'jenis_sidang.required' => 'Jenis Sidang wajib dipilih.',
            'jenis_sidang.in' => 'Jenis Sidang tidak valid.',
        ]);

        // Simpan Jam Sidang dengan format H:i:s
        $validated['jam_sidang'] = date('H:i:s', strtotime($validated['jam_sidang']));

        $this->jadwalService->create($validated);

        return redirect()->route('admin.jadwal-sidang.index')->with('success', 'Jadwal Sidang berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $jadwal = $this->jadwalService->find($id);
        $perkaras = Perkara::orderBy('nomor_perkara', 'asc')->get();
        $ruangs = RuangSidang::orderBy('nama_ruang', 'asc')->get();

        $agendas = [
            'Dismissal',
            'Pemeriksaan Persiapan',
            'Pemeriksaan Bukti Surat',
            'Pemeriksaan Bukti Saksi',
            'Pemeriksaan Bukti Ahli',
            'Eksekusi'
        ];

        return view('admin.jadwal_sidang.edit', compact('jadwal', 'perkaras', 'ruangs', 'agendas'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'perkara_id' => 'required|exists:perkara,id',
            'ruang_sidang_id' => 'required|exists:ruang_sidang,id',
            'agenda_sidang' => 'required|string|max:255',
            'tanggal_sidang' => 'required|date',
            'jam_sidang' => 'required',
            'jenis_sidang' => 'required|string|in:Offline,Online',
        ], [
            'perkara_id.required' => 'Nomor Perkara wajib dipilih.',
            'ruang_sidang_id.required' => 'Ruang Sidang wajib dipilih.',
            'agenda_sidang.required' => 'Agenda Sidang wajib diisi.',
            'tanggal_sidang.required' => 'Tanggal Sidang wajib diisi.',
            'tanggal_sidang.date' => 'Format tanggal tidak valid.',
            'jam_sidang.required' => 'Jam Sidang wajib diisi.',
            'jenis_sidang.required' => 'Jenis Sidang wajib dipilih.',
            'jenis_sidang.in' => 'Jenis Sidang tidak valid.',
        ]);

        $validated['jam_sidang'] = date('H:i:s', strtotime($validated['jam_sidang']));

        $this->jadwalService->update($id, $validated);

        return redirect()->route('admin.jadwal-sidang.index')->with('success', 'Jadwal Sidang berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->jadwalService->delete($id);
        return redirect()->route('admin.jadwal-sidang.index')->with('success', 'Jadwal Sidang berhasil dihapus.');
    }

    public function panggil(int $id, \App\Services\WhatsAppNotificationService $waService)
    {
        $jadwal = \App\Models\JadwalSidang::with(['perkara', 'pihakSidangs', 'ruangSidang'])->findOrFail($id);
        $perkara = $jadwal->perkara;
        $pihaks = $jadwal->pihakSidangs;

        if ($pihaks->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada pihak yang melakukan absensi hadir untuk jadwal ini.');
        }

        $waSuccess = 0;
        $emailSuccess = 0;

        foreach ($pihaks as $pihak) {
            // WhatsApp Notification
            if (!empty($pihak->nomor_hp)) {
                $waMessage = "PANGGILAN PERSIDANGAN: Sidang untuk perkara nomor {$perkara->nomor_perkara} dengan agenda {$jadwal->agenda_sidang} di {$jadwal->ruangSidang->nama_ruang} akan segera dimulai. Kepada Bapak/Ibu {$pihak->nama} ({$pihak->status_pihak}) harap segera memasuki ruang sidang. Terima kasih.";
                
                $waStatus = $waService->sendNotification($pihak->nomor_hp, $waMessage);
                
                \App\Models\Notifikasi::create([
                    'jadwal_sidang_id' => $jadwal->id,
                    'jenis' => 'WhatsApp',
                    'status_kirim' => $waStatus ? 'terkirim' : 'gagal',
                    'waktu_kirim' => \Carbon\Carbon::now()
                ]);
                if ($waStatus) {
                    $waSuccess++;
                }
            }

            // Email Notification - For all parties
            if (!empty($pihak->email)) {
                try {
                    \Illuminate\Support\Facades\Mail::to($pihak->email)->send(new \App\Mail\PanggilanSidangMail($jadwal, $pihak));
                    
                    \App\Models\Notifikasi::create([
                        'jadwal_sidang_id' => $jadwal->id,
                        'jenis' => 'Email',
                        'status_kirim' => 'terkirim',
                        'waktu_kirim' => \Carbon\Carbon::now()
                    ]);
                    $emailSuccess++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("JadwalSidangController@panggil: Gagal kirim email ke {$pihak->email}. Error: " . $e->getMessage());
                    \App\Models\Notifikasi::create([
                        'jadwal_sidang_id' => $jadwal->id,
                        'jenis' => 'Email',
                        'status_kirim' => 'gagal',
                        'waktu_kirim' => \Carbon\Carbon::now()
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', "Panggilan berhasil dikirim ke para pihak. WhatsApp terkirim: {$waSuccess}, Email terkirim: {$emailSuccess}.");
    }
}
