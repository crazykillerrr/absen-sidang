@extends('layouts.admin')

@section('title', 'Pihak Berperkara')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.jadwal-sidang.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mb-2">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Jadwal
    </a>
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--text-primary);">Daftar Kehadiran Pihak</h4>
            <p class="text-muted mb-0">Sidang Perkara: <strong class="text-primary">{{ $jadwal->perkara->nomor_perkara }}</strong> (Agenda: {{ $jadwal->agenda_sidang }})</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Kehadiran Pihak -->
    <div class="col-lg-8 col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bg-secondary);">
            <div class="card-header border-0 bg-transparent p-4">
                <h5 class="fw-bold mb-0" style="color: var(--text-primary);">Daftar Kehadiran Pihak</h5>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 border-0">
                        <thead>
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Nama Lengkap</th>
                                <th class="border-0">Status Pihak</th>
                                <th class="border-0">Nomor HP</th>
                                <th class="border-0">Waktu Hadir</th>
                                <th class="border-0 text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pihak-table-body">
                            @forelse ($pihaks as $index => $pihak)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong style="color: var(--text-primary);">{{ $pihak->nama }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $pihak->status_pihak }}</span></td>
                                    <td>{{ $pihak->nomor_hp }}</td>
                                    <td>
                                        @if($pihak->kehadiran)
                                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>{{ $pihak->kehadiran->waktu_hadir->format('H:i') }} WIB</span>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">Absen via QR</small>
                                        @else
                                            <span class="text-danger fw-semibold"><i class="bi bi-clock me-1"></i>Belum Hadir</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.pihak-sidang.edit', $pihak->id) }}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.pihak-sidang.destroy', $pihak->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pihak wajib hadir ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                                        Belum ada pihak yang melakukan absensi hadir untuk sidang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="col-lg-4 col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4" style="background-color: var(--bg-secondary);">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code me-2"></i>QR Code Absensi</h5>
            <p class="text-muted small">Cetak atau tampilkan QR Code ini di lokasi persidangan untuk di-scan oleh pihak berperkara.</p>
            
            <div class="row g-3 mt-2">
                <!-- QR Code 1: Pos Satpam -->
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-white">
                        <div class="mb-2">
                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(1)->color(11, 42, 73)->generate(url('/absensi?qrcode=QR-SATPAM')) !!}
                        </div>
                        <span class="fw-bold text-dark small">Pos Satpam</span>
                        <a href="{{ url('/absensi?qrcode=QR-SATPAM') }}" target="_blank" class="d-block small text-decoration-none mt-1">Uji Scan</a>
                    </div>
                </div>
                
                <!-- QR Code 2: Ruang Tunggu -->
                <div class="col-6">
                    <div class="p-2 border rounded-3 bg-white">
                        <div class="mb-2">
                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(1)->color(11, 42, 73)->generate(url('/absensi?qrcode=QR-TUNGGU')) !!}
                        </div>
                        <span class="fw-bold text-dark small">Ruang Tunggu</span>
                        <a href="{{ url('/absensi?qrcode=QR-TUNGGU') }}" target="_blank" class="d-block small text-decoration-none mt-1">Uji Scan</a>
                    </div>
                </div>
            </div>
            
            <button onclick="window.print()" class="btn btn-outline-secondary w-100 rounded-pill mt-4">
                <i class="bi bi-printer me-2"></i>Cetak QR Code Halaman Ini
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function fetchAttendanceData() {
        fetch('{{ route('admin.pihak-sidang.data', $jadwal->id) }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (response.status === 401) {
                    window.location.reload();
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;
                const tbody = document.getElementById('pihak-table-body');
                if (!tbody) return;
                
                if (data.pihaks.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                                Belum ada pihak yang melakukan absensi hadir untuk sidang ini.
                            </td>
                        </tr>`;
                    return;
                }
                
                let html = '';
                data.pihaks.forEach((pihak, index) => {
                    let statusHtml = '';
                    if (pihak.kehadiran) {
                        statusHtml = `
                            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>${pihak.kehadiran_time} WIB</span>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Absen via QR</small>`;
                    } else {
                        statusHtml = `<span class="text-danger fw-semibold"><i class="bi bi-clock me-1"></i>Belum Hadir</span>`;
                    }
                    
                    const editUrl = `{{ url('admin/pihak-sidang') }}/${pihak.id}/edit`;
                    const deleteUrl = `{{ url('admin/pihak-sidang') }}/${pihak.id}`;
                    const csrfToken = '{{ csrf_token() }}';
                    
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong style="color: var(--text-primary);">${pihak.nama}</strong></td>
                            <td><span class="badge bg-light text-dark border">${pihak.status_pihak}</span></td>
                            <td>${pihak.nomor_hp || '-'}</td>
                            <td>${statusHtml}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="${editUrl}" class="btn btn-sm btn-outline-primary border-0 rounded-circle p-2" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pihak wajib hadir ini?')">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>`;
                });
                tbody.innerHTML = html;
            })
            .catch(err => console.error('Error fetching real-time attendance:', err));
    }
    
    // Poll every 3 seconds
    setInterval(fetchAttendanceData, 3000);
});
</script>
@endsection
