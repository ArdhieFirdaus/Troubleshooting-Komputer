# 🚀 PANDUAN LENGKAP DEMO CHATBOT DIAGNOSA PER KATEGORI (FORWARD CHAINING)
**Aplikasi:** Sistem Pakar Diagnosa Troubleshooting Komputer & Software  
**Studi Kasus:** Pondok Pesantren Al-Gontory  
**Halaman Demo:** [diagnosa_chat.php](file:///c:/xampp/htdocs/Troubleshooting-Komputer/Asisten/diagnosa_chat.php) (Role: Asisten Lab)

---

## 📌 Petunjuk Pengujian Sidang Skripsi
Buka halaman `http://localhost/Troubleshooting-Komputer/Asisten/diagnosa_chat.php`.  
Panduan ini menyajikan skenario pengujian komprehensif **untuk SETIAP KATEGORI** di chatbot, mulai dari **1 Gejala Tunggal**, **Kombinasi 2-3 Gejala (Hardware & Software)**, hingga **Gejala Tidak Teridentifikasi**.

---

# 🔌 KATEGORI 1: MASALAH DAYA & BOOTING

### 🔹 Skenario 1.1: Diagnosa 1 Gejala Tunggal (Hardware)
1. Klik kategori: 👉 **`Masalah Daya & Booting`**
2. Pilih 1 gejala: ☑️ **`G001 - Komputer tidak bisa menyala sama sekali`**
3. Klik **`Process Diagnosa (1 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Power Supply` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 1.2: Diagnosa Kombinasi 2 Gejala (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Daya & Booting`**
2. Centang 2 gejala:  
   ☑️ **`G001 - Komputer tidak bisa menyala sama sekali`**  
   ☑️ **`G002 - Lampu indikator power tidak menyala`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Power Supply` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 1.3: Diagnosa Kombinasi 3 Gejala Kompleks (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Daya & Booting`**
2. Centang 3 gejala:  
   ☑️ **`G001 - Komputer tidak bisa menyala sama sekali`**  
   ☑️ **`G027 - Port USB longgar / tersengat listrik halus di casing`**  
   ☑️ **`G029 - Terdapat elco (kapasitor) di motherboard yang kembung`**
3. Klik **`Process Diagnosa (3 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Motherboard / Chipset Short` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 1.4: Diagnosa Gejala Tidak Teridentifikasi (Edge Case)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Daya & Booting`**
2. Centang kombinasi acak tanpa rule:  
   ☑️ **`G023 - Tanggal dan jam BIOS selalu terreset ke default`**  
   ☑️ **`G005 - Kipas berputar tapi tidak ada POST`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

# 🖥️ KATEGORI 2: MASALAH LAYAR & TAMPILAN

### 🔹 Skenario 2.1: Diagnosa 1 Gejala Tunggal (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Layar & Tampilan`**
2. Pilih 1 gejala: ☑️ **`G004 - Komputer menyala tapi tidak ada tampilan di layar`**
3. Klik **`Process Diagnosa (1 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan VGA Card` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 2.2: Diagnosa Kombinasi 2 Gejala (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Layar & Tampilan`**
2. Centang 2 gejala:  
   ☑️ **`G003 - Terdengar bunyi beep berulang saat dinyalakan`**  
   ☑️ **`G004 - Komputer menyala tapi tidak ada tampilan di layar`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan RAM (Memory)` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 2.3: Diagnosa Kombinasi 3 Gejala Display (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Layar & Tampilan`**
2. Centang 3 gejala:  
   ☑️ **`G004 - Komputer menyala tapi tidak ada tampilan di layar`**  
   ☑️ **`G024 - Layar monitor berkedip NO SIGNAL atau mati-nyala`**  
   ☑️ **`G028 - Monitor menampilkan warna dominan satu jenis`**
3. Klik **`Process Diagnosa (3 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Monitor / Kabel Display` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 2.4: Diagnosa Gejala Tidak Teridentifikasi
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Masalah Layar & Tampilan`**
2. Centang kombinasi tanpa rule:  
   ☑️ **`G015 - Layar bergaris atau berkedip`**  
   ☑️ **`G003 - Terdengar bunyi beep berulang`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

# 💾 KATEGORI 3: PENYIMPANAN, OS & PERFORMA

### 🔹 Skenario 3.1: Diagnosa 1 Gejala Tunggal (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Penyimpanan, OS & Performa`**
2. Pilih 1 gejala: ☑️ **`G007 - Muncul Blue Screen of Death (BSOD)`**
3. Klik **`Process Diagnosa (1 Terpilih)`**
* 🎯 **Hasil:** `Driver atau Software Bermasalah` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 3.2: Diagnosa Kombinasi 2 Gejala Hardisk (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Penyimpanan, OS & Performa`**
2. Centang 2 gejala:  
   ☑️ **`G010 - Hardisk berbunyi aneh (klik-klik)`**  
   ☑️ **`G011 - Komputer tidak dapat booting ke Windows`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Hardisk` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 3.3: Diagnosa Kombinasi 2 Gejala OS Corrupt (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Penyimpanan, OS & Performa`**
2. Centang 2 gejala:  
   ☑️ **`G011 - Komputer tidak dapat booting ke Windows`**  
   ☑️ **`G012 - Muncul pesan "Operating System Not Found"`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Sistem Operasi Corrupt` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 3.4: Diagnosa Kombinasi 3 Gejala Overheating (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Penyimpanan, OS & Performa`**
2. Centang 3 gejala:  
   ☑️ **`G006 - Komputer sering restart sendiri`**  
   ☑️ **`G016 - Suhu komputer sangat panas`**  
   ☑️ **`G026 - Terdengar suara dengung keras dari casing`**
3. Klik **`Process Diagnosa (3 Terpilih)`**
* 🎯 **Hasil:** `Overheating (Panas Berlebih)` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 3.5: Diagnosa Gejala Tidak Teridentifikasi
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Penyimpanan, OS & Performa`**
2. Centang kombinasi tanpa rule:  
   ☑️ **`G010 - Hardisk berbunyi aneh`**  
   ☑️ **`G038 - Windows Update gagal terus`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

# 🔊 KATEGORI 4: PERANGKAT INPUT/OUTPUT & SOUND

### 🔹 Skenario 4.1: Diagnosa 1 Gejala Tunggal (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Perangkat Input/Output & Sound`**
2. Pilih 1 gejala: ☑️ **`G020 - Audio tidak keluar suara`**
3. Klik **`Process Diagnosa (1 Terpilih)`**
* 🎯 **Hasil:** `Driver atau Software Bermasalah` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 4.2: Diagnosa Kombinasi 2 Gejala Port USB (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Perangkat Input/Output & Sound`**
2. Centang 2 gejala:  
   ☑️ **`G018 - USB device tidak terdeteksi`**  
   ☑️ **`G027 - Port USB longgar / tersengat listrik halus`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Port USB` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 4.3: Diagnosa Kombinasi 2 Gejala Baterai CMOS (Hardware)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Perangkat Input/Output & Sound`**
2. Centang 2 gejala:  
   ☑️ **`G011 - Komputer tidak dapat booting ke Windows`**  
   ☑️ **`G023 - Tanggal dan jam BIOS selalu terreset ke default`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Baterai CMOS / BIOS Setting` | Badge: `<span class="badge bg-primary">Hardware</span>` | Kecocokan: `100%`

### 🔹 Skenario 4.4: Diagnosa Gejala Tidak Teridentifikasi
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Perangkat Input/Output & Sound`**
2. Centang kombinasi tanpa rule:  
   ☑️ **`G019 - Keyboard atau mouse tidak berfungsi`**  
   ☑️ **`G020 - Audio tidak keluar suara`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

# 🌐 KATEGORI 5: JARINGAN & INTERNET

### 🔹 Skenario 5.1: Diagnosa 1 Gejala Tunggal (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Jaringan & Internet`**
2. Pilih 1 gejala: ☑️ **`G017 - Koneksi internet tidak stabil`**
3. Klik **`Process Diagnosa (1 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Jaringan` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 5.2: Diagnosa Kombinasi 2 Gejala IP/DNS (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Jaringan & Internet`**
2. Centang 2 gejala:  
   ☑️ **`G021 - Tidak dapat terhubung ke jaringan WiFi`**  
   ☑️ **`G039 - Icon jaringan WiFi / Ethernet bertanda seru kuning`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Konfigurasi IP / DNS Error` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 5.3: Diagnosa Gejala Tidak Teridentifikasi
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Jaringan & Internet`**
2. Centang kombinasi tanpa rule:  
   ☑️ **`G017 - Koneksi internet tidak stabil`**  
   ☑️ **`G036 - IP Address mengalami konflik`**  
   ☑️ **`G020 - Audio tidak keluar suara`** *(kombinasi lintas domain)*
3. Klik **`Process Diagnosa (3 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

# 📋 KATEGORI 6: LIHAT SEMUA GEJALA (MODE BEBAS & MULTI-RULE)

### 🔹 Skenario 6.1: Diagnosa Infeksi Virus / Malware (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Lihat Semua Gejala (40)`**
2. Centang 2 gejala:  
   ☑️ **`G008 - Komputer sangat lambat saat digunakan`**  
   ☑️ **`G031 - Muncul banyak pop-up iklan / browser terarah sendiri`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Infeksi Virus / Malware System` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 6.2: Diagnosa Kapasitas Penyimpanan Penuh (Software)
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Lihat Semua Gejala (40)`**
2. Centang 2 gejala:  
   ☑️ **`G008 - Komputer sangat lambat saat digunakan`**  
   ☑️ **`G035 - Ruang penyimpanan drive C: berwarna merah / penuh`**
3. Klik **`Process Diagnosa (2 Terpilih)`**
* 🎯 **Hasil:** `Kapasitas Penyimpanan Penuh` | Badge: `<span class="badge bg-success">Software</span>` | Kecocokan: `100%`

### 🔹 Skenario 6.3: Diagnosa Kombinasi Acak Tidak Teridentifikasi
1. Klik **`Reset Diagnosa`** $\rightarrow$ Pilih **`Lihat Semua Gejala (40)`**
2. Centang 4 gejala acak dari berbagai kategori:  
   ☑️ **`G003 - Terdengar bunyi beep`**  
   ☑️ **`G010 - Hardisk bunyi klik`**  
   ☑️ **`G018 - USB tidak terdeteksi`**  
   ☑️ **`G031 - Pop-up iklan`**
3. Klik **`Process Diagnosa (4 Terpilih)`**
* 🎯 **Hasil:** `Kerusakan Tidak Teridentifikasi` | Badge: `<span class="badge bg-secondary">Tidak Diketahui</span>`

---

## 🗣️ Naskah Presentasi Sidang & Jawaban Penguji

> *"Bapak dan Ibu Dosen Penguji, berikut adalah demonstrasi lengkap untuk seluruh 6 kategori pada sistem pakar.*
> 
> *Dapat kita lihat pada Kategori 1 hingga Kategori 6, sistem secara konsisten mampu mendiagnosa pengujian mulai dari 1 gejala tunggal, kombinasi 2 gejala, hingga kombinasi 3 gejala kompleks baik pada kelompok **Hardware** maupun **Software**.*
> 
> *Selain itu, pada setiap kategori kami juga telah menguji kasus **Kerusakan Tidak Teridentifikasi** ketika input gejala acak tidak memiliki aturan (rule) pada basis pengetahuan. Hal ini membuktikan bahwa metode Forward Chaining bekerja secara konsisten, aman, dan akurat."*
