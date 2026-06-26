# RINGKASAN EKSEKUTIF PROYEK (PROJECT SUMMARY)

## 📌 Identitas Proyek
* **Nama Sistem**: SI-ABDI (Sistem Absensi Mandiri & Monitoring Kehadiran Pihak Persidangan)
* **Instansi Implementasi**: Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung
* **Basis Platform**: Web Application (Responsive & Mobile-Friendly)
* **Tujuan Utama**: Digitalisasi proses check-in pihak berperkara secara mandiri via QR Code dan otomatisasi notifikasi waktu persidangan ke Majelis Hakim & Panitera Pengganti (PP) demi menimimalisasi keterlambatan sidang harian.

---

## 🛠️ Arsitektur & Spesifikasi Teknologi  
Sistem ini dirancang menggunakan standar pengembangan modern untuk memastikan stabilitas, performa cepat, dan keamanan tinggi:

1. **Back-End (Logika Bisnis & API)**:
   * **PHP `^8.2` & Framework Laravel `10.x`**: Menyediakan kerangka kerja MVC, manajemen routing, ORM Eloquent, dan sistem pengiriman notifikasi terstruktur.
   * **Service-Repository Pattern**: Memisahkan query database dari logika bisnis utama agar kode mudah dipelihara dan diuji.
2. **Database (Penyimpanan Data)**:
   * **PostgreSQL**: DBMS relasional tangguh untuk mengelola entitas data persidangan secara aman dengan integritas kunci asing (*foreign key*) yang presisi.
3. **Front-End (Antarmuka Pengguna)**:
   * **Bootstrap 5 & Bootstrap Icons**: Layout web responsif yang ramah perangkat seluler.
   * **Custom Glassmorphism UI**: Desain bertema peradilan modern (kombinasi warna hijau zamrud dan emas kuningan) dengan latar belakang gradasi dinamis dan dekorasi partikel bercahaya (*ambient glowing orbs*).
4. **Integrasi Pihak Ketiga & Otomatisasi**:
   * **SIPP Web Crawler (Symfony DomCrawler)**: Modul untuk mengambil data jadwal sidang langsung secara eksternal dari web SIPP PTUN Bandar Lampung.
   * **Fonnte API Gateway**: Saluran integrasi WhatsApp untuk pengiriman broadcast status kehadiran.
   * **DomPDF & Laravel Excel**: Generator dokumen ekspor untuk file laporan administratif (PDF/Excel).

---

## 🚀 Fitur & Modul Utama

### 1. Portal Publik: Absensi Mandiri Berbasis QR Code
* **Scan QR Code (Zero Login)**: Pihak berperkara (Penggugat, Tergugat, Saksi, Kuasa Hukum) cukup memindai kode QR yang diletakkan di titik-titik strategis area pengadilan (Pos Satpam, Ruang Tunggu).
* **Deteksi Lokasi QR & Pengisian Otomatis**: Sistem secara otomatis mengenali titik lokasi check-in pihak (misalnya "Pos Satpam") dan menyaring daftar jadwal persidangan hari itu. Pihak hanya perlu memilih nomor perkara dan nama mereka untuk konfirmasi tanpa perlu login.

### 2. Logika Validasi Kehadiran & Notifikasi Otomatis
* **Attendance Validation Engine (`AttendanceValidationService`)**: Setiap kali ada satu pihak yang melakukan check-in, sistem otomatis membandingkan jumlah kehadiran riil dengan roster pihak wajib hadir untuk nomor perkara tersebut.
* **Notifikasi Instan**: Begitu formasi kehadiran pihak dinyatakan lengkap (100% hadir), sistem langsung menembakkan notifikasi otomatis (melalui WhatsApp Gateway & Email) berisi pesan siap sidang kepada Ketua Majelis, Hakim Anggota, dan Panitera Pengganti (PP) perkara tersebut.

### 3. Modul Integrasi SIPP (`SippSyncService`)
* **Sinkronisasi Otomatis**: Pengambilan jadwal sidang PTUN Bandar Lampung secara mandiri hingga 10 hari ke depan, mengurangi pengerjaan entri data berulang (*double entry*) oleh staf IT pengadilan.

### 4. Dasbor Backoffice Administrator
* **Analitik Tren Kehadiran**: Grafik visual (Chart.js) yang memetakan tren volume kehadiran pihak dalam 7 hari terakhir.
* **Manajemen Data Master (CRUD)**: Pengelolaan data Hakim, Panitera Pengganti, Perkara, Formasi Majelis Hakim, dan Ruang Sidang dengan fitur *Soft Deletes* (penghapusan logis demi menjaga integritas data historis).
* **Audit & Log Notifikasi**: Memantau status pengiriman pesan (*Pending*, *Terkirim*, atau *Gagal*) lengkap dengan waktu kirim dan nomor tujuan WhatsApp/Email.
* **Ekspor Laporan**: Fitur penyaringan data absensi dan pencetakan rekapitulasi ke format PDF atau spreadsheet Excel.

### 5. Metode Keamanan: Stealth Admin Entry
* **Tombol Akses Login Tersembunyi**: Untuk menjaga agar portal publik bersih dan aman, tautan login admin disembunyikan secara rahasia di dalam simbol hak cipta `©` pada bagian footer halaman beranda. Pengguna publik tidak akan melihat tombol masuk yang berpotensi memicu serangan *brute force*.

---

## 📈 Alur Penggunaan Sistem (User Journey)

```
[ Pihak Berperkara ]               [ Sistem Absensi ]             [ Majelis Hakim / PP ]
        │                                  │                                 │
        ├─► Scan QR Code Area ────────────►│                                 │
        │   (Pos Satpam/Ruang Tunggu)      │                                 │
        │                                  │                                 │
        ├─► Pilih Perkara & Nama Pihak ───►│                                 │
        │                                  ├─► Simpan Kehadiran              │
        │                                  │                                 │
        │                                  ├─► Validasi Kelengkapan          │
        │                                  │   (Pihak Hadir == Pihak Wajib)  │
        │                                  │                                 │
        │                                  ├─► Kirim Notifikasi ────────────►│
        │                                  │   (WhatsApp/Email)              │   Hakim & PP masuk
        │                                  │                                 │◄── Ruang Sidang &
        │                                  │                                 │   mulai tepat waktu
```

---

## 🌟 Nilai Unggul & Dampak Kinerja
1. **Efisiensi Waktu Persidangan**: Mengeliminasi waktu tunggu hakim yang tidak pasti dan mengurangi waktu pencarian pihak dari 15 menit menjadi di bawah 1 menit.
2. **Kondusivitas Kantor Pengadilan**: Mengurangi kebisingan akibat pemanggilan nama berulang-ulang melalui pengeras suara di ruang tunggu.
3. **Transparansi & Akuntabilitas**: Menghasilkan data historis kehadiran yang akurat, real-time, dan terintegrasi penuh untuk keperluan laporan tahunan atau audit internal kinerja pengadilan.
