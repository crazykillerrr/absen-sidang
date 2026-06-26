@extends('layouts.admin')

@section('title', 'Detail Perkara')

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div>
        <a href="{{ route('admin.perkara.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <h4 class="fw-bold mb-0" style="color: var(--text-primary);">Detail Perkara PTUN</h4>
    </div>
    <a href="{{ route('admin.perkara.edit', $perkara->id) }}" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-pencil-square me-2"></i>Edit Perkara
    </a>
</div>

<div class="row g-4">
    <!-- Dossier/Detail Card -->
    <div class="col-md-4 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background-color: var(--bg-secondary);">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-folder2-open text-primary fs-3"></i>
                <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Dossier Perkara</h5>
            </div>
            
            <table class="table table-borderless small mb-0 text-dark">
                <tr>
                    <td class="text-secondary fw-medium py-2" style="width: 130px;">Nomor Perkara</td>
                    <td class="py-2"><strong class="text-primary fs-6">{{ $perkara->nomor_perkara }}</strong></td>
                </tr>
                <tr>
                    <td class="text-secondary fw-medium py-2">Tahun Daftar</td>
                    <td class="py-2">{{ $perkara->tahun }}</td>
                </tr>
                <tr>
                    <td class="text-secondary fw-medium py-2">Objek Sengketa</td>
                    <td class="py-2 text-wrap">{{ $perkara->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-secondary fw-medium py-2">Terdaftar Sejak</td>
                    <td class="py-2">{{ $perkara->created_at->format('d-m-Y H:i') }} WIB</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Hearing Timeline / Schedules Card -->
    <div class="col-md-8 col-12">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: var(--bg-secondary);">
            <div class="card-header border-0 bg-transparent p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-3"></i>
                    <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Riwayat Persidangan</h5>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 border-0">
                        <thead>
                            <tr>
                                <th class="border-0">Waktu</th>
                                <th class="border-0">Agenda</th>
                                <th class="border-0">Ruangan</th>
                                <th class="border-0">Jenis</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($perkara->jadwalSidangs as $jadwal)
                                <tr>
                                    <td>
                                        <span class="fw-bold d-block text-dark">{{ \Carbon\Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y') }}</span>
                                        <small class="text-muted">{{ substr($jadwal->jam_sidang, 0, 5) }} WIB</small>
                                    </td>
                                    <td><strong>{{ $jadwal->agenda_sidang }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $jadwal->ruangSidang->nama_ruang }}</span></td>
                                    <td>
                                        @if($jadwal->jenis_sidang === 'Offline')
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary badge-custom">{{ $jadwal->jenis_sidang }}</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary badge-custom">{{ $jadwal->jenis_sidang }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.pihak-sidang.index', $jadwal->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Kelola Litigant">
                                            <i class="bi bi-people me-1"></i>Pihak
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                        Belum ada rincian jadwal persidangan untuk perkara ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
