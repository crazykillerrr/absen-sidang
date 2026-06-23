@extends('layouts.admin')

@section('title', 'Tambah Perkara')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.perkara.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Tambah Perkara Baru</h4>
</div>

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.perkara.store') }}">
                @csrf

                <div class="row">
                    <!-- Nomor Perkara -->
                    <div class="col-md-8 col-12 mb-3">
                        <label for="nomor_perkara" class="form-label fw-semibold">Nomor Perkara</label>
                        <input type="text" name="nomor_perkara" id="nomor_perkara" class="form-control form-control-custom @error('nomor_perkara') is-invalid @enderror" placeholder="Contoh: 120/G/2026/PTUN.JKT" value="{{ old('nomor_perkara') }}" required>
                        @error('nomor_perkara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div class="col-md-4 col-12 mb-3">
                        <label for="tahun" class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control form-control-custom @error('tahun') is-invalid @enderror" placeholder="2026" value="{{ old('tahun', date('Y')) }}" required>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold">Keterangan / Objek Sengketa</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control form-control-custom @error('keterangan') is-invalid @enderror" placeholder="Deskripsi singkat mengenai perkara ini...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4" style="color: var(--border-color);">

                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-shield-shaded me-2"></i>Majelis Hakim & Panitera</h5>

                <!-- Ketua Majelis -->
                <div class="mb-3">
                    <label for="ketua_majelis" class="form-label fw-semibold">Ketua Majelis Hakim</label>
                    <select name="ketua_majelis" id="ketua_majelis" class="form-select form-control-custom @error('ketua_majelis') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Ketua Majelis --</option>
                        @foreach ($hakims as $hakim)
                            <option value="{{ $hakim->id }}" {{ old('ketua_majelis') == $hakim->id ? 'selected' : '' }}>{{ $hakim->nama }}</option>
                        @endforeach
                    </select>
                    @error('ketua_majelis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hakim Anggota (Checkboxes) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Hakim Anggota (Pilih minimal 1)</label>
                    <div class="row g-2 p-3 bg-light rounded-3 border" style="max-height: 200px; overflow-y: auto;">
                        @foreach ($hakims as $hakim)
                            <div class="col-md-6 col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="hakim_anggota[]" value="{{ $hakim->id }}" id="hakim_anggota_{{ $hakim->id }}" 
                                        {{ is_array(old('hakim_anggota')) && in_array($hakim->id, old('hakim_anggota')) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="hakim_anggota_{{ $hakim->id }}">
                                        {{ $hakim->nama }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('hakim_anggota')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Panitera Pengganti -->
                <div class="mb-4">
                    <label for="panitera_pengganti" class="form-label fw-semibold">Panitera Pengganti (PP)</label>
                    <select name="panitera_pengganti" id="panitera_pengganti" class="form-select form-control-custom @error('panitera_pengganti') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Panitera Pengganti --</option>
                        @foreach ($pps as $pp)
                            <option value="{{ $pp->id }}" {{ old('panitera_pengganti') == $pp->id ? 'selected' : '' }}>{{ $pp->nama }}</option>
                        @endforeach
                    </select>
                    @error('panitera_pengganti')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold">
                    <i class="bi bi-save me-2"></i>Simpan Perkara
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
