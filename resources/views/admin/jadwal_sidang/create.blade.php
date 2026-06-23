@extends('layouts.admin')

@section('title', 'Buat Jadwal Sidang')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.jadwal-sidang.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Buat Jadwal Persidangan</h4>
</div>

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.jadwal-sidang.store') }}">
                @csrf

                <!-- Perkara -->
                <div class="mb-3">
                    <label for="perkara_id" class="form-label fw-semibold">Pilih Perkara</label>
                    <select name="perkara_id" id="perkara_id" class="form-select form-control-custom @error('perkara_id') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Nomor Perkara --</option>
                        @foreach ($perkaras as $perkara)
                            <option value="{{ $perkara->id }}" {{ old('perkara_id') == $perkara->id ? 'selected' : '' }}>{{ $perkara->nomor_perkara }} - {{ $perkara->keterangan }}</option>
                        @endforeach
                    </select>
                    @error('perkara_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Ruang Sidang -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="ruang_sidang_id" class="form-label fw-semibold">Ruang Sidang</label>
                        <select name="ruang_sidang_id" id="ruang_sidang_id" class="form-select form-control-custom @error('ruang_sidang_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Ruangan --</option>
                            @foreach ($ruangs as $ruang)
                                <option value="{{ $ruang->id }}" {{ old('ruang_sidang_id') == $ruang->id ? 'selected' : '' }}>{{ $ruang->nama_ruang }} ({{ $ruang->jenis_ruang }})</option>
                            @endforeach
                        </select>
                        @error('ruang_sidang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Sidang -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="jenis_sidang" class="form-label fw-semibold">Jenis Persidangan</label>
                        <select name="jenis_sidang" id="jenis_sidang" class="form-select form-control-custom @error('jenis_sidang') is-invalid @enderror" required>
                            <option value="Offline" {{ old('jenis_sidang') === 'Offline' ? 'selected' : '' }}>Offline (Tatap Muka)</option>
                            <option value="Online" {{ old('jenis_sidang') === 'Online' ? 'selected' : '' }}>Online (E-Court)</option>
                        </select>
                        @error('jenis_sidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Agenda Sidang -->
                <div class="mb-3">
                    <label for="agenda_sidang" class="form-label fw-semibold">Agenda Sidang</label>
                    <select name="agenda_sidang" id="agenda_sidang" class="form-select form-control-custom @error('agenda_sidang') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Agenda Sidang --</option>
                        @foreach ($agendas as $agenda)
                            <option value="{{ $agenda }}" {{ old('agenda_sidang') === $agenda ? 'selected' : '' }}>{{ $agenda }}</option>
                        @endforeach
                    </select>
                    @error('agenda_sidang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Tanggal Sidang -->
                    <div class="col-md-6 col-12 mb-3">
                        <label for="tanggal_sidang" class="form-label fw-semibold">Tanggal Sidang</label>
                        <input type="date" name="tanggal_sidang" id="tanggal_sidang" class="form-control form-control-custom @error('tanggal_sidang') is-invalid @enderror" value="{{ old('tanggal_sidang', date('Y-m-d')) }}" required>
                        @error('tanggal_sidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jam Sidang -->
                    <div class="col-md-6 col-12 mb-4">
                        <label for="jam_sidang" class="form-label fw-semibold">Jam Sidang</label>
                        <input type="time" name="jam_sidang" id="jam_sidang" class="form-control form-control-custom @error('jam_sidang') is-invalid @enderror" value="{{ old('jam_sidang', '09:00') }}" required>
                        @error('jam_sidang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold">
                    <i class="bi bi-calendar-plus me-2"></i>Buat Jadwal Sidang
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
