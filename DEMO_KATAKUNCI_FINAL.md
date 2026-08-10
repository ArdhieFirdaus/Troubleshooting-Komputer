# DEMO KATA KUNCI FINAL

Dokumen ini dipakai untuk mengetes chatbot diagnosa berdasarkan kata kunci yang sudah sangat natural, baik untuk gejala tunggal, kombinasi gejala yang sesuai rule, maupun kombinasi gejala yang tidak sesuai rule atau input yang tidak relevan.

## 1. DIAGNOSA BERHASIL (SESUAI RULE)

### A. Diagnosa dengan 1 Gejala

- **Rule 1 - Kerusakan Power Supply**
  - **Input 1**: `komputer mati total` *(Gejala 1)*
  - **Input 2**: `pc saya mati total` *(Gejala 1)*
  - **Hasil**: **Kerusakan Power Supply**
  - **Solusi**: Cek kabel power apakah terpasang dengan benar, test power supply dengan PSU tester, ganti power supply jika rusak.

- **Rule 2 - Kerusakan RAM**
  - **Input 1**: `bunyi beep saat dinyalakan` *(Gejala 3)*
  - **Input 2**: `pas dinyalain bunyi beep` *(Gejala 3)*
  - **Hasil**: **Kerusakan RAM (Memory)**
  - **Solusi**: Buka casing dan bersihkan slot RAM dengan kuas halus, lepas dan pasang kembali RAM, test RAM satu per satu.

- **Rule 3 - Kerusakan VGA**
  - **Input 1**: `layar bergaris saat digunakan` *(Gejala 15)*
  - **Input 2**: `monitor pecah gambarnya` *(Gejala 15)*
  - **Hasil**: **Kerusakan VGA Card**
  - **Solusi**: Bersihkan slot VGA dan konektor VGA, pastikan VGA terpasang dengan benar, cek kabel monitor ke VGA.

- **Rule 4 - Kerusakan Hardisk**
  - **Input 1**: `hardisk bunyi klik klik` *(Gejala 10)*
  - **Input 2**: `hddnya bunyi krek krek` *(Gejala 10)*
  - **Hasil**: **Kerusakan Hardisk**
  - **Solusi**: Backup data segera jika masih bisa diakses, cek kabel SATA/IDE hardisk, scan hardisk dengan tools, perbaiki bad sector.

- **Rule 5 - Sistem Operasi Corrupt**
  - **Input 1**: `loading lama saat booting` *(Gejala 13)*
  - **Input 2**: `nunggu lama buat masuk desktop` *(Gejala 13)*
  - **Hasil**: **Sistem Operasi Corrupt**
  - **Solusi**: Repair Windows menggunakan installation media, gunakan System Restore, jalankan SFC /scannow di Command Prompt.

- **Rule 6 - Overheating**
  - **Input 1**: `komputer restart sendiri` *(Gejala 6)*
  - **Input 2**: `pc ngerestart sendiri` *(Gejala 6)*
  - **Hasil**: **Overheating (Panas Berlebih)**
  - **Solusi**: Bersihkan debu di dalam casing, periksa dan bersihkan heatsink processor, ganti thermal paste processor.

- **Rule 7 - Driver atau Software Bermasalah**
  - **Input 1**: `komputer sangat lemot` *(Gejala 8)*
  - **Input 2**: `komputer jadi super lemot` *(Gejala 8)*
  - **Hasil**: **Driver atau Software Bermasalah**
  - **Solusi**: Update driver ke versi terbaru, uninstall dan install ulang driver, rollback driver ke versi sebelumnya.

- **Rule 8 - Kerusakan Port USB**
  - **Input 1**: `usb tidak terdeteksi` *(Gejala 18)*
  - **Input 2**: `pasang flashdisk ga muncul` *(Gejala 18)*
  - **Hasil**: **Kerusakan Port USB**
  - **Solusi**: Coba port USB yang berbeda, update driver USB Controller, disable dan enable kembali USB Controller di Device Manager.

- **Rule 9 - Kerusakan Jaringan**
  - **Input 1**: `jaringan lambat` *(Gejala 17)*
  - **Input 2**: `jaringan internet ga stabil` *(Gejala 17)*
  - **Hasil**: **Kerusakan Jaringan**qq
  - **Solusi**: Periksa koneksi kabel LAN atau jaringan WiFi, restart router atau access point, periksa pengaturan IP Address.

### B. Diagnosa dengan 2 Kombinasi Gejala

- **Rule 1 - Kerusakan Power Supply**
  - **Input 1**: `laptop mati total dan lampu indikatornya nggak nyala` *(Gejala 1, 2)*
  - **Input 2**: `nggak bisa dihidupkan sama sekali dan lampu di pc mati` *(Gejala 1, 2)*
  - **Hasil**: **Kerusakan Power Supply**

- **Rule 3 - Kerusakan VGA**
  - **Input 1**: `layar ngga muncul apa apa dan layarnya patah patah` *(Gejala 4, 15)*
  - **Input 2**: `monitor ga nyala padahal kipas muter, dan muncul garis hijau` *(Gejala 4, 15)*
  - **Hasil**: **Kerusakan VGA Card**

- **Rule 5 - Sistem Operasi Corrupt**
  - **Input 1**: `windows loading lama dan pas login langsung freeze` *(Gejala 13, 14)*
  - **Input 2**: `loadingnya muter muter terus trus baru masuk windows langsung macet` *(Gejala 13, 14)*
  - **Hasil**: **Sistem Operasi Corrupt**

- **Rule 6 - Overheating**
  - **Input 1**: `sering mati sendiri lalu nyala lagi dan komputer kena bsod` *(Gejala 6, 7)*
  - **Input 2**: `komputer suka restart tiba-tiba lalu muncul tulisan di layar biru` *(Gejala 6, 7)*
  - **Hasil**: **Overheating (Panas Berlebih)**

- **Rule 7 - Driver atau Software Bermasalah**
  - **Input 1**: `lemot parah dan programnya not responding` *(Gejala 8, 9)*
  - **Input 2**: `buka aplikasi lama banget sampe layarnya macet` *(Gejala 8, 9)*
  - **Hasil**: **Driver atau Software Bermasalah**

- **Rule 8 - Kerusakan Port USB**
  - **Input 1**: `colokan usb ga fungsi dan mouse ga bisa digerakin` *(Gejala 18, 19)*
  - **Input 2**: `flashdisk tidak terbaca terus keyboard mouse mati` *(Gejala 18, 19)*
  - **Hasil**: **Kerusakan Port USB**

### C. Diagnosa dengan 3 Kombinasi Gejala

- **Rule 2 - Kerusakan RAM**
  - **Input 1**: `bunyi beep terus terusan, blank screen, tapi kipas nyala kenceng` *(Gejala 3, 4, 5)*
  - **Input 2**: `komputer bunyi aneh pas nyala, ga ada gambar di monitor padahal fan vga muter doang` *(Gejala 3, 4, 5)*
  - **Hasil**: **Kerusakan RAM (Memory)**

- **Rule 4 - Kerusakan Hardisk**
  - **Input 1**: `suara cetek cetek, gagal masuk os, lalu muncul pesan os not found` *(Gejala 10, 11, 12)*
  - **Input 2**: `ada suara kasar dari dalam pc, mentok di logo, sampai tulisan no bootable device` *(Gejala 10, 11, 12)*
  - **Hasil**: **Kerusakan Hardisk**

- **Rule 5 - Sistem Operasi Corrupt**
  - **Input 1**: `berhenti di logo windows karena pas dinyalain lama banget lalu hang di logo windows` *(Gejala 11, 13, 14)*
  - **Input 2**: `gagal loading windows, proses masuk windows lama banget, macet di tampilan awal windows` *(Gejala 11, 13, 14)*
  - **Hasil**: **Sistem Operasi Corrupt**

- **Rule 6 - Overheating**
  - **Input 1**: `komputer restart mendadak lalu muncul tulisan di layar biru karena casingnya panas banget` *(Gejala 6, 7, 16)*
  - **Input 2**: `pc ngerestart sendiri kena bluescreen of death suhunya tinggi sekali` *(Gejala 6, 7, 16)*
  - **Hasil**: **Overheating (Panas Berlebih)**

- **Rule 7 - Driver atau Software Bermasalah**
  - **Input 1**: `lag banget, aplikasi ditutup paksa, dan ga ada suaranya` *(Gejala 8, 9, 20)*
  - **Input 2**: `windowsnya lelet, game tiba tiba macet, dan suara di komputer bisu` *(Gejala 8, 9, 20)*
  - **Hasil**: **Driver atau Software Bermasalah**

- **Rule 9 - Kerusakan Jaringan**
  - **Input 1**: `internetnya lemot banget, wifi silang, dan ping besar dan putus` *(Gejala 17, 21, 22)*
  - **Input 2**: `koneksi lelet parah, ga bisa connect wifi, trus jaringan sering ilang` *(Gejala 17, 21, 22)*
  - **Hasil**: **Kerusakan Jaringan**

## 2. DIAGNOSA BERHASIL TETAPI KERUSAKAN TIDAK TERIDENTIFIKASI

Kasus ini terjadi jika gejala yang terdeteksi tidak merujuk pada satu rule yang spesifik (misal: gejalanya dimiliki oleh lebih dari 1 rule, atau kombinasi gejalanya lintas rule).

### A. Gejala Tidak Teridentifikasi (1 Gejala Ambigu)
- **Input 1**: `layar ngga muncul apa apa` *(Gejala 4)*
- **Input 2**: `monitor ga nyala` *(Gejala 4)*
- **Alasan**: Gejala tersebut dimiliki oleh Rule 2 (RAM) dan Rule 3 (VGA). Sistem tidak bisa memastikan.
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

- **Input 1**: `mentok di logo` *(Gejala 11)*
- **Input 2**: `tidak masuk windows` *(Gejala 11)*
- **Alasan**: Gejala tersebut dimiliki oleh Rule 4 (Hardisk) dan Rule 5 (OS Corrupt).
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

### B. Gejala Tidak Teridentifikasi (2 Kombinasi Gejala Lintas Rule)
- **Input 1**: `ga bisa nyala dan suara cetek cetek` *(Gejala 1 dari Rule 1, dan Gejala 10 dari Rule 4)*
- **Input 2**: `komputer mati total lalu hardisk bunyi aneh` *(Gejala 1 dari Rule 1, dan Gejala 10 dari Rule 4)*
- **Alasan**: Gejala saling bertabrakan antara kerusakan Power Supply dan Hardisk.
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

- **Input 1**: `port usb rusak dan wifi silang` *(Gejala 18 dari Rule 8, dan Gejala 21 dari Rule 9)*
- **Input 2**: `usb tidak terdeteksi sementara wifi tidak nyambung` *(Gejala 18 dari Rule 8, dan Gejala 21 dari Rule 9)*
- **Alasan**: Menandakan kerusakan Port USB sekaligus Jaringan.
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

### C. Gejala Tidak Teridentifikasi (3 Kombinasi Gejala Lintas Rule)
- **Input 1**: `mati mendadak dan ga mau nyala, bunyi asing dari hardisk, dan hawanya panas` *(Gejala 1, 10, 16)*
- **Input 2**: `tombol power ditekan ga nyala, bunyi krek krek, casingnya panas banget` *(Gejala 1, 10, 16)*
- **Alasan**: Kombinasi Rule 1 (Power Supply), Rule 4 (Hardisk), dan Rule 6 (Overheating).
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

- **Input 1**: `colokan usb ga fungsi, gambarnya berbayang, dan pas dinyalain bunyi beep` *(Gejala 18, 15, 3)*
- **Input 2**: `flashdisk ga kebaca, monitor pecah gambarnya, ada bunyi tut` *(Gejala 18, 15, 3)*
- **Alasan**: Kombinasi Rule 8 (USB), Rule 3 (VGA), dan Rule 2 (RAM).
- **Hasil**: **Kerusakan Tidak Teridentifikasi**

## 3. GAGAL DIAGNOSA (KATA KUNCI TIDAK DIKENALI)

Kasus ini terjadi jika input di luar dari aturan (rule) kata kunci yang ada pada database, sehingga sistem gagal mendiagnosa dan menyuruh user mengetik ulang gejalanya.

### Contoh Input:
- **Input 1**: `komputer rusak parah gan tolong perbaiki`
- **Input 2**: `cara merebus telur`
- **Input 3**: `halo bantuin dong`
- **Input 4**: `saya belum tahu apanya yang rusak`
- **Input 5**: `laptop jatuh dari meja`

### Output yang Diharapkan:
`❓ Maaf, saya belum bisa memahami masalah yang Anda jelaskan. Coba gunakan kata kunci seperti: "tidak menyala", "layar hitam", "hardisk bunyi", "restart sendiri", dll.`
