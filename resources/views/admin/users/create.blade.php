@extends('layouts.admin')

@section('title', 'Tambah Akun Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Akun
    </a>
    <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Tambah Akun Pengguna Baru</h4>
    <p class="text-muted small mb-0">Isi formulir di bawah ini untuk mengundangkan pengguna baru ke SIPEKA PTUN BDL.</p>
</div>

<div class="row">
    <div class="col-lg-8 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" id="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Dr. Supriadi, S.H., M.H." required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="pengguna@ptun.go.id" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold">Peran (Role) Akses <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                            <select name="role" id="role" class="form-select bg-light border-start-0 @error('role') is-invalid @enderror" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Peran --</option>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-text mt-1 text-muted small" id="roleDescription">
                            <i class="bi bi-info-circle me-1"></i>Hak akses pengguna akan disesuaikan dengan peran yang dipilih.
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-4 mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan Akun
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border rounded-pill px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-shield-shaded me-2"></i>Panduan Hak Akses Peran</h6>
                <ul class="list-unstyled mb-0 small text-muted">
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1"><span class="badge bg-danger">Super Admin</span></strong>
                        Akses penuh ke seluruh menu sistem termasuk pengelolaan akun pengguna, perkara, jadwal sidang, ruang sidang, dan notifikasi.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1"><span class="badge bg-primary">Hakim</span></strong>
                        Akses ke seluruh fitur operasional (Dashboard, Perkara, Jadwal Sidang, Ruang Sidang, Laporan, SIPP, Notifikasi, Kehadiran Hari Ini), kecuali Kelola Akun.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1"><span class="badge bg-warning text-dark">JSP / PP</span></strong>
                        Akses khusus untuk memantau <strong>Dashboard</strong> dan <strong>Live Kehadiran Hari Ini</strong>.
                    </li>
                    <li class="mb-0">
                        <strong class="text-dark d-block mb-1"><span class="badge bg-info text-dark">PTSP</span></strong>
                        Akses khusus untuk memantau <strong>Dashboard</strong>, <strong>Live Kehadiran Hari Ini</strong>, dan <strong>Laporan / Detail Kehadiran</strong>.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
