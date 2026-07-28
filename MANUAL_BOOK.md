# BUKU PANDUAN PENGGUNAAN SISTEM (MANUAL BOOK)
## SIPEKA (Sistem Pemantauan Kehadiran Pihak Persidangan)
### Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung

---

![Versi Dokumen](https://img.shields.io/badge/Dokumen-Buku%20Panduan%20Penggunaan-047857?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Produksi%20v2.0--Terbaru-d4af37?style=for-the-badge)
![Gateway](https://img.shields.io/badge/WA%20Gateway-Twilio%20API-0284c7?style=for-the-badge)
![Platform](https://img.shields.io/badge/Platform-Web%20%26%20Mobile-047857?style=for-the-badge)

---

## DAFTAR ISI

1. [PANDUAN USER (PIHAK PERSIDANGAN / PUBLIK)](#1-panduan-user-pihak-persidangan--publik)
   - 1.1 [Daftar Fitur untuk User](#11-daftar-fitur-untuk-user)
   - 1.2 [Cara Penggunaan Absensi Mandiri (Check-In QR Code)](#12-cara-penggunaan-absensi-mandiri-check-in-qr-code)
   - 1.3 [Menerima Notifikasi WhatsApp (Twilio) & Email](#13-menerima-notifikasi-whatsapp-twilio--email)
2. [PANDUAN ADMIN (OPERATOR / PETUGAS BACKOFFICE)](#2-panduan-admin-operator--petugas-backoffice)
   - 2.1 [Daftar Fitur untuk Admin](#21-daftar-fitur-untuk-admin)
   - 2.2 [Cara Akses Stealth Login Admin](#22-cara-akses-stealth-login-admin)
   - 2.3 [Cara Penggunaan Dashboard Real-Time](#23-cara-penggunaan-dashboard-real-time)
   - 2.4 [Cara Pengelolaan Data Master (Perkara, Jadwal, Pihak, Ruang)](#24-cara-pengelolaan-data-master-perkara-jadwal-pihak-ruang)
   - 2.5 [Cara Penggunaan Status Sidang (Warna Hijau & Merah)](#25-cara-penggunaan-status-sidang-warna-hijau--merah)
   - 2.6 [Cara Penggunaan Fitur Panggil Sidang Broadcast (Twilio WA)](#26-cara-penggunaan-fitur-panggil-sidang-broadcast-twilio-wa)
   - 2.7 [Cara Monitoring "Daftar Hadir Hari Ini" & Mode Layar Penuh (Fullscreen)](#27-cara-monitoring-daftar-hadir-hari-ini--mode-layar-penuh-fullscreen)
   - 2.8 [Cara Sinkronisasi Data SIPP (Termasuk Minggu Kemarin)](#28-cara-sinkronisasi-data-sipp-termasuk-minggu-kemarin)
   - 2.9 [Panduan Keamanan Data Seeder & Migration](#29-panduan-keamanan-data-seeder--migration)
   - 2.10 [Cara Monitoring Log Notifikasi WA & Email](#210-cara-monitoring-log-notifikasi-wa--email)
   - 2.11 [Cara Cetak & Ekspor Laporan Kehadiran (PDF & Excel)](#211-cara-cetak--ekspor-laporan-kehadiran-pdf--excel)
3. [PENUTUP & INFORMASI DUKUNGAN](#3-penutup--informasi-dukungan)

---

## 1. PANDUAN USER (PIHAK PERSIDANGAN / PUBLIK)

> [!NOTE]
> Pihak berperkara (Penggugat, Tergugat, Saksi, Ahli, Kuasa Hukum) **TIDAK memerlukan pendaftaran akun atau password**. Absensi dilakukan secara mudah dan mandiri via smartphone.

---

### 1.1 Daftar Fitur untuk User

| Fitur User | Deskripsi & Fungsi |
| :--- | :--- |
| **Scan QR Code Absensi** | Membuka portal absensi secara mandiri dari stiker QR yang ditempatkan di Pos Satpam, PTSP, atau Ruang Tunggu PTUN Bandar Lampung. |
| **Pencarian Nomor Perkara** | Memilih nomor perkara persidangan yang dijadwalkan hari ini beserta rincian agenda dan ruang sidang. |
| **Pilihan Nama Pihak** | Memilih identitas pihak yang sesuai (Penggugat, Tergugat, Saksi, Kuasa Hukum, dll). |
| **Konfirmasi Kontak WA/Email** | Memastikan nomor WhatsApp dan email aktif untuk penerimaan bukti absen dan notifikasi sidang. |
| **Bukti Check-In Digital** | Menerima bukti kehadiran digital berupa jam presisi absen, nama ruang sidang, dan lokasi scan. |
| **Notifikasi WA & Email Panggilan Sidang** | Menerima pesan otomatis di WhatsApp (via Twilio API) dan Email secara serentak pada hari-H ketika sidang akan dimulai di ruang sidang. |

---

### 1.2 Cara Penggunaan Absensi Mandiri (Check-In QR Code)

#### **Langkah 1: Memindai Kode QR (Scan QR Code)**
1. Saat tiba di area PTUN Bandar Lampung (Pos Satpam / PTSP / Ruang Tunggu Sidang), buka aplikasi **Kamera** atau aplikasi **QR Scanner** pada HP Anda.
2. Arahkan kamera HP ke stiker/poster **Kode QR Absensi Sidang**.
3. Ketuk tautan web yang muncul di layar HP. Anda akan masuk ke **Portal Utama Absensi**.

```
[ Tiba di PTUN ] ──► [ Scan QR Code ] ──► [ Terbuka Portal Absensi ]
```

---

#### **Langkah 2: Mengisi Form Kehadiran**
1. Pada portal utama, tekan tombol **"Mulai Absen Sekarang"**.
2. **Pilih Nomor Perkara**: Ketuk menu dropdown perkara dan pilih Nomor Perkara Anda (contoh: `12/G/2026/PTUN.BDL`). Sistem akan otomatis menampilkan Ruang Sidang dan Agenda Sidang.
3. **Pilih Nama Pihak**: Ketuk dropdown nama pihak dan pilih nama Anda yang sesuai.
4. **Periksa Nomor WhatsApp & Email**: Pastikan nomor WhatsApp terisi dengan benar (contoh: `081234567890`) agar dapat menerima notifikasi panggilan.
5. Tekan tombol **"Kirim Kehadiran (Check-In)"**.

```
+-----------------------------------------------------------------------+
|                SIPEKA : FORM ABSENSI PERSIDANGAN MANDIRI              |
+-----------------------------------------------------------------------+
|  1. Nomor Perkara : [ 12/G/2026/PTUN.BDL - Penggugat vs Tergugat ]   |
|  2. Nama Pihak    : [ H. Ahmad Subardjo (Penggugat)             ]   |
|  3. No. WhatsApp  : [ 081234567890                              ]   |
|                                                                       |
|                     [ KIRIM KEHADIRAN (CHECK-IN) ]                    |
+-----------------------------------------------------------------------+
```

---

#### **Langkah 3: Menerima Bukti Check-In**
Setelah menekan tombol kirim, layar HP akan menampilkan **Tanda Centang Hijau (Bukti Kehadiran)**:
- Kehadiran Anda telah resmi tercatat di sistem pengadilan secara real-time.
- Silakan menuju ke **Ruang Tunggu Sidang** yang tertera pada layar.
- Tetap aktifkan HP Anda untuk menerima pesan WhatsApp panggilan sidang dari petugas.

---

### 1.3 Menerima Notifikasi WhatsApp (Twilio) & Email

Sebagai Pihak Persidangan, Anda akan menerima pesan otomatis di WhatsApp HP Anda yang terhubung dengan **Twilio API Gateway**:

> [!TIP]
> **Petunjuk Uji Coba (Twilio Sandbox)**:
> Jika menggunakan akun Twilio gratis/trial, pastikan nomor WhatsApp penerima sudah mengirim pesan `join <kode-sandbox>` ke nomor WhatsApp Twilio Gateway (`+1 507 632 6184`).

1. **Pesan Panggilan Sidang (Real-time)**:
   Diterima saat petugas admin memicu panggilan sidang dari dashboard admin.

```
🔊 PANGGILAN PERSIDANGAN — PTUN BANDAR LAMPUNG

Sidang perkara No. 12/G/2026/PTUN.BDL dengan agenda Pembuktian & Saksi
di Ruang Utama (Cakra) akan segera dimulai.

Kepada Yth. Bapak/Ibu H. Ahmad Subardjo (Penggugat),
harap segera memasuki ruang sidang. Terima kasih.

-----------------------------------------
Bantu kami untuk tidak memberikan tip atau tanda terima kasih dalam bentuk apapun kepada aparat peradilan.

*SIPEKA | Sistem Pemantauan Kehadiran* 
*PTUN BANDAR LAMPUNG* | (C) 2026
```

---

## 2. PANDUAN ADMIN (OPERATOR / PETUGAS BACKOFFICE)

---

### 2.1 Daftar Fitur untuk Admin

| Fitur Admin | Deskripsi & Fungsi |
| :--- | :--- |
| **Stealth Login Entry** | Akses masuk login admin yang aman dan tersembunyi via tombol footer. |
| **Dashboard Analitik Real-Time** | Ringkasan statistik perkara, jumlah kehadiran harian, grafik tren, serta tabel realtime. |
| **Kelola Data Perkara** | Manajemen data perkara (Tambah, Edit, Hapus, Detail Majelis Hakim & PP). |
| **Kelola Jadwal & Pihak Sidang** | Menentukan jadwal sidang, agenda, dan mendaftarkan kontak WA/Email para pihak. |
| **Kelola Ruang Sidang** | Mengatur master data ruang sidang utama maupun elektronik. |
| **Kontrol Status Sidang (Hijau/Merah)**| Mengatur status *Mulai Sidang* (**Warna Hijau**) dan *Sidang Selesai* (**Warna Merah**). |
| **Panggil Sidang Broadcast (Twilio WA)**| Memicu pengiriman pesan panggilan persidangan via Twilio WhatsApp API secara instan ke HP para pihak. |
| **Daftar Hadir Hari Ini (Live Monitor)**| Pemantauan kehadiran realtime dengan fitur *Auto-Scroll* & *Mode Layar Penuh (Fullscreen)*. |
| **Integrasi & Sinkronisasi SIPP** | Sinkronisasi data perkara & jadwal otomatis dari SIPP PTUN Bandar Lampung (termasuk jadwal minggu kemarin). |
| **Keamanan Data Seeder (`firstOrCreate`)**| Perlindungan seeder agar perintah `db:seed` tidak mengapus data riil/absensi. |
| **Log Notifikasi WA & Email** | Audit pengiriman pesan notifikasi Twilio & Email dengan fitur kirim ulang (*retry*). |
| **Ekspor Laporan Kehadiran** | Mencetak dan mengunduh laporan presensi ke format PDF & Excel. |

---

### 2.2 Cara Akses Stealth Login Admin

Untuk menjaga tampilan publik tetap rapi dan aman, tombol login admin disembunyikan secara khusus:

1. Buka Halaman Utama Portal (`/`).
2. Gulir layar ke bagian paling bawah (**Footer**).
3. Ketuk/Klik simbol hak cipta **`©`** pada teks footer (*© PTUN Bandar Lampung*).
4. Anda akan otomatis diarahkan ke halaman **Login Admin** (`/login`).
5. Masukkan **Email** (`admin@ptun.go.id`) dan **Password** Admin, lalu tekan **Login**.

---

### 2.3 Cara Penggunaan Dashboard Real-Time

Setelah login, Admin akan diarahkan ke halaman **Dashboard** (`/admin/dashboard`):

1. **Kartu Statistik**:
   - **Total Perkara**: Jumlah seluruh perkara terdaftar.
   - **Sidang Hari Ini**: Jumlah jadwal persidangan hari ini.
   - **Kehadiran Hari Ini**: Jumlah pihak yang telah melakukan check-in hari ini.
   - **Sidang Berjalan**: Jumlah sidang yang sedang berlangsung / selesai.
   - **Total Notifikasi Email/WA**: Jumlah pesan terkirim.
2. **Tabel Persidangan Hari Ini**:
   - Menampilkan daftar sidang hari ini secara realtime (otomatis diperbarui setiap 2 detik).
   - Tekan tombol **"Panggil"** pada baris perkara untuk mengirim pesan panggilan ke WhatsApp pihak via Twilio Gateway.
3. **Tabel Kehadiran Terbaru**:
   - Menampilkan log urutan pihak yang baru saja melakukan absensi mandiri di lokasi.

---

### 2.4 Cara Pengelolaan Data Master (Perkara, Jadwal, Pihak, Ruang)

#### **A. Kelola Data Perkara (`/admin/perkara`)**
1. Buka menu **Kelola Perkara** di sidebar.
2. Tekan tombol **"+ Tambah Perkara"**.
3. Isi Nomor Perkara, Jenis Perkara, Tanggal Masuk, Majelis Hakim, dan Panitera Pengganti.
4. Tekan **Simpan Data Perkara**.

#### **B. Kelola Ruang Sidang (`/admin/ruang-sidang`)**
1. Buka menu **Ruang Sidang**.
2. Tekan **"+ Tambah Ruang Sidang"**.
3. Isi Nama Ruang (contoh: *Ruang Utama Cakra*), Kode Ruang, dan Kapasitas.
4. Tekan **Simpan**.

#### **C. Kelola Jadwal Sidang & Pihak Wajib Hadir (`/admin/jadwal-sidang`)**
1. Buka menu **Jadwal Sidang**.
2. Tekan **"+ Tambah Jadwal Sidang"**.
3. Pilih Perkara, Tanggal Sidang, Jam Sidang, Agenda Sidang, dan Ruang Sidang.
4. Tekan **Simpan**.
5. Pada tabel jadwal, tekan tombol **"Kelola Pihak"** untuk memasukkan daftar nama Penggugat, Tergugat, Saksi, beserta **Nomor WhatsApp & Email** masing-masing.

---

### 2.5 Cara Penggunaan Status Sidang (Warna Hijau & Merah)

Fitur ini digunakan oleh Admin untuk menandai kondisi persidangan di Ruang Sidang secara visual di layar pemantauan:

```
[ Belum Dimulai ] ──( Klik "Mulai Sidang" )──► [ SIDANG BERLANGSUNG : WARNA HIJAU ]
[ Berlangsung   ] ──( Klik "Sidang Selesai" )──► [ SIDANG SELESAI    : WARNA MERAH ]
```

#### **1. Mengaktifkan "Mulai Sidang" (Sidang Berlangsung - WARNA HIJAU)**:
1. Buka menu **Kehadiran Hari Ini** (`/admin/daftar-hadir-hari-ini`).
2. Cari kartu perkara yang akan disidangkan.
3. Tekan tombol hijau **"Mulai Sidang"**.
4. **Perubahan Visual**:
   - Seluruh bingkai kartu & latar belakang berubah menjadi **WARNA HIJAU** (`#10b981` / `#f0fdf4`).
   - Terdapat badge indikator berkedip hijau **`SIDANG BERLANGSUNG`**.
   - Banner informasi mengumumkan persidangan sedang berlangsung di ruang sidang terkait.

#### **2. Mengaktifkan "Sidang Selesai" (Sidang Selesai - WARNA MERAH)**:
1. Ketika persidangan perkara tersebut telah ditutup oleh Majelis Hakim, tekan tombol merah **"Sidang Selesai"**.
2. **Perubahan Visual**:
   - Bingkai kartu dan latar belakang berubah menjadi **WARNA MERAH** (`#ef4444` / `#fef2f2`).
   - Badge indikator berubah menjadi **`SIDANG SELESAI`** berwarna merah.
   - Banner informasi mengumumkan persidangan perkara ini telah selesai dilaksanakan.

---

### 2.6 Cara Penggunaan Fitur Panggil Sidang Broadcast (Twilio WA)

1. Buka menu **Dashboard** (`/admin/dashboard`) atau **Jadwal Sidang** (`/admin/jadwal-sidang`).
2. Cari perkara yang pihak-pihaknya telah hadir di ruang tunggu.
3. Tekan tombol **"Panggil"** (Ikon Megafon).
4. Sistem akan memanggil `WhatsAppNotificationService` dan memicu pengiriman pesan WhatsApp via **Twilio API Gateway** ke seluruh pihak terdaftar.

---

### 2.7 Cara Monitoring "Daftar Hadir Hari Ini" & Mode Layar Penuh (Fullscreen)

Menu **Kehadiran Hari Ini** (`/admin/daftar-hadir-hari-ini`) dirancang khusus untuk ditampilkan di layar monitor TV ruang tunggu maupun monitor meja admin:

1. **Auto-Polling Real-Time**: Data kehadiran otomatis terupdate setiap 2 detik tanpa perlu me-refresh halaman browser.
2. **Auto-Scroll Looping**: Tampilan kartu akan melakukan perguliran layar (*scrolling*) secara otomatis dan seamless agar seluruh perkara dapat terpantau.
3. **Hover Auto-Pause (Jeda Otomatis Kursor Mouse)**:
   - Ketika Admin mengarahkan kursor mouse ke atas kartu perkara untuk mengklik tombol **"Mulai Sidang"** atau **"Sidang Selesai"**, perguliran scroll otomatis berhenti secara instan agar posisi tombol tetap tenang dan akurat saat diklik.
   - Saat kursor mouse digeser keluar dari kartu perkara, perguliran scroll otomatis melanjutkan perjalanan kembali.
4. **Mode Layar Penuh (Fullscreen)**:
   - Tekan tombol **"Layar Penuh"** di pojok kanan atas.
   - Halaman akan memaksimalkan tampilan layar TV/Monitor dengan menyembunyikan sidebar dan navbar.
   - Untuk kembali ke tampilan normal, tekan tombol **"Layar Normal"** atau tombol **ESC** di keyboard.

---

### 2.8 Cara Sinkronisasi Data SIPP (Termasuk Minggu Kemarin)

Untuk mengambil data perkara & jadwal sidang langsung dari portal SIPP PTUN Bandar Lampung:

1. Buka menu **Integrasi SIPP** (`/admin/integrasi-sipp`).
2. Tekan tombol **"Sinkronkan SIPP"**.
3. Sistem akan secara otomatis menarik data dari rentang **7 hari ke belakang (minggu kemarin)** hingga **10 hari ke depan**.
4. Anda juga dapat menjalankan sinkronisasi via terminal/Artisan command:
   ```bash
   php artisan sipp:sync --days-back=7 --days-forward=10
   ```
5. Data Perkara, Ruang Sidang, dan Jadwal Sidang akan langsung diperbarui tanpa entri ganda.

---

### 2.9 Panduan Keamanan Data Seeder & Migration

> [!IMPORTANT]
> **Perbedaan Perintah Database yang Harus Dipahami**:
> 
> 1. 🟢 **`php artisan db:seed` (AMAN)**:
>    - Berkas `DatabaseSeeder.php` menggunakan logika `firstOrCreate()`.
>    - Menjalankan `db:seed` **TIDAK AKAN MENGHAPUS** data sinkronisasi SIPP atau data absensi yang sudah ada di database.
> 
> 2. ⚠️ **`php artisan migrate:refresh` / `migrate:fresh` (BAHAYA DARI REMOVAL DATA)**:
>    - Perintah ini berfungsi menghapus total (*drop/rollback*) seluruh tabel database dari nol.
>    - **JANGAN dijalankan** apabila database sudah memiliki data riil sinkronisasi SIPP atau riwayat absensi yang ingin dipertahankan.

---

### 2.10 Cara Monitoring Log Notifikasi WA & Email

1. Buka menu **Log Notifikasi** (`/admin/notifikasi`).
2. Tabel menampilkan riwayat pengiriman notifikasi:
   - Nama Penerima & Nomor WhatsApp/Email.
   - Jenis Notifikasi (*Pengingat H-1* / *Panggilan Sidang* via Twilio).
   - Status Pengiriman: <span style="color:green; font-weight:bold;">[TERKIRIM]</span> atau <span style="color:red; font-weight:bold;">[GAGAL]</span>.
3. Jika status pengiriman `GAGAL` (misal akibat gangguan jaringan), tekan tombol **"Kirim Ulang"** untuk mengulang pengiriman pesan.

---

### 2.11 Cara Cetak & Ekspor Laporan Kehadiran (PDF & Excel)

1. Buka menu **Laporan Kehadiran** (`/admin/laporan`).
2. Tentukan **Rentang Tanggal** laporan (misal: *01/07/2026 s/d 31/07/2026*).
3. Pilih Filter tambahan jika diperlukan (Filter Perkara / Filter Ruang Sidang).
4. Tekan tombol:
   - **"Cetak PDF"**: Untuk mengunduh berkas laporan format PDF siap cetak.
   - **"Ekspor Excel"**: Untuk mengunduh data mentah laporan format `.xlsx`.

---

## 3. PENUTUP & INFORMASI DUKUNGAN

Buku panduan penggunaan ini disusun untuk mempermudah operasional **Pihak Berperkara** dan **Petugas Administrator** dalam memanfaatkan aplikasi **SIPEKA PTUN Bandar Lampung**.

- **Dukungan Teknis IT**: Tim Komputer & IT PTUN Bandar Lampung
- **Alamat Kantor**: Jl. Basuki Rahmat No. 26, Teluk Betung, Bandar Lampung

---
*Buku Panduan Penggunaan Sistem SIPEKA — PTUN Bandar Lampung © 2026 (Versi 2.0 Terbaru)*
