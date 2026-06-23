@extends('layouts.admin')

@section('title', 'Integrasi SIPP')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Integrasi SIPP PTUN Bandar Lampung</h4>
        <p class="text-secondary small mb-0">Sinkronisasi data jadwal sidang dari SIPP secara berkala untuk kebutuhan absensi mandiri.</p>
    </div>
    <form method="POST" action="{{ route('admin.integrasi-sipp.sync') }}" id="syncForm">
        @csrf
        <button type="submit" class="btn btn-success rounded-pill px-4 py-2.5 fw-semibold d-flex align-items-center gap-2 shadow-sm" id="btnSync">
            <i class="bi bi-cloud-arrow-down-fill fs-5" id="iconSync"></i>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="spinnerSync"></span>
            <span id="textSync">Sinkronisasi Jadwal Sekarang</span>
        </button>
    </form>
</div>

<!-- Statistik Integrasi SIPP -->
<div class="row g-4 mb-4">
    <!-- Waktu Terakhir Sinkron -->
    <div class="col-md-4">
        <div class="card premium-card border-0 p-4 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="premium-card-title d-block mb-1">Terakhir Sinkron</span>
                    <span class="fs-5 fw-bold text-dark d-block">
                        {{ $lastLog ? \Carbon\Carbon::parse($lastLog->waktu_sinkronisasi)->translatedFormat('d F Y') : '-' }}
                    </span>
                    <small class="text-muted">
                        {{ $lastLog ? \Carbon\Carbon::parse($lastLog->waktu_sinkronisasi)->format('H:i:s') . ' WIB' : 'Belum pernah' }}
                    </small>
                </div>
                <div class="icon-box icon-box-primary">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Data Jadwal -->
    <div class="col-md-4">
        <div class="card premium-card border-0 p-4 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="premium-card-title d-block mb-1">Jadwal SIPP Aktif</span>
                    <span class="premium-card-value">{{ $totalSchedules }}</span>
                    <small class="text-muted d-block">Data tersinkronisasi di database</small>
                </div>
                <div class="icon-box icon-box-teal">
                    <i class="bi bi-calendar2-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Sinkronisasi Terakhir -->
    <div class="col-md-4">
        <div class="card premium-card border-0 p-4 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="premium-card-title d-block mb-1">Status Terakhir</span>
                    @if (!$lastLog)
                        <span class="badge bg-secondary badge-custom py-2 px-3 fs-6">Belum Ada</span>
                    @elseif ($lastLog->status === 'berhasil')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 badge-custom py-2 px-3 fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i>Berhasil
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 badge-custom py-2 px-3 fs-6">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal
                        </span>
                    @endif
                    <small class="text-muted d-block mt-2 text-truncate" style="max-width: 200px;" title="{{ $lastLog ? $lastLog->keterangan : '' }}">
                        {{ $lastLog ? $lastLog->keterangan : 'Tidak ada catatan' }}
                    </small>
                </div>
                <div class="icon-box {{ !$lastLog ? 'icon-box-primary' : ($lastLog->status === 'berhasil' ? 'icon-box-teal' : 'icon-box-orange') }}">
                    <i class="bi {{ !$lastLog ? 'bi-shield' : ($lastLog->status === 'berhasil' ? 'bi-shield-check' : 'bi-shield-exclamation') }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--bg-secondary);">
    <div class="card-header bg-transparent border-bottom border-light p-0">
        <ul class="nav nav-tabs border-0 px-4 pt-3 gap-2" id="sippTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active border-0 px-4 py-2.5 fw-semibold position-relative" id="schedules-tab" data-bs-toggle="tab" data-bs-target="#schedules-pane" type="button" role="tab" aria-controls="schedules-pane" aria-selected="true" style="color: var(--text-primary);">
                    <i class="bi bi-list-columns-reverse me-2"></i>Jadwal Hasil Sinkronisasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link border-0 px-4 py-2.5 fw-semibold position-relative" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs-pane" type="button" role="tab" aria-controls="logs-pane" aria-selected="false" style="color: var(--text-primary);">
                    <i class="bi bi-journal-text me-2"></i>Riwayat Sinkronisasi (Log)
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-0">
        <div class="tab-content" id="sippTabContent">
            <!-- Pane 1: Synced Schedules List -->
            <div class="tab-pane fade show active" id="schedules-pane" role="tabpanel" aria-labelledby="schedules-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 border-0">
                        <thead>
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Nomor Perkara</th>
                                <th class="border-0">Agenda</th>
                                <th class="border-0">Tanggal</th>
                                <th class="border-0">Jam</th>
                                <th class="border-0 text-center">Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $index => $sch)
                                <tr>
                                    <td>{{ $schedules->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold" style="color: var(--primary-color);">{{ $sch->perkara->nomor_perkara }}</span>
                                    </td>
                                    <td>{{ $sch->agenda_sidang }}</td>
                                    <td>
                                        <strong>{{ $sch->tanggal_sidang instanceof \Carbon\Carbon ? $sch->tanggal_sidang->format('d-m-Y') : \Carbon\Carbon::parse($sch->tanggal_sidang)->format('d-m-Y') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border"><i class="bi bi-clock me-1"></i>{{ substr($sch->jam_sidang, 0, 5) }} WIB</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 fw-semibold">{{ $sch->sumber_data }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                        Belum ada data jadwal sidang yang disinkronkan dari SIPP.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($schedules->hasPages())
                    <div class="p-4 border-top">
                        {{ $schedules->appends(['logs_page' => $logs->currentPage()])->links() }}
                    </div>
                @endif
            </div>

            <!-- Pane 2: Sync Logs History -->
            <div class="tab-pane fade" id="logs-pane" role="tabpanel" aria-labelledby="logs-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 border-0">
                        <thead>
                            <tr>
                                <th class="border-0">Waktu Sinkronisasi</th>
                                <th class="border-0">Jumlah Data</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Catatan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($log->waktu_sinkronisasi)->format('d-m-Y H:i:s') }}</strong>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $log->jumlah_data }} jadwal</span>
                                    </td>
                                    <td>
                                        @if ($log->status === 'berhasil')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 badge-custom py-1.5 px-2.5">
                                                <i class="bi bi-check-circle-fill me-1"></i>Berhasil
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 badge-custom py-1.5 px-2.5">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Gagal
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $log->keterangan }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                                        Tidak ada riwayat sinkronisasi log.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="p-4 border-top">
                        {{ $logs->appends(['schedules_page' => $schedules->currentPage()])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('syncForm');
        const button = document.getElementById('btnSync');
        const icon = document.getElementById('iconSync');
        const spinner = document.getElementById('spinnerSync');
        const text = document.getElementById('textSync');

        if (form) {
            form.addEventListener('submit', function() {
                // Disable button and show spinner
                button.disabled = true;
                button.classList.remove('btn-success');
                button.classList.add('btn-secondary');
                icon.classList.add('d-none');
                spinner.classList.remove('d-none');
                text.innerText = 'Sedang Menyinkronkan Data...';
            });
        }

        // Active tab check based on query parameter
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'history') {
            const logsTab = document.getElementById('logs-tab');
            if (logsTab) {
                bootstrap.Tab.getInstance(logsTab)?.show() || new bootstrap.Tab(logsTab).show();
            }
        }
    });
</script>

<style>
    .nav-tabs .nav-link {
        border-radius: 0;
        border-bottom: 2px solid transparent !important;
        background: transparent !important;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 2px solid var(--primary-color) !important;
        font-weight: 600 !important;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background-color: var(--primary-color);
    }
</style>
@endsection
