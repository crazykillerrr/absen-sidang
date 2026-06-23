<?php

namespace App\Http\Controllers;

use App\Services\JadwalSidangService;
use App\Services\KehadiranService;
use App\Models\QrCode;
use App\Models\JadwalSidang;
use App\Models\PihakSidang;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    protected $jadwalService;
    protected $kehadiranService;

    public function __construct(JadwalSidangService $jadwalService, KehadiranService $kehadiranService)
    {
        $this->jadwalService = $jadwalService;
        $this->kehadiranService = $kehadiranService;
    }

    /**
     * Tampilkan halaman absensi publik
     */
    public function index(Request $request)
    {
        $qrcode = $request->input('qrcode');
        $lokasi = null;

        // Validasi lokasi QR Code jika parameter qrcode diisi
        if (!empty($qrcode)) {
            $qrRecord = QrCode::where('kode', $qrcode)->first();
            if ($qrRecord) {
                $lokasi = $qrRecord->lokasi;
            }
        }

        // Ambil jadwal sidang aktif hari ini (memiliki perkara dan ruang)
        $jadwals = $this->jadwalService->getActiveSchedulesForToday();

        return view('public.absensi', compact('jadwals', 'lokasi', 'qrcode'));
    }

    /**
     * Dapatkan rincian sidang dan pihak yang diharapkan hadir (untuk AJAX)
     */
    public function getHearingDetails(Request $request)
    {
        $jadwalId = $request->input('jadwal_sidang_id');
        
        if (empty($jadwalId)) {
            return response()->json(['error' => 'Jadwal ID kosong'], 400);
        }

        $jadwal = JadwalSidang::with(['perkara', 'ruangSidang'])->find($jadwalId);

        if (!$jadwal) {
            return response()->json(['error' => 'Jadwal sidang tidak ditemukan'], 404);
        }

        // Dapatkan pihak yang wajib hadir pada jadwal tersebut dan BELUM melakukan absensi
        $pihaks = PihakSidang::where('jadwal_sidang_id', $jadwalId)
            ->whereDoesntHave('kehadiran')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'agenda_sidang' => $jadwal->agenda_sidang,
            'ruang_sidang' => $jadwal->ruangSidang->nama_ruang,
            'jam_sidang' => substr($jadwal->jam_sidang, 0, 5) . ' WIB',
            'jenis_sidang' => $jadwal->jenis_sidang,
            'nomor_perkara' => $jadwal->perkara->nomor_perkara,
            'pihaks' => $pihaks
        ]);
    }

    /**
     * Simpan data kehadiran (check-in)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_sidang_id' => 'required|exists:jadwal_sidang,id',
            'pihak_sidang_id' => 'required|exists:pihak_sidang,id',
            'nama' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:20',
        ], [
            'jadwal_sidang_id.required' => 'Sidang wajib dipilih.',
            'pihak_sidang_id.required' => 'Pilih data pihak wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
        ]);

        $pihakSidangId = $validated['pihak_sidang_id'];
        $jadwalId = $validated['jadwal_sidang_id'];

        // Periksa apakah pihak tersebut sudah melakukan absen sebelumnya
        $sudahAbsen = Kehadiran::where('pihak_sidang_id', $pihakSidangId)->exists();
        if ($sudahAbsen) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pihak Berperkara yang Anda pilih sudah melakukan absensi untuk jadwal sidang ini.');
        }

        // Ambil data pihak sidang untuk mencocokkan & memvalidasi
        $pihak = PihakSidang::find($pihakSidangId);

        // Opsional: kita update nomor HP pihak jika berbeda dengan data pendaftaran
        if ($pihak->nomor_hp !== $validated['nomor_hp']) {
            $pihak->update(['nomor_hp' => $validated['nomor_hp']]);
        }

        // Catat Kehadiran ke database
        $this->kehadiranService->recordAttendance([
            'pihak_sidang_id' => $pihakSidangId,
            'waktu_hadir' => Carbon::now(),
            'status_hadir' => 'hadir'
        ], $jadwalId);

        return redirect()->route('public.absensi.success', [
            'nama' => $pihak->nama,
            'status_pihak' => $pihak->status_pihak,
            'nomor_perkara' => $pihak->jadwalSidang->perkara->nomor_perkara
        ]);
    }

    /**
     * Tampilkan halaman sukses absensi
     */
    public function success(Request $request)
    {
        $nama = $request->input('nama');
        $status_pihak = $request->input('status_pihak');
        $nomor_perkara = $request->input('nomor_perkara');

        if (empty($nama)) {
            return redirect()->route('public.absensi');
        }

        return view('public.success', compact('nama', 'status_pihak', 'nomor_perkara'));
    }
}
