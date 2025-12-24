# 📖 PANDUAN INSTALASI

## Sistem Pakar Troubleshooting Komputer dan Software

---

## 🔧 PERSIAPAN

### Kebutuhan Sistem:

- ✅ XAMPP 7.4 atau lebih baru
- ✅ Browser: Chrome, Firefox, atau Edge (versi terbaru)
- ✅ RAM minimal: 2GB
- ✅ Storage: 100MB free space

---

## 📥 LANGKAH INSTALASI

### STEP 1: Install XAMPP

1. Download XAMPP dari: https://www.apachefriends.org/
2. Install XAMPP ke `C:\xampp\`
3. Jalankan XAMPP Control Panel

### STEP 2: Setup Database

#### Cara 1: Menggunakan phpMyAdmin

1. Start **Apache** dan **MySQL** di XAMPP Control Panel
2. Buka browser, akses: `http://localhost/phpmyadmin`
3. Klik tab **"Import"**
4. Klik **"Choose File"**, pilih file:
   ```
   C:\xampp\htdocs\Troubleshooting-Komputer\Database\db_sistem_pakar_gontory.sql
   ```
5. Scroll ke bawah, klik **"Go"**
6. Tunggu hingga muncul pesan sukses
7. Database `db_sistem_pakar_gontory` akan otomatis terbuat

#### Cara 2: Menggunakan MySQL Command Line

1. Buka Command Prompt (CMD)
2. Masuk ke direktori MySQL:
   ```bash
   cd C:\xampp\mysql\bin
   ```
3. Login ke MySQL:
   ```bash
   mysql -u root -p
   ```
   (Tekan Enter jika tidak ada password)
4. Jalankan import:
   ```sql
   source C:/xampp/htdocs/Troubleshooting-Komputer/Database/db_sistem_pakar_gontory.sql
   ```
5. Ketik `exit` untuk keluar

### STEP 3: Copy Project

1. Extract/Copy folder **"Troubleshooting-Komputer"**
2. Paste ke: `C:\xampp\htdocs\`
3. Struktur akhir:
   ```
   C:\xampp\htdocs\Troubleshooting-Komputer\
   ├── Admin/
   ├── Asisten/
   ├── Assets/
   ├── Auth/
   ├── Config/
   ├── Database/
   └── index.php
   ```

### STEP 4: Konfigurasi Database (Opsional)

Jika menggunakan password MySQL yang berbeda:

1. Buka file: `Config\koneksi.php`
2. Edit baris berikut:
   ```php
   $db_host = "localhost";    // Ganti jika host berbeda
   $db_user = "root";         // Ganti jika username berbeda
   $db_pass = "";             // Masukkan password MySQL Anda
   $db_name = "db_sistem_pakar_gontory";
   ```
3. Save file

### STEP 5: Test Aplikasi

1. Pastikan Apache dan MySQL di XAMPP sudah **running** (hijau)
2. Buka browser
3. Akses: `http://localhost/Troubleshooting-Komputer`
4. Akan muncul halaman **Login**

---

## 🔑 AKUN DEFAULT

### Admin (Ketua Lab)

```
Username: admin
Password: password
```

### Asisten Lab

```
Username: asisten1
Password: password
```

> ⚠️ **PENTING:** Ganti password setelah login pertama!

---

## ✅ VERIFIKASI INSTALASI

### Cek 1: Database

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik database **"db_sistem_pakar_gontory"** di sidebar kiri
3. Pastikan ada 7 tabel:
   - ✅ users
   - ✅ gejala
   - ✅ kerusakan
   - ✅ rule
   - ✅ rule_detail
   - ✅ diagnosa
   - ✅ diagnosa_detail

### Cek 2: Login

1. Akses: `http://localhost/Troubleshooting-Komputer`
2. Login dengan akun admin
3. Jika berhasil, akan masuk ke **Dashboard Admin**

### Cek 3: Data Dummy

1. Login sebagai admin
2. Klik menu **"Manajemen Gejala"**
3. Seharusnya ada 20 data gejala
4. Klik menu **"Manajemen Kerusakan"**
5. Seharusnya ada 8 data kerusakan
6. Klik menu **"Manajemen Rule"**
7. Seharusnya ada 8 data rule

---

## 🐛 TROUBLESHOOTING

### ❌ Error: "Koneksi Database Gagal"

**Penyebab:**

- MySQL belum running
- Username/password salah
- Database belum diimport

**Solusi:**

1. Buka XAMPP Control Panel
2. Pastikan MySQL sudah **running** (hijau)
3. Cek konfigurasi di `Config\koneksi.php`
4. Import ulang database jika perlu

---

### ❌ Error: "Access Denied for user 'root'@'localhost'"

**Penyebab:**

- Password MySQL tidak kosong

**Solusi:**

1. Buka `Config\koneksi.php`
2. Ganti:
   ```php
   $db_pass = "";
   ```
   Menjadi:
   ```php
   $db_pass = "password_mysql_anda";
   ```

---

### ❌ Halaman Login tidak muncul CSS

**Penyebab:**

- Path folder salah
- Browser cache

**Solusi:**

1. Hard refresh browser: `Ctrl + F5`
2. Clear browser cache
3. Pastikan folder Assets ada di:
   ```
   C:\xampp\htdocs\Troubleshooting-Komputer\Assets\
   ```

---

### ❌ Error 404 Not Found

**Penyebab:**

- Apache belum running
- URL salah

**Solusi:**

1. Pastikan Apache running di XAMPP
2. Gunakan URL yang benar:
   ```
   http://localhost/Troubleshooting-Komputer
   ```
   (Huruf besar/kecil harus sesuai nama folder)

---

### ❌ Session tidak berfungsi

**Penyebab:**

- Session PHP belum aktif

**Solusi:**

1. Buka file `php.ini` di:
   ```
   C:\xampp\php\php.ini
   ```
2. Cari baris:
   ```
   session.save_path
   ```
3. Pastikan tidak ada tanda `;` di awal (uncomment)
4. Restart Apache di XAMPP

---

## 📝 TESTING APLIKASI

### Test 1: Login Admin

1. Login dengan username: `admin`, password: `password`
2. Cek apakah masuk ke Dashboard Admin
3. Cek statistik (Total Gejala, Kerusakan, dll.)

### Test 2: CRUD Gejala

1. Klik menu **"Manajemen Gejala"**
2. Tambah gejala baru:
   - Kode: G999
   - Nama: Test Gejala
3. Klik **"Simpan"**
4. Edit gejala yang baru dibuat
5. Hapus gejala tersebut

### Test 3: CRUD Rule

1. Klik menu **"Manajemen Rule"**
2. Tambah rule baru:
   - Pilih kerusakan
   - Centang beberapa gejala
3. Klik **"Simpan"**
4. Lihat rule yang dibuat di tabel

### Test 4: Diagnosa (Asisten)

1. Logout dari admin
2. Login dengan username: `asisten1`, password: `password`
3. Klik menu **"Diagnosa Kerusakan"**
4. Centang gejala:
   - G001 - Komputer tidak bisa menyala
   - G002 - Lampu indikator tidak menyala
5. Klik **"Proses Diagnosa"**
6. Seharusnya muncul hasil: **"Kerusakan Power Supply"**
7. Lihat solusi yang ditampilkan

### Test 5: Riwayat & Export

1. Klik menu **"Riwayat Diagnosa"**
2. Lihat diagnosa yang baru dibuat
3. Klik **"Detail"** untuk melihat detail
4. Klik menu **"Export Laporan"**
5. Klik **"Cetak / Save as PDF"**

---

## 🎯 NEXT STEPS

Setelah instalasi berhasil:

1. ✅ **Ganti Password Default**

   - Login sebagai admin dan asisten
   - (Note: Fitur ganti password bisa ditambahkan di pengembangan selanjutnya)

2. ✅ **Tambah Data Gejala & Kerusakan**

   - Sesuaikan dengan kebutuhan lab komputer Anda
   - Tambah gejala-gejala yang sering terjadi

3. ✅ **Buat Rule Baru**

   - Buat aturan forward chaining yang sesuai
   - Kombinasikan gejala dengan kerusakan yang tepat

4. ✅ **Test Diagnosa**

   - Lakukan beberapa diagnosa test
   - Pastikan hasil akurat

5. ✅ **Training User**
   - Latih asisten lab cara menggunakan sistem
   - Berikan panduan penggunaan

---

## 📞 BANTUAN

Jika masih ada kendala:

1. Baca file **README.md** untuk dokumentasi lengkap
2. Cek folder **Database/** untuk struktur database
3. Lihat kode di folder **Config/** untuk konfigurasi
4. Konsultasikan dengan admin sistem

---

## ✨ SELAMAT!

Instalasi berhasil! Sistem Pakar Troubleshooting Komputer siap digunakan.

**Happy Diagnosing! 🎉**

---

**© 2025 - Pondok Pesantren Al-Gontory**
