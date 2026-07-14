-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 14, 2026 at 12:46 PM
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
(55, 2, '2026-07-14 11:37:38', 'Kerusakan Tidak Teridentifikasi'),
(56, 2, '2026-07-14 11:38:08', 'Kerusakan Power Supply'),
(57, 2, '2026-07-14 11:39:52', 'Kerusakan Power Supply'),
(58, 2, '2026-07-14 11:51:24', 'Kerusakan Power Supply'),
(59, 2, '2026-07-14 11:51:52', 'Kerusakan RAM (Memory)'),
(60, 2, '2026-07-14 11:52:46', 'Kerusakan Hardisk'),
(61, 2, '2026-07-14 11:53:22', 'Overheating (Panas Berlebih)'),
(62, 2, '2026-07-14 11:53:38', 'Overheating (Panas Berlebih)'),
(63, 2, '2026-07-14 11:53:52', 'Overheating (Panas Berlebih)'),
(64, 2, '2026-07-14 11:54:06', 'Overheating (Panas Berlebih)'),
(65, 2, '2026-07-14 11:54:20', 'Overheating (Panas Berlebih)'),
(66, 3, '2026-07-14 12:20:51', 'Kerusakan Power Supply'),
(67, 3, '2026-07-14 12:21:05', 'Kerusakan Power Supply'),
(68, 3, '2026-07-14 12:22:26', 'Kerusakan Power Supply'),
(69, 3, '2026-07-14 12:22:44', 'Kerusakan Power Supply'),
(70, 3, '2026-07-14 12:24:30', 'Kerusakan Tidak Teridentifikasi'),
(71, 3, '2026-07-14 12:24:38', 'Kerusakan Power Supply'),
(72, 3, '2026-07-14 12:28:02', 'Kerusakan Tidak Teridentifikasi');

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
(90, 55, 18),
(91, 55, 20),
(92, 56, 1),
(93, 56, 2),
(94, 57, 1),
(95, 58, 1),
(96, 59, 3),
(97, 59, 4),
(98, 59, 5),
(99, 60, 10),
(100, 60, 11),
(101, 60, 12),
(102, 61, 16),
(103, 62, 7),
(104, 63, 7),
(105, 64, 7),
(106, 65, 7),
(107, 66, 1),
(108, 66, 18),
(109, 67, 1),
(110, 68, 1),
(111, 69, 1),
(112, 69, 18),
(113, 70, 1),
(114, 70, 18),
(115, 71, 1),
(116, 71, 2),
(117, 72, 1),
(118, 72, 8),
(119, 72, 17);

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
(1, 'G001', 'Komputer tidak bisa menyala sama sekali', 'tidak menyala, mati total, tidak bisa nyala, mati sama sekali, tidak hidup, komputer mati, pc mati, komputer saya tidak menyala, pc saya tidak menyala, unit cpu tidak menyala, tidak ada tanda kehidupan, mati dan tidak ada lampu, mati total lampu mati, komputer saya tidak menyala, lampu power juga tidak hidup sama sekali'),
(2, 'G002', 'Lampu indikator power tidak menyala', 'lampu power, lampu indikator, led mati, indikator tidak menyala, lampu mati semua, indikator power mati, lampu power tidak menyala, lampu indikator power mati, indikator power tidak hidup, led indikator mati total, lampu depan mati, komputer saya tidak menyala, lampu power juga tidak hidup sama sekali'),
(3, 'G003', 'Terdengar bunyi beep berulang saat dinyalakan', 'bunyi beep, beep berulang, bunyi tut, beep berbunyi, beep dan layar hitam, beep tidak ada tampilan, beep kipas nyala, beep nyala tapi gelap, komputer bunyi beep, komputer beep terus, bunyi beep saat dinyalakan, beep berkali-kali, motherboard bunyi beep, komputer saya tidak menyala lalu terdengar bunyi beep berulang saat dinyalakan'),
(4, 'G004', 'Komputer menyala tapi tidak ada tampilan di layar', 'tidak ada tampilan, layar hitam, no display, layar mati, monitor hitam, tidak tampil, nyala tapi gelap, hidup layar hitam, bunyi beep layar hitam, kipas nyala layar hitam, nyala tapi tidak ada gambar, monitor tidak menampilkan gambar, pc hidup tapi layar kosong, monitor gelap, tidak muncul gambar, komputer menyala tapi monitor hitam, layar tidak ada gambar, komputer saya tidak menyala dan monitor hanya menampilkan layar hitam, komputer bunyi beep, layar hitam, sementara kipas masih berputar'),
(5, 'G005', 'Kipas berputar tapi tidak ada POST', 'kipas berputar, fan nyala, tidak post, no post, nyala tapi tidak booting, hidup tapi tidak masuk bios, kipas jalan layar hitam, fan muter tapi gelap, kipas muter, kipas hidup tapi tidak booting, komputer nyala tapi tidak masuk bios, cpu hidup tapi layar hitam, komputer bunyi beep, layar hitam, sementara kipas masih berputar'),
(6, 'G006', 'Komputer sering restart sendiri', 'restart sendiri, restart otomatis, nyala mati sendiri, restart terus, restart berulang, nyala sebentar mati lagi, restart dan panas, restart blue screen, mati hidup mati hidup, restart terus menerus, komputer restart sendiri, pc restart terus, komputer menyala lalu restart, hidup sebentar lalu mati lagi, komputer saya sering restart sendiri lalu tiba-tiba muncul blue screen, komputer restart terus dan terasa panas sekali saat dipakai'),
(7, 'G007', 'Muncul Blue Screen of Death (BSOD)', 'blue screen, bsod, layar biru, blue screen restart, bsod terus menerus, error biru, layar biru restart, bsod dan restart, komputer blue screen, muncul layar biru, error bsod, komputer saya sering restart sendiri lalu tiba-tiba muncul blue screen'),
(8, 'G008', 'Komputer sangat lambat saat digunakan', 'lambat, lemot, lelet, hang, sering hang, lambat dan freeze, lemot aplikasi macet, kinerja menurun, performa lemot, komputer lemot, komputer sangat lambat, pc terasa berat, komputer terasa lemot dan beberapa aplikasi jadi not responding'),
(9, 'G009', 'Aplikasi sering not responding', 'not responding, aplikasi freeze, program macet, aplikasi tidak merespon, sering freeze, lambat dan freeze, aplikasi sering macet, aplikasi tidak bisa dibuka, program tidak merespon, komputer sering freeze, komputer terasa lemot dan beberapa aplikasi jadi not responding'),
(10, 'G010', 'Hardisk berbunyi aneh (klik-klik)', 'hardisk bunyi, hdd bunyi, bunyi klik, klik klik, hardisk klik, bunyi aneh dan tidak boot, bunyi klik tidak bisa booting, hardisk berbunyi tidak boot, klik klik os not found, hardisk bunyi klik terus, hardisk seperti berdecit, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device'),
(11, 'G011', 'Komputer tidak dapat booting ke Windows', 'tidak bisa booting, gagal boot, tidak masuk windows, booting error, hardisk bunyi tidak boot, loading lama tidak masuk, stuck di logo, tidak bisa masuk windows, booting gagal terus, komputer gagal boot, windows tidak mau masuk, komputer gagal boot karena proses loading windows terlalu lama, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device'),
(12, 'G012', 'Muncul pesan \"Operating System Not Found\"', 'operating system not found, os not found, sistem operasi tidak ditemukan, hardisk tidak terdeteksi, boot device not found, no bootable device, os hilang, komputer no bootable device, tidak ada sistem operasi, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device'),
(13, 'G013', 'Windows loading sangat lama', 'loading lama, windows lama, booting lama, loading lama dan hang, startup lambat sekali, lama masuk windows, lama banget loadingnya, booting lambat sekali, komputer lama sekali masuk windows, startup sangat lama, komputer gagal boot karena proses loading windows terlalu lama, windows loading lama lalu hang saat masuk ke desktop'),
(14, 'G014', 'Komputer hang saat masuk Windows', 'hang masuk windows, freeze windows, macet windows, loading lama hang, stuck starting windows, macet waktu login, freeze saat masuk windows, windows hang saat startup, komputer macet di logo windows, windows loading lama lalu hang saat masuk ke desktop'),
(15, 'G015', 'Layar bergaris atau berkedip', 'layar bergaris, garis di layar, monitor bergaris, layar berkedip, artifact di layar, tampilan rusak, glitch layar, garis garis di monitor, monitor muncul garis, gambar di layar pecah, layar bergaris dan komputer terasa panas setelah beberapa menit dipakai'),
(16, 'G016', 'Suhu komputer sangat panas', 'panas, overheat, suhu tinggi, kepanasan, panas dan restart, overheat shutdown, panas blue screen, terlalu panas, kepanasan dan mati, panas restart sendiri, komputer terasa sangat panas, kipas berbunyi keras karena panas, komputer restart terus dan terasa panas sekali saat dipakai, layar bergaris dan komputer terasa panas setelah beberapa menit dipakai'),
(17, 'G017', 'Koneksi internet tidak stabil', 'internet lambat, koneksi putus, wifi error, jaringan lambat, putus nyambung, wifi disconnect, internet sering putus, wifi lemot, sinyal internet lemah, komputer susah konek internet, internet lambat sementara usb juga tidak terdeteksi di komputer saya'),
(18, 'G018', 'USB device tidak terdeteksi', 'usb tidak terdeteksi, usb tidak kebaca, flashdisk tidak terbaca, port usb mati, usb device not recognized, usb tidak terdeteksi sama sekali, flashdisk tidak terbaca di pc, usb gagal dikenali, internet lambat sementara usb juga tidak terdeteksi di komputer saya'),
(19, 'G019', 'Keyboard atau mouse tidak berfungsi', 'keyboard error, mouse error, keyboard tidak fungsi, mouse mati, keyboard tidak merespon, mouse tidak gerak, keyboard mouse mati, keyboard tidak terdeteksi, mouse tidak terdeteksi, keyboard tidak terdeteksi dan speaker tidak bunyi sama sekali'),
(20, 'G020', 'Audio tidak keluar suara', 'tidak ada suara, audio mati, speaker mati, suara hilang, no sound, suara tidak keluar, speaker tidak bunyi, komputer tidak ada suara, audio tidak terdengar, keyboard tidak terdeteksi dan speaker tidak bunyi sama sekali'),
(21, 'G021', 'Tidak dapat terhubung ke jaringan WiFi', NULL),
(22, 'G022', 'Koneksi internet sering terputus', NULL);

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
(3, 'asisten2', '$2y$10$6O4dJmlzHZ4A4bWOtyV6K.Y7sFaJfSkKZLBxWS7xRCqd0ZRlvhSoG', 'asisten_lab', 'Ardhie Firdaus', '2026-04-20 05:37:29');

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
  MODIFY `id_diagnosa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `diagnosa_detail`
--
ALTER TABLE `diagnosa_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rule`
--
ALTER TABLE `rule`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rule_detail`
--
ALTER TABLE `rule_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
