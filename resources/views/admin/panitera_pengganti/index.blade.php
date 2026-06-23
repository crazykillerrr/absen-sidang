@extends('layouts.admin')

@section('title', 'Panitera Pengganti')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--text-primary);">Daftar Panitera Pengganti</h4>
    <a href="{{ route('admin.panitera-pengganti.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah PP
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <form method="GET" action="{{ route('admin.panitera-pengganti.index') }}" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama, WhatsApp, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.panitera-pengganti.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th class="border-0">Nama Panitera Pengganti</th>
                        <th class="border-0">Nomor WhatsApp</th>
                        <th class="border-0">Email</th>
                        <th class="border-0 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pps as $index => $pp)
                        <tr>
                            <td>{{ $pps->firstItem() + $index }}</td>
                            <td><strong style="color: var(--text-primary);">{{ $pp->nama }}</strong></td>
                            <td>
                                <a href="https://wa.me/{{ $pp->nomor_whatsapp }}" target="_blank" class="text-decoration-none text-success">
                                    <i class="bi bi-whatsapp me-1"></i>{{ $pp->nomor_whatsapp }}
                                </a>
                            </td>
                            <td>{{ $pp->email ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.panitera-pengganti.edit', $pp->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.panitera-pengganti.destroy', $pp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Panitera Pengganti ini?')">
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
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-person-badge fs-1 d-block mb-3"></i>
                                Data Panitera Pengganti tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($pps->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $pps->links() }}
        </div>
    @endif
</div>
@endsection
