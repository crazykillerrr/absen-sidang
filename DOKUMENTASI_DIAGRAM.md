# DOKUMENTASI DIAGRAM PERANCANGAN SISTEM
## SI-ABDI (Sistem Absensi Mandiri & Monitoring Kehadiran Pihak Persidangan)
### PENGADILAN TATA USAHA NEGARA BANDAR LAMPUNG

Dokumen ini berisi spesifikasi teknis dan visualisasi diagram untuk perancangan sistem **SI-ABDI**. Diagram di bawah ini ditulis menggunakan format **Mermaid** yang valid, sehingga dapat dirender secara langsung pada visualizer Markdown (seperti VS Code, GitHub) atau diekspor ke aplikasi editor diagram lainnya.

---

## 1. DAFTAR AKTOR & ELEMEN SISTEM

Sebelum masuk ke diagram, berikut adalah aktor yang berinteraksi dengan sistem SI-ABDI:

| Aktor | Peran & Deskripsi |
| :--- | :--- |
| **Pihak Berperkara** | Pengguna publik (Penggugat, Tergugat, Kuasa Hukum, Saksi, atau Ahli) yang memindai QR Code di area pengadilan untuk melaporkan kehadiran mereka secara mandiri tanpa harus login (*Zero Login*). |
| **Administrator / Staf IT** | Pengguna internal pengadilan yang memiliki hak akses penuh untuk mengelola data master, menyinkronkan jadwal SIPP secara manual, memantau log kehadiran, memicu panggilan WhatsApp, dan mencetak laporan rekapitulasi. |
| **Sistem SI-ABDI (Internal)** | Layanan background sistem (seperti scheduler, email generator, dan validation service) yang berjalan otomatis untuk memproses kelengkapan data dan mengirimkan notifikasi. |

---

## 2. USE CASE DIAGRAM

Diagram Use Case menggambarkan hubungan antara aktor dengan fungsionalitas utama yang disediakan oleh aplikasi SI-ABDI.

### A. Visualisasi Diagram Use Case
```mermaid
graph LR
    subgraph Aktor
        P[Pihak Berperkara]
        AD[Administrator / Staf IT]
        SYS[Sistem SI-ABDI]
    end

    subgraph "Sistem SI-ABDI (Use Case Boundary)"
        UC1((Pindai QR Code Lokasi))
        UC2((Pilih Perkara & Konfirmasi Pihak))
        UC3((Kirim Kehadiran Mandiri))
        UC4((Validasi Kelengkapan Kehadiran))
        UC5((Kirim Notifikasi Otomatis))
        UC6((Login Admin - Stealth Entry))
        UC7((Kelola Data Master))
        UC8((Sinkronisasi Jadwal SIPP))
        UC9((Pemantauan Log & Dashboard))
        UC10((Ekspor & Cetak Laporan))
    end

    %% Relasi Pihak Berperkara
    P --> UC1
    P --> UC2
    P --> UC3

    %% Relasi Sistem Internal
    UC3 --> UC4
    UC4 --> UC5
    UC5 --> SYS
    SYS --> UC8

    %% Relasi Administrator
    AD --> UC6
    AD --> UC7
    AD --> UC8
    AD --> UC9
    AD --> UC10
```

### B. Deskripsi Use Case Utama

1. **Use Case: Absensi Mandiri Pihak**
   * **Deskripsi**: Pihak berperkara melaporkan kehadirannya dengan memindai QR Code menggunakan smartphone mereka di lokasi persidangan.
   * **Kondisi Awal**: Jadwal sidang hari ini sudah termuat di sistem.
   * **Kondisi Akhir**: Rekam kehadiran masuk ke database dan memicu validasi kelengkapan kehadiran.
   * **Alur**: Scan QR $\rightarrow$ Dialihkan ke halaman absensi $\rightarrow$ Pilih nomor perkara $\rightarrow$ Pilih nama pihak $\rightarrow$ Klik kirim.

2. **Use Case: Validasi & Notifikasi Otomatis**
   * **Deskripsi**: Sistem secara otomatis mendeteksi apakah para pihak persidangan dari suatu perkara sudah hadir 100%. Jika lengkap, sistem mengirimkan email pemberitahuan ke Majelis Hakim & PP.
   * **Kondisi Awal**: Pihak mengirim absensi mandiri.
   * **Kondisi Akhir**: Email siap sidang dikirim ke Hakim & PP, log terekam di tabel `notifikasi`.

3. **Use Case: Sinkronisasi Jadwal SIPP**
   * **Deskripsi**: Menarik data persidangan dari basis data SIPP PTUN Bandar Lampung menggunakan crawler.
   * **Kondisi Akhir**: Jadwal persidangan lokal sinkron dengan data SIPP.

---

## 3. ACTIVITY DIAGRAM

Activity diagram di bawah ini didefinisikan menggunakan **subgraph swimlanes** yang valid dalam sintaksis Mermaid.

### A. Activity Diagram: Absensi Mandiri Pihak
Diagram ini menunjukkan alur aktivitas pihak berperkara ketika melaporkan kehadirannya secara mandiri melalui pemindaian QR Code.

```mermaid
flowchart TD
    subgraph Pihak[Pihak Berperkara]
        Start1([Mulai]) --> A1[Datang ke area PTUN Bandar Lampung]
        A1 --> A2[Memindai QR Code di area pengadilan]
        A5[Memilih Nomor Perkara Sidang]
        A6[Memilih Nama Pihak & Verifikasi Data]
        A7[Klik tombol 'Kirim Kehadiran']
    end

    subgraph Sistem[Sistem SI-ABDI]
        S1[Mengalihkan ke URL website Portal Absensi]
        S2[Memuat daftar jadwal persidangan aktif hari ini]
        S3[Menampilkan halaman Portal Absensi Publik]
        S4[Validasi input form absensi]
        S5[Menyimpan data kehadiran ke database]
        S6[Memicu validasi kelengkapan kehadiran]
        S7[Menampilkan visualisasi sukses dengan SweetAlert2] --> End1([Selesai])
    end

    A2 --> S1
    S1 --> S2
    S2 --> S3
    S3 --> A5
    A5 --> A6
    A6 --> A7
    A7 --> S4
    S4 --> S5
    S5 --> S6
    S6 --> S7
```

---

### B. Activity Diagram: Monitoring Kehadiran & Backoffice
Diagram ini menjelaskan bagaimana administrator mengakses dashboard backoffice melalui mekanisme masuk tersembunyi (*Stealth Entry*) dan melakukan tugas-tugas administratif.

```mermaid
flowchart TD
    subgraph Admin[Administrator]
        Start2([Mulai]) --> A_Log1[Membuka halaman utama SI-ABDI]
        A_Log1 --> A_Log2[Mengklik ikon hak cipta © di footer / Stealth Entry]
        A_Log3[Memasukkan Email & Password Admin]
        A_Log3 --> A_Log4[Klik 'Login']
        
        A_Menu[Memilih Menu Dashboard]
        
        %% Menu Branches
        A_Menu --> A_M1[Pilih menu Log Notifikasi]
        A_Menu --> A_M2[Pilih menu Sinkronisasi SIPP]
        A_Menu --> A_M3[Pilih menu Laporan Kehadiran]
        
        A_M1_Act[Melihat status pengiriman Email / WA] --> End2
        A_M2_Act[Klik tombol Sinkronisasi Manual] --> End2
        A_M3_Act[Filter tanggal & Klik Ekspor PDF / Excel] --> End2
    end

    subgraph Sistem[Sistem SI-ABDI]
        S_Log1[Membuka formulir login tersembunyi]
        S_Log2{Kredensial Valid?}
        S_Log3[Mengarahkan ke Dashboard Backoffice & memuat analitik Chart.js]
        S_Log4[Tampilkan pesan error password salah]
    end

    A_Log2 --> S_Log1
    S_Log1 --> A_Log3
    A_Log4 --> S_Log2
    S_Log2 -- Ya --> S_Log3
    S_Log2 -- Tidak --> S_Log4
    S_Log4 --> End2([Selesai])
    S_Log3 --> A_Menu
    
    A_M1 --> A_M1_Act
    A_M2 --> A_M2_Act
    A_M3 --> A_M3_Act
```

---

### C. Activity Diagram: Notifikasi Panggilan WhatsApp (Manual Trigger)
Diagram ini menjelaskan alur pengiriman pesan panggilan sidang ke WhatsApp pihak berperkara (via Twilio API) saat administrator mengklik tombol "Panggil Pihak".

```mermaid
flowchart TD
    subgraph Admin[Administrator]
        Start3([Mulai]) --> A_Call[Mengklik tombol 'Panggil Pihak' pada Dashboard]
    end

    subgraph Sistem[Sistem SI-ABDI]
        S_C1[Menerima request panggil pihak jadwal_sidang_id]
        S_C2[Memuat data Jadwal, Perkara, Pihak, dan Ruang Sidang]
        S_C3[Menyaring Pihak Sidang yang hadir kehadiran != null]
        S_C4{Apakah ada pihak yang hadir?}
        S_C5[Tampilkan error: Belum ada pihak yang absen]
        S_C6[Redirect ke Dashboard]
        
        S_C7[Inisialisasi counter waSuccess=0, emailSuccess=0]
        S_C8[Pilih entitas pihak berikutnya]
        S_C9{No. HP ada?}
        S_C10[Susun template pesan panggilan WhatsApp]
        
        S_C11{Sukses Kirim WA?}
        S_C12[Set status_kirim = 'terkirim' & waSuccess++]
        S_C13[Set status_kirim = 'gagal']
        S_C14[Simpan log notifikasi WhatsApp ke DB]
        
        S_C15{Email ada?}
        S_C16[Kirim email panggilan PanggilanSidangMail]
        S_C17{Email sukses?}
        S_C18[Set status_kirim = 'terkirim' & emailSuccess++]
        S_C19[Set status_kirim = 'gagal']
        S_C20[Simpan log notifikasi Email ke DB]
        
        S_C21{Semua pihak selesai?}
        S_C22[Redirect ke Dashboard & Tampilkan alert sukses]
    end

    subgraph WA_Service[WhatsAppNotificationService]
        W_Format[Format nomor hp: awalan 0 ke 62]
        W_Send[Kirim POST Request ke Twilio API Gateway]
    end

    A_Call --> S_C1
    S_C1 --> S_C2
    S_C2 --> S_C3
    S_C3 --> S_C4
    S_C4 -- Tidak --> S_C5
    S_C5 --> S_C6 --> End3([Selesai])
    
    S_C4 -- Ya --> S_C7
    S_C7 --> S_C8
    S_C8 --> S_C9
    S_C9 -- Ya --> S_C10
    S_C9 -- Tidak --> S_C15
    
    S_C10 --> W_Format
    W_Format --> W_Send
    W_Send --> S_C11
    
    S_C11 -- Ya --> S_C12
    S_C11 -- Tidak --> S_C13
    S_C12 --> S_C14
    S_C13 --> S_C14
    S_C14 --> S_C15
    
    S_C15 -- Ya --> S_C16
    S_C15 -- Tidak --> S_C21
    
    S_C16 --> S_C17
    S_C17 -- Ya --> S_C18
    S_C17 -- Tidak --> S_C19
    S_C18 --> S_C20
    S_C19 --> S_C20
    S_C20 --> S_C21
    
    S_C21 -- Tidak --> S_C8
    S_C21 -- Ya --> S_C22
    S_C22 --> End3
```

---

## 4. SEQUENCE DIAGRAM

Diagram urutan (*Sequence Diagram*) di bawah ini menggambarkan interaksi antarkomponen perangkat lunak saat pihak melakukan absensi mandiri, dilanjutkan dengan validasi kehadiran 100% dan pengiriman notifikasi otomatis.

```mermaid
sequenceDiagram
    autonumber
    actor Pihak as Pihak Berperkara
    participant Portal as Portal Absensi (Client)
    participant Ctrl as KehadiranController
    participant Srv as KehadiranService
    participant RepoK as KehadiranRepository
    participant ValSrv as AttendanceValidationService
    participant RepoN as NotifikasiRepository
    participant MailSrv as Laravel Mailer (SMTP)
    participant DB as Database PostgreSQL

    Pihak->>Portal: Scan QR & Isi Formulir Absensi
    Pihak->>Portal: Klik "Kirim Kehadiran"
    Portal->>Ctrl: POST /absensi/store (pihak_sidang_id, status_hadir)
    Ctrl->>Srv: recordAttendance(data, jadwalSidangId)
    
    activate Srv
    Srv->>DB: Mulai Transaksi (DB::transaction)
    Srv->>RepoK: create(data)
    
    activate RepoK
    RepoK->>DB: INSERT INTO kehadiran (pihak_sidang_id, waktu_hadir, status_hadir)
    RepoK-->>Srv: Mengembalikan Kehadiran Model
    deactivate RepoK

    Srv->>ValSrv: validateAndNotify(jadwalSidangId)
    
    activate ValSrv
    ValSrv->>DB: Hitung total pihak wajib hadir & jumlah kehadiran actual
    DB-->>ValSrv: return count
    
    alt Jumlah Hadir == Jumlah Wajib
        ValSrv->>DB: Cek keberadaan log notifikasi terkirim sebelumnya
        DB-->>ValSrv: return false (belum ada)
        ValSrv->>MailSrv: send(NotifikasiSidangMail)
        activate MailSrv
        MailSrv-->>ValSrv: return status kirim (sukses/gagal)
        deactivate MailSrv
        ValSrv->>RepoN: create(notifikasiData)
        RepoN->>DB: INSERT INTO notifikasi (jadwal_sidang_id, jenis, status_kirim, waktu_kirim)
    end
    
    deactivate ValSrv
    Srv->>DB: Commit Transaksi
    Srv-->>Ctrl: return Kehadiran
    deactivate Srv
    
    Ctrl-->>Portal: return JSON Response (Success)
    Portal-->>Pihak: Tampilkan Modal Sukses (SweetAlert2)
```

---

## 5. PANDUAN REKAYASA & RENDERING DIAGRAM

Jika Anda ingin meregenerasi diagram ini ke dalam format gambar (PNG/SVG) atau ingin mengedit alurnya kembali, ikuti langkah-langkah praktis berikut:

### Metode A: Menggunakan Mermaid Live Editor (Sangat Direkomendasikan)
1. Buka browser dan akses **[Mermaid Live Editor](https://live.mermaid.live/)**.
2. Salin kode Mermaid di atas (mulai dari baris `graph ...`, `flowchart ...`, atau `sequenceDiagram ...` hingga baris akhir di dalam blok kode).
3. Tempelkan kode ke dalam kolom editor sebelah kiri (**Code**).
4. Diagram akan ter-render secara interaktif di panel sebelah kanan.
5. Klik menu **Actions** di bawah panel render untuk mengunduh gambar dalam format **PNG**, **SVG**, atau membagikan link diagram tersebut.

### Metode B: Menggunakan Draw.io (Integrasi ke Desain Laporan)
Jika Anda membutuhkan kustomisasi layout yang lebih fleksibel untuk laporan formal:
1. Buka situs **[Draw.io](https://app.diagrams.net/)**.
2. Buat diagram baru atau buka diagram kosong.
3. Pada baris menu atas, pilih **Arrange** $\rightarrow$ **Insert** $\rightarrow$ **Advanced** $\rightarrow$ **Mermaid...**
4. Tempelkan kode Mermaid dari dokumen ini ke dalam kotak input yang disediakan, lalu klik **Insert**.
5. Draw.io akan secara otomatis menerjemahkan kode Mermaid menjadi bentuk-bentuk diagram yang siap digeser, diwarnai, dan diedit secara visual.

### Metode C: Rendering Langsung di VS Code
Jika Anda menggunakan Visual Studio Code untuk mengedit file Markdown ini:
1. Pasang ekstensi **Markdown Preview Mermaid Support** atau **Markdown Preview Enhanced**.
2. Buka preview Markdown di VS Code (`Ctrl + Shift + V`).
3. Semua diagram di atas akan dirender secara langsung dan responsif sesuai tema editor Anda.
