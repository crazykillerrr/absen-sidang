@extends('layouts.admin')

@section('title', 'Edit Ruang Sidang')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.ruang-sidang.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Edit Ruang Sidang</h4>
</div>

<div class="row">
    <div class="col-lg-6 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.ruang-sidang.update', $ruang->id) }}">
                @csrf
                @method('PUT')

                <!-- Nama Ruang -->
                <div class="mb-3">
                    <label for="nama_ruang" class="form-label fw-semibold">Nama Ruangan</label>
                    <input type="text" name="nama_ruang" id="nama_ruang" class="form-control form-control-custom @error('nama_ruang') is-invalid @enderror" placeholder="Contoh: Ruang Sidang Cakra" value="{{ old('nama_ruang', $ruang->nama_ruang) }}" required>
                    @error('nama_ruang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis Ruang -->
                <div class="mb-4">
                    <label for="jenis_ruang" class="form-label fw-semibold">Jenis Ruangan</label>
                    <select name="jenis_ruang" id="jenis_ruang" class="form-select form-control-custom @error('jenis_ruang') is-invalid @enderror" required>
                        <option value="" disabled>-- Pilih Jenis Ruangan --</option>
                        @foreach ($jenisRuangs as $jenis)
                            <option value="{{ $jenis }}" {{ old('jenis_ruang', $ruang->jenis_ruang) === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_ruang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-save me-2"></i>Perbarui Data
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
