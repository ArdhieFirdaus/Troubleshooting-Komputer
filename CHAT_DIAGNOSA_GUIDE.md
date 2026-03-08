# 💬 Sistem Chat Diagnosa Troubleshooting Komputer

## 📋 Deskripsi

Fitur chat diagnosa adalah upgrade dari sistem diagnosa checkbox sebelumnya. User sekarang dapat menjelaskan masalah komputer dengan bahasa natural (seperti berbicara dengan chatbot), dan sistem akan secara otomatis mengidentifikasi gejala dan memberikan diagnosa menggunakan metode **Forward Chaining**.

---

## ✨ Fitur Utama

### 1. **UI Chat Modern**

- Chat bubble user dan sistem
- Avatar untuk setiap pesan
- Timestamp pada setiap pesan
- Auto scroll ke pesan terbaru
- Typing indicator animasi
- Desain gradient modern (Purple-Blue)

### 2. **Natural Language Processing**

- User mengetik masalah dengan bahasa sehari-hari
- Contoh: "komputer tidak menyala", "layar hitam", "hardisk bunyi klik"
- Sistem mencocokkan kata kunci dengan database gejala

### 3. **Forward Chaining Otomatis**

- Sistem mengidentifikasi gejala dari input user
- Menjalankan algoritma forward chaining
- Mencari rule yang paling cocok (minimal 50% match)
- Memberikan diagnosa kerusakan + solusi

### 4. **Quick Action Buttons**

- Tombol shortcut untuk masalah umum
- Klik langsung tanpa mengetik

### 5. **Penyimpanan Otomatis**

- Setiap diagnosa disimpan ke database
- Dapat dilihat di riwayat diagnosa

---

## 📁 Struktur File

```
Asisten/
├── diagnosa_chat.php          # Halaman UI chat (REKOMENDASI)
├── proses_chat.php            # Proses backend (kata kunci hardcode)
├── proses_chat_v2.php         # Proses backend (kata kunci dari database)
└── sidebar_asisten.php        # Sidebar navigasi

Database/
└── update_kata_kunci.sql      # Script SQL untuk menambah kolom kata_kunci

Assets/
├── css/
│   └── style.css              # CSS global (sudah include chat styling)
└── js/
    └── script.js              # JavaScript global
```

---

## 🚀 Cara Install & Menggunakan

### **Langkah 1: Update Database**

Jalankan script SQL untuk menambahkan kolom `kata_kunci` pada tabel `gejala`:

```bash
# Buka phpMyAdmin atau MySQL CLI
# Pilih database: db_sistem_pakar_gontory
# Jalankan file: Database/update_kata_kunci.sql
```

atau langsung via command line:

```bash
mysql -u root -p db_sistem_pakar_gontory < Database/update_kata_kunci.sql
```

### **Langkah 2: Pilih Backend Processor**

Ada 2 versi backend:

**A. proses_chat.php (Hardcode - Tidak perlu update database)**

- Kata kunci sudah di-hardcode dalam array PHP
- Langsung bisa digunakan tanpa update database
- Cocok untuk testing cepat

**B. proses_chat_v2.php (Dynamic - Perlu update database)**

- Membaca kata kunci dari kolom `kata_kunci` di database
- Lebih fleksibel, admin bisa update kata kunci via CRUD
- Memerlukan langkah 1 selesai

### **Langkah 3: Ubah Backend di diagnosa_chat.php**

Jika menggunakan **proses_chat_v2.php**, edit file `diagnosa_chat.php`:

Cari baris ini (sekitar line 434):

```javascript
fetch('proses_chat.php', {
```

Ganti menjadi:

```javascript
fetch('proses_chat_v2.php', {
```

### **Langkah 4: Update Menu Navigasi**

Edit file `sidebar_asisten.php` untuk menambahkan menu Chat Diagnosa:

```php
<a href="diagnosa_chat.php" class="sidebar-link">
    <i class="bi bi-chat-dots"></i>
    Chat Diagnosa
</a>
```

### **Langkah 5: Akses Halaman**

Buka browser dan akses:

```
http://localhost/Troubleshooting-Komputer/Asisten/diagnosa_chat.php
```

---

## 🎯 Cara Menggunakan

### **Untuk User (Asisten Lab)**

1. **Login** sebagai asisten lab
2. Klik menu **"Chat Diagnosa"**
3. Ketik masalah komputer Anda, contoh:
   - "komputer tidak menyala"
   - "layar hitam pas dinyalakan"
   - "hardisk saya berbunyi klik klik"
   - "komputer restart sendiri terus"
4. Tekan **Enter** atau klik tombol **Kirim**
5. Sistem akan:
   - Mengidentifikasi gejala
   - Menjalankan forward chaining
   - Menampilkan diagnosa + solusi
6. Hasil diagnosa otomatis tersimpan

### **Quick Action Buttons**

Untuk kemudahan, tersedia tombol quick action:

- 💡 Komputer tidak menyala
- 🖥️ Layar hitam/no display
- 🔄 Restart sendiri
- 💾 Hardisk bunyi aneh

Klik salah satu untuk langsung mengirim pesan.

---

## 📊 Contoh Dialog Chat

### **Contoh 1: Komputer Tidak Menyala**

**User:**

> komputer saya tidak bisa menyala

**Sistem:**

> ✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.
>
> **Diagnosa Kerusakan:**  
> Kerusakan Power Supply
>
> **Solusi Perbaikan:**
>
> 1. Cek kabel power apakah terpasang dengan benar
> 2. Test power supply dengan PSU tester
> 3. Ganti power supply jika rusak
> 4. Periksa saklar power di belakang PSU

---

### **Contoh 2: Layar Hitam**

**User:**

> komputer nyala tapi layar hitam

**Sistem:**

> ✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.
>
> **Diagnosa Kerusakan:**  
> Kerusakan VGA Card
>
> **Solusi Perbaikan:**
>
> 1. Bersihkan slot VGA dan konektor VGA
> 2. Pastikan VGA terpasang dengan benar
> 3. Cek kabel monitor ke VGA
> 4. Test dengan VGA lain jika memungkinkan
> 5. Update driver VGA atau ganti VGA jika rusak

---

### **Contoh 3: Gejala Tidak Dikenali**

**User:**

> wifi saya lambat sekali

**Sistem:**

> ❓ Maaf, saya belum bisa memahami masalah yang Anda jelaskan. Coba gunakan kata kunci seperti: "tidak menyala", "layar hitam", "hardisk bunyi", "restart sendiri", dll.

---

## 🔧 Algoritma Forward Chaining

### **Langkah-langkah:**

1. **Input User** → "komputer tidak menyala"

2. **Identifikasi Gejala**
   - Sistem mencari kata kunci di database
   - Kata "tidak menyala" cocok dengan:
     - G001: Komputer tidak bisa menyala sama sekali
     - G002: Lampu indikator power tidak menyala

3. **Pencarian Rule**
   - Sistem mencari rule yang mengandung gejala G001, G002
   - Rule 1: Power Supply → Gejala [G001, G002] ✅ 100% Match

4. **Hasil Diagnosa**
   - Kerusakan: Power Supply
   - Solusi: (tampilkan solusi dari database)

5. **Simpan ke Database**
   - Tabel `diagnosa`: id_user, tanggal, hasil_kerusakan
   - Tabel `diagnosa_detail`: gejala yang teridentifikasi

---

## 🎨 Kustomisasi

### **Ubah Warna Chat Bubble**

Edit `diagnosa_chat.php` di bagian `<style>`:

```css
/* Ubah gradient background sistem */
.chat-bubble.system {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Ubah background user bubble */
.chat-bubble.user {
  background: #e9ecef;
  color: #212529;
}
```

### **Tambah Kata Kunci Baru**

**Jika menggunakan proses_chat.php (hardcode):**

Edit array `$kata_kunci_gejala` di `proses_chat.php`:

```php
$kata_kunci_gejala = [
    1 => ['tidak menyala', 'mati total', 'KATA_BARU'],
    // dst...
];
```

**Jika menggunakan proses_chat_v2.php (database):**

Update via SQL:

```sql
UPDATE gejala
SET kata_kunci = 'tidak menyala, mati total, KATA_BARU'
WHERE id_gejala = 1;
```

atau buat CRUD di halaman admin untuk manage kata kunci.

---

## 🐛 Troubleshooting

### **Error: "Sesi berakhir"**

- Pastikan sudah login sebagai asisten_lab
- Cek session di `cek_session.php`

### **Gejala tidak teridentifikasi**

- Pastikan kata kunci sudah di-update di database
- Cek apakah kata kunci di input user mirip dengan database
- Gunakan kata yang lebih spesifik

### **Forward chaining tidak cocok**

- Cek data di tabel `rule` dan `rule_detail`
- Pastikan minimal 50% gejala cocok dengan rule

### **Chat tidak muncul**

- Buka Console Browser (F12) untuk cek error JavaScript
- Pastikan Bootstrap dan CDN lainnya ter-load

---

## 📝 Catatan Pengembangan

### **Fitur yang Bisa Ditambahkan:**

1. **Multi-language Detection**
   - Support bahasa Inggris & Indonesia

2. **Fuzzy Matching**
   - Toleransi typo dengan Levenshtein Distance
   - Contoh: "komp tidak nyala" tetap terdeteksi

3. **Confidence Score**
   - Tampilkan % kepercayaan diagnosa
   - "Diagnosa ini 85% akurat"

4. **Chat History per User**
   - User bisa lihat riwayat chat sebelumnya
   - Continue conversation

5. **Export Chat to PDF**
   - Download hasil chat sebagai laporan

6. **Voice Input**
   - User berbicara ke microphone
   - Menggunakan Web Speech API

7. **Admin Panel untuk Kata Kunci**
   - CRUD kata kunci tanpa edit database manual
   - Import/Export kata kunci

---

## 📚 Referensi

- **Forward Chaining**: Expert System AI
- **Bootstrap 5**: https://getbootstrap.com/
- **Fetch API**: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
- **MySQLi PHP**: https://www.php.net/manual/en/book.mysqli.php

---

## 👨‍💻 Developer

Sistem Pakar Troubleshooting Komputer  
Pondok Pesantren Al-Gontory

**Stack:**

- PHP Native 7.4+
- MySQL 5.7+
- Bootstrap 5.3
- Vanilla JavaScript (ES6+)

---

## 📄 Lisensi

Free to use for educational purposes.

---

**Selamat Menggunakan! 🚀**

Jika ada pertanyaan atau bug, silakan hubungi administrator sistem.
