-- ============================================================================
-- SCRIPT SEEDER EXPANDED MASTER DATA (40 GEJALA, 18 KERUSAKAN, 18 RULES)
-- Sistem Pakar Diagnosa Troubleshooting Komputer & Software (Forward Chaining)
-- Studi Kasus: Pondok Pesantren Al-Gontory
-- Penyusun: Ardhie Firdaus (NIM: 221011400647)
-- ============================================================================

USE `db_sistem_pakar_gontory`;

-- 1. Tambah Kolom Kategori pada tabel Kerusakan (aman dari error jika sudah ada)
SET @dbname = DATABASE();
SET @tablename = 'kerusakan';
SET @columnname = 'kategori';
SET @precexec = (SELECT IF(
    (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE
            TABLE_SCHEMA = @dbname
            AND TABLE_NAME = @tablename
            AND COLUMN_NAME = @columnname
    ) > 0,
    'SELECT 1',
    'ALTER TABLE `kerusakan` ADD COLUMN `kategori` ENUM(\'Hardware\', \'Software\') NOT NULL DEFAULT \'Hardware\''
));
PREPARE alterIfNotExists FROM @precexec;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Update Kategori Kerusakan Eksisting (K001 - K009)
UPDATE `kerusakan` SET `kategori` = 'Hardware' WHERE `kode_kerusakan` IN ('K001', 'K002', 'K003', 'K004', 'K006', 'K008');
UPDATE `kerusakan` SET `kategori` = 'Software' WHERE `kode_kerusakan` IN ('K005', 'K007', 'K009');

-- 2. Tambah Master Data Kerusakan Baru (K010 s.d. K018)
INSERT IGNORE INTO `kerusakan` (`id_kerusakan`, `kode_kerusakan`, `nama_kerusakan`, `solusi`, `kategori`) VALUES
(10, 'K010', 'Kerusakan Baterai CMOS / BIOS Setting', '1. Ganti Baterai CMOS CR2032 di motherboard\r\n2. Masuk BIOS (tekan DEL/F2) lalu set tanggal & waktu dengan benar\r\n3. Load Default BIOS Settings\r\n4. Simpan konfigurasi BIOS (F10) dan restart', 'Hardware'),
(11, 'K011', 'Kerusakan Monitor / Kabel Display', '1. Periksa sambungan kabel VGA/HDMI ke monitor dan PC\r\n2. Coba ganti kabel display dengan yang baru\r\n3. Test monitor di PC lain untuk memastikan layar tidak rusak\r\n4. Periksa daya adaptor monitor', 'Hardware'),
(12, 'K012', 'Kerusakan Motherboard / Chipset Short', '1. Periksa adanya fisik komponen yang terbakar/kapasitor kembung\r\n2. Lakukan de-dusting dan bersihkan motherboard dari korosi\r\n3. Lakukan reset jumper CLR_CMOS\r\n4. Test dengan PSU lain, jika tetap tidak POST ganti motherboard', 'Hardware'),
(13, 'K013', 'Infeksi Virus / Malware System', '1. Jalankan Full System Scan dengan Windows Defender / Antivirus terupdate\r\n2. Hapus file karantina dan file temporary (%temp%)\r\n3. Bersihkan Startup Program melalui Task Manager\r\n4. Lakukan booting ke Safe Mode jika virus memblokir antivirus', 'Software'),
(14, 'K014', 'Registry / System File Corrupt', '1. Buka Command Prompt (Run as Administrator)\r\n2. Jalankan perintah: sfc /scannow\r\n3. Jalankan perintah DISM: DISM /Online /Cleanup-Image /RestoreHealth\r\n4. Restart komputer setelah selesai', 'Software'),
(15, 'K015', 'Kapasitas Penyimpanan Penuh', '1. Hapus file di Recycle Bin dan folder Downloads\r\n2. Jalankan Disk Cleanup pada Drive C:\r\n3. Uninstall aplikasi yang tidak digunakan\r\n4. Pindahkan data pribadi ke Drive D: atau eksternal storage', 'Software'),
(16, 'K016', 'Konfigurasi IP / DNS Error', '1. Buka CMD lalu ketik: ipconfig /flushdns\r\n2. Ketik: ipconfig /release dan ipconfig /renew\r\n3. Ketik: netsh winsock reset\r\n4. Set IP Address & DNS Server ke Automatic (DHCP)', 'Software'),
(17, 'K017', 'Konflik Aplikasi / Memory Leak', '1. Tutup aplikasi bermasalah melalui Task Manager (End Task)\r\n2. Check update aplikasi ke versi stabil terbaru\r\n3. Uninstall aplikasi pihak ketiga yang baru diinstall sebelum masalah terjadi\r\n4. Periksa kecukupan Virtual Memory (Paging File)', 'Software'),
(18, 'K018', 'Corrupt Framework / Missing System DLL', '1. Download dan Reinstall Visual C++ Redistributable Package terbaru\r\n2. Reinstall .NET Framework\r\n3. Copy file DLL terkait ke System32 / SysWOW64 jika diperlukan\r\n4. Install ulang aplikasi yang membutuhkan dependency tersebut', 'Software');

-- 3. Tambah Master Data Gejala Baru (G023 s.d. G040)
INSERT IGNORE INTO `gejala` (`id_gejala`, `kode_gejala`, `nama_gejala`, `kata_kunci`) VALUES
(23, 'G023', 'Tanggal dan jam BIOS selalu terreset ke default', 'bios reset, jam pc salah, tanggal jam terreset, cmos battery, jam selalu balik ke tahun lama'),
(24, 'G024', 'Layar monitor berkedip NO SIGNAL atau mati-nyala secara acak', 'no signal, layar kedip no signal, monitor no signal, kabel vga kendor, mati nyala acak'),
(25, 'G025', 'Komputer mendadak mati total saat beban kerja tinggi', 'mati mendadak, thermal shutdown, psu drop, mati saat main game, shutdown sendiri pas beban tinggi'),
(26, 'G026', 'Terdengar suara dengung keras dari casing / kipas berisik', 'kipas berisik, suara dengung, fan bunyi keras, casing getar, fan processor kasar'),
(27, 'G027', 'Port USB longgar / tersengat listrik halus di casing', 'casing nyetrum, listrik halus, grounding buruk, usb kendor, konsleting halus'),
(28, 'G028', 'Monitor menampilkan warna dominan satu jenis (merah/biru/hijau)', 'layar kebiruan, layar kemerahan, monitor dominan hijau, warna monitor rusak, kabel vga rusak warna'),
(29, 'G029', 'Terdapat elco (kapasitor) di motherboard yang kembung', 'kapasitor kembung, elco bocor, motherboard kembung, komponen gosong, elco pecah'),
(30, 'G030', 'Tombol power fisik PC harus ditekan keras / tertahan', 'tombol power keras, saklar pc macet, tombol power dol, pencet tombol lama baru nyala'),
(31, 'G031', 'Muncul banyak pop-up iklan / browser terarah sendiri', 'pop up iklan, iklan sendiri, virus browser, malware iklan, redirect browser'),
(32, 'G032', 'File dokumen tiba-tiba hilang atau berubah format', 'file hilang, ekstensi berubah, file terenkripsi, kena virus, file jadi shortcut'),
(33, 'G033', 'CPU usage atau Disk usage selalu 100% di Task Manager', 'cpu 100%, disk 100%, task manager merah, komputer lemot berat'),
(34, 'G034', 'Muncul pesan error dll missing / application initialization error', 'dll missing, error dll, app error, vcruntime missing, error 0xc000007b'),
(35, 'G035', 'Ruang penyimpanan drive C: berwarna merah / penuh', 'drive c penuh, c merah, storage penuh, memori internal habis'),
(36, 'G036', 'IP Address mengalami konflik / Invalid IP Configuration', 'ip conflict, invalid ip, ip address conflict, no internet access, limited access'),
(37, 'G037', 'Aplikasi sering Crash / Force Close saat dijalankan', 'force close, app crash, aplikasi keluar sendiri, program terhenti mendadak'),
(38, 'G038', 'Windows Update gagal terus / error code 0x800...', 'windows update error, update gagal, update stuck, error update windows'),
(39, 'G039', 'Icon jaringan WiFi / Ethernet bertanda seru kuning', 'tanda seru kuning, wifi no internet, ethernet limited, icon jaringan kuning'),
(40, 'G040', 'Penggunaan RAM melonjak tinggi tanpa aplikasi berat (Memory Leak)', 'ram 90%, memory leak, ram penuh padahal sepi, ram habis sendiri');

-- 4. Tambah Master Rule Baru (Rule 10 s.d. 18)
INSERT IGNORE INTO `rule` (`id_rule`, `id_kerusakan`) VALUES
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18);

-- 5. Tambah Detail Rule (Mencegah Duplikasi Pasangan Rule - Gejala)
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 1, 25 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 1 AND `id_gejala` = 25);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 1, 30 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 1 AND `id_gejala` = 30);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 2, 40 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 2 AND `id_gejala` = 40);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 3, 28 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 3 AND `id_gejala` = 28);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 6, 26 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 6 AND `id_gejala` = 26);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 7, 37 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 7 AND `id_gejala` = 37);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 8, 27 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 8 AND `id_gejala` = 27);

-- Rule Detail untuk Rule 10 s.d. 18
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 10, 23 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 10 AND `id_gejala` = 23);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 10, 11 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 10 AND `id_gejala` = 11);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 11, 4 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 11 AND `id_gejala` = 4);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 11, 24 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 11 AND `id_gejala` = 24);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 11, 28 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 11 AND `id_gejala` = 28);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 12, 1 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 12 AND `id_gejala` = 1);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 12, 27 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 12 AND `id_gejala` = 27);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 12, 29 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 12 AND `id_gejala` = 29);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 13, 8 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 13 AND `id_gejala` = 8);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 13, 31 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 13 AND `id_gejala` = 31);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 13, 32 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 13 AND `id_gejala` = 32);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 13, 33 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 13 AND `id_gejala` = 33);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 14, 7 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 14 AND `id_gejala` = 7);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 14, 9 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 14 AND `id_gejala` = 9);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 14, 34 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 14 AND `id_gejala` = 34);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 15, 8 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 15 AND `id_gejala` = 8);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 15, 13 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 15 AND `id_gejala` = 13);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 15, 35 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 15 AND `id_gejala` = 35);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 16, 17 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 16 AND `id_gejala` = 17);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 16, 36 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 16 AND `id_gejala` = 36);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 16, 39 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 16 AND `id_gejala` = 39);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 17, 9 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 17 AND `id_gejala` = 9);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 17, 37 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 17 AND `id_gejala` = 37);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 17, 40 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 17 AND `id_gejala` = 40);

INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 18, 34 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 18 AND `id_gejala` = 34);
INSERT INTO `rule_detail` (`id_rule`, `id_gejala`) SELECT 18, 37 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `rule_detail` WHERE `id_rule` = 18 AND `id_gejala` = 37);

-- ============================================================================
-- CATATAN: SCRIPT DI ATAS AMAN DI-IMPORT BERKALI-KALI TANPA ERROR ATAU DUPLIKASI
-- ============================================================================
