<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PTUN Jakarta</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="gateway-bg">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                
                <div class="text-center mb-4">
                    <a href="{{ route('portal') }}" class="text-decoration-none text-white">
                        <i class="bi bi-balance2 fs-1 text-success bg-white px-3 py-2 rounded-circle shadow-lg mb-2"></i>
                        <h2 class="fw-bold mt-2">PTUN JAKARTA</h2>
                        <p class="text-white-50">Sistem Absensi & Kehadiran Sidang</p>
                    </a>
                </div>

                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-dark">
                    <h3 class="fw-bold mb-4 text-center">Masuk Administrator</h3>

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

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-start-0 py-2" placeholder="admin@ptun.go.id" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <label for="password" class="form-label fw-medium mb-0">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-start-0 py-2" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-muted small" for="remember_me">
                                Ingat Perangkat Saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                        </button>
                        
                        <a href="{{ route('portal') }}" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-medium text-center text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Portal
                        </a>
                    </form>
                </div>
                
                <div class="text-center mt-4 text-white-50 small">
                    &copy; 2026 Pengadilan Tata Usaha Negara Jakarta.
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
