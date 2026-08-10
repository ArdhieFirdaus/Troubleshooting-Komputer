-- ============================================================================
-- SCRIPT SEEDER 50 DATASET KASUS UJI TROUBLESHOOTING KOMPUTER
-- Sistem Pakar Diagnosa Troubleshooting Komputer & Software (Forward Chaining)
-- Studi Kasus: Pondok Pesantren Al-Gontory
-- Penyusun: Ardhie Firdaus (NIM: 221011400647)
-- ============================================================================

USE `db_sistem_pakar_gontory`;

-- 1. Tambah Kolom Kategori pada tabel Kerusakan jika belum ada
ALTER TABLE `kerusakan` ADD COLUMN IF NOT EXISTS `kategori` ENUM('Hardware', 'Software') NOT NULL DEFAULT 'Hardware';

-- Update Kategori Kerusakan Eksisting
UPDATE `kerusakan` SET `kategori` = 'Hardware' WHERE `kode_kerusakan` IN ('K001', 'K002', 'K003', 'K004', 'K006', 'K008');
UPDATE `kerusakan` SET `kategori` = 'Software' WHERE `kode_kerusakan` IN ('K005', 'K007', 'K009');

-- 2. Tambah Data Master Kerusakan Baru (Hardware & Software tambahan)
INSERT IGNORE INTO `kerusakan` (`id_kerusakan`, `kode_kerusakan`, `nama_kerusakan`, `solusi`, `kategori`) VALUES
(10, 'K010', 'Infeksi Virus / Malware System', '1. Jalankan Full System Scan dengan Windows Defender / Antivirus terupdate\r\n2. Hapus file karantina dan file temporary (%temp%)\r\n3. Bersihkan Startup Program melalui Task Manager\r\n4. Lakukan booting ke Safe Mode jika virus memblokir antivirus', 'Software'),
(11, 'K011', 'Kerusakan Baterai CMOS / BIOS Setting', '1. Ganti Baterai CMOS CR2032 di motherboard\r\n2. Masuk BIOS (tekan DEL/F2) lalu set tanggal & waktu dengan benar\r\n3. Load Default BIOS Settings\r\n4. Simpan konfigurasi BIOS (F10) dan restart', 'Hardware'),
(12, 'K012', 'Registry / System File Corrupt', '1. Buka Command Prompt (Run as Administrator)\r\n2. Jalankan perintah: sfc /scannow\r\n3. Jalankan perintah DISM: DISM /Online /Cleanup-Image /RestoreHealth\r\n4. Restart komputer setelah selesai', 'Software'),
(13, 'K013', 'Kerusakan Monitor / Kabel Display', '1. Periksa sambungan kabel VGA/HDMI ke monitor dan PC\r\n2. Coba ganti kabel display dengan yang baru\r\n3. Test monitor di PC lain untuk memastikan layar tidak rusak\r\n4. Periksa daya adaptor monitor', 'Hardware'),
(14, 'K014', 'Kapasitas Penyimpanan Penuh', '1. Hapus file di Recycle Bin dan folder Downloads\r\n2. Jalankan Disk Cleanup pada Drive C:\r\n3. Uninstall aplikasi yang tidak digunakan\r\n4. Pindahkan data pribadi ke Drive D: atau eksternal storage', 'Software'),
(15, 'K015', 'Konfigurasi IP / DNS Error', '1. Buka CMD lalu ketik: ipconfig /flushdns\r\n2. Ketik: ipconfig /release dan ipconfig /renew\r\n3. Ketik: netsh winsock reset\r\n4. Set IP Address & DNS Server ke Automatic (DHCP)', 'Software');

-- 3. Tambah Data Master Gejala Baru
INSERT IGNORE INTO `gejala` (`id_gejala`, `kode_gejala`, `nama_gejala`, `kata_kunci`) VALUES
(23, 'G023', 'Tanggal dan jam BIOS selalu terreset ke default', 'bios reset, jam pc salah, tanggal jam terreset, cmos battery, jam selalu balik ke tahun lama'),
(24, 'G024', 'Muncul banyak pop-up iklan / browser terarah sendiri', 'pop up iklan, iklan sendiri, virus browser, malware iklan, redirect browser'),
(25, 'G025', 'File dokumen tiba-tiba hilang atau berubah format', 'file hilang, ekstensi berubah, file terenkripsi, kena virus, file jadi shortcut'),
(26, 'G026', 'Layar monitor berkedip NO SIGNAL secara acak', 'no signal, layar kedip no signal, monitor no signal, kabel vga kendor'),
(27, 'G027', 'CPU usage atau Disk usage selalu 100% di Task Manager', 'cpu 100%, disk 100%, task manager merah, komputer lemot berat'),
(28, 'G028', 'Muncul pesan error dll missing / application initialization error', 'dll missing, error dll, app error, vcruntime missing, error 0xc000007b'),
(29, 'G029', 'Ruang penyimpanan drive C: berwarna merah / penuh', 'drive c penuh, c merah, storage penuh, memori internal habis'),
(30, 'G030', 'IP Address mengalami konflik / Invalid IP Configuration', 'ip conflict, invalid ip, ip address conflict, no internet access, limited access');

-- 4. Tambah Rule Baru untuk Kerusakan K010 - K015
INSERT IGNORE INTO `rule` (`id_rule`, `id_kerusakan`) VALUES
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- Tambah Detail Rule
INSERT IGNORE INTO `rule_detail` (`id_rule`, `id_gejala`) VALUES
(10, 8), (10, 24), (10, 25),
(11, 23), (11, 11),
(12, 9), (12, 28),
(13, 4), (13, 26),
(14, 8), (14, 29),
(15, 17), (15, 30);

-- ============================================================================
-- CATATAN: SCRIPT DI ATAS SIAP DI-IMPORT KE MYSQL VIA PHPMYADMIN
-- Data 50 Kasus Uji lengkap dapat dilihat di file: docs/DATASET_50_KASUS_UJI.md
-- ============================================================================
