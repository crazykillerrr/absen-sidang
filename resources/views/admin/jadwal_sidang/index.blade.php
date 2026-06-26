@extends('layouts.admin')

@section('title', 'Jadwal Sidang')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--text-primary);">Kelola Jadwal Persidangan</h4>
    <a href="{{ route('admin.jadwal-sidang.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Jadwal
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <form method="GET" action="{{ route('admin.jadwal-sidang.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary">Cari Sidang</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Nomor perkara, agenda, ruangan..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Filter Tanggal</label>
                <input type="date" name="tanggal" class="form-control bg-light" value="{{ request('tanggal') }}">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100 py-2">Filter</button>
            </div>
            @if(request('search') || request('tanggal'))
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.jadwal-sidang.index') }}" class="btn btn-outline-secondary w-100 py-2">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0 border-0">
                <thead>
                    <tr>
                        <th class="border-0">Waktu & Jenis</th>
                        <th class="border-0">Nomor Perkara</th>
                        <th class="border-0">Agenda</th>
                        <th class="border-0">Ruang Sidang</th>
                        <th class="border-0 text-center">Sumber</th>
                        <th class="border-0 text-center" style="width: 250px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $jadwal)
                        @php
                            $totalPihak = $jadwal->pihakSidangs->count();
                            
                            $tanggal = $jadwal->tanggal_sidang instanceof \Carbon\Carbon 
                                ? $jadwal->tanggal_sidang->format('d-m-Y') 
                                : \Carbon\Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');
                        @endphp
                        <tr>
                            <td>
                                <strong class="d-block text-dark">{{ $tanggal }}</strong>
                                <span class="badge bg-light text-primary border"><i class="bi bi-clock me-1"></i>{{ substr($jadwal->jam_sidang, 0, 5) }}</span>
                                <small class="text-secondary d-block">{{ $jadwal->jenis_sidang }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.perkara.show', $jadwal->perkara_id) }}" class="fw-bold text-decoration-none" style="color: var(--primary-color);">
                                    {{ $jadwal->perkara->nomor_perkara }}
                                </a>
                            </td>
                            <td>{{ $jadwal->agenda_sidang }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $jadwal->ruangSidang->nama_ruang }}</span></td>
                            <td class="text-center">
                                @if(($jadwal->sumber_data ?? 'MANUAL') === 'SIPP')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 fw-semibold">SIPP</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 fw-semibold">MANUAL</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.pihak-sidang.index', $jadwal->id) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-semibold small" title="Kelola Litigant/Pihak">
                                        <i class="bi bi-people me-1"></i>Pihak Hadir ({{ $totalPihak }})
                                    </a>
                                    <a href="{{ route('admin.jadwal-sidang.edit', $jadwal->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal-sidang.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal sidang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                Jadwal Persidangan tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($jadwals->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $jadwals->links() }}
        </div>
    @endif
</div>
@endsection
