<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Sidang PTUN Jakarta</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px double #0b2a49;
            padding-bottom: 10px;
        }
        .logo-title {
            font-size: 18px;
            font-weight: bold;
            color: #0b2a49;
            text-transform: uppercase;
            margin: 0;
        }
        .logo-subtitle {
            font-size: 12px;
            color: #555555;
            margin: 5px 0 0 0;
        }
        .meta {
            margin-bottom: 15px;
            width: 100%;
        }
        .meta td {
            padding: 3px 0;
        }
        .table-report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-report th {
            background-color: #0b2a49;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #0b2a49;
            text-transform: uppercase;
            font-size: 10px;
        }
        .table-report td {
            padding: 8px;
            border: 1px solid #dddddd;
            vertical-align: middle;
        }
        .table-report tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 9px;
            text-align: center;
            display: inline-block;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
            font-size: 10px;
        }
        .signature-section {
            float: right;
            width: 250px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="logo-title">PENGADILAN TATA USAHA NEGARA JAKARTA</h1>
        <p class="logo-subtitle">Sistem Absensi & Kehadiran Sidang Pihak Berperkara Berbasis QR Code</p>
    </div>

    <table class="meta">
        <tr>
            <td style="width: 120px; font-weight: bold;">Jenis Dokumen</td>
            <td>: Laporan Kehadiran Persidangan PTUN Jakarta</td>
            <td style="text-align: right; font-weight: bold;">Tanggal Unduh</td>
            <td style="text-align: right; width: 120px;">: {{ date('d-m-Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jumlah Data Kehadiran</td>
            <td>: {{ $kehadirans->count() }} Pihak</td>
            <td style="text-align: right; font-weight: bold;">Petugas Ekspor</td>
            <td style="text-align: right;">: {{ Auth::user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    <table class="table-report">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 130px;">Nomor Perkara</th>
                <th>Agenda Sidang</th>
                <th style="width: 100px;">Jadwal Sidang</th>
                <th>Nama Pihak</th>
                <th style="width: 110px;">Status Pihak</th>
                <th style="width: 90px;">Nomor HP</th>
                <th style="width: 90px;">Waktu Absen</th>
                <th style="width: 60px; text-align: center;">Status</th>
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
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $perkara->nomor_perkara }}</strong></td>
                    <td>{{ $jadwal->agenda_sidang }}</td>
                    <td>
                        {{ $tanggal }}<br>
                        <small style="color: #666666;">{{ substr($jadwal->jam_sidang, 0, 5) }} WIB</small>
                    </td>
                    <td><strong>{{ $pihak->nama }}</strong></td>
                    <td>{{ $pihak->status_pihak }}</td>
                    <td>{{ $pihak->nomor_hp }}</td>
                    <td>{{ $kehadiran->waktu_hadir->format('H:i') }} WIB</td>
                    <td style="text-align: center;">
                        <span class="status-badge">{{ $kehadiran->status_hadir }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #666666; padding: 30px;">
                        Tidak ada data kehadiran yang tercatat dalam periode saringan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-section">
            <p>Jakarta, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Petugas Absensi PTUN Jakarta,</p>
            <p style="text-decoration: underline; font-weight: bold;">{{ Auth::user()->name ?? 'Administrator' }}</p>
            <p style="color: #666666;">NIP. 19920815 201801 1 002</p>
        </div>
    </div>

</body>
</html>
