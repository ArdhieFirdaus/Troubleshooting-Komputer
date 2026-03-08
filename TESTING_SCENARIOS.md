# 🧪 Testing Scenarios - Chat Diagnosa

## Test Environment

- **URL:** http://localhost/Troubleshooting-Komputer/Asisten/diagnosa_chat.php
- **User:** asisten1 / password (asisten_lab)
- **Browser:** Chrome/Firefox/Edge
- **Database:** db_sistem_pakar_gontory

---

## ✅ Positive Test Cases

### TC-001: Kerusakan Power Supply

**Input User:**

```
komputer tidak menyala
```

**Expected Result:**

```
✅ Diagnosa selesai! Saya telah mengidentifikasi masalah Anda.

Diagnosa Kerusakan:
Kerusakan Power Supply

Solusi Perbaikan:
1. Cek kabel power apakah terpasang dengan benar
2. Test power supply dengan PSU tester
3. Ganti power supply jika rusak
4. Periksa saklar power di belakang PSU
```

**Gejala Teridentifikasi:** G001, G002  
**Rule Match:** Rule 1 (100%)

---

### TC-002: Kerusakan RAM

**Input User:**

```
bunyi beep terus saat dinyalakan
```

**Expected Result:**

```
Diagnosa: Kerusakan RAM (Memory)

Solusi:
1. Buka casing dan bersihkan slot RAM dengan kuas halus
2. Lepas dan pasang kembali RAM dengan benar
3. Test RAM satu per satu jika menggunakan lebih dari satu
4. Ganti RAM jika masih bermasalah
5. Coba pindahkan RAM ke slot yang berbeda
```

**Gejala Teridentifikasi:** G003  
**Rule Match:** Rule 2 (33%)

---

### TC-003: Kerusakan VGA Card

**Input User:**

```
komputer nyala tapi layar hitam
```

**Expected Result:**

```
Diagnosa: Kerusakan VGA Card

Solusi:
1. Bersihkan slot VGA dan konektor VGA
2. Pastikan VGA terpasang dengan benar
3. Cek kabel monitor ke VGA
4. Test dengan VGA lain jika memungkinkan
5. Update driver VGA atau ganti VGA jika rusak
```

**Gejala Teridentifikasi:** G004  
**Rule Match:** Rule 2 atau Rule 3

---

### TC-004: Kerusakan Hardisk

**Input User:**

```
hardisk bunyi klik klik dan tidak bisa booting
```

**Expected Result:**

```
Diagnosa: Kerusakan Hardisk

Solusi:
1. Backup data segera jika masih bisa diakses
2. Cek kabel SATA/IDE hardisk
3. Scan hardisk dengan tools seperti HD Tune
4. Perbaiki bad sector dengan HDD Regenerator
5. Ganti hardisk jika kerusakan parah
```

**Gejala Teridentifikasi:** G010, G011  
**Rule Match:** Rule 4 (66%)

---

### TC-005: Sistem Operasi Corrupt

**Input User:**

```
windows loading lama dan sering hang
```

**Expected Result:**

```
Diagnosa: Sistem Operasi Corrupt

Solusi:
1. Repair Windows menggunakan installation media
2. Gunakan System Restore ke titik sebelumnya
3. Jalankan SFC /scannow di Command Prompt
4. Install ulang Windows jika tidak bisa diperbaiki
5. Backup data penting terlebih dahulu
```

**Gejala Teridentifikasi:** G013, G014  
**Rule Match:** Rule 5 (66%)

---

### TC-006: Overheating

**Input User:**

```
komputer restart sendiri dan suhu panas
```

**Expected Result:**

```
Diagnosa: Overheating (Panas Berlebih)

Solusi:
1. Bersihkan debu di dalam casing dengan air duster
2. Periksa dan bersihkan heatsink processor
3. Ganti thermal paste processor
4. Pastikan semua kipas berfungsi dengan baik
5. Tambah kipas casing jika perlu
6. Periksa ventilasi udara
```

**Gejala Teridentifikasi:** G006, G016  
**Rule Match:** Rule 6 (66%)

---

### TC-007: Driver Bermasalah

**Input User:**

```
komputer lambat dan aplikasi sering not responding
```

**Expected Result:**

```
Diagnosa: Driver atau Software Bermasalah

Solusi:
1. Update driver ke versi terbaru
2. Uninstall dan install ulang driver
3. Gunakan Driver Booster untuk update otomatis
4. Rollback driver ke versi sebelumnya jika masalah muncul setelah update
5. Scan sistem dengan antivirus
```

**Gejala Teridentifikasi:** G008, G009  
**Rule Match:** Rule 7 (66%)

---

### TC-008: Kerusakan Port USB

**Input User:**

```
usb tidak terdeteksi
```

**Expected Result:**

```
Diagnosa: Kerusakan Port USB

Solusi:
1. Coba port USB yang berbeda
2. Update driver USB Controller
3. Disable dan enable kembali USB Controller di Device Manager
4. Cek di BIOS apakah USB diaktifkan
5. Gunakan USB hub eksternal jika port rusak
```

**Gejala Teridentifikasi:** G018  
**Rule Match:** Rule 8

---

## ⚠️ Negative Test Cases

### TC-101: Kata Kunci Tidak Dikenali

**Input User:**

```
komputer saya rusak
```

**Expected Result:**

```
❓ Maaf, saya belum bisa memahami masalah yang Anda jelaskan.
Coba gunakan kata kunci seperti: "tidak menyala", "layar hitam",
"hardisk bunyi", "restart sendiri", dll.
```

**Gejala Teridentifikasi:** None  
**Reason:** Kata "rusak" terlalu umum, tidak ada di kata kunci

---

### TC-102: Input Kosong

**Input User:**

```
(empty string)
```

**Expected Result:**

```
Pesan tidak boleh kosong.
```

**Error Handling:** Client-side validation (HTML5 required)

---

### TC-103: Input Terlalu Pendek

**Input User:**

```
error
```

**Expected Result:**

```
❓ Maaf, saya belum bisa memahami masalah yang Anda jelaskan...
```

**Reason:** Kata "error" tidak spesifik, tidak ada di kata kunci

---

### TC-104: Input Bahasa Inggris (Mixed)

**Input User:**

```
my computer cannot boot
```

**Expected Result:**

```
❓ Maaf, saya belum bisa memahami...
```

**Reason:** Sistem saat ini hanya support kata kunci bahasa Indonesia  
**Improvement:** Bisa tambahkan kata kunci bahasa Inggris di database

---

## 🔄 Edge Cases

### TC-201: Multiple Keywords Match Multiple Gejalas

**Input User:**

```
komputer tidak menyala, bunyi beep, dan layar hitam
```

**Expected Result:**

- Gejala Teridentifikasi: G001, G002, G003, G004
- Rule Match: Pilih yang paling tinggi % kecocokan
- Diagnosa: Power Supply atau RAM (tergantung algoritma)

---

### TC-202: Typo Input

**Input User:**

```
komputr tidka menyala
```

**Expected Result:**

```
❓ Maaf, saya belum bisa memahami...
```

**Reason:** Sistem belum support fuzzy matching  
**Improvement:** Implementasi Levenshtein Distance

---

### TC-203: Case Sensitivity

**Input User:**

```
KOMPUTER TIDAK MENYALA
```

**Expected Result:**

```
Diagnosa: Kerusakan Power Supply
```

**Reason:** Sistem auto-convert ke lowercase (case-insensitive)

---

### TC-204: Whitespace & Special Characters

**Input User:**

```
  komputer   tidak   menyala  !!!
```

**Expected Result:**

```
Diagnosa: Kerusakan Power Supply
```

**Reason:** PHP trim() dan stripos() handle whitespace

---

## 🚀 Performance Test

### PT-001: Response Time

**Test:** Kirim 10 pesan berturut-turut  
**Expected:** Response < 2 detik per pesan

### PT-002: Concurrent Users

**Test:** 5 user chat bersamaan  
**Expected:** Tidak ada deadlock database

### PT-003: Large Input

**Test:** Input 1000 karakter  
**Expected:** Sistem tetap proses normal

---

## 🔒 Security Test

### ST-001: SQL Injection

**Input User:**

```
' OR '1'='1
```

**Expected:** Input di-escape dengan mysqli_real_escape_string  
**Result:** Tidak ada SQL injection

### ST-002: XSS Attack

**Input User:**

```
<script>alert('XSS')</script>
```

**Expected:** JavaScript function escapeHtml() mencegah eksekusi  
**Result:** Ditampilkan sebagai text biasa

### ST-003: Session Hijacking

**Test:** Akses tanpa login  
**Expected:** Redirect ke login page atau error "Sesi berakhir"

---

## 📊 Test Report Template

### Test Execution Summary

| Test ID | Description     | Status  | Notes |
| ------- | --------------- | ------- | ----- |
| TC-001  | Power Supply    | ✅ PASS | -     |
| TC-002  | RAM             | ✅ PASS | -     |
| TC-003  | VGA             | ✅ PASS | -     |
| TC-004  | Hardisk         | ✅ PASS | -     |
| TC-005  | OS Corrupt      | ✅ PASS | -     |
| TC-006  | Overheating     | ✅ PASS | -     |
| TC-007  | Driver          | ✅ PASS | -     |
| TC-008  | USB Port        | ✅ PASS | -     |
| TC-101  | Invalid Keyword | ✅ PASS | -     |
| TC-102  | Empty Input     | ✅ PASS | -     |

**Total:** 10 Tests  
**Passed:** 10  
**Failed:** 0  
**Pass Rate:** 100%

---

## 🎯 Checklist Pre-Deployment

- [ ] Database update_kata_kunci.sql dijalankan
- [ ] File diagnosa_chat.php terupload
- [ ] File proses_chat.php atau proses_chat_v2.php terupload
- [ ] Menu sidebar sudah ditambahkan
- [ ] Testing TC-001 sampai TC-008 sukses
- [ ] Testing negative cases sukses
- [ ] Security test passed
- [ ] Browser compatibility test (Chrome, Firefox, Edge)
- [ ] Mobile responsive test
- [ ] Performance < 2 detik per request

---

## 📝 Bug Report Template

**Bug ID:** BUG-001  
**Severity:** High/Medium/Low  
**Priority:** P1/P2/P3

**Steps to Reproduce:**

1. Login sebagai asisten_lab
2. Buka diagnosa_chat.php
3. Ketik "komputer tidak menyala"
4. Klik Kirim

**Expected Result:**  
Diagnosa Power Supply muncul

**Actual Result:**  
Error 500 Internal Server Error

**Screenshots:**  
(attach screenshot)

**Environment:**

- Browser: Chrome 120
- PHP: 7.4.33
- MySQL: 5.7.40

**Additional Notes:**  
Error di console: "Uncaught TypeError..."

---

**Happy Testing! 🎉**
