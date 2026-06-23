<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Mandiri Sidang - PTUN Bandar Lampung</title>
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

    <div class="container">
        <div class="absensi-container">
            
            <!-- Logo Portal -->
            <div class="text-center mb-4">
                <a href="{{ route('portal') }}" class="text-decoration-none text-white d-inline-flex align-items-center gap-3">
                    <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo" style="height: 55px; width: auto; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.25));">
                    <span class="fw-bold tracking-wider fs-4 text-white" style="letter-spacing: 0.5px;">PTUN BANDAR LAMPUNG</span>
                </a>
            </div>

            <!-- Card Absensi -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="absensi-header-card">
                    <h3 class="fw-bold mb-1">Form Absensi Mandiri</h3>
                    <p class="text-white-50 small mb-0">Silakan isi formulir kehadiran di bawah ini untuk memulai absensi sidang</p>
                    
                    @if(!empty($lokasi))
                        <div class="d-inline-flex align-items-center gap-1 bg-success bg-opacity-25 border border-success border-opacity-50 text-white rounded-pill px-3 py-1 mt-3 small">
                            <i class="bi bi-geo-alt-fill text-warning"></i>
                            <span>Terdeteksi di lokasi: <strong>{{ $lokasi }}</strong></span>
                        </div>
                    @endif
                </div>

                <div class="absensi-body-card p-4 p-md-5 bg-white text-dark">
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
                            <label for="jadwal_sidang_id" class="form-label fw-bold text-secondary">1. Pilih Jadwal Sidang Aktif Hari Ini</label>
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
                        <div id="detail_container" class="card border-0 bg-light p-3 rounded-3 mb-4 d-none">
                            <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-info-circle me-1"></i>Informasi Sidang Terpilih:</h6>
                            <table class="table table-sm table-borderless mb-0 text-dark small">
                                <tr>
                                    <td class="text-muted py-1" style="width: 120px;">Agenda Sidang</td>
                                    <td class="py-1"><strong id="detail_agenda">-</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Ruang Sidang</td>
                                    <td class="py-1"><span class="badge bg-secondary" id="detail_ruang">-</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Jam Sidang</td>
                                    <td class="py-1" id="detail_jam">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted py-1">Jenis Sidang</td>
                                    <td class="py-1" id="detail_jenis">-</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Dropdown Pihak Wajib Hadir (Awalnya tersembunyi) -->
                        <div id="pihak_container" class="mb-4 d-none">
                            <label for="pihak_sidang_id" class="form-label fw-bold text-secondary">2. Pilih Nama / Kedudukan Pihak</label>
                            <select name="pihak_sidang_id" id="pihak_sidang_id" class="form-select form-control-custom" required>
                                <option value="" disabled selected>-- Pilih Kedudukan & Nama Anda --</option>
                            </select>
                            <small class="text-muted">Hanya menampilkan pihak yang terdaftar dan belum melakukan absensi.</small>
                        </div>

                        <!-- Input fields Nama & HP (Prefilled setelah pihak dipilih) -->
                        <div id="fields_input_container" class="d-none">
                            <!-- Input Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold text-secondary">Nama Lengkap Konfirmasi</label>
                                <input type="text" name="nama" id="nama" class="form-control form-control-custom" placeholder="Konfirmasi nama lengkap Anda" required readonly>
                            </div>

                            <!-- Input Nomor HP -->
                            <div class="mb-4">
                                <label for="nomor_hp" class="form-label fw-bold text-secondary">Nomor WhatsApp Konfirmasi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="nomor_hp" id="nomor_hp" class="form-control form-control-custom" placeholder="Nomor WhatsApp yang aktif" required>
                                </div>
                                <small class="text-muted">Pastikan nomor ini aktif jika Majelis Hakim perlu menghubungi Anda.</small>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-semibold shadow-sm mb-3">
                                <i class="bi bi-qr-code-scan me-2"></i>Kirim Kehadiran (Check-In)
                            </button>
                        </div>
                        
                        <a href="{{ route('portal') }}" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-medium text-center text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Portal
                        </a>
                    </form>
                </div>
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
            const pihakContainer = document.getElementById('pihak_container');
            const selectPihak = document.getElementById('pihak_sidang_id');
            const fieldsInputContainer = document.getElementById('fields_input_container');
            
            const detailAgenda = document.getElementById('detail_agenda');
            const detailRuang = document.getElementById('detail_ruang');
            const detailJam = document.getElementById('detail_jam');
            const detailJenis = document.getElementById('detail_jenis');
            
            const inputNama = document.getElementById('nama');
            const inputHp = document.getElementById('nomor_hp');

            // Trigger AJAX call on Schedule Dropdown Change
            selectJadwal.addEventListener('change', function() {
                const id = this.value;
                if(!id) return;

                // Reset sub-containers
                detailContainer.classList.add('d-none');
                pihakContainer.classList.add('d-none');
                fieldsInputContainer.classList.add('d-none');
                selectPihak.innerHTML = '<option value="" disabled selected>Memuat data pihak...</option>';

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
                        
                        // Show hearing details
                        detailContainer.classList.remove('d-none');

                        // Populate expected parties
                        selectPihak.innerHTML = '<option value="" disabled selected>-- Pilih Kedudukan & Nama Anda --</option>';
                        
                        if (data.pihaks.length === 0) {
                            selectPihak.innerHTML = '<option value="" disabled>-- Semua Pihak Sudah Melakukan Absen --</option>';
                        } else {
                            data.pihaks.forEach(pihak => {
                                const option = document.createElement('option');
                                option.value = pihak.id;
                                option.text = `${pihak.status_pihak} - ${pihak.nama}`;
                                option.dataset.nama = pihak.nama;
                                option.dataset.hp = pihak.nomor_hp;
                                selectPihak.add(option);
                            });
                        }
                        
                        pihakContainer.classList.remove('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengambil detail sidang. Silakan coba lagi.');
                    });
            });

            // Prefill Input Fields on Pihak Dropdown Change
            selectPihak.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedOption.value) return;

                inputNama.value = selectedOption.dataset.nama || '';
                inputHp.value = selectedOption.dataset.hp || '';

                fieldsInputContainer.classList.remove('d-none');
            });
            
            // Handle if there is a pre-selected value from old() on validation fail
            if (selectJadwal.value) {
                selectJadwal.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
