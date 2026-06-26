@extends('layouts.admin')

@section('title', 'Log Notifikasi')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Log Notifikasi Email</h4>
    <p class="text-muted mb-0">Daftar riwayat pengiriman notifikasi otomatis kepada para pihak persidangan via Email.</p>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <form method="GET" action="{{ route('admin.notifikasi.index') }}" class="row g-3">
            <div class="col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">-- Semua Status --</option>
                    <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                    <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Gagal</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            @if(request('status'))
                <div class="col-md-2">
                    <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0 border-0">
                <thead>
                    <tr>
                        <th class="border-0">No</th>
                        <th class="border-0">Nomor Perkara</th>
                        <th class="border-0">Agenda Sidang</th>
                        <th class="border-0">Ruang Sidang</th>
                        <th class="border-0">Jenis</th>
                        <th class="border-0">Waktu Kirim</th>
                        <th class="border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifikasis as $index => $notif)
                        <tr>
                            <td>{{ $notifikasis->firstItem() + $index }}</td>
                            <td>
                                <strong style="color: var(--text-primary);">
                                    {{ $notif->jadwalSidang?->perkara?->nomor_perkara ?? '-' }}
                                </strong>
                            </td>
                            <td>{{ $notif->jadwalSidang?->agenda_sidang ?? '-' }}</td>
                            <td>{{ $notif->jadwalSidang?->ruangSidang?->nama_ruang ?? '-' }}</td>
                            <td>
                                @if($notif->jenis === 'Email')
                                    <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-envelope me-1"></i>{{ $notif->jenis }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-whatsapp me-1"></i>{{ $notif->jenis }}</span>
                                @endif
                            </td>
                            <td>{{ $notif->waktu_kirim ? $notif->waktu_kirim->format('d-m-Y H:i:s') . ' WIB' : '-' }}</td>
                            <td class="text-center">
                                @if($notif->status_kirim === 'terkirim')
                                    <span class="badge bg-success bg-opacity-10 text-success badge-custom"><i class="bi bi-check2-circle me-1"></i>Terkirim</span>
                                @elseif($notif->status_kirim === 'gagal')
                                    <span class="badge bg-danger bg-opacity-10 text-danger badge-custom"><i class="bi bi-x-circle me-1"></i>Gagal</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning badge-custom"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-envelope fs-1 d-block mb-3"></i>
                                Log notifikasi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($notifikasis->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $notifikasis->links() }}
        </div>
    @endif
</div>
@endsection
