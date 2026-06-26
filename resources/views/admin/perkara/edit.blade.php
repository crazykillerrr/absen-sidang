@extends('layouts.admin')

@section('title', 'Edit Perkara')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.perkara.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Edit Data Perkara</h4>
</div>

<div class="row">
    <div class="col-lg-8 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.perkara.update', $perkara->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Nomor Perkara -->
                    <div class="col-md-8 col-12 mb-3">
                        <label for="nomor_perkara" class="form-label fw-semibold">Nomor Perkara</label>
                        <input type="text" name="nomor_perkara" id="nomor_perkara" class="form-control form-control-custom @error('nomor_perkara') is-invalid @enderror" placeholder="Contoh: 120/G/2026/PTUN.BDL" value="{{ old('nomor_perkara', $perkara->nomor_perkara) }}" required>
                        @error('nomor_perkara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div class="col-md-4 col-12 mb-3">
                        <label for="tahun" class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control form-control-custom @error('tahun') is-invalid @enderror" placeholder="2026" value="{{ old('tahun', $perkara->tahun) }}" required>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold">Keterangan / Objek Sengketa</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-control form-control-custom @error('keterangan') is-invalid @enderror" placeholder="Deskripsi singkat mengenai perkara ini...">{{ old('keterangan', $perkara->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold">
                    <i class="bi bi-save me-2"></i>Perbarui Perkara
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
