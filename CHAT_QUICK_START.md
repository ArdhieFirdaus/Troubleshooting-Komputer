# 🚀 Quick Start - Chat Diagnosa

## Install dalam 3 Langkah

### 1️⃣ Update Database

```bash
# Via phpMyAdmin atau MySQL CLI
mysql -u root -p db_sistem_pakar_gontory < Database/update_kata_kunci.sql
```

### 2️⃣ Pilih Backend (Pilih salah satu)

**OPSI A: Gunakan proses_chat.php (Hardcode)**

- Langsung jalan tanpa update database
- Kata kunci di-hardcode dalam PHP

**OPSI B: Gunakan proses_chat_v2.php (Dynamic)**

- Data kata kunci dari database
- Perlu jalankan langkah 1 terlebih dahulu

Jika pilih OPSI B, edit `diagnosa_chat.php` baris 434:

```javascript
// Ganti ini:
fetch('proses_chat.php', {

// Menjadi:
fetch('proses_chat_v2.php', {
```

### 3️⃣ Akses Halaman

```
http://localhost/Troubleshooting-Komputer/Asisten/diagnosa_chat.php
```

---

## 🧪 Testing

### Test Case 1: Komputer Mati

**Input:** `komputer tidak menyala`  
**Expected:** Diagnosa "Kerusakan Power Supply"

### Test Case 2: Layar Hitam

**Input:** `layar hitam`  
**Expected:** Diagnosa "Kerusakan RAM" atau "Kerusakan VGA"

### Test Case 3: Hardisk Bunyi

**Input:** `hardisk bunyi klik`  
**Expected:** Diagnosa "Kerusakan Hardisk"

### Test Case 4: Restart Sendiri

**Input:** `komputer restart sendiri`  
**Expected:** Diagnosa "Overheating"

### Test Case 5: Kata Kunci Tidak Dikenali

**Input:** `wifi saya lambat`  
**Expected:** Pesan "Maaf, saya belum bisa memahami..."

---

## 📂 File Summary

| File                     | Fungsi                           |
| ------------------------ | -------------------------------- |
| `diagnosa_chat.php`      | UI Chat + AJAX Frontend          |
| `proses_chat.php`        | Backend (Hardcode keywords)      |
| `proses_chat_v2.php`     | Backend (Database keywords)      |
| `update_kata_kunci.sql`  | Script ALTER TABLE + UPDATE data |
| `CHAT_DIAGNOSA_GUIDE.md` | Dokumentasi lengkap              |

---

## 🔑 Kata Kunci yang Tersedia

| Gejala ID | Kata Kunci                                                      |
| --------- | --------------------------------------------------------------- |
| G001      | tidak menyala, mati total, tidak bisa nyala                     |
| G002      | lampu power, lampu indikator, led mati                          |
| G003      | bunyi beep, beep berulang                                       |
| G004      | tidak ada tampilan, layar hitam, no display, monitor hitam      |
| G005      | kipas berputar, fan nyala, tidak post                           |
| G006      | restart sendiri, restart otomatis, nyala mati sendiri           |
| G007      | blue screen, bsod, layar biru                                   |
| G008      | lambat, lemot, lelet, hang                                      |
| G009      | not responding, aplikasi freeze, program macet                  |
| G010      | hardisk bunyi, hdd bunyi, bunyi klik, klik klik                 |
| G011      | tidak bisa booting, gagal boot, tidak masuk windows             |
| G012      | operating system not found, os not found                        |
| G013      | loading lama, windows lama, booting lama                        |
| G014      | hang masuk windows, freeze windows                              |
| G015      | layar bergaris, garis di layar, monitor bergaris                |
| G016      | panas, overheat, suhu tinggi, kepanasan                         |
| G017      | internet lambat, koneksi putus, wifi error                      |
| G018      | usb tidak terdeteksi, usb tidak kebaca, flashdisk tidak terbaca |
| G019      | keyboard error, mouse error, keyboard tidak fungsi              |
| G020      | tidak ada suara, audio mati, speaker mati, suara hilang         |

---

## 🎯 Contoh Input User

### ✅ Good Examples (Akan Dikenali)

- "komputer tidak menyala"
- "layar hitam saat dinyalakan"
- "hardisk berbunyi klik klik"
- "komputer restart sendiri terus"
- "blue screen muncul terus"
- "loading windows lama banget"

### ❌ Bad Examples (Tidak Dikenali)

- "rusak nih komputer" → terlalu umum
- "error" → tidak spesifik
- "bantuin dong" → bukan gejala
- "gimana ini" → tidak jelas

---

## 🛠️ Custom Kata Kunci Baru

### Via Database (proses_chat_v2.php)

```sql
UPDATE gejala
SET kata_kunci = 'tidak menyala, mati total, KATA_BARU_1, KATA_BARU_2'
WHERE id_gejala = 1;
```

### Via PHP (proses_chat.php)

```php
// Edit array di proses_chat.php
$kata_kunci_gejala = [
    1 => ['tidak menyala', 'mati total', 'KATA_BARU'],
];
```

---

## 💡 Tips Pengembangan

1. **Tambah Variasi Kata Kunci**
   - Makin banyak variasi, makin akurat
   - Contoh: "tidak menyala" + "ga bisa nyala" + "gak bisa hidup"

2. **Gunakan Lowercase**
   - Sistem auto-convert ke lowercase
   - Tidak perlu uppercase di kata kunci

3. **Kata Kunci Spesifik**
   - Hindari kata terlalu umum: "rusak", "error"
   - Gunakan kata spesifik: "layar hitam", "bunyi beep"

4. **Testing Berkala**
   - Test dengan berbagai variasi input
   - Tambahkan kata kunci yang sering di-input user

---

## 🐛 Debug Mode

Untuk melihat semua kecocokan rule, gunakan `proses_chat_v2.php` dan cek response JSON:

```json
{
  "all_matches": [
    {
      "kerusakan": "Kerusakan Power Supply",
      "percentage": 100,
      "match_count": 2,
      "total_gejala": 2
    }
  ]
}
```

---

**Butuh bantuan? Baca dokumentasi lengkap di `CHAT_DIAGNOSA_GUIDE.md`**
