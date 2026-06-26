<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-OCID : Sistem Informasi Online Check-In Diri - PTUN Bandar Lampung</title>
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
            font-size: calc(1.6rem + 0.8vw);
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

        .glass-card-portal {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
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
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            border-color: rgba(212, 175, 55, 0.4);
        }

        .icon-wrapper {
            width: 75px;
            height: 75px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0.05) 100%);
            border: 2px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.1);
        }

        .btn-portal-primary {
            background: linear-gradient(135deg, #d4af37 0%, #aa841c 100%);
            color: #04180e !important;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn-portal-primary:hover {
            background: linear-gradient(135deg, #f3cd5a 0%, #d4af37 100%);
            transform: scale(1.03);
            box-shadow: 0 15px 25px rgba(212, 175, 55, 0.35);
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
            .icon-wrapper {
                width: 70px;
                height: 70px;
                margin-bottom: 15px;
            }
            .icon-wrapper i {
                font-size: 2rem !important;
            }
            .glass-card-portal h3 {
                font-size: 1.25rem !important;
            }
            .glass-card-portal p {
                font-size: 0.85rem !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-bottom: 20px !important;
            }
            .btn-portal-primary {
                padding: 10px 22px;
                font-size: 0.95rem;
            }
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
            <div class="col-md-9 col-lg-7">
                <!-- Logo Mahkamah Agung -->
                <div class="court-logo-container">
                    <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo Mahkamah Agung" class="court-logo">
                </div>
                
                <h1 class="app-title mb-1 text-uppercase">PTUN Bandar Lampung</h1>
                <p class="fs-5 app-subtitle mb-4">SI-OCID: Sistem Informasi Online Check-In Diri</p>

                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <!-- Card Absensi Mandiri (Publik) -->
                        <div class="card glass-card-portal p-4 text-center">
                            <h3 class="fw-bold fs-3 mb-2 text-white">Absensi Mandiri Sidang</h3>
                            <p class="text-white-50 mb-4 px-md-3">Silakan lakukan absensi persidangan secara mandiri dengan mengklik tombol di bawah untuk mengisi formulir kehadiran.</p>
                            
                            <div class="col-md-8 mx-auto">
                                <a href="{{ route('public.absensi') }}" class="btn btn-portal-primary w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                    <span>Mulai Absen Sekarang</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 footer-text">
                    <span><a href="{{ route('login') }}" style="color: inherit; text-decoration: none; cursor: default;">&copy;</a> 2026 Pengadilan Tata Usaha Negara Bandar Lampung. Semua Hak Dilindungi.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to cycle backgrounds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.portal-bg-slideshow .slide');
            if (slides.length === 0) return;
            
            let currentSlide = 0;
            slides[currentSlide].classList.add('active');
            
            setInterval(() => {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }, 6000); // Ganti gambar setiap 6 detik
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
