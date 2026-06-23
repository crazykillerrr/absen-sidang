@extends('layouts.admin')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Laporan Kehadiran Pihak Berperkara</h4>
    <p class="text-muted mb-0">Hasilkan, saring, dan ekspor laporan kehadiran persidangan ke format PDF atau Excel.</p>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" style="background-color: var(--bg-secondary);">
    <div class="card-header border-0 bg-transparent p-4">
        <h5 class="fw-bold mb-3" style="color: var(--text-primary);">Filter Laporan</h5>
        
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3">
            <!-- Tanggal Awal -->
            <div class="col-md-3">
                <label for="tanggal_awal" class="form-label small fw-semibold text-secondary">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control bg-light" value="{{ request('tanggal_awal') }}">
            </div>

            <!-- Tanggal Akhir -->
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label small fw-semibold text-secondary">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control bg-light" value="{{ request('tanggal_akhir') }}">
            </div>

            <!-- Perkara -->
            <div class="col-md-3">
                <label for="perkara_id" class="form-label small fw-semibold text-secondary">Nomor Perkara</label>
                <select name="perkara_id" id="perkara_id" class="form-select bg-light">
                    <option value="">-- Semua Perkara --</option>
                    @foreach ($perkaras as $perkara)
                        <option value="{{ $perkara->id }}" {{ request('perkara_id') == $perkara->id ? 'selected' : '' }}>{{ $perkara->nomor_perkara }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Agenda -->
            <div class="col-md-3">
                <label for="agenda_sidang" class="form-label small fw-semibold text-secondary">Agenda Sidang</label>
                <select name="agenda_sidang" id="agenda_sidang" class="form-select bg-light">
                    <option value="">-- Semua Agenda --</option>
                    @foreach ($agendas as $agenda)
                        <option value="{{ $agenda }}" {{ request('agenda_sidang') === $agenda ? 'selected' : '' }}>{{ $agenda }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 d-flex justify-content-between mt-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-secondary px-4"><i class="bi bi-filter me-2"></i>Filter</button>
                    @if(request('tanggal_awal') || request('tanggal_akhir') || request('perkara_id') || request('agenda_sidang'))
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary px-3">Reset</a>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn btn-outline-danger px-4">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Ekspor PDF
                    </a>
                    <a href="{{ route('admin.laporan.export-excel', request()->query()) }}" class="btn btn-outline-success px-4">
                        <i class="bi bi-file-earmark-excel me-2"></i>Ekspor Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom mb-0 border-0">
                <thead>
                    <tr>
                        <th class="border-0">No</th>
                        <th class="border-0">Nomor Perkara</th>
                        <th class="border-0">Agenda Sidang</th>
                        <th class="border-0">Tanggal Sidang</th>
                        <th class="border-0">Nama Pihak</th>
                        <th class="border-0">Status Pihak</th>
                        <th class="border-0">Nomor HP</th>
                        <th class="border-0">Waktu Absen</th>
                        <th class="border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kehadirans as $index => $kehadiran)
                        @php
                            $pihak = $kehadiran->pihakSidang;
                            $jadwal = $pihak->jadwalSidang;
                            $perkara = $jadwal->perkara;
                            
                            $tanggal = $jadwal->tanggal_sidang instanceof \Carbon\Carbon 
                                ? $jadwal->tanggal_sidang->format('d-m-Y') 
                                : \Carbon\Carbon::parse($jadwal->tanggal_sidang)->format('d-m-Y');
                        @endphp
                        <tr>
                            <td>{{ $kehadirans->firstItem() + $index }}</td>
                            <td><strong style="color: var(--text-primary);">{{ $perkara->nomor_perkara }}</strong></td>
                            <td>{{ $jadwal->agenda_sidang }}</td>
                            <td>
                                <span>{{ $tanggal }}</span>
                                <small class="text-muted d-block">{{ substr($jadwal->jam_sidang, 0, 5) }} WIB</small>
                            </td>
                            <td><strong>{{ $pihak->nama }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $pihak->status_pihak }}</span></td>
                            <td>{{ $pihak->nomor_hp }}</td>
                            <td><span class="text-success fw-semibold"><i class="bi bi-clock me-1"></i>{{ $kehadiran->waktu_hadir->format('H:i') }} WIB</span></td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success badge-custom"><i class="bi bi-check me-1"></i>{{ $kehadiran->status_hadir }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-file-earmark-bar-graph fs-1 d-block mb-3"></i>
                                Data kehadiran tidak ditemukan untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($kehadirans->hasPages())
        <div class="card-footer bg-transparent border-0 p-4">
            {{ $kehadirans->links() }}
        </div>
    @endif
</div>
@endsection
