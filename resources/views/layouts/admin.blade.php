<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SI-OCID PTUN BDL</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom Stylesheet -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @yield('styles')
    
    <!-- Theme Script to prevent flashing light background when dark mode is enabled -->
    <script>
        (function () {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('dark-mode');
                });
            }
        })();
    </script>
</head>
<body>

    <div id="wrapper">
        <!-- Sidebar Navigation -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                    <img src="{{ asset('images/logo-ma.png') }}?v=3" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <span class="fw-bold fs-6 text-uppercase tracking-wider text-white">SI-OCID</span>
                </a>
                <button type="button" id="sidebarCollapseMobile" class="btn d-md-none border-0 text-secondary">
                    <i class="bi bi-x-lg fs-4"></i>
                </button>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="{{ Route::is('admin.perkara.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.perkara.index') }}">
                        <i class="bi bi-folder2-open"></i> Kelola Perkara
                    </a>
                </li>
                <li class="{{ Route::is('admin.jadwal-sidang.*') || Route::is('admin.pihak-sidang.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jadwal-sidang.index') }}">
                        <i class="bi bi-calendar3"></i> Jadwal Sidang
                    </a>
                </li>

                <li class="{{ Route::is('admin.ruang-sidang.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.ruang-sidang.index') }}">
                        <i class="bi bi-door-closed"></i> Ruang Sidang
                    </a>
                </li>
                <li class="{{ Route::is('admin.notifikasi.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.notifikasi.index') }}">
                        <i class="bi bi-envelope"></i> Log Notifikasi
                    </a>
                </li>
                <li class="{{ Route::is('admin.laporan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.laporan.index') }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Kehadiran
                    </a>
                </li>
                <li class="{{ Route::is('admin.integrasi-sipp.*') ? 'active' : '' }}">
                    <a href="#sippSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Route::is('admin.integrasi-sipp.*') ? 'true' : 'false' }}" class="dropdown-toggle d-flex align-items-center justify-content-between w-100">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-arrow-down"></i> Integrasi SIPP
                        </span>
                        <i class="bi bi-chevron-down small" style="transition: transform 0.2s;"></i>
                    </a>
                    <ul class="collapse list-unstyled submenu-list {{ Route::is('admin.integrasi-sipp.*') ? 'show' : '' }}" id="sippSubmenu" style="background: rgba(0,0,0,0.015);">
                        <li class="{{ Route::is('admin.integrasi-sipp.*') && request()->get('tab') !== 'history' ? 'active' : '' }}">
                            <a href="{{ route('admin.integrasi-sipp.index') }}">
                                <i class="bi bi-arrow-right-short"></i> Sinkronisasi Jadwal
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.integrasi-sipp.*') && request()->get('tab') === 'history' ? 'active' : '' }}">
                            <a href="{{ route('admin.integrasi-sipp.index') }}?tab=history">
                                <i class="bi bi-arrow-right-short"></i> Riwayat Sinkronisasi
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="px-4 py-3 border-top mt-auto" style="border-top: 1px solid rgba(255, 255, 255, 0.07) !important; background: transparent;">
                <span class="small d-block" style="color: #829a8f;">&copy; PTUN Bandar Lampung 2026</span>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
                <div class="container-fluid p-0">
                    <!-- Toggle Sidebar Button -->
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary border-0 rounded-circle p-2">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <!-- Navbar Actions -->
                    <div class="d-flex align-items-center gap-3 ms-auto">
                        <!-- Dark Mode Toggle Button -->
                        <button type="button" id="darkModeToggle" class="btn btn-outline-secondary border-0 rounded-circle p-2" title="Ganti Tema">
                            <i class="bi bi-moon-stars fs-5" id="themeIcon"></i>
                        </button>

                        <div class="vr mx-1"></div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle fw-bold" style="width: 38px; height: 38px;">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </span>
                                <span class="d-none d-md-inline fw-medium" style="color: var(--text-primary);">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 rounded-3" aria-labelledby="userDropdown" style="background-color: var(--bg-secondary);">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person text-secondary"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('portal') }}">
                                        <i class="bi bi-house text-secondary"></i> Halaman Depan
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Container -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Sidebar & Dark Mode Script -->
    <script>
        // Sidebar Toggles
        const sidebar = document.getElementById('sidebar');
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebarCollapseMobile = document.getElementById('sidebarCollapseMobile');

        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
        if (sidebarCollapseMobile) {
            sidebarCollapseMobile.addEventListener('click', () => {
                sidebar.classList.remove('active');
            });
        }

        // Dark Mode Logic
        const darkModeToggle = document.getElementById('darkModeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function updateThemeIcon() {
            if (document.body.classList.contains('dark-mode')) {
                themeIcon.classList.replace('bi-moon-stars', 'bi-sun');
            } else {
                themeIcon.classList.replace('bi-sun', 'bi-moon-stars');
            }
        }

        if (darkModeToggle) {
            // Initial sync
            updateThemeIcon();

            darkModeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                document.documentElement.classList.toggle('dark-mode');
                
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
                updateThemeIcon();
            });
        }

        // Show SweetAlert Notifications based on Session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Force page refresh if loaded from browser history back/forward cache
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
