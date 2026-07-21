# BUKU PANDUAN PENGGUNAAN SISTEM (MANUAL BOOK)
## SI-ABDI / SI-OCID (Sistem Absensi Mandiri & Monitoring Kehadiran Pihak Persidangan)
### Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung

---

![Versi Dokumen](https://img.shields.io/badge/Dokumen-Buku%20Panduan%20Penggunaan-047857?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Produksi%20v1.0-d4af37?style=for-the-badge)
![Platform](https://img.shields.io/badge/Platform-Web%20%26%20Mobile-0284c7?style=for-the-badge)

---

## DAFTAR ISI

1. [BAB I: PENDAHULUAN](#bab-i-pendahuluan)
   - 1.1 [Tentang Sistem](#11-tentang-sistem)
   - 1.2 [Tujuan & Manfaat](#12-tujuan--manfaat)
   - 1.3 [Pembagian Peran Pengguna (User Roles)](#13-pembagian-peran-pengguna-user-roles)
   - 1.4 [Kebutuhan Perangkat & Peramban (Browser)](#14-kebutuhan-perangkat--peramban-browser)
2. [BAB II: PANDUAN PIHAK BERPERKARA (PORTAL PUBLIK)](#bab-ii-panduan-pihak-berperkara-portal-publik)
   - 2.1 [Metode Akses (Scan Kode QR / URL Portal)](#21-metode-akses-scan-kode-qr--url-portal)
   - 2.2 [Langkah-Langkah Absensi Mandiri (Check-In)](#22-langkah-langkah-absensi-mandiri-check-in)
   - 2.3 [Halaman Konfirmasi & Bukti Check-In](#23-halaman-konfirmasi--bukti-check-in)
3. [BAB III: PANDUAN ADMINISTRATOR (BACKOFFICE PANEL)](#bab-iii-panduan-administrator-backoffice-panel)
   - 3.1 [Akses Login Tersembunyi (Stealth Login Entry)](#31-akses-login-tersembunyi-stealth-login-entry)
   - 3.2 [Memahami Dashboard Analitik Real-Time](#32-memahami-dashboard-analitik-real-time)
   - 3.3 [Kelola Data Master (CRUD)](#33-kelola-data-master-crud)
     - 3.3.1 [Manajemen Ruang Sidang](#331-manajemen-ruang-sidang)
     - 3.3.2 [Manajemen Data Perkara](#332-manajemen-data-perkara)
     - 3.3.3 [Manajemen Jadwal Persidangan](#333-manajemen-jadwal-persidangan)
     - 3.3.4 [Manajemen Pihak Wajib Hadir](#334-manajemen-pihak-wajib-hadir)
   - 3.4 [Fitur Panggil Persidangan Manual](#34-fitur-panggil-persidangan-manual)
   - 3.5 [Integrasi & Sinkronisasi SIPP](#35-integrasi--sinkronisasi-sipp)
   - 3.6 [Audit & Monitoring Log Notifikasi WhatsApp / Email](#36-audit--monitoring-log-notifikasi-whatsapp--email)
   - 3.7 [Pencetakan & Ekspor Laporan Kehadiran (PDF & Excel)](#37-pencetakan--ekspor-laporan-kehadiran-pdf--excel)
   - 3.8 [Akses Cepat "Daftar Hadir Hari Ini"](#38-akses-cepat-daftar-hadir-hari-ini)
4. [BAB IV: OTOMATISASI NOTIFIKASI PIHAK PERSIDANGAN](#bab-iv-otomatisasi-notifikasi-pihak-persidangan)
   - 4.1 [Jenis Notifikasi Otomatis untuk Pihak](#41-jenis-notifikasi-otomatis-untuk-pihak)
   - 4.2 [Struktur & Contoh Pesan WhatsApp Pengingat H-1](#42-struktur--contoh-pesan-whatsapp-pengingat-h-1)
   - 4.3 [Struktur & Contoh Pesan Panggilan Sidang](#43-struktur--contoh-pesan-panggilan-sidang)
5. [BAB V: PEMELIHARAAN & PENANGANAN MASALAH (TROUBLESHOOTING)](#bab-v-pemeliharaan--penanganan-masalah-troubleshooting)
   - 5.1 [Masalah QR Code & Sesi Check-In](#51-masalah-qr-code--sesi-check-in)
   - 5.2 [Pengaturan Token & Koneksi WhatsApp Gateway (Fonnte API)](#52-pengaturan-token--koneksi-whatsapp-gateway-fonnte-api)
   - 5.3 [Kegagalan Sinkronisasi Database SIPP](#53-kegagalan-sinkronisasi-database-sipp)
6. [BAB VI: PENUTUP & KETENTUAN HUKUM/SOP](#bab-vi-penutup--ketentuan-hukumsop)

---

## BAB I: PENDAHULUAN

### 1.1 Tentang Sistem
**SI-ABDI / SI-OCID** (*Sistem Absensi Mandiri & Monitoring Kehadiran Pihak Persidangan*) adalah aplikasi berbasis web yang dikembangkan khusus untuk **Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung**. Sistem ini memfasilitasi pihak berperkara (Penggugat, Tergugat, Saksi, Ahli, Kuasa Hukum) untuk melakukan pencatatan kehadiran (*check-in*) secara mandiri melalui pemindaian kode QR (*Zero-Login QR Scan*).

Sistem dilengkapi dengan modul otomatisasi notifikasi (*WhatsApp & Email Gateway*) yang ditujukan langsung **kepada Pihak Berperkara / Pihak Persidangan** untuk memberikan pengingat jadwal sidang (H-1) serta notifikasi panggilan memasuki ruang sidang secara cepat dan transparan.

### 1.2 Tujuan & Manfaat
- **Pengingat Otomatis Pihak Sidang**: Mengirimkan pengingat H-1 dan notifikasi panggilan sidang langsung ke nomor WhatsApp/Email pihak berperkara.
- **Pengurangan Kebisingan Area Tunggu**: Mengurangi panggilan berulang via pengeras suara (*panggilan fisik*) di ruang tunggu karena pihak menerima pesan panggilan langsung di ponselnya.
- **Ketepatan Waktu Sidang**: Memastikan pihak hadir tepat waktu sesuai jam dan ruang sidang yang dijadwalkan.
- **Transparansi & Akuntabilitas Data**: Menghasilkan log kehadiran presisi (timestamp & lokasi QR) yang terhubung langsung dengan data perkara SIPP.

### 1.3 Pembagian Peran Pengguna (User Roles)

| Peran Pengguna | Hak Akses & Deskripsi Tugas |
| :--- | :--- |
| **Pihak Berperkara (Publik / Penerima Notifikasi)** | Memindai Kode QR lokasi, memilih nomor perkara & nama pihak, mengonfirmasi kontak WhatsApp, melakukan absensi mandiri, serta **menerima notifikasi pengingat & panggilan sidang via WhatsApp/Email**. |
| **Administrator / Operator IT** | Mengelola data master (Ruang, Perkara, Jadwal, Pihak), memantau Dashboard analitik, memicu panggil sidang/sync SIPP, serta mengekspor laporan. |

### 1.4 Kebutuhan Perangkat & Peramban (Browser)
- **Smartphone / Tablet (Pihak Berperkara)**: Menggunakan kamera smartphone dengan browser standar (Google Chrome, Safari, Samsung Internet, Edge) dan menerima WhatsApp/Email.
- **Komputer PC / Laptop (Administrator)**: Layar minimal resolution 1280x720, browser Google Chrome / Mozilla Firefox versi terbaru.

---

## BAB II: PANDUAN PIHAK BERPERKARA (PORTAL PUBLIK)

> [!NOTE]
> Pihak berperkara **TIDAK memerlukan nama pengguna (username) atau kata sandi (password)** untuk melakukan absensi mandiri. Notifikasi pengingat dan panggilan persidangan akan dikirimkan otomatis ke WhatsApp/Email yang terdaftar pada sistem.

### 2.1 Metode Akses (Scan Kode QR / URL Portal)

1. Pihak berperkara tiba di lokasi PTUN Bandar Lampung (misalnya: **Pos Satpam**, **Ruang Tunggu Sidang**, atau **Loket PTSP**).
2. Buka aplikasi **Kamera** atau **QR Scanner** pada HP/Smartphone.
3. Arahkan kamera ke stiker/poster **Kode QR Absensi Sidang**.
4. Ketuk tautan URL yang muncul di layar HP.
5. Sistem akan secara otomatis mengarahkan ke **Portal Halaman Utama** dengan mendeteksi parameter lokasi (contoh: `Lokasi: Pos Satpam`).

```
[ Pintu Masuk PTUN ] ──► [ Scan QR Code Area ] ──► [ Terbuka Form Absensi ]
```

---

### 2.2 Langkah-Langkah Absensi Mandiri (Check-In)

```
+-----------------------------------------------------------------------+
|                SI-OCID : ABSENSI MANDIRI PERSIDANGAN                  |
+-----------------------------------------------------------------------+
|  1. Pilih Nomor Perkara : [ 12/G/2026/PTUN.BDL - Penggugat vs Tergugat ] |
|  2. Pilih Nama Pihak   : [ H. Ahmad Subardjo (Penggugat)             ] |
|  3. Nomor WhatsApp     : [ 081234567890                              ] |
|                                                                       |
|                     [ KIRIM KEHADIRAN (CHECK-IN) ]                    |
+-----------------------------------------------------------------------+
```

1. **Buka Form Absensi**: Pada halaman portal utama, tekan tombol **"Mulai Absen Sekarang"**.
2. **Pilih Nomor Perkara**:
   - Klik dropdown menu **Nomor Perkara / Agenda Sidang**.
   - Pilih nomor perkara persidangan yang dijadwalkan untuk sidang hari ini.
   - *Sistem akan menampilkan rincian agenda sidang (misal: Pembuktian, Saksi, Putusan) serta Ruang Sidang terkait.*
3. **Pilih Nama Pihak**:
   - Klik dropdown **Nama Pihak Berperkara**.
   - Pilih nama Anda yang sesuai dengan daftar pihak terdaftar (Penggugat, Tergugat, Saksi, Ahli, dsb.).
4. **Konfirmasi Nomor WhatsApp**:
   - Pastikan nomor WhatsApp aktif terisi dengan benar. Nomor ini berguna untuk menerima konfirmasi absensi dan pesan panggilan sidang.
5. **Kirim Kehadiran**:
   - Periksa kembali kebenaran data.
   - Tekan tombol **"Kirim Kehadiran (Check-In)"**.

---

### 2.3 Halaman Konfirmasi & Bukti Check-In

Setelah menekan tombol kirim, layar HP akan menampilkan **Halaman Sukses Absensi**:
- **Tanda Centang Hijau**: Menandakan kehadiran telah berhasil dicatat ke dalam basis data pengadilan.
- **Rincian Absensi**: Menampilkan Nama Pihak, Status Pihak, Nomor Perkara, Ruang Sidang, dan Waktu Absen (*Timestamp*).
- **Status Kelengkapan**: Menampilkan status kehadiran para pihak untuk perkara tersebut.
- **Petunjuk Layanan**: Pihak diminta untuk menuju ke Ruang Tunggu Sidang dan memperhatikan notifikasi WhatsApp panggilan masuk.

---

## BAB III: PANDUAN ADMINISTRATOR (BACKOFFICE PANEL)

### 3.1 Akses Login Tersembunyi (Stealth Login Entry)

Demi menjaga keamanan portal publik dari percobaan *brute force*, tombol pintu masuk administrator dirancang secara tersembunyi (*Stealth Entry*).

> [!TIP]
> **Cara Mengakses Halaman Login Admin**:
> 1. Buka Halaman Utama Portal (`/`).
> 2. Gulir layar ke bagian paling bawah (**Footer**).
> 3. Ketuk/Klik simbol hak cipta **`©`** yang berada pada teks footer.
> 4. Anda akan dialihkan secara otomatis ke halaman **Login Admin** (`/login`).
> 5. Atau, ketikkan URL langsung: `https://[domain-anda]/login`.

```
Footer Website:
"© 2026 PTUN Bandar Lampung. Hak Cipta Dilindungi."
 ▲
 └──► KLIK PADA SIMBOL HAK CIPTA © UNTUK MEMBUKA FORM LOGIN
```

- **Masukkan Email & Password Administrator**.
- Tekan **Masuk / Login**.

---

### 3.2 Memahami Dashboard Analitik Real-Time

Setelah berhasil login, administrator akan disambut oleh **Dashboard Utama** (`/admin/dashboard`):

1. **Kartu Ringkasan KPI**:
   - **Total Perkara Hari Ini**: Jumlah perkara yang memiliki agenda sidang pada hari ini.
   - **Total Pihak Wajib Hadir**: Jumlah akumulasi roster pihak yang harus hadir hari ini.
   - **Tingkat Kehadiran (%)**: Persentase pihak yang sudah check-in dibanding total pihak wajib hadir.
   - **Notifikasi Terkirim**: Jumlah notifikasi WhatsApp / Email yang berhasil dikirimkan ke pihak berperkara.
2. **Grafik Visual (Chart.js)**:
   - **Tren Kehadiran 7 Hari Terakhir**: Memantau fluktuasi statistik persidangan sepekan.
   - **Distribusi Kehadiran per Ruang Sidang**: Memantau kepadatan ruang sidang harian.
3. **Tabel Ringkasan Jadwal Sidang Hari Ini**:
   - Menampilkan status realtime tiap perkara (`Belum Hadir`, `Sebagian Hadir`, `Lengkap 100%`).

---

### 3.3 Kelola Data Master (CRUD)

Menu bilah samping (*Sidebar*) menyediakan pengelolaan data master:

#### 3.3.1 Manajemen Ruang Sidang (`/admin/ruang-sidang`)
- **Fungsi**: Mengatur ruang sidang utama dan ruang sidang elektronik (E-Court).
- **Langkah Tambah**: Tekan **+ Tambah Ruang Sidang** ──► Isi Kode Ruang, Nama Ruang, Kapasitas, & Keterangan ──► Simpan.

#### 3.3.2 Manajemen Data Perkara (`/admin/perkara`)
- **Fungsi**: Mendata nomor perkara, jenis gugatan, data Penggugat/Tergugat, serta penunjukan Majelis Hakim & Panitera Pengganti (PP).

#### 3.3.3 Manajemen Jadwal Persidangan (`/admin/jadwal-sidang`)
- **Fungsi**: Menentukan agenda sidang per tanggal, jam, dan lokasi ruang sidang.

#### 3.3.4 Manajemen Pihak Wajib Hadir (`/admin/jadwal-sidang/{id}/pihak`)
- **Fungsi**: Memasukkan nama-nama pihak berperkara (Penggugat, Tergugat, Kuasa Hukum, Saksi) beserta **Nomor WhatsApp & Email** masing-masing pihak untuk penerimaan notifikasi otomatis.

---

### 3.4 Fitur Panggil Persidangan Manual

Ketika sidang siap dimulai dan petugas admin ingin memanggil para pihak yang telah hadir:
1. Masuk ke menu **Jadwal Sidang** (`/admin/jadwal-sidang`).
2. Cari perkara yang bersangkutan.
3. Klik tombol **"Panggil Sidang"** (Ikon Megafon/Lonceng).
4. Sistem akan secara otomatis menembakkan pesan WhatsApp broadcast pemicu panggilan **langsung ke HP/WhatsApp pihak-pihak berperkara** yang terdaftar dan telah absen.

---

### 3.5 Integrasi & Sinkronisasi SIPP

Aplikasi dilengkapi dengan modul `SippSyncService` untuk mengambil data jadwal persidangan dan data pihak berperkara secara otomatis dari server **SIPP PTUN Bandar Lampung**.

```
[ Database SIPP PTUN ] ════( Sync Engine )════► [ Database SI-ABDI ]
```

1. Buka menu **Integrasi SIPP** (`/admin/integrasi-sipp`).
2. Tekan tombol **"Sinkronkan Sekarang"**.
3. Data perkara, jadwal, dan kontak para pihak terikat akan tersinkronkan otomatis.

---

### 3.6 Audit & Monitoring Log Notifikasi WhatsApp / Email

Untuk memastikan pesan panggilan/pengingat sampai ke pihak berperkara:

1. Buka menu **Log Notifikasi** (`/admin/notifikasi`).
2. Tabel menampilkan:
   - **Penerima**: Nama Pihak & Nomor WhatsApp / Email Tujuan.
   - **Tipe Notifikasi**: *Pengingat Sidang (H-1)* atau *Panggilan Sidang (Memasuki Ruang)*.
   - **Status**: <span style="color:green; font-weight:bold;">[TERKIRIM]</span> / <span style="color:red; font-weight:bold;">[GAGAL]</span>.
   - **Waktu Kirim**: Timestamp jam dan detik pengiriman.
3. Tombol **"Kirim Ulang"** dapat ditekan jika terjadi pengiriman gagal.

---

### 3.7 Pencetakan & Ekspor Laporan Kehadiran (PDF & Excel)

1. Buka menu **Laporan Kehadiran** (`/admin/laporan`).
2. Tentukan rentang tanggal & filter data.
3. Unduh dalam format **PDF** atau **Excel**.

---

### 3.8 Akses Cepat "Daftar Hadir Hari Ini"

- Klik menu pintas **"Daftar Hadir Hari Ini"** (`/admin/daftar-hadir-hari-ini`) untuk melihat daftar realtime pihak yang telah check-in hari ini.

---

## BAB IV: OTOMATISASI NOTIFIKASI PIHAK PERSIDANGAN

### 4.1 Jenis Notifikasi Otomatis untuk Pihak

Pesan otomatis pada sistem ini dikirimkan **khusus kepada Pihak Persidangan** (Penggugat, Tergugat, Saksi, Kuasa Hukum) melalui 2 metode pemicu:

1. **Pengingat Sidang H-1 (Scheduled Task)**:
   - Berjalan secara otomatis setiap hari untuk mengirimkan pengingat ke seluruh pihak yang memiliki jadwal sidang pada esok hari.
2. **Panggilan Persidangan (Admin Trigger / Realtime)**:
   - Dipicu saat petugas/admin menekan tombol *Panggil Sidang*, mengirimkan pesan agar pihak segera memasuki ruang sidang terkait.

```
┌─────────────────────────────────────────────────────────────┐
│ Pemicu Notifikasi (Jadwal H-1 / Tombol Panggil Sidang)      │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│ Kirim Pesan Otomatis (WhatsApp Gateway & Laravel Mail)      │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│ Diterima di WhatsApp / Email Pihak Persidangan              │
│ (Penggugat, Tergugat, Saksi, Kuasa Hukum)                   │
└─────────────────────────────────────────────────────────────┘
```

---

### 4.2 Struktur & Contoh Pesan WhatsApp Pengingat H-1

Berikut adalah contoh pengingat otomatis H-1 yang diterima pihak persidangan:

```
📌 PENGINGAT SIDANG H-1 — PTUN BANDAR LAMPUNG

Diingatkan kembali bahwa jadwal sidang Anda untuk:
• Nomor Perkara : 12/G/2026/PTUN.BDL
• Agenda Sidang : Pembuktian & Saksi
• Ruang Sidang  : Ruang Utama (Cakra)
• Waktu Sidang  : Esok Hari (22-07-2026 pukul 09:00 WIB)

Harap hadir 30 menit sebelum sidang dimulai dan melakukan scan absensi mandiri di lokasi PTUN Bandar Lampung. Terima kasih.
---
SI-OCID | Sistem Informasi Terpadu Absensi Sidang
PTUN BANDAR LAMPUNG | (C) 2026
-----------------------------------------
Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan.
```

---

### 4.3 Struktur & Contoh Pesan Panggilan Sidang

Berikut adalah contoh pesan panggilan persidangan yang diterima pihak di ponselnya ketika sidang akan dimulai:

```
🔊 PANGGILAN PERSIDANGAN — PTUN BANDAR LAMPUNG

Sidang untuk perkara nomor 12/G/2026/PTUN.BDL dengan agenda Pembuktian & Saksi di Ruang Utama (Cakra) akan segera dimulai.

Kepada Bapak/Ibu H. Ahmad Subardjo (Penggugat) harap segera memasuki ruang sidang. Terima kasih.
---
SI-OCID | Sistem Informasi Terpadu Absensi Sidang
PTUN BANDAR LAMPUNG | (C) 2026
-----------------------------------------
Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan.
```

---

## BAB V: PEMELIHARAAN & PENANGANAN MASALAH (TROUBLESHOOTING)

### 5.1 Masalah QR Code & Sesi Check-In

| Gejala Masalah | Kemungkinan Penyebab | Solusi / Tindakan |
| :--- | :--- | :--- |
| **Lokasi QR tidak terdeteksi di Form** | URL QR Code rusak atau parameter `?qrcode=` hilang | Pastikan Kode QR yang dicetak merujuk pada format URL valid (misal: `https://[domain]/?qrcode=QR-SATPAM`). |
| **Pihak tidak menerima WhatsApp** | Nomor HP pihak salah / tidak terformat dengan benar | Pastikan nomor telepon diawali angka `08...` atau `628...` di data Pihak Sidang. |

---

### 5.2 Pengaturan Token & Koneksi WhatsApp Gateway (Fonnte API)

Pengiriman notifikasi WhatsApp mengandalkan API Fonnte. Jika notifikasi tidak terkirim:

1. Buka berkas `.env` pada server web.
2. Pastikan variabel berikut terisi dengan token Fonnte aktif:
   ```env
   FONNTE_TOKEN=your_fonnte_api_token_here
   FONNTE_URL=https://api.fonnte.com/send
   ```
3. Cek sisa kuota pesan di akun Fonnte (`https://fonnte.com`).

---

### 5.3 Kegagalan Sinkronisasi Database SIPP

| Gejala | Penyebab | Solusi |
| :--- | :--- | :--- |
| **Gagal terhubung ke Database SIPP** | IP Server web terblokir / Kredensial DB SIPP berubah | Periksa konfigurasi `SIPP_DB_HOST`, `SIPP_DB_PORT`, dan password di `.env`. |

---

## BAB VI: PENUTUP & KETENTUAN HUKUM/SOP

Buku Panduan ini disusun sebagai pedoman operasional resmi penggunaan **SI-ABDI / SI-OCID** di lingkungan **Pengadilan Tata Usaha Negara Bandar Lampung**.

- **Disusun Oleh**: Tim Pengembang & Tim IT PTUN Bandar Lampung
- **Standar Operasional**: Mengacu pada Cetak Biru Pembaharuan Peradilan Mahkamah Agung RI & Digitalisasi Layanan Persidangan Era Modern.

---
*Buku Panduan Penggunaan System — PTUN Bandar Lampung © 2026*
