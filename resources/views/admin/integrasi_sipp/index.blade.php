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
                                <th class="border-0">Waktu & Jenis</th>
                                <th class="border-0">Nomor Perkara</th>
                                <th class="border-0">Agenda</th>
                                <th class="border-0">Ruang Sidang</th>
                                <th class="border-0 text-center">Kehadiran Pihak</th>
                                <th class="border-0">Terakhir Sinkron</th>
                                <th class="border-0 text-center" style="width: 220px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $index => $sch)
                                @php
                                    $totalPihak = $sch->pihakSidangs->count();
                                    $hadirPihak = $sch->pihakSidangs->filter(function($p) { return $p->kehadiran; })->count();
                                    $lengkap = ($totalPihak > 0 && $hadirPihak === $totalPihak);
                                    
                                    $tanggal = $sch->tanggal_sidang instanceof \Carbon\Carbon 
                                        ? $sch->tanggal_sidang->format('d-m-Y') 
                                        : \Carbon\Carbon::parse($sch->tanggal_sidang)->format('d-m-Y');
                                @endphp
                                <tr>
                                    <td>{{ $schedules->firstItem() + $index }}</td>
                                    <td>
                                        <strong class="d-block text-dark">{{ $tanggal }}</strong>
                                        <span class="badge bg-light text-primary border mb-1"><i class="bi bi-clock me-1"></i>{{ substr($sch->jam_sidang, 0, 5) }} WIB</span>
                                        <small class="text-secondary d-block">{{ $sch->jenis_sidang }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold d-block" style="color: var(--primary-color);">{{ $sch->perkara->nomor_perkara }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5 small mt-1">SIPP</span>
                                    </td>
                                    <td>
                                        <span class="text-wrap d-block" style="max-width: 250px;">{{ $sch->agenda_sidang }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $sch->ruangSidang->nama_ruang }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">{{ $totalPihak }} Pihak Hadir</span>
                                    </td>
                                    <td>
                                        <span class="small text-secondary d-block">
                                            {{ $sch->terakhir_sinkron ? \Carbon\Carbon::parse($sch->terakhir_sinkron)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ $sch->terakhir_sinkron ? \Carbon\Carbon::parse($sch->terakhir_sinkron)->format('H:i') . ' WIB' : '' }}
                                        </small>
                                    </td>
                                                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <button type="button" 
                                                class="btn btn-sm btn-outline-info border-0 rounded-circle p-2 btn-detail" 
                                                title="Detil Sidang SIPP"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailSidangModal"
                                                data-nomor-perkara="{{ $sch->perkara->nomor_perkara }}"
                                                data-jenis-perkara="{{ $sch->jenis_perkara ?? 'Lain-Lain' }}"
                                                data-pihak="{{ $sch->pihak ?? 'Belum terdata di SIPP' }}"
                                                data-hari-tanggal="{{ \Carbon\Carbon::parse($sch->tanggal_sidang)->translatedFormat('l, d M Y') }}"
                                                data-jam="{{ substr($sch->jam_sidang, 0, 5) }} WIB s/d Selesai"
                                                data-agenda="{{ $sch->agenda_sidang }}"
                                                data-sidang-keliling="{{ $sch->sidang_keliling ?? 'Tidak' }}"
                                                data-ruang="{{ $sch->ruangSidang->nama_ruang }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.pihak-sidang.index', $sch->id) }}" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-semibold small" title="Kelola Litigant/Pihak">
                                                <i class="bi bi-people me-1"></i>Pihak Hadir ({{ $totalPihak }})
                                            </a>
                                            <a href="{{ route('admin.jadwal-sidang.edit', $sch->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.jadwal-sidang.destroy', $sch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal sidang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
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

<!-- Modal Detil Jadwal Sidang SIPP -->
<div class="modal fade" id="detailSidangModal" tabindex="-1" aria-labelledby="detailSidangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-success text-white text-center py-4 position-relative">
                <h4 class="modal-title fw-bold m-0" id="detailSidangModalLabel">Detil Jadwal Sidang</h4>
                <button type="button" class="btn-close btn-close-white position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0" style="font-size: 15px;">
                        <tbody>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="width: 35%; --bs-bg-opacity: .85;">Nomor Perkara</th>
                                <td id="modal-nomor-perkara" class="py-3 px-4 fw-semibold text-dark"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Jenis Perkara</th>
                                <td id="modal-jenis-perkara" class="py-3 px-4 text-secondary"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Pihak</th>
                                <td id="modal-pihak" class="py-3 px-4 text-dark text-wrap" style="line-height: 1.6;"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Hari dan Tanggal Sidang</th>
                                <td id="modal-hari-tanggal" class="py-3 px-4 text-secondary"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Jam Sidang</th>
                                <td id="modal-jam" class="py-3 px-4 text-secondary"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Agenda</th>
                                <td id="modal-agenda" class="py-3 px-4 text-dark"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Sidang Keliling</th>
                                <td id="modal-sidang-keliling" class="py-3 px-4 text-secondary"></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-white py-3 px-4" style="--bs-bg-opacity: .85;">Ruang Sidang</th>
                                <td id="modal-ruang" class="py-3 px-4 fw-semibold text-dark"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer bg-light justify-content-center border-0 py-3">
                <button type="button" class="btn btn-success px-5 rounded-pill fw-semibold shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle detail button clicks to populate modal
        const detailButtons = document.querySelectorAll('.btn-detail');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modal-nomor-perkara').innerText = this.getAttribute('data-nomor-perkara');
                document.getElementById('modal-jenis-perkara').innerText = this.getAttribute('data-jenis-perkara');
                document.getElementById('modal-pihak').innerText = this.getAttribute('data-pihak');
                document.getElementById('modal-hari-tanggal').innerText = this.getAttribute('data-hari-tanggal');
                document.getElementById('modal-jam').innerText = this.getAttribute('data-jam');
                document.getElementById('modal-agenda').innerText = this.getAttribute('data-agenda');
                document.getElementById('modal-sidang-keliling').innerText = this.getAttribute('data-sidang-keliling');
                document.getElementById('modal-ruang').innerText = this.getAttribute('data-ruang');
            });
        });

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
