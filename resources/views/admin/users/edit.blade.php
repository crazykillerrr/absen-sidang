@extends('layouts.admin')

@section('title', 'Edit Akun Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Akun
    </a>
    <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Edit Akun Pengguna</h4>
    <p class="text-muted small mb-0">Ubah informasi akun dan hak akses pengguna {{ $user->name }}.</p>
</div>

<div class="row">
    <div class="col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" id="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Peran (Role) Akses <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                            <select name="role" id="role" class="form-select bg-light border-start-0 @error('role') is-invalid @enderror" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif
                        </div>
                        @if($user->id === auth()->id())
                            <div class="form-text text-warning small mt-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Anda tidak dapat mengubah peran akun Anda sendiri saat sedang menggunakannya.
                            </div>
                        @endif
                        @error('role')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4" style="border-color: rgba(0,0,0,0.08);">

                    <div class="mb-3">
                        <h6 class="fw-bold text-dark mb-1">Ubah Kata Sandi (Opsional)</h6>
                        <p class="text-muted small mb-3">Kosongkan kolom kata sandi jika tidak ingin mengganti kata sandi akun ini.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0" placeholder="Ulangi kata sandi baru">
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-4 mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Akun
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border rounded-pill px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
