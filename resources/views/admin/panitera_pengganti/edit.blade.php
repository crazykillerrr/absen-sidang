@extends('layouts.admin')

@section('title', 'Edit Panitera Pengganti')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.panitera-pengganti.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Edit Panitera Pengganti</h4>
</div>

<div class="row">
    <div class="col-lg-6 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.panitera-pengganti.update', $pp->id) }}">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" id="nama" class="form-control form-control-custom @error('nama') is-invalid @enderror" placeholder="Contoh: Syamsul Bahri, S.H." value="{{ old('nama', $pp->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-3">
                    <label for="nomor_whatsapp" class="form-label fw-semibold">Nomor WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                        <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-control form-control-custom @error('nomor_whatsapp') is-invalid @enderror" placeholder="Contoh: 085234567811" value="{{ old('nomor_whatsapp', $pp->nomor_whatsapp) }}" required>
                    </div>
                    <small class="text-muted">Gunakan format angka tanpa spasi atau tanda hubung.</small>
                    @error('nomor_whatsapp')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Alamat Email (Opsional)</label>
                    <input type="email" name="email" id="email" class="form-control form-control-custom @error('email') is-invalid @enderror" placeholder="Contoh: syamsul.b@ptun.go.id" value="{{ old('email', $pp->email) }}">
                    @error('email')
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
