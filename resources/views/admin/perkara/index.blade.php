@extends('layouts.admin')

@section('title', 'Kelola Perkara')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0" style="color: var(--text-primary);">Daftar Perkara PTUN</h4>
    <a href="{{ route('admin.perkara.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Perkara
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <form method="GET" action="{{ route('admin.perkara.index') }}" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nomor perkara, tahun, keterangan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.perkara.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th class="border-0">Tahun</th>
                        <th class="border-0 text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perkaras as $index => $perkara)
                        <tr>
                            <td>{{ $perkaras->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('admin.perkara.show', $perkara->id) }}" class="fw-bold text-decoration-none" style="color: var(--primary-color);">
                                    {{ $perkara->nomor_perkara }}
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $perkara->tahun }}</span></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.perkara.show', $perkara->id) }}" class="btn btn-sm btn-outline-info border-0 rounded-circle p-2" title="Detail Perkara & Sidang">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.perkara.edit', $perkara->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.perkara.destroy', $perkara->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data perkara ini?')">
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
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                                Data Perkara tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($perkaras->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $perkaras->links() }}
        </div>
    @endif
</div>
@endsection
