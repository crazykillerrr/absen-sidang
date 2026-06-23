@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Pengaturan Profil Saya</h4>
    <p class="text-muted mb-0">Ubah informasi profil, perbarui kata sandi, atau kelola kredensial akun administrator Anda.</p>
</div>

<div class="row g-4">
    <!-- Kolom 1: Update Profil & Password -->
    <div class="col-lg-6 col-12">
        <!-- Card 1: Informasi Profil -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Informasi Profil</h5>
            
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <!-- Nama -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Card 2: Perbarui Password -->
        <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-shield-lock-fill me-2"></i>Perbarui Kata Sandi</h5>
            
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <!-- Password Sekarang -->
                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label fw-semibold">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="update_password_current_password" class="form-control form-control-custom @error('current_password', 'updatePassword') is-invalid @enderror" required>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div class="mb-3">
                    <label for="update_password_password" class="form-label fw-semibold">Kata Sandi Baru</label>
                    <input type="password" name="password" id="update_password_password" class="form-control form-control-custom @error('password', 'updatePassword') is-invalid @enderror" required>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label for="update_password_password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control form-control-custom" required>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4">Perbarui Sandi</button>
            </form>
        </div>
    </div>

    <!-- Kolom 2: Informasi Keamanan Tambahan -->
    <div class="col-lg-6 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-4" style="color: var(--text-primary);"><i class="bi bi-shield-check text-success me-2"></i>Panduan Kredensial Admin</h5>
            
            <div class="p-3 bg-light rounded-3 mb-3 border text-dark">
                <h6><i class="bi bi-key text-warning me-1"></i>Keamanan Akun</h6>
                <p class="small mb-0 text-secondary">Pastikan untuk menggunakan kombinasi kata sandi minimal 8 karakter yang mengandung huruf besar, huruf kecil, angka, dan simbol khusus untuk menjamin keamanan hak akses.</p>
            </div>

            <div class="p-3 bg-light rounded-3 mb-3 border text-dark">
                <h6><i class="bi bi-whatsapp text-success me-1"></i>Integrasi Fonnte</h6>
                <p class="small mb-0 text-secondary">Sistem notifikasi absensi sidang terhubung secara otomatis ke gateway Fonnte menggunakan token API yang dikonfigurasi pada file lingkungan `.env`. Jika pengiriman notifikasi terganggu, silakan hubungi Tim IT Pengadilan untuk memeriksa sisa kuota atau keaktifan nomor gateway.</p>
            </div>

            <div class="p-3 bg-light rounded-3 border text-dark">
                <h6><i class="bi bi-info-circle text-primary me-1"></i>Peran Sistem</h6>
                <p class="small mb-0 text-secondary">Akun Anda saat ini memiliki kedudukan peran sebagai **Administrator (Admin)**. Anda memiliki hak akses penuh untuk melakukan modifikasi perkara, jadwal sidang, data majelis hakim, serta melihat log notifikasi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
