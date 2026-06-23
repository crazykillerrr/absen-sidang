<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Persidangan PTUN Jakarta</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 40px 20px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
        }
        .header {
            background-color: #0b2a49;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #94a3b8;
            margin: 4px 0 0 0;
            font-size: 13px;
        }
        .content {
            padding: 40px 32px;
        }
        .badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .headline {
            font-size: 22px;
            font-weight: 700;
            color: #0b2a49;
            margin: 0 0 12px 0;
            line-height: 1.3;
        }
        .intro {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 32px 0;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
        }
        .detail-row {
            margin-bottom: 16px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 12px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }
        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .action-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .action-text {
            font-size: 14px;
            color: #1e3a8a;
            font-weight: 500;
            margin: 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin: 0 0 8px 0;
        }
        .footer p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>MONITORING PERSIDANGAN</h1>
                <p>Pengadilan Tata Usaha Negara Jakarta</p>
            </div>
            
            <div class="content">
                <div class="badge">Kehadiran Lengkap</div>
                <h2 class="headline">Pemberitahuan Persidangan Siap Dimulai</h2>
                <p class="intro">Yth. Bapak/Ibu Majelis Hakim dan Panitera Pengganti, diberitahukan bahwa seluruh pihak yang diwajibkan hadir pada jadwal sidang di bawah ini telah melakukan absensi di persidangan:</p>
                
                <div class="details-card">
                    <div class="detail-row">
                        <div class="detail-label">Nomor Perkara</div>
                        <div class="detail-value">{{ $perkara->nomor_perkara }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Agenda Sidang</div>
                        <div class="detail-value">{{ $jadwal->agenda_sidang }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Ruang Sidang</div>
                        <div class="detail-value">{{ $jadwal->ruangSidang->nama_ruang }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Waktu Pelaksanaan</div>
                        <div class="detail-value">{{ $waktu }}</div>
                    </div>
                </div>

                <div class="action-box">
                    <p class="action-text"><strong>Rekomendasi Tindakan:</strong> Silakan mempersiapkan diri dan berkas persidangan untuk memulai jalannya sidang.</p>
                </div>
            </div>
            
            <div class="footer">
                <p>Surat elektronik ini dikirimkan secara otomatis oleh Sistem Absensi PTUN Jakarta.</p>
                <p>&copy; 2026 PTUN Jakarta. Semua Hak Dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
