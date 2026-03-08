# 🧪 DEMO TEST KATA KUNCI - Sistem Chat Diagnosa

## 📋 Panduan Testing

File ini berisi contoh input untuk testing sistem chat diagnosa:

- ✅ **Test 1 Gejala** (G001-G020): Kata kunci tunggal
- ✅ **Test 2 Gejala Kombinasi**: Kata kunci yang menggabungkan 2 gejala
- ✅ **Test 3 Gejala Kombinasi**: Kata kunci yang menggabungkan 3 gejala

**Cara Testing:**

1. Buka halaman: `http://localhost/Troubleshooting-Komputer/Asisten/diagnosa_chat.php`
2. Login sebagai: `asisten1` / `password`
3. Copy paste input dari list di bawah satu per satu
4. Periksa apakah gejala teridentifikasi sesuai expected result

---

## 🔍 TEST GEJALA TUNGGAL (Single Symptom)

### G001 - Komputer Tidak Menyala

**Input Test:**

```
komputer tidak menyala
```

**Expected Gejala:** G001  
**Expected Diagnosa:** Kerusakan Power Supply

**Variasi Input:**

```
1. mati total
2. tidak bisa nyala
3. komputer mati sama sekali
4. pc saya tidak hidup
```

---

### G002 - Lampu Power Tidak Menyala

**Input Test:**

```
lampu power tidak menyala
```

**Expected Gejala:** G002  
**Expected Diagnosa:** Kerusakan Power Supply

**Variasi Input:**

```
1. lampu indikator mati
2. led power tidak nyala
3. indikator power mati semua
```

---

### G003 - Bunyi Beep

**Input Test:**

```
komputer bunyi beep berulang
```

**Expected Gejala:** G003  
**Expected Diagnosa:** Kerusakan RAM

**Variasi Input:**

```
1. ada bunyi beep terus
2. beep berulang kali
3. bunyi tut tut tut
```

---

### G004 - Layar Hitam

**Input Test:**

```
layar hitam tidak ada tampilan
```

**Expected Gejala:** G004  
**Expected Diagnosa:** Kerusakan VGA atau RAM

**Variasi Input:**

```
1. monitor hitam
2. no display
3. tidak ada tampilan sama sekali
4. layar mati gelap
```

---

### G005 - Kipas Berputar Tapi Tidak POST

**Input Test:**

```
kipas berputar tapi tidak post
```

**Expected Gejala:** G005  
**Expected Diagnosa:** Kerusakan RAM

**Variasi Input:**

```
1. fan nyala tapi tidak booting
2. kipas jalan tapi tidak masuk bios
```

---

### G006 - Restart Sendiri

**Input Test:**

```
komputer restart sendiri
```

**Expected Gejala:** G006  
**Expected Diagnosa:** Overheating

**Variasi Input:**

```
1. restart otomatis terus
2. nyala mati sendiri
3. restart terus menerus
4. mati hidup mati hidup
```

---

### G007 - Blue Screen (BSOD)

**Input Test:**

```
blue screen terus muncul
```

**Expected Gejala:** G007  
**Expected Diagnosa:** Overheating

**Variasi Input:**

```
1. bsod terus
2. layar biru error
3. muncul blue screen of death
```

---

### G008 - Komputer Lambat

**Input Test:**

```
komputer sangat lambat
```

**Expected Gejala:** G008  
**Expected Diagnosa:** Driver Bermasalah

**Variasi Input:**

```
1. pc lemot banget
2. kinerja sangat lelet
3. komputer sering hang
```

---

### G009 - Aplikasi Not Responding

**Input Test:**

```
aplikasi sering not responding
```

**Expected Gejala:** G009  
**Expected Diagnosa:** Driver Bermasalah

**Variasi Input:**

```
1. program sering freeze
2. aplikasi macet terus
3. aplikasi sering tidak merespon
```

---

### G010 - Hardisk Bunyi Aneh

**Input Test:**

```
hardisk bunyi klik klik
```

**Expected Gejala:** G010  
**Expected Diagnosa:** Kerusakan Hardisk

**Variasi Input:**

```
1. hdd berbunyi aneh
2. hardisk bunyi klik
3. ada bunyi aneh dari hardisk
```

---

### G011 - Tidak Bisa Booting

**Input Test:**

```
komputer tidak bisa booting
```

**Expected Gejala:** G011  
**Expected Diagnosa:** Sistem Operasi Corrupt atau Hardisk

**Variasi Input:**

```
1. gagal boot ke windows
2. tidak bisa masuk windows
3. stuck di logo
```

---

### G012 - Operating System Not Found

**Input Test:**

```
muncul pesan operating system not found
```

**Expected Gejala:** G012  
**Expected Diagnosa:** Kerusakan Hardisk

**Variasi Input:**

```
1. os not found
2. sistem operasi tidak ditemukan
3. boot device not found
```

---

### G013 - Loading Windows Lama

**Input Test:**

```
loading windows sangat lama
```

**Expected Gejala:** G013  
**Expected Diagnosa:** Sistem Operasi Corrupt

**Variasi Input:**

```
1. booting lama sekali
2. startup lambat banget
3. lama masuk windows
```

---

### G014 - Hang Saat Masuk Windows

**Input Test:**

```
hang saat masuk windows
```

**Expected Gejala:** G014  
**Expected Diagnosa:** Sistem Operasi Corrupt

**Variasi Input:**

```
1. freeze waktu login
2. macet saat starting windows
3. stuck waktu masuk windows
```

---

### G015 - Layar Bergaris

**Input Test:**

```
layar bergaris garis
```

**Expected Gejala:** G015  
**Expected Diagnosa:** Kerusakan VGA

**Variasi Input:**

```
1. monitor bergaris
2. ada artifact di layar
3. tampilan rusak bergaris
```

---

### G016 - Komputer Panas

**Input Test:**

```
komputer sangat panas
```

**Expected Gejala:** G016  
**Expected Diagnosa:** Overheating

**Variasi Input:**

```
1. pc overheat
2. suhu sangat tinggi
3. komputer kepanasan
```

---

### G017 - Internet Lambat

**Input Test:**

```
internet sangat lambat
```

**Expected Gejala:** G017  
**Expected Diagnosa:** Driver Bermasalah

**Variasi Input:**

```
1. koneksi wifi sering putus
2. jaringan lambat sekali
3. wifi disconnect terus
```

---

### G018 - USB Tidak Terdeteksi

**Input Test:**

```
usb tidak terdeteksi
```

**Expected Gejala:** G018  
**Expected Diagnosa:** Kerusakan Port USB

**Variasi Input:**

```
1. flashdisk tidak terbaca
2. port usb mati
3. usb device not recognized
```

---

### G019 - Keyboard/Mouse Error

**Input Test:**

```
keyboard tidak berfungsi
```

**Expected Gejala:** G019  
**Expected Diagnosa:** Kerusakan Port USB

**Variasi Input:**

```
1. mouse tidak gerak
2. keyboard error
3. keyboard mouse mati
```

---

### G020 - Audio Mati

**Input Test:**

```
tidak ada suara sama sekali
```

**Expected Gejala:** G020  
**Expected Diagnosa:** Driver Bermasalah

**Variasi Input:**

```
1. audio mati total
2. speaker tidak bunyi
3. suara hilang
```

---

## 🔗 TEST KOMBINASI 2 GEJALA

### Kombinasi 1: G001 + G002 (Power Supply)

**Input Test:**

```
komputer mati total dan lampu indikator tidak nyala
```

**Expected Gejala:** G001, G002  
**Expected Diagnosa:** Kerusakan Power Supply  
**Match Confidence:** 100% (2/2 gejala Rule 1)

**Variasi Input:**

```
1. mati dan tidak ada tanda kehidupan
2. pc mati total lampu mati semua
3. tidak menyala dan led power mati
```

---

### Kombinasi 2: G003 + G004 (RAM)

**Input Test:**

```
bunyi beep dan layar hitam
```

**Expected Gejala:** G003, G004  
**Expected Diagnosa:** Kerusakan RAM  
**Match Confidence:** 66% (2/3 gejala Rule 2)

**Variasi Input:**

```
1. beep berulang tidak ada tampilan
2. bunyi beep layar mati
3. beep terus monitor hitam
```

---

### Kombinasi 3: G004 + G005 (RAM/VGA)

**Input Test:**

```
kipas nyala tapi layar hitam
```

**Expected Gejala:** G004, G005  
**Expected Diagnosa:** Kerusakan RAM atau VGA  
**Match Confidence:** 66%

**Variasi Input:**

```
1. fan berputar tapi no display
2. kipas jalan layar gelap
3. nyala tapi tidak ada gambar
```

---

### Kombinasi 4: G006 + G016 (Overheating)

**Input Test:**

```
komputer restart sendiri dan sangat panas
```

**Expected Gejala:** G006, G016  
**Expected Diagnosa:** Overheating  
**Match Confidence:** 66% (2/3 gejala Rule 6)

**Variasi Input:**

```
1. restart terus dan kepanasan
2. panas dan restart otomatis
3. overheat terus restart
4. panas restart sendiri
```

---

### Kombinasi 5: G006 + G007 (Overheating)

**Input Test:**

```
restart sendiri setelah blue screen
```

**Expected Gejala:** G006, G007  
**Expected Diagnosa:** Overheating  
**Match Confidence:** 66%

**Variasi Input:**

```
1. bsod lalu restart
2. blue screen terus restart
3. layar biru restart otomatis
```

---

### Kombinasi 6: G008 + G009 (Driver Bermasalah)

**Input Test:**

```
komputer lambat dan aplikasi sering freeze
```

**Expected Gejala:** G008, G009  
**Expected Diagnosa:** Driver atau Software Bermasalah  
**Match Confidence:** 66%

**Variasi Input:**

```
1. lemot dan sering not responding
2. lambat aplikasi macet
3. hang dan freeze terus
```

---

### Kombinasi 7: G010 + G011 (Hardisk Rusak)

**Input Test:**

```
hardisk bunyi klik dan tidak bisa booting
```

**Expected Gejala:** G010, G011  
**Expected Diagnosa:** Kerusakan Hardisk  
**Match Confidence:** 66% (2/3 gejala Rule 4)

**Variasi Input:**

```
1. bunyi klik tidak boot
2. hdd berbunyi tidak masuk windows
3. hardisk bunyi aneh gagal boot
```

---

### Kombinasi 8: G010 + G012 (Hardisk Rusak)

**Input Test:**

```
hardisk bunyi dan muncul os not found
```

**Expected Gejala:** G010, G012  
**Expected Diagnosa:** Kerusakan Hardisk  
**Match Confidence:** 66%

**Variasi Input:**

```
1. bunyi klik os not found
2. hardisk berbunyi operating system not found
```

---

### Kombinasi 9: G011 + G013 (OS Corrupt)

**Input Test:**

```
loading lama dan tidak bisa masuk windows
```

**Expected Gejala:** G011, G013  
**Expected Diagnosa:** Sistem Operasi Corrupt  
**Match Confidence:** 66% (2/3 gejala Rule 5)

**Variasi Input:**

```
1. booting lama gagal masuk
2. startup lambat tidak boot
```

---

### Kombinasi 10: G013 + G014 (OS Corrupt)

**Input Test:**

```
loading lama dan hang saat masuk windows
```

**Expected Gejala:** G013, G014  
**Expected Diagnosa:** Sistem Operasi Corrupt  
**Match Confidence:** 66%

**Variasi Input:**

```
1. booting lama freeze windows
2. lama masuk dan macet
3. windows lama hang waktu login
```

---

## 🎯 TEST KOMBINASI 3 GEJALA

### Kombinasi 3A: G001 + G002 + (lainnya) (Power Supply)

**Input Test:**

```
komputer mati total, tidak ada lampu, dan tidak ada tanda kehidupan
```

**Expected Gejala:** G001, G002  
**Expected Diagnosa:** Kerusakan Power Supply  
**Match Confidence:** 100% (2/2 gejala Rule 1)

---

### Kombinasi 3B: G003 + G004 + G005 (RAM)

**Input Test:**

```
bunyi beep, layar hitam, dan kipas berputar
```

**Expected Gejala:** G003, G004, G005  
**Expected Diagnosa:** Kerusakan RAM  
**Match Confidence:** 100% (3/3 gejala Rule 2)

**Variasi Input:**

```
1. beep berulang kipas nyala layar gelap
2. bunyi beep fan muter tidak ada tampilan
3. beep tidak post layar hitam
```

---

### Kombinasi 3C: G006 + G007 + G016 (Overheating)

**Input Test:**

```
komputer restart sendiri, blue screen, dan sangat panas
```

**Expected Gejala:** G006, G007, G016  
**Expected Diagnosa:** Overheating  
**Match Confidence:** 100% (3/3 gejala Rule 6)

**Variasi Input:**

```
1. panas restart blue screen
2. overheat bsod restart terus
3. kepanasan layar biru restart otomatis
4. panas blue screen restart sendiri
```

---

### Kombinasi 3D: G010 + G011 + G012 (Hardisk Rusak)

**Input Test:**

```
hardisk bunyi klik, tidak bisa booting, dan muncul os not found
```

**Expected Gejala:** G010, G011, G012  
**Expected Diagnosa:** Kerusakan Hardisk  
**Match Confidence:** 100% (3/3 gejala Rule 4)

**Variasi Input:**

```
1. bunyi klik gagal boot os not found
2. hardisk berbunyi tidak boot operating system not found
3. klik klik tidak masuk windows hardisk tidak terdeteksi
```

---

### Kombinasi 3E: G011 + G013 + G014 (OS Corrupt)

**Input Test:**

```
tidak bisa booting, loading lama, dan hang masuk windows
```

**Expected Gejala:** G011, G013, G014  
**Expected Diagnosa:** Sistem Operasi Corrupt  
**Match Confidence:** 100% (3/3 gejala Rule 5)

**Variasi Input:**

```
1. gagal boot loading lama freeze windows
2. stuck di logo booting lama hang
3. tidak masuk windows lama banget macet
```

---

### Kombinasi 3F: G008 + G009 + (lainnya) (Driver)

**Input Test:**

```
komputer lambat, aplikasi freeze, dan sering hang
```

**Expected Gejala:** G008, G009  
**Expected Diagnosa:** Driver atau Software Bermasalah  
**Match Confidence:** 66% atau lebih

**Variasi Input:**

```
1. lemot not responding kinerja menurun
2. lelet program macet performa jelek
```

---

## 📊 TEST MATRIX SUMMARY

| Test Type        | Total Tests   | Gejala Count   | Expected Match |
| ---------------- | ------------- | -------------- | -------------- |
| Single Symptom   | 20 tests      | 1 gejala       | 1+ diagnosa    |
| 2 Symptoms Combo | 10 tests      | 2 gejala       | 40-100% match  |
| 3 Symptoms Combo | 6 tests       | 3 gejala       | 66-100% match  |
| **TOTAL**        | **36+ tests** | **1-3 gejala** | **Varies**     |

---

## ✅ CHECKLIST TESTING

### Pre-Test Setup

- [ ] Database updated (kata_kunci sudah diupdate)
- [ ] Login sebagai asisten_lab
- [ ] Halaman chat diagnosa terbuka
- [ ] Browser console clear (F12)

### Single Symptom Tests (20 tests)

- [ ] G001 - Power tidak menyala ✓
- [ ] G002 - Lampu power mati ✓
- [ ] G003 - Bunyi beep ✓
- [ ] G004 - Layar hitam ✓
- [ ] G005 - Kipas nyala tidak POST ✓
- [ ] G006 - Restart sendiri ✓
- [ ] G007 - Blue screen ✓
- [ ] G008 - Lambat ✓
- [ ] G009 - Not responding ✓
- [ ] G010 - Hardisk bunyi ✓
- [ ] G011 - Tidak boot ✓
- [ ] G012 - OS not found ✓
- [ ] G013 - Loading lama ✓
- [ ] G014 - Hang windows ✓
- [ ] G015 - Layar bergaris ✓
- [ ] G016 - Panas ✓
- [ ] G017 - Internet lambat ✓
- [ ] G018 - USB error ✓
- [ ] G019 - Keyboard/Mouse error ✓
- [ ] G020 - Audio mati ✓

### 2 Symptoms Combo Tests (10 tests)

- [ ] G001 + G002 → Power Supply ✓
- [ ] G003 + G004 → RAM ✓
- [ ] G004 + G005 → RAM/VGA ✓
- [ ] G006 + G016 → Overheating ✓
- [ ] G006 + G007 → Overheating ✓
- [ ] G008 + G009 → Driver ✓
- [ ] G010 + G011 → Hardisk ✓
- [ ] G010 + G012 → Hardisk ✓
- [ ] G011 + G013 → OS Corrupt ✓
- [ ] G013 + G014 → OS Corrupt ✓

### 3 Symptoms Combo Tests (6 tests)

- [ ] G001 + G002 + ... → Power Supply ✓
- [ ] G003 + G004 + G005 → RAM (100% match) ✓
- [ ] G006 + G007 + G016 → Overheating (100% match) ✓
- [ ] G010 + G011 + G012 → Hardisk (100% match) ✓
- [ ] G011 + G013 + G014 → OS Corrupt (100% match) ✓
- [ ] G008 + G009 + ... → Driver ✓

---

## 🐛 Bug Report Template

**Test ID:** TC-XXX  
**Input:** [kata kunci yang ditest]  
**Expected Gejala:** [G001, G002]  
**Actual Gejala:** [G003]  
**Expected Diagnosa:** [Kerusakan Power Supply]  
**Actual Diagnosa:** [Kerusakan RAM]  
**Status:** ❌ FAIL  
**Notes:** [catatan bug]

---

## 📈 Tips Testing

1. **Clear cache** setelah update database
2. **Refresh halaman** sebelum mulai testing
3. **Test satu per satu** jangan batch
4. **Catat hasil** di checklist
5. **Screenshot** jika ada error
6. **Check console** untuk error JavaScript
7. **Verify database** kata_kunci sudah update

---

## 🎯 Success Criteria

**PASS Criteria:**

- ✅ Gejala teridentifikasi sesuai expected
- ✅ Diagnosa muncul dengan benar
- ✅ Solusi ditampilkan lengkap
- ✅ Response time < 3 detik
- ✅ Tidak ada error di console

**FAIL Criteria:**

- ❌ Gejala tidak teridentifikasi
- ❌ Diagnosa salah
- ❌ Error database/PHP
- ❌ Response timeout
- ❌ JavaScript error

---

**Happy Testing! 🚀**

Last Updated: March 8, 2026  
Version: 2.0.0  
Total Test Cases: 36+
