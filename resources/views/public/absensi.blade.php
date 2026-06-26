<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-OCID - Absensi Mandiri Sidang PTUN BDL</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
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

        /* Prevent table background override in glass container */
        #detail_container table {
            --bs-table-bg: transparent !important;
            background-color: transparent !important;
        }
        #detail_container table td {
            background-color: transparent !important;
        }

        /* Neutral blue badge styling for electronic hearing rooms */
        .badge-blue-neutral {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #60a5fa !important;
            border: 1px solid rgba(59, 130, 246, 0.4) !important;
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

    <div class="container">
        <div class="absensi-container">
            
            <!-- Logo Portal -->
            <div class="text-center mb-4">
                <a href="{{ route('portal') }}" class="text-decoration-none text-white d-inline-flex align-items-center gap-3">
                    <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo" style="height: 55px; width: auto; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.25));">
                    <span class="fw-bold tracking-wider fs-4 text-white" style="letter-spacing: 0.5px;">PTUN BANDAR LAMPUNG</span>
                </a>
            </div>

            <!-- Card Absensi (Glassmorphism) -->
            <div class="card glass-card-portal border-0 shadow-lg p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold fs-3 text-white mb-1">SI-OCID: Form Absensi Mandiri</h3>
                    <p class="text-white-50 small mb-0">Silakan isi formulir kehadiran di bawah ini untuk memulai absensi sidang</p>
                    
                    @if(!empty($lokasi))
                        <div class="d-inline-flex align-items-center gap-1 bg-success bg-opacity-25 border border-success border-opacity-50 text-white rounded-pill px-3 py-1 mt-3 small">
                            <i class="bi bi-geo-alt-fill text-warning"></i>
                            <span>Terdeteksi di lokasi: <strong>{{ $lokasi }}</strong></span>
                        </div>
                    @endif
                </div>

                @if(session('error'))
                    <div class="alert alert-danger rounded-3 alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('public.absensi.store') }}">
                    @csrf

                    <!-- QR Code hidden parameter if scanned -->
                    <input type="hidden" name="qrcode" value="{{ $qrcode }}">

                    <!-- Dropdown Nomor Perkara (Sidang Aktif Hari Ini) -->
                    <div class="mb-4">
                        <label for="jadwal_sidang_id" class="form-label fw-bold text-white-50">1. Pilih Jadwal Sidang Aktif Hari Ini</label>
                        <select name="jadwal_sidang_id" id="jadwal_sidang_id" class="form-select form-control-custom" required>
                            <option value="" disabled selected>-- Pilih Nomor Perkara Persidangan --</option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" {{ old('jadwal_sidang_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->perkara->nomor_perkara }} [{{ substr($jadwal->jam_sidang, 0, 5) }} WIB - {{ $jadwal->ruangSidang->nama_ruang }}]
                                </option>
                            @endforeach
                        </select>
                        @if($jadwals->isEmpty())
                            <small class="text-danger d-block mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Saat ini tidak ada jadwal sidang aktif yang terdaftar untuk hari ini.
                            </small>
                        @endif
                    </div>

                    <!-- Card Detail Sidang Otomatis (Awalnya tersembunyi) -->
                    <div id="detail_container" class="card border-0 p-3 rounded-3 mb-4 d-none" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <h6 class="fw-bold mb-2 text-warning"><i class="bi bi-info-circle me-1"></i>Informasi Sidang Terpilih:</h6>
                        <table class="table table-sm table-borderless mb-0 text-white small">
                            <tr>
                                <td class="text-white-50 py-1" style="width: 120px;">Agenda Sidang</td>
                                <td class="py-1"><strong class="text-white" id="detail_agenda">-</strong></td>
                            </tr>
                            <tr>
                                <td class="text-white-50 py-1">Ruang Sidang</td>
                                <td class="py-1"><span class="badge badge-blue-neutral" id="detail_ruang">-</span></td>
                            </tr>
                            <tr>
                                <td class="text-white-50 py-1">Jam Sidang</td>
                                <td class="py-1 text-white" id="detail_jam">-</td>
                            </tr>
                            <tr>
                                <td class="text-white-50 py-1">Jenis Sidang</td>
                                <td class="py-1 text-white" id="detail_jenis">-</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Input fields Nama, Kedudukan, HP & Email (Awalnya tersembunyi, ditunjukkan setelah memilih nomor perkara) -->
                    <div id="fields_input_container" class="d-none">
                        <!-- Dropdown Status Kedudukan -->
                        <div class="mb-4">
                            <label for="status_pihak" class="form-label fw-bold text-white-50">2. Pilih Status Kedudukan Anda</label>
                            <select name="status_pihak" id="status_pihak" class="form-select form-control-custom" required>
                                <option value="" disabled selected>-- Pilih Status Kedudukan Pihak --</option>
                                <option value="Penggugat" {{ old('status_pihak') == 'Penggugat' ? 'selected' : '' }}>Penggugat</option>
                                <option value="Tergugat" {{ old('status_pihak') == 'Tergugat' ? 'selected' : '' }}>Tergugat</option>
                                <option value="Saksi Penggugat" {{ old('status_pihak') == 'Saksi Penggugat' ? 'selected' : '' }}>Saksi Penggugat</option>
                                <option value="Saksi Tergugat" {{ old('status_pihak') == 'Saksi Tergugat' ? 'selected' : '' }}>Saksi Tergugat</option>
                                <option value="Ahli Penggugat" {{ old('status_pihak') == 'Ahli Penggugat' ? 'selected' : '' }}>Ahli Penggugat</option>
                                <option value="Ahli Tergugat" {{ old('status_pihak') == 'Ahli Tergugat' ? 'selected' : '' }}>Ahli Tergugat</option>
                                <option value="Kuasa Hukum Penggugat" {{ old('status_pihak') == 'Kuasa Hukum Penggugat' ? 'selected' : '' }}>Kuasa Hukum Penggugat</option>
                                <option value="Kuasa Hukum Tergugat" {{ old('status_pihak') == 'Kuasa Hukum Tergugat' ? 'selected' : '' }}>Kuasa Hukum Tergugat</option>
                                <option value="Lain-lain" {{ old('status_pihak') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>
                        </div>

                        <!-- Input Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-white-50">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control form-control-custom" placeholder="Masukkan nama lengkap Anda" value="{{ old('nama') }}" required>
                        </div>

                        <!-- Input Nomor HP -->
                        <div class="mb-3">
                            <label for="nomor_hp" class="form-label fw-bold text-white-50">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control form-control-custom" placeholder="Nomor WhatsApp yang aktif" value="{{ old('nomor_hp') }}" required>
                            </div>
                            <small class="text-white-50">Pastikan nomor ini aktif agar dapat menerima notifikasi/panggilan sidang.</small>
                        </div>

                        <!-- Input Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-white-50">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope text-primary"></i></span>
                                <input type="email" name="email" id="email" class="form-control form-control-custom" placeholder="Alamat email Anda" value="{{ old('email') }}" required>
                            </div>
                            <small class="text-white-50">Digunakan untuk menerima salinan pengingat dan pemberitahuan persidangan.</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-semibold shadow-sm mb-3">
                            <i class="bi bi-qr-code-scan me-2"></i>Kirim Kehadiran (Check-In)
                        </button>
                    </div>
                    
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

    <!-- AJAX & Dynamic Prefill Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectJadwal = document.getElementById('jadwal_sidang_id');
            const detailContainer = document.getElementById('detail_container');
            const fieldsInputContainer = document.getElementById('fields_input_container');
            
            const detailAgenda = document.getElementById('detail_agenda');
            const detailRuang = document.getElementById('detail_ruang');
            const detailJam = document.getElementById('detail_jam');
            const detailJenis = document.getElementById('detail_jenis');

            // Trigger AJAX call on Schedule Dropdown Change
            selectJadwal.addEventListener('change', function() {
                const id = this.value;
                if(!id) return;

                // Reset sub-containers
                detailContainer.classList.add('d-none');
                fieldsInputContainer.classList.add('d-none');

                fetch(`{{ url('/absensi/hearing-details') }}?jadwal_sidang_id=${id}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.json();
                    })
                    .then(data => {
                        // Prefill hearing details
                        detailAgenda.innerText = data.agenda_sidang;
                        detailRuang.innerText = data.ruang_sidang;
                        detailJam.innerText = data.jam_sidang;
                        detailJenis.innerText = data.jenis_sidang;

                        // Set badge styling dynamically for all courtrooms to neutral blue
                        detailRuang.className = "badge badge-blue-neutral";
                        
                        // Show hearing details & fields input container
                        detailContainer.classList.remove('d-none');
                        fieldsInputContainer.classList.remove('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengambil detail sidang. Silakan coba lagi.');
                    });
            });
            
            // Handle if there is a pre-selected value from old() on validation fail
            if (selectJadwal.value) {
                selectJadwal.dispatchEvent(new Event('change'));
            }

            // Background slideshow cycle
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
    </script>
</body>
</html>
