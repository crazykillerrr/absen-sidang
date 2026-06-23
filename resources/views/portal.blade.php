<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring & Absensi Persidangan - PTUN</title>
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

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Logo PTUN / Simbol -->
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-white text-dark rounded-circle shadow-lg" style="width: 100px; height: 100px;">
                        <i class="bi bi-balance2 text-success" style="font-size: 3rem;"></i>
                    </span>
                </div>
                
                <h1 class="fw-bold mb-2 text-white">PTUN JAKARTA</h1>
                <p class="fs-5 text-white-50 mb-5">Sistem Absensi & Monitoring Kehadiran Pihak Berperkara Berbasis QR Code & Notifikasi WhatsApp Otomatis</p>

                <div class="row justify-content-center">
                    <!-- Card Kehadiran (Publik) -->
                    <div class="col-md-8 col-lg-7">
                        <div class="card border-0 shadow-lg bg-white text-dark rounded-4 p-4 p-md-5 text-center transition-hover" style="transform: translateZ(0); transition: all 0.3s ease;">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-4 mx-auto" style="width: 80px; height: 80px;">
                                <i class="bi bi-qr-code-scan" style="font-size: 2.5rem;"></i>
                            </div>
                            <h3 class="fw-bold fs-4 mb-2">Absensi Mandiri</h3>
                            <p class="text-muted mb-4 px-md-3">Lakukan absensi persidangan secara mandiri dengan memindai kode QR atau isi form kehadiran.</p>
                            <div class="col-sm-8 mx-auto">
                                <a href="{{ route('public.absensi') }}" class="btn btn-success w-100 rounded-pill py-2.5 fw-semibold shadow-sm">
                                    <i class="bi bi-qr-code me-2"></i>Mulai Absen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-white-50 small d-flex justify-content-between align-items-center px-4">
                    <span>&copy; 2026 Pengadilan Tata Usaha Negara Jakarta. Hak Cipta Dilindungi.</span>
                    <a href="{{ route('login') }}" class="text-white-50 text-decoration-none admin-secret-link" style="opacity: 0.15; transition: opacity 0.3s ease;" title="Portal Admin">
                        <i class="bi bi-shield-lock-fill"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hover Effect Scripts -->
    <style>
        .transition-hover:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.3) !important;
        }
        .admin-secret-link:hover {
            opacity: 0.8 !important;
        }
    </style>
</body>
</html>
