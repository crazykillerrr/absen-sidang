@extends('layouts.admin')

@section('title', 'Daftar Kehadiran Hari Ini')

@section('content')
<div class="card premium-card border-0 shadow-sm rounded-4 overflow-hidden attendance-card" style="background-color: var(--bg-secondary); position: relative;">
    <!-- Header Halaman -->
    <div class="card-header bg-transparent border-bottom border-light p-4 attendance-header d-flex flex-wrap align-items-center justify-content-between gap-3" style="position: relative; z-index: 2;">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-person-check-fill text-success fs-3"></i> 
                <span>Daftar Kehadiran Hari Ini</span>
            </h4>
            <p class="text-secondary small mb-0">Daftar kehadiran para pihak persidangan hari ini diurutkan berdasarkan Nomor Perkara.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Jam & Tanggal Digital -->
            <div class="text-end d-none d-sm-block">
                <span id="digital-clock" class="fs-4 fw-bold text-success d-block tracking-widest" style="font-family: 'Outfit', monospace;">00:00:00</span>
                <span id="digital-date" class="small text-secondary">Tanggal Hari Ini</span>
            </div>
            <div class="vr d-none d-sm-block" style="opacity: 0.15;"></div>
            <!-- Fullscreen Button -->
            <button type="button" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2 shadow-sm" id="btn-fullscreen">
                <i class="bi bi-fullscreen"></i>
                <span class="btn-text">Layar Penuh</span>
            </button>
        </div>
    </div>

    <!-- Statistik Ringkas -->
    <div class="p-4 bg-light bg-opacity-50 border-bottom border-light stat-container" style="position: relative; z-index: 2;">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-white border-light rounded-3 p-3 stat-card h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small d-block mb-1">Total Pihak Hadir</span>
                            <span id="stat-total-pihak" class="fs-3 fw-bold text-dark">{{ $kehadirans->count() }}</span>
                        </div>
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white border-light rounded-3 p-3 stat-card h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small d-block mb-1">Jumlah Perkara</span>
                            <span id="stat-jumlah-perkara" class="fs-3 fw-bold text-dark">{{ $kehadirans->unique('pihakSidang.jadwalSidang.perkara_id')->count() }}</span>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="bi bi-folder2-open fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white border-light rounded-3 p-3 stat-card h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small d-block mb-1">Status Operasional</span>
                            <span class="fs-5 fw-bold text-success d-flex align-items-center gap-1 mt-1">
                                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                                Live Monitoring
                            </span>
                        </div>
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="bi bi-broadcast fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Body / Daftar Kehadiran -->
    <div class="card-body p-4 position-relative" style="position: relative; z-index: 2;">
        <!-- Auto Scrolling Container -->
        <div id="attendance-scroll-container">
            <div id="attendance-scroll-content" class="scroll-content">
                @include('admin.laporan.hari_ini_list')
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Clock & Scrolling -->
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Jam & Tanggal Digital ---
        const clockElement = document.getElementById('digital-clock');
        const dateElement = document.getElementById('digital-date');
        
        function updateClock() {
            const now = new Date();
            
            // Format Jam
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            if (clockElement) {
                clockElement.innerText = `${hours}:${minutes}:${seconds}`;
            }
            
            // Format Tanggal Indonesia
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            if (dateElement) {
                dateElement.innerText = now.toLocaleDateString('id-ID', options);
            }
        }
        
        updateClock();
        setInterval(updateClock, 1000);

        // --- 2. Fullscreen Toggle ---
        const btnFullscreen = document.getElementById('btn-fullscreen');
        if (btnFullscreen) {
            btnFullscreen.addEventListener('click', function() {
                document.body.classList.toggle('fullscreen-mode');
                
                const icon = btnFullscreen.querySelector('i');
                const text = btnFullscreen.querySelector('.btn-text');
                
                if (document.body.classList.contains('fullscreen-mode')) {
                    icon.className = 'bi bi-fullscreen-exit';
                    text.innerText = 'Layar Normal';
                    
                    // Request actual fullscreen if supported
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.log("Gagal masuk layar penuh peramban: ", err.message);
                        });
                    }
                } else {
                    icon.className = 'bi bi-fullscreen';
                    text.innerText = 'Layar Penuh';
                    
                    if (document.exitFullscreen && document.fullscreenElement) {
                        document.exitFullscreen().catch(err => {
                            console.log("Gagal keluar layar penuh: ", err.message);
                        });
                    }
                }
                
                // Re-initialize scrolling metrics when layout changes
                setTimeout(initializeScrolling, 300);
            });
            
            // Sync status jika keluar fullscreen dengan tombol ESC peramban
            document.addEventListener('fullscreenchange', function() {
                if (!document.fullscreenElement && document.body.classList.contains('fullscreen-mode')) {
                    document.body.classList.remove('fullscreen-mode');
                    const icon = btnFullscreen.querySelector('i');
                    const text = btnFullscreen.querySelector('.btn-text');
                    icon.className = 'bi bi-fullscreen';
                    text.innerText = 'Layar Penuh';
                    
                    setTimeout(initializeScrolling, 300);
                }
            });
        }

        // --- 3. Animasi Auto-Scroll Looping ---
        const scrollContainer = document.getElementById('attendance-scroll-container');
        const scrollContent = document.getElementById('attendance-scroll-content');
        
        let scrollInterval;
        let scrollTop = 0;
        let scrollSpeed = 0.5; // Kecepatan scroll sedang & nyaman dibaca
        
        function initializeScrolling() {
            if (!scrollContainer || !scrollContent) return;
            
            // Hentikan interval/loop sebelumnya
            if (scrollInterval) {
                cancelAnimationFrame(scrollInterval);
            }
            
            // Hapus klon sebelumnya jika ada
            const clones = scrollContainer.querySelectorAll('.scroll-content-clone');
            clones.forEach(c => c.remove());
            
            const containerHeight = scrollContainer.clientHeight;
            const contentHeight = scrollContent.scrollHeight;
            
            // Hanya aktifkan scroll jika tinggi konten melebihi container
            if (contentHeight > containerHeight) {
                // Duplikasi konten untuk loop tak terbatas yang seamless
                const clone = scrollContent.cloneNode(true);
                clone.id = ''; // Hapus id duplikat
                clone.classList.add('scroll-content-clone');
                scrollContainer.appendChild(clone);
                
                scrollTop = scrollContainer.scrollTop;
                
                function animateScroll() {
                    const currentContentHeight = scrollContent.scrollHeight;
                    scrollTop += scrollSpeed;
                    
                    // Reset ke atas ketika konten asli selesai ter-scroll
                    if (scrollTop >= currentContentHeight + 24) { // 24 adalah margin/gap
                        scrollTop = 0;
                    }
                    
                    scrollContainer.scrollTop = scrollTop;
                    scrollInterval = requestAnimationFrame(animateScroll);
                }
                
                scrollInterval = requestAnimationFrame(animateScroll);
            }
        }
        
        // Jalankan inisialisasi scroll di awal
        setTimeout(initializeScrolling, 500);

        // --- 4. AJAX Realtime Polling (2 Detik) ---
        let lastHtmlContent = '';

        function fetchLatestData() {
            fetch(window.location.href, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.reload();
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                
                // Update statistik ringkas
                const statTotal = document.getElementById('stat-total-pihak');
                const statPerkara = document.getElementById('stat-jumlah-perkara');
                
                if (statTotal && statTotal.innerText !== String(data.totalPihakHadir)) {
                    statTotal.innerText = data.totalPihakHadir;
                }
                
                if (statPerkara && statPerkara.innerText !== String(data.totalJumlahPerkara)) {
                    statPerkara.innerText = data.totalJumlahPerkara;
                }
                
                // Update daftar kehadiran hanya jika ada perubahan konten HTML
                const contentArea = document.getElementById('attendance-scroll-content');
                if (contentArea && data.html && lastHtmlContent !== data.html) {
                    lastHtmlContent = data.html;
                    contentArea.innerHTML = data.html;
                    setTimeout(initializeScrolling, 100);
                }
            })
            .catch(err => console.error("Gagal memperbarui data absensi: ", err));
        }

        // Fast Polling secara realtime setiap 2 detik
        setInterval(fetchLatestData, 2000);
        window.fetchLatestData = fetchLatestData;
    });

    // Global function to update status sidang via AJAX
    function changeStatusSidang(jadwalId, newStatus) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch(`{{ url('admin/jadwal-sidang') }}/${jadwalId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status_sidang: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof window.fetchLatestData === 'function') {
                window.fetchLatestData();
            }
        })
        .catch(err => console.error('Error updating status sidang:', err));
    }
</script>
@endsection

<!-- Custom CSS untuk Animasi Status Sidang & Fullscreen Mode -->
<style>
    /* Flashing / Blinking Animation for Live Sidang (Red) */
    @keyframes blink-red-anim {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.98); }
        100% { opacity: 1; transform: scale(1); }
    }

    .blink-red-badge {
        animation: blink-red-anim 1.2s infinite ease-in-out;
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.75) !important;
    }

    .blink-red-text {
        animation: blink-red-anim 1.8s infinite ease-in-out;
    }

    .pulse-dot-red-blink {
        width: 8px;
        height: 8px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.8);
        animation: pulse-dot-white 1s infinite;
    }

    @keyframes pulse-dot-white {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.8); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(255, 255, 255, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }

    .border-danger-live {
        border: 2.5px solid #ef4444 !important;
        background-color: #fef2f2 !important;
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.2) !important;
    }

    .border-success-done {
        border: 2.5px solid #10b981 !important;
        background-color: #f0fdf4 !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15) !important;
    }

    .pulse-red-btn {
        transition: all 0.2s ease;
    }
    .pulse-red-btn:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
    }
    /* Base element design (Normal mode) */
    #attendance-scroll-container {
        height: calc(100vh - 360px);
        overflow: hidden;
        position: relative;
        transition: height 0.3s ease;
    }
    
    .scroll-content {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    
    .scroll-item-card {
        background-color: var(--bg-secondary) !important;
        border: 1px solid var(--border-color) !important;
        transition: all 0.25s ease;
    }
    
    .scroll-item-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover) !important;
        border-color: var(--primary-color) !important;
    }

    .icon-case-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        box-shadow: 0 4px 8px rgba(12, 62, 38, 0.05);
    }

    .badge-ruang {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-pihak {
        background-color: var(--primary-light);
        border: 1px solid rgba(12, 62, 38, 0.15);
        color: var(--primary-color);
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .attendee-row-card {
        border-color: var(--border-color) !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease;
    }

    .attendee-row-card:hover {
        transform: scale(1.005);
        background-color: #f1f5f9 !important;
        border-color: var(--primary-color) !important;
    }

    .avatar-circle-display {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #153e26 100%);
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(12, 62, 38, 0.12);
    }

    .pulse-dot-green {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green-anim 1.5s infinite;
    }
    
    @keyframes pulse-green-anim {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* --- FULLSCREEN STYLES (Sama persis dengan tampilan normal, hanya di-maximize) --- */
    body.fullscreen-mode #sidebar {
        display: none !important;
    }
    
    body.fullscreen-mode .navbar-custom {
        display: none !important;
    }
    
    body.fullscreen-mode #content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100vw !important;
        min-height: 100vh !important;
        max-height: 100vh !important;
        overflow: hidden !important;
    }
    
    body.fullscreen-mode .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100vw !important;
        height: 100vh !important;
    }
    
    body.fullscreen-mode .attendance-card {
        height: 100vh !important;
        border-radius: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        box-shadow: none !important;
    }

    body.fullscreen-mode .attendance-header {
        border-bottom: 1px solid var(--border-color) !important;
        padding: 25px 40px !important;
    }
    
    body.fullscreen-mode .stat-container {
        display: none !important; /* Tetap disembunyikan agar sisa halaman fokus untuk list scroll */
    }
    
    body.fullscreen-mode #attendance-scroll-container {
        height: calc(100vh - 130px) !important;
        padding: 0 40px !important;
    }
</style>
@endsection
