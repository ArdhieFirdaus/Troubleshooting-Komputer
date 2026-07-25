-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2026 at 04:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistem_pakar_gontory`
--

-- --------------------------------------------------------

--
-- Table structure for table `diagnosa`
--

CREATE TABLE `diagnosa` (
  `id_diagnosa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `hasil_kerusakan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnosa`
--

INSERT INTO `diagnosa` (`id_diagnosa`, `id_user`, `tanggal`, `hasil_kerusakan`) VALUES
(1, 2, '2025-12-20 09:33:36', 'Kerusakan Power Supply'),
(2, 2, '2025-12-31 20:21:53', 'Kerusakan Tidak Teridentifikasi'),
(3, 2, '2026-01-07 07:07:40', 'Kerusakan Power Supply'),
(4, 2, '2026-03-08 07:34:21', 'Kerusakan Tidak Teridentifikasi'),
(5, 2, '2026-03-08 07:34:56', 'Kerusakan Tidak Teridentifikasi'),
(6, 2, '2026-03-08 07:35:01', 'Kerusakan Tidak Teridentifikasi'),
(7, 2, '2026-03-08 07:40:17', 'Kerusakan Tidak Teridentifikasi'),
(8, 2, '2026-03-08 07:40:21', 'Kerusakan Power Supply'),
(9, 2, '2026-03-08 07:40:52', 'Kerusakan VGA Card'),
(10, 2, '2026-03-08 07:40:58', 'Kerusakan Tidak Teridentifikasi'),
(11, 2, '2026-03-08 07:42:59', 'Overheating (Panas Berlebih)'),
(12, 2, '2026-03-08 07:44:12', 'Kerusakan Hardisk'),
(13, 2, '2026-03-08 07:44:44', 'Kerusakan Power Supply'),
(14, 2, '2026-03-08 07:45:15', 'Kerusakan Power Supply'),
(15, 2, '2026-03-08 07:45:54', 'Kerusakan VGA Card'),
(16, 2, '2026-03-08 07:46:11', 'Kerusakan Hardisk'),
(17, 2, '2026-03-08 07:46:24', 'Driver atau Software Bermasalah'),
(18, 2, '2026-03-08 07:59:23', 'Kerusakan Power Supply'),
(19, 2, '2026-03-08 08:00:14', 'Kerusakan RAM (Memory)'),
(20, 2, '2026-03-08 08:01:43', 'Sistem Operasi Corrupt'),
(21, 2, '2026-03-08 08:02:33', 'Kerusakan Hardisk'),
(22, 2, '2026-03-08 08:33:09', 'Kerusakan RAM (Memory)'),
(23, 3, '2026-04-20 07:40:02', 'Overheating (Panas Berlebih)'),
(24, 3, '2026-04-20 07:42:50', 'Kerusakan Power Supply'),
(25, 3, '2026-04-20 07:44:34', 'Kerusakan Hardisk'),
(26, 3, '2026-04-20 07:50:24', 'Kerusakan Tidak Teridentifikasi'),
(27, 3, '2026-04-20 09:41:22', 'Kerusakan Power Supply'),
(28, 3, '2026-04-20 09:43:55', 'Overheating (Panas Berlebih)'),
(29, 3, '2026-04-20 11:13:27', 'Kerusakan Tidak Teridentifikasi'),
(30, 3, '2026-04-20 11:14:42', 'Kerusakan Tidak Teridentifikasi'),
(31, 3, '2026-04-20 11:14:53', 'Kerusakan Power Supply'),
(32, 3, '2026-04-20 12:36:12', 'Kerusakan Hardisk'),
(33, 3, '2026-04-20 12:42:01', 'Sistem Operasi Corrupt'),
(34, 3, '2026-04-20 12:42:09', 'Kerusakan Hardisk'),
(35, 3, '2026-05-16 08:03:55', 'Kerusakan Hardisk'),
(36, 3, '2026-05-16 08:05:07', 'Kerusakan Tidak Teridentifikasi'),
(37, 3, '2026-05-16 08:05:58', 'Kerusakan Tidak Teridentifikasi'),
(38, 3, '2026-05-16 08:06:24', 'Kerusakan Power Supply'),
(39, 3, '2026-05-16 08:07:22', 'Overheating (Panas Berlebih)'),
(40, 3, '2026-05-16 08:07:41', 'Overheating (Panas Berlebih)'),
(41, 3, '2026-05-18 15:35:36', 'Kerusakan Power Supply'),
(42, 3, '2026-05-18 15:35:53', 'Kerusakan RAM (Memory)'),
(43, 2, '2026-05-30 07:50:18', 'Kerusakan Tidak Teridentifikasi'),
(44, 2, '2026-05-30 08:26:45', 'Kerusakan Tidak Teridentifikasi'),
(45, 2, '2026-05-30 08:28:07', 'Kerusakan Tidak Teridentifikasi'),
(46, 2, '2026-05-30 08:29:23', 'Overheating (Panas Berlebih)'),
(47, 2, '2026-06-03 05:45:53', 'Kerusakan Tidak Teridentifikasi'),
(48, 2, '2026-06-06 13:17:46', 'Kerusakan Jaringan'),
(49, 3, '2026-06-06 13:18:07', 'Kerusakan Tidak Teridentifikasi'),
(50, 3, '2026-06-06 13:18:28', 'Kerusakan Tidak Teridentifikasi'),
(51, 3, '2026-06-06 13:18:49', 'Kerusakan Jaringan'),
(52, 2, '2026-06-13 09:15:12', 'Kerusakan Jaringan'),
(53, 2, '2026-06-22 08:22:42', 'Kerusakan Power Supply'),
(54, 2, '2026-06-22 08:23:01', 'Kerusakan Tidak Teridentifikasi'),
(55, 2, '2026-07-20 21:19:33', 'Kerusakan RAM (Memory)'),
(56, 2, '2026-07-20 21:20:53', 'Kerusakan Tidak Teridentifikasi'),
(57, 2, '2026-07-25 15:27:16', 'Overheating (Panas Berlebih)'),
(58, 3, '2026-07-25 15:36:57', 'Overheating (Panas Berlebih)'),
(59, 3, '2026-07-25 16:05:49', 'Kerusakan Power Supply'),
(60, 3, '2026-07-25 16:05:53', 'Kerusakan RAM (Memory)'),
(61, 3, '2026-07-25 16:05:55', 'Overheating (Panas Berlebih)'),
(62, 3, '2026-07-25 16:06:01', 'Kerusakan Hardisk'),
(63, 3, '2026-07-25 16:11:15', 'Kerusakan Tidak Teridentifikasi'),
(64, 3, '2026-07-25 16:29:53', 'Kerusakan Power Supply'),
(65, 3, '2026-07-25 16:30:01', 'Kerusakan RAM (Memory)'),
(66, 3, '2026-07-25 16:30:08', 'Kerusakan VGA Card'),
(67, 3, '2026-07-25 16:30:14', 'Kerusakan Hardisk'),
(68, 3, '2026-07-25 16:30:37', 'Sistem Operasi Corrupt'),
(69, 3, '2026-07-25 16:30:44', 'Overheating (Panas Berlebih)'),
(70, 3, '2026-07-25 16:30:52', 'Driver atau Software Bermasalah'),
(71, 3, '2026-07-25 16:31:01', 'Kerusakan Port USB'),
(72, 3, '2026-07-25 16:31:07', 'Kerusakan Tidak Teridentifikasi'),
(73, 3, '2026-07-25 16:31:40', 'Driver atau Software Bermasalah'),
(74, 3, '2026-07-25 16:31:51', 'Kerusakan Tidak Teridentifikasi'),
(75, 3, '2026-07-25 16:32:50', 'Kerusakan Tidak Teridentifikasi'),
(76, 3, '2026-07-25 16:36:39', 'Kerusakan Power Supply'),
(77, 3, '2026-07-25 16:36:48', 'Kerusakan VGA Card'),
(78, 3, '2026-07-25 16:36:58', 'Kerusakan Tidak Teridentifikasi'),
(79, 3, '2026-07-25 16:46:17', 'Kerusakan Power Supply'),
(80, 3, '2026-07-25 16:46:26', 'Kerusakan RAM (Memory)'),
(81, 3, '2026-07-25 16:48:09', 'Kerusakan Port USB'),
(82, 3, '2026-07-25 16:48:18', 'Kerusakan Power Supply'),
(83, 3, '2026-07-25 16:48:34', 'Sistem Operasi Corrupt');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosa_detail`
--

CREATE TABLE `diagnosa_detail` (
  `id` int(11) NOT NULL,
  `id_diagnosa` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnosa_detail`
--

INSERT INTO `diagnosa_detail` (`id`, `id_diagnosa`, `id_gejala`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 13),
(4, 2, 14),
(5, 3, 1),
(6, 3, 2),
(7, 4, 6),
(8, 5, 6),
(9, 6, 6),
(10, 7, 20),
(11, 8, 1),
(12, 9, 4),
(13, 10, 6),
(14, 11, 6),
(15, 12, 10),
(16, 13, 1),
(17, 14, 1),
(18, 15, 15),
(19, 16, 12),
(20, 17, 9),
(21, 18, 1),
(22, 18, 2),
(23, 19, 3),
(24, 19, 4),
(25, 20, 8),
(26, 20, 11),
(27, 20, 13),
(28, 20, 14),
(29, 21, 10),
(30, 21, 11),
(31, 21, 12),
(32, 22, 4),
(33, 23, 6),
(34, 24, 1),
(35, 24, 2),
(36, 25, 11),
(37, 25, 12),
(38, 26, 9),
(39, 26, 10),
(40, 27, 1),
(41, 27, 2),
(42, 28, 6),
(43, 29, 1),
(44, 29, 3),
(45, 30, 2),
(46, 30, 4),
(47, 31, 1),
(48, 32, 10),
(49, 33, 11),
(50, 33, 13),
(51, 34, 11),
(52, 35, 10),
(53, 36, 15),
(54, 37, 2),
(55, 37, 10),
(56, 37, 12),
(57, 37, 14),
(58, 37, 16),
(59, 38, 1),
(60, 39, 6),
(61, 39, 7),
(62, 39, 16),
(63, 40, 6),
(64, 41, 1),
(65, 41, 2),
(66, 42, 4),
(67, 43, 1),
(68, 43, 4),
(69, 44, 16),
(70, 45, 8),
(71, 46, 6),
(72, 47, 1),
(73, 47, 3),
(74, 48, 17),
(75, 48, 21),
(76, 48, 22),
(77, 49, 17),
(78, 50, 17),
(79, 50, 21),
(80, 51, 17),
(81, 51, 21),
(82, 51, 22),
(83, 52, 17),
(84, 52, 21),
(85, 52, 22),
(86, 53, 1),
(87, 53, 2),
(88, 54, 1),
(89, 54, 4),
(90, 55, 3),
(91, 55, 4),
(92, 55, 5),
(93, 56, 1),
(94, 56, 18),
(95, 57, 6),
(96, 57, 7),
(97, 57, 16),
(98, 58, 6),
(99, 58, 7),
(100, 58, 16),
(101, 59, 1),
(102, 60, 4),
(103, 61, 6),
(104, 62, 10),
(105, 63, 11),
(106, 63, 13),
(107, 64, 1),
(108, 65, 3),
(109, 66, 15),
(110, 67, 10),
(111, 68, 13),
(112, 69, 6),
(113, 70, 8),
(114, 71, 18),
(115, 72, 8),
(116, 72, 17),
(117, 73, 8),
(118, 74, 8),
(119, 74, 17),
(120, 75, 8),
(121, 75, 17),
(122, 76, 1),
(123, 76, 2),
(124, 77, 4),
(125, 77, 15),
(126, 78, 13),
(127, 78, 14),
(128, 79, 1),
(129, 80, 3),
(130, 81, 18),
(131, 82, 1),
(132, 82, 2),
(133, 83, 13);

-- --------------------------------------------------------

--
-- Table structure for table `gejala`
--

CREATE TABLE `gejala` (
  `id_gejala` int(11) NOT NULL,
  `kode_gejala` varchar(10) NOT NULL,
  `nama_gejala` text NOT NULL,
  `kata_kunci` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gejala`
--

INSERT INTO `gejala` (`id_gejala`, `kode_gejala`, `nama_gejala`, `kata_kunci`) VALUES
(1, 'G001', 'Komputer tidak bisa menyala sama sekali', 'tidak menyala, mati total, tidak bisa nyala, mati sama sekali, tidak hidup, komputer mati, pc mati, tidak ada tanda kehidupan, mati dan tidak ada lampu, mati total lampu mati, ga bisa nyala, komputer saya mati, pc saya mati total, laptop mati total, mati mendadak dan ga mau nyala, nggak bisa dihidupkan, tombol power ditekan ga nyala'),
(2, 'G002', 'Lampu indikator power tidak menyala', 'lampu power, lampu indikator, led mati, indikator tidak menyala, lampu mati semua, indikator power mati, lampu di pc mati, led power ga nyala, lampu powernya mati, lampu indikatornya nggak nyala'),
(3, 'G003', 'Terdengar bunyi beep berulang saat dinyalakan', 'bunyi beep, beep berulang, bunyi tut, beep berbunyi, beep dan layar hitam, beep tidak ada tampilan, beep kipas nyala, beep nyala tapi gelap, bunyi tit tit, pas dinyalain bunyi beep, bunyi beep terus terusan, komputer bunyi aneh pas nyala, ada suara beep panjang'),
(4, 'G004', 'Komputer menyala tapi tidak ada tampilan di layar', 'tidak ada tampilan, layar hitam, no display, layar mati, monitor hitam, tidak tampil, nyala tapi gelap, hidup layar hitam, bunyi beep layar hitam, kipas nyala layar hitam, nyala tapi tidak ada gambar, monitor ga nyala, layar gelap gulita, blank screen, layar ngga muncul apa apa, ga ada gambar di monitor'),
(5, 'G005', 'Kipas berputar tapi tidak ada POST', 'kipas berputar, fan nyala, tidak post, no post, nyala tapi tidak booting, hidup tapi tidak masuk bios, kipas jalan layar hitam, fan muter tapi gelap, kipas nyala kenceng, kipasnya muter tapi layarnya mati, fan vga muter doang, kipas hidup layar mati'),
(6, 'G006', 'Komputer sering restart sendiri', 'restart sendiri, restart otomatis, nyala mati sendiri, restart terus, restart berulang, nyala sebentar mati lagi, restart dan panas, restart blue screen, mati hidup mati hidup, restart terus menerus, komputer suka restart, tiba tiba restart, pc ngerestart sendiri, sering mati sendiri lalu nyala lagi, komputer restart mendadak'),
(7, 'G007', 'Muncul Blue Screen of Death (BSOD)', 'blue screen, bsod, layar biru, blue screen restart, bsod terus menerus, error biru, layar biru restart, bsod dan restart, tiba tiba layar biru, muncul tulisan di layar biru, bluescreen of death, komputer kena bsod'),
(8, 'G008', 'Komputer sangat lambat saat digunakan', 'lambat, lemot, lelet, hang, sering hang, lambat dan freeze, lemot aplikasi macet, kinerja menurun, performa lemot, komputer jadi super lemot, buka aplikasi lama banget, windowsnya lelet, lemot parah, sangat lambat saat dipakai, lag banget'),
(9, 'G009', 'Aplikasi sering not responding', 'not responding, aplikasi freeze, program macet, aplikasi tidak merespon, sering freeze, lambat dan freeze, aplikasi sering macet, programnya not responding, layar macet, tiba tiba ngefreeze, aplikasi ditutup paksa, force close, game tiba tiba macet'),
(10, 'G010', 'Hardisk berbunyi aneh (klik-klik)', 'hardisk bunyi, hdd bunyi, bunyi klik, klik klik, hardisk klik, bunyi aneh dan tidak boot, bunyi klik tidak bisa booting, hardisk berbunyi tidak boot, klik klik os not found, ada suara kasar dari dalam pc, hddnya bunyi krek krek, suara cetek cetek, bunyi asing dari hardisk'),
(11, 'G011', 'Komputer tidak dapat booting ke Windows', 'tidak bisa booting, gagal boot, tidak masuk windows, booting error, hardisk bunyi tidak boot, loading lama tidak masuk, stuck di logo, tidak bisa masuk windows, booting gagal terus, gagal loading windows, macet saat booting, berhenti di logo windows, mentok di logo, ga bisa masuk menu utama, gagal masuk os'),
(12, 'G012', 'Muncul pesan \"Operating System Not Found\"', 'operating system not found, os not found, sistem operasi tidak ditemukan, hardisk tidak terdeteksi, boot device not found, no bootable device, os hilang, tulisan no bootable device, muncul pesan os not found, tidak ada sistem operasi, hdd ga kebaca di bios'),
(13, 'G013', 'Windows loading sangat lama', 'loading lama, windows lama, booting lama, loading lama dan hang, startup lambat sekali, lama masuk windows, lama banget loadingnya, booting lambat sekali, windows loading lama, proses masuk windows lama, pas dinyalain lama banget, loadingnya muter muter terus, nunggu lama buat masuk desktop'),
(14, 'G014', 'Komputer hang saat masuk Windows', 'hang masuk windows, freeze windows, macet windows, loading lama hang, stuck starting windows, macet waktu login, freeze saat masuk windows, windows loading lama dan freeze saat masuk windows, macet di tampilan awal windows, hang di logo windows, baru masuk windows langsung macet, pas login langsung freeze'),
(15, 'G015', 'Layar bergaris atau berkedip', 'layar bergaris, garis di layar, monitor bergaris, layar berkedip, artifact di layar, tampilan rusak, glitch layar, garis garis di monitor, layar kedap kedip, muncul garis hijau, layarnya patah patah, gambarnya berbayang, monitor pecah gambarnya, vga artifact'),
(16, 'G016', 'Suhu komputer sangat panas', 'panas, overheat, suhu tinggi, kepanasan, panas dan restart, overheat shutdown, panas blue screen, terlalu panas, kepanasan dan mati, panas restart sendiri, casingnya panas banget, suhunya tinggi sekali, komputer cepet panas, processornya overheat, hawanya panas'),
(17, 'G017', 'Koneksi internet tidak stabil', 'internet lambat, koneksi putus, wifi error, jaringan lambat, putus nyambung, wifi disconnect, internet sering putus, internetnya lemot banget, browsing lambat sekali, koneksi lelet parah, lemot buat buka web, jaringan internet ga stabil'),
(18, 'G018', 'USB device tidak terdeteksi', 'usb tidak terdeteksi, usb tidak kebaca, flashdisk tidak terbaca, port usb mati, usb device not recognized, usb tidak terdeteksi sama sekali, colokan usb ga fungsi, pasang flashdisk ga muncul, port usb rusak, usb ga ngerespon'),
(19, 'G019', 'Keyboard atau mouse tidak berfungsi', 'keyboard error, mouse error, keyboard tidak fungsi, mouse mati, keyboard tidak merespon, mouse tidak gerak, keyboard mouse mati, ga bisa ngetik, kursor ga jalan, mouse ga bisa digerakin, tombol keyboard rusak, peripheral mati'),
(20, 'G020', 'Audio tidak keluar suara', 'tidak ada suara, audio mati, speaker mati, suara hilang, no sound, suara tidak keluar, speaker tidak bunyi, ga ada suaranya, audionya ga fungsi, speaker eksternal mati, bunyi ga keluar, suara di komputer bisu'),
(21, 'G021', 'Tidak dapat terhubung ke jaringan WiFi', 'tidak bisa konek wifi, wifi tidak nyambung, gagal terhubung wifi, wifi silang, jaringan wifi bermasalah, tidak bisa terhubung ke wifi, wifi no internet, gabisa connect wifi, icon wifi tanda seru, wifi menolak koneksi'),
(22, 'G022', 'Koneksi internet sering terputus', 'internet putus nyambung, koneksi sering putus, wifi sering disconnect, jaringan sering rto, internet mati nyala, koneksi tidak stabil, internet sering terputus, ping besar dan putus, wifi drop terus, jaringan sering ilang');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `id_kerusakan` int(11) NOT NULL,
  `kode_kerusakan` varchar(10) NOT NULL,
  `nama_kerusakan` text NOT NULL,
  `solusi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kerusakan`
--

INSERT INTO `kerusakan` (`id_kerusakan`, `kode_kerusakan`, `nama_kerusakan`, `solusi`) VALUES
(1, 'K001', 'Kerusakan Power Supply', '1. Cek kabel power apakah terpasang dengan benar\r\n2. Test power supply dengan PSU tester\r\n3. Ganti power supply jika rusak\r\n4. Periksa saklar power di belakang PSU'),
(2, 'K002', 'Kerusakan RAM (Memory)', '1. Buka casing dan bersihkan slot RAM dengan kuas halus\r\n2. Lepas dan pasang kembali RAM dengan benar\r\n3. Test RAM satu per satu jika menggunakan lebih dari satu\r\n4. Ganti RAM jika masih bermasalah\r\n5. Coba pindahkan RAM ke slot yang berbeda'),
(3, 'K003', 'Kerusakan VGA Card', '1. Bersihkan slot VGA dan konektor VGA\r\n2. Pastikan VGA terpasang dengan benar\r\n3. Cek kabel monitor ke VGA\r\n4. Test dengan VGA lain jika memungkinkan\r\n5. Update driver VGA atau ganti VGA jika rusak'),
(4, 'K004', 'Kerusakan Hardisk', '1. Backup data segera jika masih bisa diakses\r\n2. Cek kabel SATA/IDE hardisk\r\n3. Scan hardisk dengan tools seperti HD Tune\r\n4. Perbaiki bad sector dengan HDD Regenerator\r\n5. Ganti hardisk jika kerusakan parah'),
(5, 'K005', 'Sistem Operasi Corrupt', '1. Repair Windows menggunakan installation media\r\n2. Gunakan System Restore ke titik sebelumnya\r\n3. Jalankan SFC /scannow di Command Prompt\r\n4. Install ulang Windows jika tidak bisa diperbaiki\r\n5. Backup data penting terlebih dahulu'),
(6, 'K006', 'Overheating (Panas Berlebih)', '1. Bersihkan debu di dalam casing dengan air duster\r\n2. Periksa dan bersihkan heatsink processor\r\n3. Ganti thermal paste processor\r\n4. Pastikan semua kipas berfungsi dengan baik\r\n5. Tambah kipas casing jika perlu\r\n6. Periksa ventilasi udara'),
(7, 'K007', 'Driver atau Software Bermasalah', '1. Update driver ke versi terbaru\r\n2. Uninstall dan install ulang driver\r\n3. Gunakan Driver Booster untuk update otomatis\r\n4. Rollback driver ke versi sebelumnya jika masalah muncul setelah update\r\n5. Scan sistem dengan antivirus'),
(8, 'K008', 'Kerusakan Port USB', '1. Coba port USB yang berbeda\r\n2. Update driver USB Controller\r\n3. Disable dan enable kembali USB Controller di Device Manager\r\n4. Cek di BIOS apakah USB diaktifkan\r\n5. Gunakan USB hub eksternal jika port rusak'),
(9, 'K009', 'Kerusakan Jaringan', '1. Periksa koneksi kabel LAN atau jaringan WiFi yang digunakan\r\n2. Pastikan komputer terhubung ke jaringan yang benar\r\n3. Restart router atau access point jika koneksi tidak stabil\r\n4. Periksa pengaturan IP Address, Gateway, dan DNS pada komputer\r\n5. Update atau instal ulang driver network adapter\r\n6. Disable dan enable kembali adapter jaringan melalui Device Manager\r\n7. Hubungi teknisi jaringan apabila koneksi tetap bermasalah');

-- --------------------------------------------------------

--
-- Table structure for table `rule`
--

CREATE TABLE `rule` (
  `id_rule` int(11) NOT NULL,
  `id_kerusakan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule`
--

INSERT INTO `rule` (`id_rule`, `id_kerusakan`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `rule_detail`
--

CREATE TABLE `rule_detail` (
  `id` int(11) NOT NULL,
  `id_rule` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule_detail`
--

INSERT INTO `rule_detail` (`id`, `id_rule`, `id_gejala`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(6, 3, 4),
(7, 3, 15),
(8, 4, 10),
(9, 4, 11),
(10, 4, 12),
(11, 5, 11),
(12, 5, 13),
(13, 5, 14),
(14, 6, 6),
(15, 6, 7),
(16, 6, 16),
(17, 7, 8),
(18, 7, 9),
(19, 7, 20),
(20, 8, 18),
(21, 8, 19),
(22, 9, 17),
(23, 9, 21),
(24, 9, 22);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','asisten_lab') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `created_at`) VALUES
(1, 'admin', '$2y$10$Fn7a6kAfLye3N67eK1PIzONpyB/XykAZRl98VUd9bT9bPlvhJuqKi', 'admin', 'Ketua Lab Komputer', '2025-12-20 08:29:33'),
(2, 'asisten1', '$2y$10$Byb14JfLf.8QSlfH5NKYBe.Osb7qEEC5n1CipsV0UcPnm1nAeUgGa', 'asisten_lab', 'Ahmad Fauzi', '2025-12-20 08:29:33'),
(3, 'asisten2', '$2y$10$6O4dJmlzHZ4A4bWOtyV6K.Y7sFaJfSkKZLBxWS7xRCqd0ZRlvhSoG', 'asisten_lab', 'Ardhie Firdaus', '2026-04-20 05:37:29'),
(4, 'asisten3', '$2y$10$jfIq2hF/Xaf8ainMQ7ebOuVKf8uOnVpSNqUmGCwPYqBT8PZ0nCKq6', 'asisten_lab', 'Yono Yatno', '2026-07-25 13:21:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diagnosa`
--
ALTER TABLE `diagnosa`
  ADD PRIMARY KEY (`id_diagnosa`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `diagnosa_detail`
--
ALTER TABLE `diagnosa_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_diagnosa` (`id_diagnosa`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`id_kerusakan`);

--
-- Indexes for table `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`id_rule`),
  ADD KEY `id_kerusakan` (`id_kerusakan`);

--
-- Indexes for table `rule_detail`
--
ALTER TABLE `rule_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rule` (`id_rule`),
  ADD KEY `id_gejala` (`id_gejala`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diagnosa`
--
ALTER TABLE `diagnosa`
  MODIFY `id_diagnosa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `diagnosa_detail`
--
ALTER TABLE `diagnosa_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rule`
--
ALTER TABLE `rule`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rule_detail`
--
ALTER TABLE `rule_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnosa`
--
ALTER TABLE `diagnosa`
  ADD CONSTRAINT `diagnosa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `diagnosa_detail`
--
ALTER TABLE `diagnosa_detail`
  ADD CONSTRAINT `diagnosa_detail_ibfk_1` FOREIGN KEY (`id_diagnosa`) REFERENCES `diagnosa` (`id_diagnosa`) ON DELETE CASCADE,
  ADD CONSTRAINT `diagnosa_detail_ibfk_2` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`) ON DELETE CASCADE;

--
-- Constraints for table `rule`
--
ALTER TABLE `rule`
  ADD CONSTRAINT `rule_ibfk_1` FOREIGN KEY (`id_kerusakan`) REFERENCES `kerusakan` (`id_kerusakan`) ON DELETE CASCADE;

--
-- Constraints for table `rule_detail`
--
ALTER TABLE `rule_detail`
  ADD CONSTRAINT `rule_detail_ibfk_1` FOREIGN KEY (`id_rule`) REFERENCES `rule` (`id_rule`) ON DELETE CASCADE,
  ADD CONSTRAINT `rule_detail_ibfk_2` FOREIGN KEY (`id_gejala`) REFERENCES `gejala` (`id_gejala`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
