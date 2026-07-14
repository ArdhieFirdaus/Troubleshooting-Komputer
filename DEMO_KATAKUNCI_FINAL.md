# DEMO KATA KUNCI FINAL

Dokumen ini dipakai untuk mengetes chatbot diagnosa berdasarkan kata kunci, baik untuk gejala tunggal, kombinasi gejala yang sesuai rule, maupun kombinasi gejala yang tidak sesuai rule sehingga hasilnya menjadi **Kerusakan Tidak Teridentifikasi**.

## Cara Uji

1. Buka [Asisten/diagnosa_chat.php](Asisten/diagnosa_chat.php).
2. Login sebagai `asisten1` dengan password `password`.
3. Kirim satu per satu contoh input di bawah.
4. Jika backend masih memakai endpoint lama, pastikan halaman chat memanggil [Asisten/proses_chat_v2.php](Asisten/proses_chat_v2.php) untuk keyword dari database.

## A. Test Gejala Satu-Satu Sesuai Rule

### Rule 1 - Kerusakan Power Supply

- Input: `komputer tidak menyala`
- Input: `lampu power tidak menyala`
- Input: `komputer saya tidak menyala`

### Rule 2 - Kerusakan RAM

- Input: `bunyi beep terus saat dinyalakan`
- Input: `beep berulang saat komputer dinyalakan`
- Input: `komputer bunyi beep`

### Rule 3 - Kerusakan VGA

- Input: `komputer nyala tapi layar hitam`
- Input: `monitor tidak menampilkan gambar`
- Input: `layar hitam saat komputer hidup`

### Rule 4 - Kerusakan Hardisk

- Input: `hardisk bunyi klik klik`
- Input: `hardisk bunyi klik terus`
- Input: `komputer gagal boot`

### Rule 5 - Sistem Operasi Corrupt

- Input: `windows loading lama dan sering hang`
- Input: `loading lama saat masuk windows`
- Input: `windows loading lama lalu hang`

### Rule 6 - Overheating

- Input: `komputer restart sendiri dan suhu panas`
- Input: `komputer restart sendiri terus dan panas`
- Input: `komputer terasa sangat panas`

### Rule 7 - Driver Bermasalah

- Input: `komputer lambat dan aplikasi sering not responding`
- Input: `komputer lemot dan aplikasi not responding`
- Input: `aplikasi sering freeze`

### Rule 8 - Kerusakan Port USB

- Input: `usb tidak terdeteksi`
- Input: `keyboard tidak terdeteksi`
- Input: `flashdisk tidak terbaca`

## B. Test Kombinasi Gejala Sesuai Rule

### Kombinasi 1 - Power Supply

- Input: `komputer tidak menyala dan lampu power juga mati`
- Hasil yang diharapkan: **Kerusakan Power Supply**

### Kombinasi 2 - RAM

- Input: `komputer bunyi beep, layar hitam, sementara kipas masih berputar`
- Hasil yang diharapkan: **Kerusakan RAM**

### Kombinasi 3 - VGA

- Input: `komputer menyala tapi monitor hitam dan layar tidak ada gambar`
- Hasil yang diharapkan: **Kerusakan VGA**

### Kombinasi 4 - Hardisk

- Input: `hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device`
- Hasil yang diharapkan: **Kerusakan Hardisk**

### Kombinasi 5 - Sistem Operasi Corrupt

- Input: `komputer gagal boot karena proses loading windows terlalu lama`
- Hasil yang diharapkan: **Sistem Operasi Corrupt**

### Kombinasi 6 - Overheating

- Input: `komputer restart terus dan terasa panas sekali saat dipakai`
- Hasil yang diharapkan: **Overheating**

### Kombinasi 7 - Driver Bermasalah

- Input: `komputer terasa lemot dan beberapa aplikasi jadi not responding`
- Hasil yang diharapkan: **Driver Bermasalah**

### Kombinasi 8 - USB

- Input: `internet lambat sementara usb juga tidak terdeteksi di komputer saya`
- Hasil yang diharapkan: **Kerusakan Port USB**

## C. Test Gejala Di Luar Rule / Tidak Teridentifikasi

### Contoh 1

- Input: `komputer tidak menyala dan port usb mati`
- Hasil yang diharapkan: **Kerusakan Tidak Teridentifikasi**

### Contoh 2

- Input: `komputer mati total lalu flashdisk tidak terbaca`
- Hasil yang diharapkan: **Kerusakan Tidak Teridentifikasi**

### Contoh 3

- Input: `komputer tidak menyala dan internet lambat`
- Hasil yang diharapkan: **Kerusakan Tidak Teridentifikasi**

### Contoh 4

- Input: `hardisk bunyi klik terus dan keyboard tidak terdeteksi`
- Hasil yang diharapkan: **Kerusakan Tidak Teridentifikasi**

### Contoh 5

- Input: `komputer restart sendiri dan usb gagal dikenali`
- Hasil yang diharapkan: **Kerusakan Tidak Teridentifikasi**

## D. Test Input Umum Tidak Jelas

### Contoh 1

- Input: `komputer rusak`
- Hasil yang diharapkan: pesan tidak bisa memahami masalah

### Contoh 2

- Input: `bantuin dong`
- Hasil yang diharapkan: pesan tidak bisa memahami masalah

### Contoh 3

- Input: `saya belum tahu kerusakannya`
- Hasil yang diharapkan: respons bahwa kerusakan belum diketahui

### Contoh 4

- Input: `kombinasi gejalanya salah`
- Hasil yang diharapkan: respons bahwa gejala ada tapi tidak cocok rule

## E. Skenario Uji Singkat

### Urutan Demo

1. Kirim `komputer tidak menyala` untuk rule power supply.
2. Kirim `bunyi beep terus saat dinyalakan` untuk rule RAM.
3. Kirim `komputer nyala tapi layar hitam` untuk rule VGA.
4. Kirim `komputer tidak menyala dan port usb mati` untuk hasil **Kerusakan Tidak Teridentifikasi**.
5. Kirim `komputer tidak menyala dan internet lambat` untuk hasil **Kerusakan Tidak Teridentifikasi**.

## F. Catatan Penting

- Jika Anda ingin test keyword dari database, pastikan [Database/update_kata_kunci.sql](Database/update_kata_kunci.sql) sudah dijalankan ke MySQL.
- Jika Anda ingin test backend hardcode, gunakan [Asisten/proses_chat.php](Asisten/proses_chat.php).
- Jika Anda ingin test backend database keyword, gunakan [Asisten/proses_chat_v2.php](Asisten/proses_chat_v2.php).
- Untuk kombinasi yang tidak cocok rule, sistem seharusnya menampilkan **Kerusakan Tidak Teridentifikasi**.
