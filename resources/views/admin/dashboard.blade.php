@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
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
                    <h3 class="premium-card-value mb-0">{{ $totalKehadiranHariIni }}</h3>
                </div>
                <div class="icon-box icon-box-orange">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Sidang Lengkap -->
    <div class="col-md-6 col-lg-3 col-12">
        <div class="card premium-card border-0 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="premium-card-title mb-1">Sidang Lengkap</h6>
                    <h3 class="premium-card-value mb-0">{{ $totalSidangLengkap }} <span class="fs-6 text-muted">/{{ $totalJadwalHariIni }}</span></h3>
                </div>
                <div class="icon-box" style="background-color: #d1fae5; color: #10b981;">
                    <i class="bi bi-check2-all"></i>
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

<div class="row g-4 mb-4">
    <!-- Grafik Kehadiran per Hari -->
    <div class="col-md-7 col-12">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-4" style="color: var(--text-primary);">Statistik Kehadiran 7 Hari Terakhir</h5>
            <div style="height: 300px;">
                <canvas id="chartKehadiranPerHari"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Kehadiran per Agenda -->
    <div class="col-md-5 col-12">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-4" style="color: var(--text-primary);">Distribusi Kehadiran per Agenda</h5>
            <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <canvas id="chartKehadiranPerAgenda"></canvas>
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
                        <th class="border-0">Majelis Hakim & PP</th>
                        <th class="border-0">Kehadiran Pihak</th>
                        <th class="border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sidangHariIni as $sidang)
                        @php
                            $totalPihak = $sidang->pihakSidangs->count();
                            $hadirPihak = $sidang->pihakSidangs->filter(function($p) { return $p->kehadiran; })->count();
                            $lengkap = ($totalPihak > 0 && $hadirPihak === $totalPihak);
                            
                            $ketua = $sidang->perkara->hakims->firstWhere('pivot.jabatan', 'Ketua Majelis');
                            $pp = $sidang->perkara->paniteraPenggantis->first();
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
                                <small class="d-block"><strong class="text-secondary">Ketua:</strong> {{ $ketua->nama ?? '-' }}</small>
                                <small class="d-block"><strong class="text-secondary">PP:</strong> {{ $pp->nama ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px; min-width: 80px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalPihak > 0 ? ($hadirPihak / $totalPihak) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="small fw-semibold">{{ $hadirPihak }}/{{ $totalPihak }}</span>
                                </div>
                                <small class="text-muted"><a href="{{ route('admin.pihak-sidang.index', $sidang->id) }}" class="text-decoration-none">Detail Pihak</a></small>
                            </td>
                            <td class="text-center">
                                @if ($totalPihak === 0)
                                    <span class="badge bg-secondary">Belum Diisi Pihak</span>
                                @elseif ($lengkap)
                                    <span class="badge bg-success bg-opacity-10 text-success badge-custom"><i class="bi bi-check-circle me-1"></i>Lengkap</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning badge-custom"><i class="bi bi-exclamation-circle me-1"></i>Belum Lengkap</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dynamically retrieve theme colors from CSS variables
    const getThemeColor = (variableName, fallback) => {
        return window.getComputedStyle(document.documentElement).getPropertyValue(variableName).trim() || fallback;
    };

    const textSecondary = getThemeColor('--text-secondary', '#566a5e');
    const primaryColor = getThemeColor('--primary-color', '#0c3e26');
    const accentGold = getThemeColor('--accent-gold', '#aa841c');
    const borderColor = getThemeColor('--border-color', 'rgba(12, 62, 38, 0.06)');

    // Config Chart 1: Kehadiran per Hari
    const labelsHari = {!! json_encode($kehadiranPerHariLabels) !!};
    const dataHari = {!! json_encode($kehadiranPerHariData) !!};

    const ctxHari = document.getElementById('chartKehadiranPerHari').getContext('2d');
    new Chart(ctxHari, {
        type: 'line',
        data: {
            labels: labelsHari,
            datasets: [{
                label: 'Jumlah Hadir',
                data: dataHari,
                borderColor: primaryColor,
                backgroundColor: 'rgba(12, 62, 38, 0.05)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: accentGold,
                pointBorderColor: '#ffffff',
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: textSecondary, font: { family: 'Outfit' } },
                    grid: { color: borderColor }
                },
                x: {
                    ticks: { color: textSecondary, font: { family: 'Outfit' } },
                    grid: { display: false }
                }
            }
        }
    });

    // Config Chart 2: Kehadiran per Agenda
    const labelsAgenda = {!! json_encode($agendaLabels) !!};
    const dataAgenda = {!! json_encode($agendaTotals) !!};

    const ctxAgenda = document.getElementById('chartKehadiranPerAgenda').getContext('2d');
    if (labelsAgenda.length === 0) {
        // Draw empty text if no data
        ctxAgenda.font = "14px Outfit";
        ctxAgenda.fillStyle = textSecondary;
        ctxAgenda.textAlign = "center";
        ctxAgenda.fillText("Belum ada data kehadiran berdasarkan agenda.", 150, 150);
    } else {
        new Chart(ctxAgenda, {
            type: 'doughnut',
            data: {
                labels: labelsAgenda,
                datasets: [{
                    data: dataAgenda,
                    backgroundColor: [
                        '#0c3e26',
                        '#aa841c',
                        '#0d9488',
                        '#d97706',
                        '#10b981',
                        '#f59e0b'
                    ],
                    borderWidth: 2,
                    borderColor: getThemeColor('--bg-secondary', '#ffffff')
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { family: 'Outfit', size: 11 },
                            color: textSecondary
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
</script>
@endsection
