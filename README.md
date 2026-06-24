# Sistem Absensi & Monitoring Kehadiran Sidang — PTUN Bandar Lampung

Sistem manajemen absensi mandiri berbasis kode QR dan otomatisasi notifikasi WhatsApp untuk memantau kehadiran pihak berperkara secara real-time di **Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung**.

Sistem ini didesain secara modern dengan skema warna hijau-emas khas institusi peradilan Mahkamah Agung RI, mendukung transisi tema (Light/Dark Mode), sinkronisasi jadwal otomatis dengan SIPP (Sistem Informasi Penelusuran Perkara), dan integrasi API Fonnte untuk notifikasi kehadiran langsung ke WhatsApp Majelis Hakim & Panitera Pengganti.

---

## 🚀 Fitur Utama

### 1. Portal & Absensi Mandiri (Publik)
* **Scan QR Code (Zero Login)**: Pihak berperkara (Penggugat, Tergugat, Saksi, Ahli, dsb.) melakukan check-in dengan memindai kode QR di area pengadilan (Pos Satpam, Ruang Tunggu) tanpa perlu autentikasi.
* **Deteksi Lokasi & Prefill Otomatis**: Form absensi memvalidasi parameter QR, mendeteksi lokasi check-in secara dinamis, serta menampilkan data pihak yang terdaftar untuk dikonfirmasi.
* **Desain Glassmorphism Premium**: Antarmuka responsif yang ramah perangkat seluler, berlatar belakang gradasi hijau-emas dengan dekorasi partikel bercahaya (ambient orbs) yang premium.

### 2. Logika Validasi Kehadiran & Auto-WhatsApp (`AttendanceValidationService`)
* **Deteksi Kehadiran Lengkap**: Setiap kali ada pihak yang absen, sistem mencocokkan jumlah kehadiran aktual dengan daftar pihak yang wajib hadir pada jadwal persidangan hari itu.
* **Notifikasi WhatsApp Instan (via Fonnte API)**: Begitu seluruh pihak dinyatakan lengkap, sistem langsung mengirimkan pesan pemberitahuan ke WhatsApp Ketua Majelis, Hakim Anggota, dan Panitera Pengganti agar persidangan dapat segera dimulai.
* **Log Pengiriman Transparan**: Dashboard Admin memuat menu audit untuk melacak status pengiriman (Pending, Terkirim, Gagal), waktu kirim, dan nomor tujuan.

### 3. Integrasi Sinkronisasi SIPP (`SippSyncService`)
* **Koneksi Database SIPP**: Mengambil data perkara, pihak, jadwal sidang, hakim, dan panitera pengganti secara otomatis dari basis data SIPP.
* **Manajemen Sinkronisasi**: Petugas admin dapat memicu sinkronisasi manual atau terjadwal melalui tab khusus di dalam menu integrasi admin.

### 4. Panel Administrator (Backoffice)
* **Dashboard Statistik Real-time**: Grafik analitik dinamis (Chart.js) yang memetakan tren kehadiran 7 hari terakhir dan distribusi kehadiran berdasarkan agenda sidang.
* **Manajemen CRUD Data Master**:
  * **Perkara & Majelis Hakim**: Penentuan formasi majelis hakim (Ketua & Anggota) dan PP penanggung jawab.
  * **Jadwal Sidang**: Penjadwalan agenda sidang (Pemeriksaan Persiapan, Pembuktian, Saksi, Putusan, dll.) lengkap dengan jam dan ruang.
  * **Pihak Sidang**: Pendataan roster wajib hadir per sidang.
  * **Hakim, PP, & Ruang Sidang**: Data referensi yang mendukung penghapusan logis (Soft Delete) untuk menjaga integritas data historis.
* **Laporan & Ekspor**: Penyaringan data kehadiran sidang dan ekspor ke **PDF** (DomPDF) atau **Excel** (Maatwebsite Excel).

### 5. Keamanan & Akses Tersembunyi (Stealth Login)
* **Stealth Admin Entry**: Untuk menjaga fokus portal publik tetap bersih dan aman, tombol login administrator disembunyikan secara rahasia di dalam karakter hak cipta `©` pada bagian footer halaman.

---

## 🛠️ Spesifikasi Lingkungan

* **PHP**: `^8.2`
* **Framework**: Laravel 10 (dengan starter kit Laravel Breeze)
* **Database**: PostgreSQL (skema relasional teroptimasi)
* **CSS Framework**: Bootstrap 5 + Bootstrap Icons
* **UI Theme**: Custom Emerald Green & Brass Gold Identity (Dark Mode support via LocalStorage)
* **Pihak Ketiga**: Fonnte API (WhatsApp Gateway), DomPDF, Laravel Excel, Chart.js, SweetAlert2.

---

## 🚀 Panduan Deployment ke Hosting

### 1. Unggah Source Code
Unggah seluruh source code proyek ini ke direktori web server hosting Anda (misalnya `public_html` atau root direktori VPS). Pastikan direktori seperti `/vendor`, `/node_modules`, dan berkas `.env` tidak ikut diunggah.

### 2. Konfigurasi Environment Variables
Konfigurasikan Environment Variables pada panel hosting atau server Anda dengan merujuk pada template berkas `.env.example`. Masukkan kredensial database PostgreSQL, APP_KEY yang valid, serta token WhatsApp Fonnte API (`FONNTE_TOKEN` & `FONNTE_URL`) Anda.

### 3. Pasang Dependensi & Build Assets
Jalankan perintah berikut di direktori root proyek via SSH/Terminal hosting:
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```
*(Catatan: Jika hosting Anda tidak menyediakan akses terminal/SSH, pasang dependensi dan lakukan build asset secara lokal terlebih dahulu, kemudian unggah folder `vendor` dan `public/build` ke hosting).*

### 4. Migrasi Skema Database
Jalankan migrasi tabel database di server hosting:
```bash
php artisan migrate --force
```
Jika Anda perlu mengisi data referensi dasar ke database, jalankan seeder:
```bash
php artisan db:seed --force
```

### 5. Atur Hak Akses Direktori (Permissions)
Pastikan direktori berikut memiliki hak akses tulis (write permission) oleh web server:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 6. Konfigurasi Document Root
Konfigurasikan web server atau arahkan domain Anda agar mengarah ke direktori `public` sebagai Document Root utama.

---

## 👤 Akses Halaman Admin

Untuk menjaga keamanan pada lingkungan hosting:
* Form login administrator diakses secara tersembunyi dengan mengklik karakter hak cipta `©` pada bagian footer halaman utama.
* Kredensial administrator harus dibuat secara aman menggunakan database seeder kustom atau dengan menambahkan data pengguna baru secara manual pada tabel `users` dengan enkripsi password (menggunakan bcrypt) demi mencegah kerentanan keamanan akses default.

---

## 📅 Alur Uji Coba Simulasi Kehadiran

1. **Akses Form Absensi**: Buka `https://[domain-anda]/` lalu klik **Mulai Absen Sekarang**, atau simulasikan check-in lokasi QR dengan mengakses URL `https://[domain-anda]/absensi?qrcode=QR-SATPAM` (Lokasi: Pos Satpam).
2. **Pilih Perkara & Konfirmasi Pihak**:
   * Pilih nomor perkara persidangan yang dijadwalkan hari ini.
   * Pilih nama pihak dari daftar yang wajib hadir.
   * Masukkan/konfirmasikan nomor WhatsApp pihak tersebut, lalu tekan **Kirim Kehadiran (Check-In)**.
3. **Penyelesaian Check-In**: Ulangi langkah di atas untuk pihak-pihak lain yang terdaftar dalam jadwal sidang hari tersebut.
4. **Pemicu Notifikasi**: Setelah semua pihak yang wajib hadir melakukan check-in, sistem secara otomatis mendeteksi kehadiran lengkap dan mengirimkan broadcast notifikasi WhatsApp kepada Majelis Hakim dan Panitera Pengganti. Log pengiriman dapat dipantau di menu **Log Notifikasi** pada panel admin.