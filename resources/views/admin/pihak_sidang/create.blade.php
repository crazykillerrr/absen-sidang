@extends('layouts.admin')

@section('title', 'Tambah Pihak Berperkara')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pihak-sidang.index', $jadwal->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <h4 class="fw-bold" style="color: var(--text-primary);">Tambah Pihak Sidang Wajib Hadir</h4>
    <p class="text-muted">Jadwal Perkara: <strong>{{ $jadwal->perkara->nomor_perkara }}</strong> (Agenda: {{ $jadwal->agenda_sidang }})</p>
</div>

<div class="row">
    <div class="col-lg-6 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" style="background-color: var(--bg-secondary);">
            
            <form method="POST" action="{{ route('admin.pihak-sidang.store', $jadwal->id) }}">
                @csrf

                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap Pihak</label>
                    <input type="text" name="nama" id="nama" class="form-control form-control-custom @error('nama') is-invalid @enderror" placeholder="Contoh: Supriyanto, S.E." value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div class="mb-3">
                    <label for="nomor_hp" class="form-label fw-semibold">Nomor HP / WhatsApp Pihak</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                        <input type="text" name="nomor_hp" id="nomor_hp" class="form-control form-control-custom @error('nomor_hp') is-invalid @enderror" placeholder="Contoh: 081299990001" value="{{ old('nomor_hp') }}" required>
                    </div>
                    <small class="text-muted font-monospace">Nomor HP yang dihubungi (bisa untuk update/absensi).</small>
                    @error('nomor_hp')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Pihak -->
                <div class="mb-4">
                    <label for="status_pihak" class="form-label fw-semibold">Status Pihak / Kedudukan Hukum</label>
                    <select name="status_pihak" id="status_pihak" class="form-select form-control-custom @error('status_pihak') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Status Kedudukan Pihak --</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ old('status_pihak') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status_pihak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-save me-2"></i>Simpan Pihak
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
