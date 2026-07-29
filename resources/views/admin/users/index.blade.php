@extends('layouts.admin')

@section('title', 'Kelola Akun Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Kelola Akun Pengguna</h4>
        <p class="text-muted small mb-0">Manajemen akun dan hak akses pengguna SIPEKA PTUN BDL.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-person-plus-fill me-2"></i>Tambah Akun Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select bg-light">
                    <option value="">-- Semua Peran --</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="hakim" {{ request('role') === 'hakim' ? 'selected' : '' }}>Hakim</option>
                    <option value="jsp_pp" {{ request('role') === 'jsp_pp' ? 'selected' : '' }}>JSP / PP</option>
                    <option value="ptsp" {{ request('role') === 'ptsp' ? 'selected' : '' }}>PTSP</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
            @if(request('search') || request('role'))
                <div class="col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
                        <th class="border-0">Nama Pengguna</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Peran (Role)</th>
                        <th class="border-0">Tanggal Dibuat</th>
                        <th class="border-0 text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                    <strong style="color: var(--text-primary);">{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1 small" style="font-size: 0.7rem;">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td><span class="text-muted">{{ $user->email }}</span></td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger badge-custom fw-semibold">
                                        <i class="bi bi-shield-lock-fill me-1"></i>Super Admin
                                    </span>
                                @elseif($user->role === 'hakim')
                                    <span class="badge bg-primary bg-opacity-10 text-primary badge-custom fw-semibold">
                                        <i class="bi bi-person-badge-fill me-1"></i>Hakim
                                    </span>
                                @elseif($user->role === 'jsp_pp')
                                    <span class="badge bg-warning bg-opacity-10 text-dark badge-custom fw-semibold">
                                        <i class="bi bi-file-earmark-person-fill me-1"></i>JSP / PP
                                    </span>
                                @elseif($user->role === 'ptsp')
                                    <span class="badge bg-info bg-opacity-10 text-info badge-custom fw-semibold">
                                        <i class="bi bi-headset me-1"></i>PTSP
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary badge-custom">{{ $user->role_label }}</span>
                                @endif
                            </td>
                            <td><span class="text-muted small">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y H:i') : '-' }}</span></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit Akun">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus Akun">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2 disabled" title="Tidak dapat menghapus akun sendiri" disabled>
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-people fs-1 d-block mb-3 text-muted"></i>
                                Data akun pengguna tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
