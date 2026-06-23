<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin Baru - PTUN Bandar Lampung</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="gateway-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                
                <div class="text-center mb-4">
                    <a href="{{ route('portal') }}" class="text-decoration-none text-white d-inline-flex flex-column align-items-center">
                        <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo" style="height: 100px; width: auto; object-fit: contain; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.25));" class="mb-3">
                        <h2 class="fw-bold mt-1 text-uppercase fs-4">PTUN BANDAR LAMPUNG</h2>
                        <p class="text-white-50 mb-0 small">Registrasi Akun Petugas</p>
                    </a>
                </div>

                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-dark">
                    <h3 class="fw-bold mb-4 text-center">Registrasi Petugas Baru</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-light border-start-0 py-2" placeholder="Nama Lengkap & Gelar" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-start-0 py-2" placeholder="petugas@ptun.go.id" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 py-2" placeholder="Minimal 8 karakter" required autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-secondary"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-start-0 py-2" placeholder="Ulangi kata sandi" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-semibold shadow-sm mb-3">
                            <i class="bi bi-person-plus me-2"></i>Daftarkan Akun
                        </button>
                        
                        <div class="text-center">
                            <span class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-success">Masuk disini</a></span>
                        </div>
                    </form>
                </div>
                <div class="mt-4 footer-text">
                    <span>&copy; 2026 Pengadilan Tata Usaha Negara Bandar Lampung. Semua Hak Dilindungi.</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
