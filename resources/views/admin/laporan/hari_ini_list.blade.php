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
        @endphp
        <div class="card border-light rounded-4 mb-4 scroll-item-card shadow-sm">
            <!-- Card Header (Nomor Perkara, Ruang Sidang, Agenda) -->
            <div class="card-header border-0 bg-transparent p-4 pb-2 card-item-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-case-box">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div>
                            <span class="text-secondary small d-block mb-0.5 fw-semibold text-uppercase tracking-wider" style="font-size: 11px;">Nomor Perkara</span>
                            <h5 class="fw-bold mb-0 card-title-text text-dark">{{ $nomorPerkara }}</h5>
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <span class="badge badge-ruang"><i class="bi bi-door-closed me-1.5"></i>{{ $ruang ? $ruang->nama_ruang : '-' }}</span>
                        <span class="badge badge-pihak">
                            <i class="bi bi-people-fill me-1.5"></i>{{ $items->count() }} Pihak Hadir
                        </span>
                    </div>
                </div>
                
                @if($jadwal)
                    <div class="mt-3 p-3 rounded-3 bg-light bg-opacity-70 border border-light d-flex flex-wrap gap-4 small agenda-info-bar">
                        <div>
                            <span class="text-secondary d-block" style="font-size: 11px;">Agenda Sidang</span>
                            <strong class="text-dark">{{ $jadwal->agenda_sidang }}</strong>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <span class="text-secondary d-block" style="font-size: 11px;">Jam Sidang</span>
                            <strong class="text-dark"><i class="bi bi-clock me-1"></i>{{ substr($jadwal->jam_sidang, 0, 5) }} WIB</strong>
                        </div>
                        @if($jadwal->jenis_sidang)
                            <div class="vr"></div>
                            <div>
                                <span class="text-secondary d-block" style="font-size: 11px;">Sifat Sidang</span>
                                <span class="badge bg-white text-success border mt-0.5" style="border-color: var(--border-color) !important;">{{ $jadwal->jenis_sidang }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Card Body (Daftar Pihak Hadir) -->
            <div class="card-body p-4 pt-2 card-item-body">
                <div class="d-flex flex-column gap-3 scroll-rows-container">
                    @foreach ($items as $subIndex => $kehadiran)
                        @php
                            $subPihak = $kehadiran->pihakSidang;
                            $initial = strtoupper(substr($subPihak->nama, 0, 1));
                        @endphp
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-light transition-all attendee-row-card" style="background-color: var(--bg-secondary);">
                            <!-- Pihak Info -->
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle-display d-flex align-items-center justify-content-center text-white fw-bold">
                                    {{ $initial }}
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark attendee-name">{{ $subPihak->nama }}</h6>
                                    <span class="badge bg-light text-secondary border attendee-role-badge" style="font-size: 11px;">{{ $subPihak->status_pihak }}</span>
                                </div>
                            </div>
                            
                            <!-- Kontak -->
                            {{--
                            <div class="d-none d-md-block text-secondary small text-truncate" style="max-width: 180px;">
                                <i class="bi bi-telephone me-1.5 text-success"></i> {{ $subPihak->nomor_hp }}
                            </div>
                            --}}

                            <!-- Waktu Hadir & Status -->
                            <div class="d-flex align-items-center gap-4">
                                <div class="text-end">
                                    <span class="small text-secondary d-block" style="font-size: 10px;">Waktu Absen</span>
                                    <span class="badge bg-light text-primary border px-3 py-1.5 fw-semibold font-monospace attendance-time-badge">
                                        <i class="bi bi-clock me-1"></i>{{ $kehadiran->waktu_hadir->format('H:i') }} WIB
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                <div class="text-end">
                                    <span class="small text-secondary d-block text-center" style="font-size: 10px;">Status</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-1.5 status-badge" style="min-width: 100px;">
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
