<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PTUN Bandar Lampung</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            padding: 20px 15px;
        }

        .gateway-container {
            position: relative;
            z-index: 10;
            padding: 10px;
            width: 100%;
        }

        .court-logo-container {
            margin-bottom: 12px;
            animation: fadeInDown 0.8s ease-out;
            display: inline-block;
        }

        .court-logo {
            height: 100px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.4));
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .court-logo:hover {
            transform: scale(1.06) rotate(1deg);
        }

        .app-title {
            font-weight: 800;
            font-size: calc(1.4rem + 0.6vw);
            letter-spacing: 1px;
            background: linear-gradient(to right, #ffffff, #e2e8f0, #d4af37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .app-subtitle {
            font-weight: 400;
            color: #a7f3d0;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Fixed Background Slideshow Carousel */
        .portal-bg-slideshow {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 1 !important;
            overflow: hidden !important;
        }

        .portal-bg-slideshow .slide {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            opacity: 0;
            transition: opacity 1.8s ease-in-out !important;
            z-index: 1 !important;
        }

        .portal-bg-slideshow .slide.active {
            opacity: 1 !important;
        }

        /* Dark overlay to make sure text remains readable */
        .portal-bg-slideshow::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(4, 24, 14, 0.78); /* Dark forest green overlay matching PTUN */
            z-index: 2;
        }

        /* Hide background orbs to let images shine */
        .gateway-bg .orb {
            display: none !important;
        }

        /* Glassmorphism Card Style */
        .glass-card-portal {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 24px !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3) !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .glass-card-portal::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.05), transparent);
            transition: all 0.6s ease;
        }

        .glass-card-portal:hover::before {
            left: 150%;
        }

        .glass-card-portal:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(212, 175, 55, 0.4) !important;
        }

        /* Premium Form Control Glass Overrides */
        .form-control-custom {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 0 12px 12px 0 !important;
            padding: 10px 15px !important;
            transition: all 0.2s ease !important;
        }

        .form-control-custom:focus {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(212, 175, 55, 0.5) !important;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15) !important;
            color: #ffffff !important;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.35) !important;
        }

        .input-group-text-custom {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: rgba(255, 255, 255, 0.5) !important;
            border-radius: 12px 0 0 12px !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .custom-checkbox {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        .custom-checkbox:checked {
            background-color: #d4af37 !important;
            border-color: #d4af37 !important;
        }

        .btn-portal-primary {
            background: linear-gradient(135deg, #d4af37 0%, #aa841c 100%) !important;
            color: #04180e !important;
            border: none !important;
            border-radius: 50px !important;
            padding: 12px 30px !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            letter-spacing: 0.5px !important;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.25) !important;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        }

        .btn-portal-primary:hover {
            background: linear-gradient(135deg, #f3cd5a 0%, #d4af37 100%) !important;
            transform: scale(1.03) !important;
            box-shadow: 0 15px 25px rgba(212, 175, 55, 0.35) !important;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsiveness Improvements */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px 40px 10px;
                overflow-y: auto;
                height: auto;
            }
            .gateway-container {
                padding: 5px;
            }
            .court-logo {
                height: 85px;
            }
            .app-title {
                font-size: 1.7rem;
            }
            .app-subtitle {
                font-size: 0.9rem;
                margin-bottom: 1.75rem !important;
            }
            .glass-card-portal {
                padding: 30px 15px !important;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body class="gateway-bg">
    <!-- Slideshow Background -->
    <section class="portal-bg-slideshow">
        <div class="slide" style="background-image: url('{{ asset('images/bg-building.png') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('images/bg-lobby.png') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('images/bg-entrance.png') }}');"></div>
    </section>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container gateway-container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                
                <!-- Logo Mahkamah Agung -->
                <div class="court-logo-container">
                    <a href="{{ route('portal') }}">
                        <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo Mahkamah Agung" class="court-logo">
                    </a>
                </div>
                
                <h1 class="app-title mb-1 text-uppercase fs-4">PTUN Bandar Lampung</h1>
                <p class="app-subtitle mb-4 small text-white-50">SI-OCID: Sistem Informasi Online Check-In Diri</p>

                <div class="card glass-card-portal border-0 shadow-lg p-4 p-md-5 text-start">
                    <h3 class="fw-bold mb-4 text-center text-white">Masuk Administrator</h3>

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
                            <label for="email" class="form-label fw-medium text-white-50">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control form-control-custom border-start-0 py-2" placeholder="Masukan Email Anda" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <label for="password" class="form-label fw-medium mb-0 text-white-50">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small text-warning" href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom border-end-0"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control form-control-custom border-start-0 py-2" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input custom-checkbox" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-white-50 small" for="remember_me">
                                Ingat Perangkat Saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-portal-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                        </button>
                        
                        <a href="{{ route('portal') }}" class="btn btn-outline-light w-100 py-2 rounded-pill fw-medium text-center text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Portal
                        </a>
                    </form>
                </div>
                <div class="mt-4 footer-text">
                    <span><a href="{{ route('login') }}" style="color: inherit; text-decoration: none; cursor: default;">&copy;</a> 2026 Pengadilan Tata Usaha Negara Bandar Lampung. Semua Hak Dilindungi.</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script to cycle backgrounds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.portal-bg-slideshow .slide');
            if (slides.length > 0) {
                let currentSlide = 0;
                slides[currentSlide].classList.add('active');
                
                setInterval(() => {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                }, 6000); // Ganti gambar setiap 6 detik
            }
        });

        // Force page refresh if loaded from browser history back/forward cache
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
