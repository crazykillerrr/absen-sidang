@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 p-3" role="alert" style="background-color: #d1fae5; color: #065f46;">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 p-3" role="alert" style="background-color: #fee2e2; color: #991b1b;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
            <div>
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-4">
    <!-- Stat 1: Total Perkara -->
    <div class="col-md-6 col-lg-3 col-12">
        <div class="card premium-card border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="premium-card-title mb-1">Total Perkara</h6>
                    <h3 class="premium-card-value mb-0">{{ $totalPerkara }}</h3>
                </div>
                <div class="icon-box icon-box-primary">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Sidang Hari Ini -->
    <div class="col-md-6 col-lg-3 col-12">
        <div class="card premium-card border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="premium-card-title mb-1">Sidang Hari Ini</h6>
                    <h3 class="premium-card-value mb-0">{{ $totalJadwalHariIni }}</h3>
                </div>
                <div class="icon-box icon-box-teal">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Kehadiran Hari Ini -->
    <div class="col-md-6 col-lg-3 col-12">
        <div class="card premium-card border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="premium-card-title mb-1">Kehadiran Hari Ini</h6>
                    <h3 id="stat-kehadiran" class="premium-card-value mb-0">{{ $totalKehadiranHariIni }}</h3>
                </div>
                <div class="icon-box icon-box-orange">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Sidang Berjalan -->
    <div class="col-md-6 col-lg-3 col-12">
        <div class="card premium-card border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="premium-card-title mb-1">Sidang Berjalan</h6>
                    <h3 id="stat-sidang-lengkap" class="premium-card-value mb-0">{{ $totalSidangBerjalan }} <span class="fs-6 text-muted">/{{ $totalJadwalHariIni }}</span></h3>
                </div>
                <div class="icon-box" style="background-color: #d1fae5; color: #10b981;">
                    <i class="bi bi-play-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stat 5: Notifikasi Email -->
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: var(--bg-secondary);">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box" style="background-color: #dbeafe; color: #1d4ed8; width: 60px; height: 60px; font-size: 1.8rem;">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <h6 class="text-uppercase tracking-wider text-muted fw-semibold mb-1" style="font-size: 0.8rem;">Total Notifikasi Email Terkirim</h6>
                    <h3 class="fw-bold mb-0 text-primary" style="font-size: 2.2rem;">{{ $totalNotifikasiTerkirim }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Tabel Sidang Hari Ini -->
<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Daftar Persidangan Hari Ini</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small">
            {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0 border-0">
                <thead>
                    <tr>
                        <th class="border-0">Waktu</th>
                        <th class="border-0">Nomor Perkara</th>
                        <th class="border-0">Agenda</th>
                        <th class="border-0">Ruang Sidang</th>
                        <th class="border-0">Kehadiran Pihak</th>
                        <th class="border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dashboard-schedules-body">
                    @forelse ($sidangHariIni as $sidang)
                        @php
                            $totalHadir = $sidang->pihakSidangs->count();
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold text-primary"><i class="bi bi-clock me-1"></i>{{ substr($sidang->jam_sidang, 0, 5) }}</span>
                                <small class="text-muted d-block">{{ $sidang->jenis_sidang }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.perkara.show', $sidang->perkara_id) }}" class="fw-bold text-decoration-none" style="color: var(--text-primary);">
                                    {{ $sidang->perkara->nomor_perkara }}
                                </a>
                            </td>
                            <td>{{ $sidang->agenda_sidang }}</td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-door-closed me-1"></i>{{ $sidang->ruangSidang->nama_ruang }}</span></td>

                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">{{ $totalHadir }} Pihak Hadir</span>
                                <small class="text-muted d-block mt-1"><a href="{{ route('admin.pihak-sidang.index', $sidang->id) }}" class="text-decoration-none">Detail Kehadiran</a></small>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.jadwal-sidang.panggil', $sidang->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill" {{ $totalHadir === 0 ? 'disabled' : '' }} title="Panggil & kirim notifikasi ke para pihak">
                                        <i class="bi bi-megaphone me-1"></i>Panggil
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                Tidak ada jadwal persidangan untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel Kehadiran Terbaru -->
<div class="card border-0 shadow-sm rounded-4 mt-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Daftar Kehadiran Terbaru</h5>
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill small" style="border-color: var(--border-color); color: var(--text-secondary);">
            <i class="bi bi-file-earmark-bar-graph me-1"></i>Lihat Semua Laporan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0 border-0">
                <thead>
                    <tr>
                        <th class="border-0">Waktu Absen</th>
                        <th class="border-0">Nama Pihak</th>
                        <th class="border-0">Status Pihak</th>
                        <th class="border-0">Nomor Perkara</th>
                        <th class="border-0">Agenda Sidang</th>
                        <th class="border-0">Ruang Sidang</th>
                        <th class="border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="dashboard-recent-checkins-body">
                    @forelse ($kehadiranTerbaru as $kehadiran)
                        @php
                            $pihak = $kehadiran->pihakSidang;
                            $jadwal = $pihak?->jadwalSidang;
                            $perkara = $jadwal?->perkara;
                            $ruangSidang = $jadwal?->ruangSidang;
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold text-primary"><i class="bi bi-clock me-1"></i>{{ $kehadiran->waktu_hadir->format('H:i') }} WIB</span>
                                <small class="text-muted d-block">{{ $kehadiran->waktu_hadir->translatedFormat('d-m-Y') }}</small>
                            </td>
                            <td><strong>{{ $pihak?->nama ?? '-' }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $pihak?->status_pihak ?? '-' }}</span></td>
                            <td>
                                @if ($perkara)
                                    <a href="{{ route('admin.perkara.show', $perkara->id) }}" class="fw-bold text-decoration-none" style="color: var(--text-primary);">
                                        {{ $perkara->nomor_perkara }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $jadwal?->agenda_sidang ?? '-' }}</td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-door-closed me-1"></i>{{ $ruangSidang?->nama_ruang ?? '-' }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success badge-custom"><i class="bi bi-check me-1"></i>{{ $kehadiran->status_hadir }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                                Belum ada data kehadiran yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

    function fetchDashboardData() {
        fetch('{{ route('admin.dashboard.data') }}', {
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
                // 1. Update stats counters
                const statKehadiran = document.getElementById('stat-kehadiran');
                if (statKehadiran) {
                    statKehadiran.innerText = data.totalKehadiranHariIni;
                }
                const statSidangLengkap = document.getElementById('stat-sidang-lengkap');
                if (statSidangLengkap) {
                    statSidangLengkap.innerHTML = `${data.totalSidangBerjalan} <span class="fs-6 text-muted">/{{ $totalJadwalHariIni }}</span>`;
                }

                // 2. Update today's schedules table
                const schedulesBody = document.getElementById('dashboard-schedules-body');
                if (schedulesBody) {
                    if (data.sidangHariIni.length === 0) {
                        schedulesBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                    Tidak ada jadwal persidangan untuk hari ini.
                                </td>
                            </tr>`;
                    } else {
                        let html = '';
                        data.sidangHariIni.forEach(sidang => {
                            const csrfToken = '{{ csrf_token() }}';
                            const disabledAttr = sidang.total_hadir === 0 ? 'disabled' : '';

                            html += `
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-primary"><i class="bi bi-clock me-1"></i>${sidang.jam_sidang}</span>
                                        <small class="text-muted d-block">${sidang.jenis_sidang}</small>
                                    </td>
                                    <td>
                                        <a href="${sidang.perkara_show_route}" class="fw-bold text-decoration-none" style="color: var(--text-primary);">
                                            ${sidang.nomor_perkara}
                                        </a>
                                    </td>
                                    <td>${sidang.agenda_sidang}</td>
                                    <td><span class="badge bg-light text-dark border"><i class="bi bi-door-closed me-1"></i>${sidang.ruang_sidang}</span></td>

                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">${sidang.total_hadir} Pihak Hadir</span>
                                        <small class="text-muted d-block mt-1"><a href="${sidang.pihak_sidang_route}" class="text-decoration-none">Detail Kehadiran</a></small>
                                    </td>
                                    <td class="text-center">
                                        <form action="${sidang.panggil_route}" method="POST" class="d-inline">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill" ${disabledAttr} title="Panggil & kirim notifikasi ke para pihak">
                                                <i class="bi bi-megaphone me-1"></i>Panggil
                                            </button>
                                        </form>
                                    </td>
                                </tr>`;
                        });
                        schedulesBody.innerHTML = html;
                    }
                }

                // 3. Update recent check-ins table
                const recentBody = document.getElementById('dashboard-recent-checkins-body');
                if (recentBody) {
                    if (data.kehadiranTerbaru.length === 0) {
                        recentBody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                                    Belum ada data kehadiran yang tercatat.
                                </td>
                            </tr>`;
                    } else {
                        let html = '';
                        data.kehadiranTerbaru.forEach(k => {
                            html += `
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-primary"><i class="bi bi-clock me-1"></i>${k.waktu_hadir}</span>
                                        <small class="text-muted d-block">${k.tanggal_hadir}</small>
                                    </td>
                                    <td><strong>${k.pihak_nama}</strong></td>
                                    <td><span class="badge bg-light text-dark border">${k.pihak_status}</span></td>
                                    <td>
                                        <a href="${k.perkara_show_route}" class="fw-bold text-decoration-none" style="color: var(--text-primary);">
                                            ${k.nomor_perkara}
                                        </a>
                                    </td>
                                    <td>${k.agenda_sidang}</td>
                                    <td><span class="badge bg-light text-dark border"><i class="bi bi-door-closed me-1"></i>${k.ruang_sidang}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success badge-custom"><i class="bi bi-check me-1"></i>${k.status_hadir}</span>
                                    </td>
                                </tr>`;
                        });
                        recentBody.innerHTML = html;
                    }
                }
            })
            .catch(err => console.error('Error fetching dashboard real-time data:', err));
    }

    // Poll every 3 seconds
    setInterval(fetchDashboardData, 3000);
    fetchDashboardData(); // Also run immediately on load
});
</script>
@endsection
