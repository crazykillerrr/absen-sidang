@php
    $groupedKehadirans = $kehadirans->groupBy(function($kehadiran) {
        return $kehadiran->pihakSidang->jadwalSidang->perkara->nomor_perkara;
    });
@endphp

@if($groupedKehadirans->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar2-x fs-1 d-block mb-3 text-secondary"></i>
        <h5 class="fw-bold">Belum Ada Kehadiran Hari Ini</h5>
        <p class="small mb-0">Daftar akan terupdate otomatis saat ada pihak persidangan melakukan absensi.</p>
    </div>
@else
    @foreach($groupedKehadirans as $nomorPerkara => $items)
        @php
            $firstItem = $items->first();
            $pihak = $firstItem->pihakSidang;
            $jadwal = $pihak ? $pihak->jadwalSidang : null;
            $ruang = $jadwal ? $jadwal->ruangSidang : null;
            $statusSidang = $jadwal ? ($jadwal->status_sidang ?? 'belum_dimulai') : 'belum_dimulai';

            $cardClass = 'scroll-item-card shadow-sm border-light';
            $cardStyle = '';
            if ($statusSidang === 'berlangsung') {
                $cardClass = 'scroll-item-card shadow-sm border-success-live';
                $cardStyle = 'border: 2.5px solid #10b981 !important; background-color: #f0fdf4 !important;';
            } elseif ($statusSidang === 'selesai') {
                $cardClass = 'scroll-item-card shadow-sm border-danger-done';
                $cardStyle = 'border: 2.5px solid #ef4444 !important; background-color: #fef2f2 !important;';
            }
        @endphp
        <div class="card rounded-4 mb-4 {{ $cardClass }}" style="{{ $cardStyle }}">
            <!-- Card Header (Nomor Perkara, Ruang Sidang, Agenda & Tombol Status Sidang) -->
            <div class="card-header border-0 bg-transparent p-3 p-md-4 pb-2 card-item-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 gap-md-3">
                    <div class="d-flex align-items-center gap-2 gap-md-3">
                        <div class="icon-case-box {{ $statusSidang === 'berlangsung' ? 'bg-success text-white' : ($statusSidang === 'selesai' ? 'bg-danger text-white' : '') }}">
                            <i class="bi {{ $statusSidang === 'berlangsung' ? 'bi-broadcast' : ($statusSidang === 'selesai' ? 'bi-check-circle-fill' : 'bi-folder2-open') }}"></i>
                        </div>
                        <div>
                            <span class="text-secondary small d-block mb-0.5 fw-semibold text-uppercase tracking-wider" style="font-size: 11px;">Nomor Perkara</span>
                            <h5 class="fw-bold mb-0 card-title-text {{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-dark') }}">{{ $nomorPerkara }}</h5>
                        </div>
                    </div>
                    
                    <!-- Ujung Kanan Atas: Ruang, Count, & Button Status Sidang -->
                    <div class="d-flex gap-1.5 gap-md-2 align-items-center flex-wrap ms-auto ms-sm-0 mt-2 mt-sm-0">
                        <span class="badge badge-ruang"><i class="bi bi-door-closed me-1.5"></i>{{ $ruang ? $ruang->nama_ruang : '-' }}</span>
                        <span class="badge badge-pihak">
                            <i class="bi bi-people-fill me-1.5"></i>{{ $items->count() }} Pihak Hadir
                        </span>

                        @if($jadwal)
                            @if($statusSidang === 'berlangsung')
                                <span class="badge bg-success text-white px-2.5 px-md-3 py-1.5 py-md-2 rounded-pill fw-bold blink-green-badge shadow-sm d-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                                    <span class="pulse-dot-green-blink"></span>
                                    <span>SIDANG BERLANGSUNG</span>
                                </span>
                                <button type="button" onclick="changeStatusSidang({{ $jadwal->id }}, 'selesai')" class="btn btn-sm btn-danger rounded-pill px-2.5 px-md-3 py-1.5 shadow-sm fw-bold border-0 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Sidang Selesai</span>
                                </button>
                            @elseif($statusSidang === 'selesai')
                                <span class="badge bg-danger text-white px-2.5 px-md-3 py-1.5 py-md-2 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>SIDANG SELESAI</span>
                                </span>
                                <button type="button" onclick="changeStatusSidang({{ $jadwal->id }}, 'berlangsung')" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small" title="Mulai Kembali Sidang">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            @else
                                <button type="button" onclick="changeStatusSidang({{ $jadwal->id }}, 'berlangsung')" class="btn btn-sm btn-success rounded-pill px-2.5 px-md-3 py-1.5 shadow-sm fw-bold d-flex align-items-center gap-1.5 pulse-green-btn">
                                    <i class="bi bi-play-circle-fill"></i>
                                    <span>Mulai Sidang</span>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
                
                @if($jadwal)
                    <div class="mt-3 p-2.5 p-md-3 rounded-3 {{ $statusSidang === 'berlangsung' ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : ($statusSidang === 'selesai' ? 'bg-danger bg-opacity-10 border border-danger border-opacity-25' : 'bg-light bg-opacity-70 border border-light') }} d-flex flex-wrap gap-2 gap-md-4 small agenda-info-bar">
                        <div>
                            <span class="{{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-secondary') }} d-block" style="font-size: 11px;">Agenda Sidang</span>
                            <strong class="{{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-dark') }}">{{ $jadwal->agenda_sidang }}</strong>
                        </div>
                        <div class="vr d-none d-sm-block"></div>
                        <div>
                            <span class="{{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-secondary') }} d-block" style="font-size: 11px;">Jam Sidang</span>
                            <strong class="{{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-dark') }}"><i class="bi bi-clock me-1"></i>{{ substr($jadwal->jam_sidang, 0, 5) }} WIB</strong>
                        </div>
                        @if($jadwal->jenis_sidang)
                            <div class="vr d-none d-sm-block"></div>
                            <div>
                                <span class="{{ $statusSidang === 'berlangsung' ? 'text-success' : ($statusSidang === 'selesai' ? 'text-danger' : 'text-secondary') }} d-block" style="font-size: 11px;">Sifat Sidang</span>
                                <span class="badge {{ $statusSidang === 'berlangsung' ? 'bg-success text-white' : ($statusSidang === 'selesai' ? 'bg-danger text-white' : 'bg-white text-success border') }} mt-0.5">{{ $jadwal->jenis_sidang }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Banner Status Sidang -->
                    @if($statusSidang === 'berlangsung')
                        <div class="mt-2.5 p-2.5 px-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-20 d-flex align-items-center gap-2 text-success fw-semibold small blink-green-text">
                            <i class="bi bi-broadcast fs-5 flex-shrink-0"></i>
                            <span>LIVE PERSIDANGAN: Sidang sedang berlangsung di {{ $ruang ? $ruang->nama_ruang : 'Ruang Sidang' }}. Harap para pihak tenang dan tertib.</span>
                        </div>
                    @elseif($statusSidang === 'selesai')
                        <div class="mt-2.5 p-2.5 px-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-20 d-flex align-items-center gap-2 text-danger fw-semibold small">
                            <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                            <span>INFORMASI: Persidangan perkara ini telah selesai dilaksanakan.</span>
                        </div>
                    @endif
                @endif
            </div>
            
            <!-- Card Body (Daftar Pihak Hadir) -->
            <div class="card-body p-3 p-md-4 pt-2 card-item-body">
                <div class="d-flex flex-column gap-2 gap-md-3 scroll-rows-container">
                    @foreach ($items as $subIndex => $kehadiran)
                        @php
                            $subPihak = $kehadiran->pihakSidang;
                            $initial = strtoupper(substr($subPihak->nama, 0, 1));
                        @endphp
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between p-2.5 p-md-3 gap-2 gap-sm-3 rounded-3 border border-light transition-all attendee-row-card" style="background-color: var(--bg-secondary);">
                            <!-- Pihak Info -->
                            <div class="d-flex align-items-center gap-2.5 gap-md-3 w-100 w-sm-auto">
                                <div class="avatar-circle-display d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0">
                                    {{ $initial }}
                                </div>
                                <div class="text-truncate">
                                    <h6 class="mb-0.5 fw-bold text-dark attendee-name text-truncate">{{ $subPihak->nama }}</h6>
                                    <span class="badge bg-light text-secondary border attendee-role-badge" style="font-size: 11px;">{{ $subPihak->status_pihak }}</span>
                                </div>
                            </div>

                            <!-- Waktu Hadir & Status -->
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 gap-md-4 w-100 w-sm-auto pt-2 pt-sm-0 border-top border-sm-0" style="border-color: rgba(0,0,0,0.06) !important;">
                                <div class="text-start text-sm-end">
                                    <span class="small text-secondary d-block" style="font-size: 10px;">Waktu Absen</span>
                                    <span class="badge bg-light text-primary border px-2.5 px-md-3 py-1.5 fw-semibold font-monospace attendance-time-badge">
                                        <i class="bi bi-clock me-1"></i>{{ $kehadiran->waktu_hadir->format('H:i') }} WIB
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                <div class="text-end">
                                    <span class="small text-secondary d-block text-center" style="font-size: 10px;">Status</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 px-md-3 py-1.5 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-1.5 status-badge" style="min-width: 90px;">
                                        <span class="pulse-dot-green"></span>
                                        {{ strtoupper($kehadiran->status_hadir) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endif
