<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Berhasil - PTUN Jakarta</title>
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
            <div class="col-md-6 col-lg-5">
                
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white text-dark mb-4">
                    <!-- Animated Check Circle -->
                    <div class="mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle shadow-sm" style="width: 90px; height: 90px; border: 4px solid #d1fae5;">
                            <i class="bi bi-patch-check-fill" style="font-size: 3.5rem;"></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-success mb-2">Absensi Berhasil!</h2>
                    <p class="text-muted small mb-4">Kehadiran Anda telah dicatat dalam sistem monitoring sidang PTUN Jakarta.</p>

                    <!-- Receipt Details Card -->
                    <div class="card border-0 bg-light p-4 rounded-3 text-start mb-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2"><i class="bi bi-file-earmark-text me-1"></i>Tanda Terima Absensi:</h6>
                        
                        <div class="mb-2">
                            <span class="text-muted small d-block">Nama Lengkap</span>
                            <span class="fw-bold text-dark fs-6">{{ $nama }}</span>
                        </div>
                        
                        <div class="mb-2">
                            <span class="text-muted small d-block">Status / Kedudukan</span>
                            <span class="badge bg-secondary">{{ $status_pihak }}</span>
                        </div>
                        
                        <div class="mb-2">
                            <span class="text-muted small d-block">Nomor Perkara</span>
                            <span class="fw-bold text-primary">{{ $nomor_perkara }}</span>
                        </div>

                        <div>
                            <span class="text-muted small d-block">Waktu Absen</span>
                            <span class="text-dark"><i class="bi bi-clock me-1 text-success"></i>{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="p-3 bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-3 mb-4 text-start small">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Sistem mendeteksi semua pihak wajib hadir telah melakukan absen. Notifikasi email otomatis telah dikirimkan ke Majelis Hakim dan Panitera untuk bersiap memulai sidang.
                    </div>

                    <a href="{{ route('portal') }}" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                </div>

                <div class="text-white-50 small">
                    &copy; 2026 Pengadilan Tata Usaha Negara Jakarta.
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
