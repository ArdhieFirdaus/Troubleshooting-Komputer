-- =====================================================
-- Database: db_sistem_pakar_gontory
-- Sistem Pakar Troubleshooting Komputer
-- Pondok Pesantren Al-Gontory
-- =====================================================

-- Hapus database jika sudah ada
DROP DATABASE IF EXISTS db_sistem_pakar_gontory;

-- Buat database baru
CREATE DATABASE db_sistem_pakar_gontory;

-- Gunakan database
USE db_sistem_pakar_gontory;

-- =====================================================
-- Tabel: users
-- Menyimpan data pengguna sistem (admin & asisten lab)
-- =====================================================
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'asisten_lab') NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: gejala
-- Menyimpan data gejala kerusakan komputer
-- =====================================================
CREATE TABLE gejala (
    id_gejala INT PRIMARY KEY AUTO_INCREMENT,
    kode_gejala VARCHAR(10) NOT NULL,
    nama_gejala TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: kerusakan
-- Menyimpan data kerusakan dan solusinya
-- =====================================================
CREATE TABLE kerusakan (
    id_kerusakan INT PRIMARY KEY AUTO_INCREMENT,
    kode_kerusakan VARCHAR(10) NOT NULL,
    nama_kerusakan TEXT NOT NULL,
    solusi TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: rule
-- Menyimpan rule untuk forward chaining
-- =====================================================
CREATE TABLE rule (
    id_rule INT PRIMARY KEY AUTO_INCREMENT,
    id_kerusakan INT NOT NULL,
    FOREIGN KEY (id_kerusakan) REFERENCES kerusakan(id_kerusakan) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: rule_detail
-- Menyimpan detail gejala untuk setiap rule
-- =====================================================
CREATE TABLE rule_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rule INT NOT NULL,
    id_gejala INT NOT NULL,
    FOREIGN KEY (id_rule) REFERENCES rule(id_rule) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: diagnosa
-- Menyimpan data hasil diagnosa
-- =====================================================
CREATE TABLE diagnosa (
    id_diagnosa INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    tanggal DATETIME NOT NULL,
    hasil_kerusakan TEXT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabel: diagnosa_detail
-- Menyimpan detail gejala yang dipilih saat diagnosa
-- =====================================================
CREATE TABLE diagnosa_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_diagnosa INT NOT NULL,
    id_gejala INT NOT NULL,
    FOREIGN KEY (id_diagnosa) REFERENCES diagnosa(id_diagnosa) ON DELETE CASCADE,
    FOREIGN KEY (id_gejala) REFERENCES gejala(id_gejala) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DATA DUMMY: users
-- Password plain: admin123 & asisten123
-- =====================================================
INSERT INTO users (username, password, role, nama_lengkap) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Ketua Lab Komputer'),
('asisten1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'asisten_lab', 'Ahmad Fauzi');

-- Password untuk kedua user di atas: password (hashed dengan password_hash())
-- Untuk testing: username: admin / password: password
--                username: asisten1 / password: password

-- =====================================================
-- DATA DUMMY: gejala
-- Gejala-gejala umum troubleshooting komputer
-- =====================================================
INSERT INTO gejala (kode_gejala, nama_gejala) VALUES
('G001', 'Komputer tidak bisa menyala sama sekali'),
('G002', 'Lampu indikator power tidak menyala'),
('G003', 'Terdengar bunyi beep berulang saat dinyalakan'),
('G004', 'Komputer menyala tapi tidak ada tampilan di layar'),
('G005', 'Kipas berputar tapi tidak ada POST'),
('G006', 'Komputer sering restart sendiri'),
('G007', 'Muncul Blue Screen of Death (BSOD)'),
('G008', 'Komputer sangat lambat saat digunakan'),
('G009', 'Aplikasi sering not responding'),
('G010', 'Hardisk berbunyi aneh (klik-klik)'),
('G011', 'Komputer tidak dapat booting ke Windows'),
('G012', 'Muncul pesan "Operating System Not Found"'),
('G013', 'Windows loading sangat lama'),
('G014', 'Komputer hang saat masuk Windows'),
('G015', 'Layar bergaris atau berkedip'),
('G016', 'Suhu komputer sangat panas'),
('G017', 'Koneksi internet tidak stabil'),
('G018', 'USB device tidak terdeteksi'),
('G019', 'Keyboard atau mouse tidak berfungsi'),
('G020', 'Audio tidak keluar suara');

-- =====================================================
-- DATA DUMMY: kerusakan
-- Jenis kerusakan dan solusinya
-- =====================================================
INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, solusi) VALUES
('K001', 'Kerusakan Power Supply', 
    '1. Cek kabel power apakah terpasang dengan benar
2. Test power supply dengan PSU tester
3. Ganti power supply jika rusak
4. Periksa saklar power di belakang PSU'),

('K002', 'Kerusakan RAM (Memory)',
    '1. Buka casing dan bersihkan slot RAM dengan kuas halus
2. Lepas dan pasang kembali RAM dengan benar
3. Test RAM satu per satu jika menggunakan lebih dari satu
4. Ganti RAM jika masih bermasalah
5. Coba pindahkan RAM ke slot yang berbeda'),

('K003', 'Kerusakan VGA Card',
    '1. Bersihkan slot VGA dan konektor VGA
2. Pastikan VGA terpasang dengan benar
3. Cek kabel monitor ke VGA
4. Test dengan VGA lain jika memungkinkan
5. Update driver VGA atau ganti VGA jika rusak'),

('K004', 'Kerusakan Hardisk',
    '1. Backup data segera jika masih bisa diakses
2. Cek kabel SATA/IDE hardisk
3. Scan hardisk dengan tools seperti HD Tune
4. Perbaiki bad sector dengan HDD Regenerator
5. Ganti hardisk jika kerusakan parah'),

('K005', 'Sistem Operasi Corrupt',
    '1. Repair Windows menggunakan installation media
2. Gunakan System Restore ke titik sebelumnya
3. Jalankan SFC /scannow di Command Prompt
4. Install ulang Windows jika tidak bisa diperbaiki
5. Backup data penting terlebih dahulu'),

('K006', 'Overheating (Panas Berlebih)',
    '1. Bersihkan debu di dalam casing dengan air duster
2. Periksa dan bersihkan heatsink processor
3. Ganti thermal paste processor
4. Pastikan semua kipas berfungsi dengan baik
5. Tambah kipas casing jika perlu
6. Periksa ventilasi udara'),

('K007', 'Driver atau Software Bermasalah',
    '1. Update driver ke versi terbaru
2. Uninstall dan install ulang driver
3. Gunakan Driver Booster untuk update otomatis
4. Rollback driver ke versi sebelumnya jika masalah muncul setelah update
5. Scan sistem dengan antivirus'),

('K008', 'Kerusakan Port USB',
    '1. Coba port USB yang berbeda
2. Update driver USB Controller
3. Disable dan enable kembali USB Controller di Device Manager
4. Cek di BIOS apakah USB diaktifkan
5. Gunakan USB hub eksternal jika port rusak');

-- =====================================================
-- DATA DUMMY: rule
-- Hubungan antara kerusakan dan rule
-- =====================================================
INSERT INTO rule (id_kerusakan) VALUES
(1), -- Rule 1: Kerusakan Power Supply
(2), -- Rule 2: Kerusakan RAM
(3), -- Rule 3: Kerusakan VGA
(4), -- Rule 4: Kerusakan Hardisk
(5), -- Rule 5: Sistem Operasi Corrupt
(6), -- Rule 6: Overheating
(7), -- Rule 7: Driver Bermasalah
(8); -- Rule 8: Kerusakan Port USB

-- =====================================================
-- DATA DUMMY: rule_detail
-- Detail gejala untuk setiap rule (Forward Chaining)
-- =====================================================

-- Rule 1: Power Supply (Gejala: G001, G002)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(1, 1), -- Komputer tidak bisa menyala
(1, 2); -- Lampu indikator tidak menyala

-- Rule 2: Kerusakan RAM (Gejala: G003, G004, G005)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(2, 3), -- Bunyi beep berulang
(2, 4), -- Tidak ada tampilan
(2, 5); -- Kipas berputar tapi tidak POST

-- Rule 3: Kerusakan VGA (Gejala: G004, G015)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(3, 4),  -- Tidak ada tampilan
(3, 15); -- Layar bergaris

-- Rule 4: Kerusakan Hardisk (Gejala: G010, G011, G012)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(4, 10), -- Hardisk bunyi aneh
(4, 11), -- Tidak bisa booting
(4, 12); -- OS Not Found

-- Rule 5: Sistem Operasi Corrupt (Gejala: G011, G013, G014)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(5, 11), -- Tidak bisa booting
(5, 13), -- Windows loading lama
(5, 14); -- Hang saat masuk Windows

-- Rule 6: Overheating (Gejala: G006, G007, G016)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(6, 6),  -- Restart sendiri
(6, 7),  -- BSOD
(6, 16); -- Suhu panas

-- Rule 7: Driver Bermasalah (Gejala: G008, G009, G020)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(7, 8),  -- Komputer lambat
(7, 9),  -- Aplikasi not responding
(7, 20); -- Audio tidak keluar

-- Rule 8: Port USB Rusak (Gejala: G018, G019)
INSERT INTO rule_detail (id_rule, id_gejala) VALUES
(8, 18), -- USB tidak terdeteksi
(8, 19); -- Keyboard/mouse tidak berfungsi

-- =====================================================
-- END OF SQL SCRIPT
-- =====================================================
