# SISTEM PAKAR TROUBLESHOOTING KOMPUTER DAN SOFTWARE

## 📋 Informasi Proyek

**Judul:** Implementasi Sistem Pakar untuk Diagnosa Troubleshooting Komputer dan Software Menggunakan Metode Forward Chaining Berbasis Web

**Studi Kasus:** Pondok Pesantren Al-Gontory

**Teknologi:**

- PHP 7.4+ (Prosedural, tanpa framework)
- MySQL Database
- HTML5, CSS3, JavaScript (ES6+)
- Bootstrap 5 (CDN)
- AJAX / Fetch API

---

## 🚀 What's New in v2.0

### 💬 Chat Diagnosa AI (NEW!)

Fitur revolusioner yang mengubah cara diagnosa dari checkbox menjadi **natural language chat interface**!

**Highlights:**

- 🤖 Chat dengan sistem seperti chatbot
- 🔍 Auto-detect gejala dari input user
- ⚡ Real-time diagnosis tanpa reload
- 📱 Modern chat UI dengan typing indicator
- 🎯 Quick action buttons untuk masalah umum

**Baca dokumentasi:** [CHAT_DIAGNOSA_GUIDE.md](CHAT_DIAGNOSA_GUIDE.md)
**Quick Start:** [CHAT_QUICK_START.md](CHAT_QUICK_START.md)

---

## 🎯 Fitur Utama

### Role: Admin (Ketua Lab)

1. ✅ **Manajemen Gejala** - CRUD data gejala kerusakan + kata kunci
2. ✅ **Manajemen Kerusakan** - CRUD data kerusakan dan solusi
3. ✅ **Manajemen Rule** - CRUD aturan forward chaining
4. ✅ **Laporan Diagnosa** - Melihat rekap semua diagnosa

### Role: Asisten Lab

1. ✅ **Diagnosa Kerusakan** - Input gejala dengan checklist
2. ✅ **💬 Chat Diagnosa AI (NEW!)** - Diagnosa via chat natural language
3. ✅ **Hasil Diagnosa** - Melihat hasil dan solusi
4. ✅ **Riwayat Diagnosa** - Melihat histori diagnosa sendiri
5. ✅ **Export Laporan** - Export/cetak laporan PDF

---

## 📁 Struktur Folder

```
Troubleshooting-Komputer/
│
├── Config/
│   └── koneksi.php                 # Konfigurasi database
│
├── Database/
│   ├── db_sistem_pakar_gontory.sql # File SQL database
│   └── update_kata_kunci.sql       # 🆕 Update tabel gejala (kata kunci)
│
├── Auth/
│   ├── login.php                   # Halaman login
│   ├── proses_login.php            # Proses autentikasi
│   ├── logout.php                  # Proses logout
│   └── cek_session.php             # Helper validasi session
│
├── Admin/
│   ├── dashboard_admin.php         # Dashboard admin
│   ├── sidebar_admin.php           # Sidebar menu admin
│   ├── gejala_management.php       # CRUD Gejala
│   ├── kerusakan_management.php    # CRUD Kerusakan
│   ├── rule_management.php         # CRUD Rule
│   ├── laporan_diagnosa.php        # Laporan semua diagnosa
│   ├── detail_diagnosa.php         # Detail diagnosa
│   └── user_management.php         # CRUD Users
│
├── Asisten/
│   ├── dashboard_asisten.php       # Dashboard asisten
│   ├── sidebar_asisten.php         # Sidebar menu asisten
│   ├── diagnosa.php                # Input diagnosa (checklist)
│   ├── diagnosa_chat.php           # 🆕 Chat diagnosa AI
│   ├── proses_diagnosa.php         # Proses forward chaining
│   ├── proses_chat.php             # 🆕 Backend chat (hardcoded)
│   ├── proses_chat_v2.php          # 🆕 Backend chat (dynamic)
│   ├── hasil_diagnosa.php          # Tampil hasil & solusi
│   ├── riwayat_diagnosa.php        # Riwayat diagnosa
│   └── export_laporan.php          # Export laporan
│
├── Assets/
│   ├── css/
│   │   └── style.css               # Custom CSS (+ chat styles)
│   └── js/
│       └── script.js               # Custom JavaScript
│
├── index.php                       # Landing page (redirect to login)
├── README.md                       # 📖 Documentation utama
├── CHANGELOG.md                    # 📝 Version history
├── INSTALL.md                      # 🛠️ Installation guide
├── QUICK_REFERENCE.md              # ⚡ Quick reference
├── CHAT_DIAGNOSA_GUIDE.md          # 🆕 Chat feature documentation
├── CHAT_QUICK_START.md             # 🆕 Chat quick start guide
├── TESTING_SCENARIOS.md            # 🆕 Testing & QA scenarios
└── DEMO_SCRIPT.md                  # 🆕 Demo presentation script
```

---

## 🗄️ Database Schema

### Tabel: users

- `id_user` (INT, PK, AI)
- `username` (VARCHAR)
- `password` (VARCHAR - hashed)
- `role` (ENUM: admin, asisten_lab)
- `nama_lengkap` (VARCHAR)
- `created_at` (TIMESTAMP)

### Tabel: gejala

- `id_gejala` (INT, PK, AI)
- `kode_gejala` (VARCHAR)
- `nama_gejala` (TEXT)
- `kata_kunci` (TEXT) 🆕 - Keyword untuk chat matching

### Tabel: kerusakan

- `id_kerusakan` (INT, PK, AI)
- `kode_kerusakan` (VARCHAR)
- `nama_kerusakan` (TEXT)
- `solusi` (TEXT)

### Tabel: rule

- `id_rule` (INT, PK, AI)
- `id_kerusakan` (INT, FK)

### Tabel: rule_detail

- `id` (INT, PK, AI)
- `id_rule` (INT, FK)
- `id_gejala` (INT, FK)

### Tabel: diagnosa

- `id_diagnosa` (INT, PK, AI)
- `id_user` (INT, FK)
- `tanggal` (DATETIME)
- `hasil_kerusakan` (TEXT)

### Tabel: diagnosa_detail

- `id` (INT, PK, AI)
- `id_diagnosa` (INT, FK)
- `id_gejala` (INT, FK)

---

## 🚀 Cara Instalasi

### 1. Prerequisites

- XAMPP (PHP 7.4+ & MySQL)
- Browser modern (Chrome, Firefox, Edge)

### 2. Langkah Instalasi

#### A. Setup Database

1. Buka **phpMyAdmin** (http://localhost/phpmyadmin)
2. Import file `Database/db_sistem_pakar_gontory.sql`
3. Database akan otomatis terbuat dengan nama `db_sistem_pakar_gontory`

#### B. Setup Aplikasi

1. Copy folder `Troubleshooting-Komputer` ke `C:\xampp\htdocs\`
2. Pastikan struktur folder: `C:\xampp\htdocs\Troubleshooting-Komputer\`
3. Edit file `Config/koneksi.php` jika perlu (default: host=localhost, user=root, pass='')

#### C. Akses Aplikasi

1. Start Apache dan MySQL di XAMPP Control Panel
2. Buka browser, akses: **http://localhost/Troubleshooting-Komputer**
3. Akan otomatis redirect ke halaman login

### 3. Akun Default

**Admin:**

- Username: `admin`
- Password: `password`

**Asisten Lab:**

- Username: `asisten1`
- Password: `password`

> ⚠️ **PENTING:** Ganti password default setelah login pertama kali!

---

## 🔬 Algoritma Forward Chaining

### Cara Kerja:

1. User (Asisten) memilih beberapa gejala melalui checkbox
2. Sistem mengambil semua rule dari database
3. Untuk setiap rule:
   - Ambil semua gejala yang menjadi syarat rule tersebut
   - Cek apakah **SEMUA** gejala rule ada di pilihan user
   - Jika ya, rule tersebut **MATCH**
4. Ambil kerusakan dari rule yang match pertama
5. Tampilkan nama kerusakan dan solusinya
6. Simpan hasil diagnosa ke database

### Implementasi (proses_diagnosa.php):

```php
// Ambil gejala yang dipilih user
$gejala_dipilih = $_POST['gejala']; // Array

// Loop setiap rule
while ($rule = ...) {
    // Ambil gejala syarat rule
    $gejala_rule = [...];

    // PENCOCOKAN menggunakan array_diff
    $difference = array_diff($gejala_rule, $gejala_dipilih);

    // Jika difference kosong = semua gejala rule ada di pilihan
    if (empty($difference)) {
        // Rule MATCH!
        $kerusakan = ...;
        break;
    }
}
```

---

## 📊 Fitur Tambahan

### Keamanan

- ✅ Password di-hash menggunakan `password_hash()` PHP
- ✅ Validasi session di setiap halaman
- ✅ Role-based access control (admin vs asisten)
- ✅ SQL Injection protection dengan `mysqli_real_escape_string()`

### User Experience

- ✅ Responsive design (Bootstrap 5)
- ✅ Sidebar navigation
- ✅ Alert auto-hide
- ✅ Konfirmasi sebelum hapus data
- ✅ Loading spinner
- ✅ Print-friendly layouts

### Database

- ✅ Foreign key constraints
- ✅ Cascade delete
- ✅ Auto increment
- ✅ Timestamp otomatis

---

## 🎨 Tampilan UI

- **Color Scheme:** Modern gradient (blue-purple)
- **Layout:** Sidebar kiri + konten kanan
- **Responsive:** Mobile-friendly
- **Icons:** Bootstrap Icons
- **Typography:** Segoe UI

---

## 📝 Panduan Penggunaan

### Untuk Admin:

#### 1. Menambah Gejala

1. Login sebagai admin
2. Klik menu "Manajemen Gejala"
3. Isi form (Kode Gejala & Nama Gejala)
4. Klik "Simpan"

#### 2. Menambah Kerusakan & Solusi

1. Klik menu "Manajemen Kerusakan"
2. Isi form:
   - Kode Kerusakan (contoh: K001)
   - Nama Kerusakan
   - Solusi (langkah-langkah penanganan)
3. Klik "Simpan"

#### 3. Membuat Rule Forward Chaining

1. Klik menu "Manajemen Rule"
2. Pilih kerusakan dari dropdown
3. Centang gejala-gejala yang menjadi ciri kerusakan tersebut
4. Klik "Simpan"

**Contoh Rule:**

- Kerusakan: "Kerusakan Power Supply"
- Gejala yang dipilih:
  - ✅ G001 - Komputer tidak bisa menyala
  - ✅ G002 - Lampu indikator power tidak menyala

Artinya: Jika user memilih kedua gejala tersebut, sistem akan mendiagnosa sebagai "Kerusakan Power Supply"

#### 4. Melihat Laporan

1. Klik menu "Laporan Diagnosa"
2. Gunakan filter tanggal/asisten jika perlu
3. Klik "Detail" untuk melihat gejala yang dipilih

### Untuk Asisten Lab:

#### 1. Melakukan Diagnosa

1. Login sebagai asisten
2. Klik menu "Diagnosa Kerusakan"
3. Centang semua gejala yang dialami komputer
4. Klik "Proses Diagnosa"
5. Lihat hasil kerusakan dan solusi

#### 2. Melihat Riwayat

1. Klik menu "Riwayat Diagnosa"
2. Klik "Detail" untuk melihat hasil diagnosa sebelumnya

#### 3. Export Laporan

1. Klik menu "Export Laporan"
2. Pilih filter tanggal (opsional)
3. Klik "Cetak / Save as PDF"
4. Gunakan fungsi print browser (Ctrl+P) → Save as PDF

---

## 🐛 Troubleshooting

### Error: "Koneksi Database Gagal"

**Solusi:**

- Pastikan MySQL di XAMPP sudah running
- Cek konfigurasi di `Config/koneksi.php`
- Pastikan database `db_sistem_pakar_gontory` sudah diimport

### Error: "Session tidak ditemukan"

**Solusi:**

- Pastikan sudah login
- Clear browser cache dan cookies
- Logout dan login kembali

### Halaman tidak muncul CSS/JS

**Solusi:**

- Cek path folder Assets (huruf besar/kecil)
- Hard refresh browser (Ctrl+F5)
- Periksa console browser untuk error

### Rule tidak match padahal gejala sudah sesuai

**Solusi:**

- Periksa rule di database, pastikan id_gejala benar
- Cek apakah semua gejala yang dipilih sudah terdaftar di rule_detail

---

## 📚 Referensi

1. **Forward Chaining:** Metode inferensi yang dimulai dari fakta (gejala) menuju kesimpulan (kerusakan)
2. **Expert System:** Sistem yang meniru kemampuan seorang pakar dalam bidang tertentu
3. **PHP Procedural:** Paradigma pemrograman tanpa menggunakan OOP atau framework

---

## 👨‍💻 Developer Notes

### Code Style:

- Indentasi: 4 spasi
- Penamaan variabel: snake_case
- Penamaan file: lowercase dengan underscore
- Komentar: Bahasa Indonesia untuk deskripsi, English untuk code

### Database Convention:

- Primary Key: `id_[nama_tabel]`
- Foreign Key: `id_[nama_tabel_referensi]`
- Timestamp: `created_at`, `updated_at`

### Security Notes:

- Semua input di-escape dengan `mysqli_real_escape_string()`
- Password di-hash dengan `password_hash()` (bcrypt)
- Session validation di setiap halaman
- Role-based access control

---

## 📞 Support

Jika ada pertanyaan atau kendala dalam penggunaan sistem:

1. Hubungi Admin Sistem
2. Konsultasikan dengan Ketua Lab
3. Baca dokumentasi ini dengan teliti

---

## 📄 License

Proyek ini dibuat untuk keperluan Tugas Akhir.
© 2025 - Pondok Pesantren Al-Gontory

---

## ✅ Checklist Fitur

- [x] Login & Logout
- [x] Role-based Access (Admin & Asisten)
- [x] CRUD Gejala
- [x] CRUD Kerusakan & Solusi
- [x] CRUD Rule (Forward Chaining)
- [x] Proses Diagnosa dengan Forward Chaining
- [x] Hasil Diagnosa & Solusi
- [x] Riwayat Diagnosa
- [x] Laporan untuk Admin
- [x] Export/Print Laporan
- [x] Responsive Design
- [x] Validasi Form
- [x] Alert & Notification
- [x] Database dengan Foreign Key

---

**Selamat menggunakan Sistem Pakar Troubleshooting Komputer!** 🎉
