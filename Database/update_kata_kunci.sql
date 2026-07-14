-- =====================================================
-- Script SQL: Update Tabel Gejala
-- Menambahkan kolom kata_kunci untuk pencarian chat
-- =====================================================

USE db_sistem_pakar_gontory;

-- Tambahkan kolom kata_kunci ke tabel gejala hanya jika belum ada
SET @kata_kunci_exists := (
	SELECT COUNT(*)
	FROM information_schema.COLUMNS
	WHERE TABLE_SCHEMA = 'db_sistem_pakar_gontory'
	  AND TABLE_NAME = 'gejala'
	  AND COLUMN_NAME = 'kata_kunci'
);

SET @sql_add_kata_kunci := IF(
	@kata_kunci_exists = 0,
	'ALTER TABLE gejala ADD COLUMN kata_kunci TEXT AFTER nama_gejala',
	'SELECT 1'
);

PREPARE stmt_add_kata_kunci FROM @sql_add_kata_kunci;
EXECUTE stmt_add_kata_kunci;
DEALLOCATE PREPARE stmt_add_kata_kunci;

-- =====================================================
-- Update data kata kunci untuk setiap gejala
-- Kata kunci asli + Kata kunci kombinasi 2-3 gejala
-- =====================================================

-- G001: Komputer tidak menyala (kata kunci asli + kombinasi dengan G002)
UPDATE gejala SET kata_kunci = 'tidak menyala, mati total, tidak bisa nyala, mati sama sekali, tidak hidup, komputer mati, pc mati, komputer saya tidak menyala, pc saya tidak menyala, unit cpu tidak menyala, tidak ada tanda kehidupan, mati dan tidak ada lampu, mati total lampu mati, komputer saya tidak menyala, lampu power juga tidak hidup sama sekali' WHERE id_gejala = 1;

-- G002: Lampu power tidak menyala (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'lampu power, lampu indikator, led mati, indikator tidak menyala, lampu mati semua, indikator power mati, lampu power tidak menyala, lampu indikator power mati, indikator power tidak hidup, led indikator mati total, lampu depan mati, komputer saya tidak menyala, lampu power juga tidak hidup sama sekali' WHERE id_gejala = 2;

-- G003: Bunyi beep (kata kunci asli + kombinasi dengan G004, G005)
UPDATE gejala SET kata_kunci = 'bunyi beep, beep berulang, bunyi tut, beep berbunyi, beep dan layar hitam, beep tidak ada tampilan, beep kipas nyala, beep nyala tapi gelap, komputer bunyi beep, komputer beep terus, bunyi beep saat dinyalakan, beep berkali-kali, motherboard bunyi beep, komputer saya tidak menyala lalu terdengar bunyi beep berulang saat dinyalakan' WHERE id_gejala = 3;

-- G004: Layar hitam (kata kunci asli + kombinasi dengan G003, G005)
UPDATE gejala SET kata_kunci = 'tidak ada tampilan, layar hitam, no display, layar mati, monitor hitam, tidak tampil, nyala tapi gelap, hidup layar hitam, bunyi beep layar hitam, kipas nyala layar hitam, nyala tapi tidak ada gambar, monitor tidak menampilkan gambar, pc hidup tapi layar kosong, monitor gelap, tidak muncul gambar, komputer menyala tapi monitor hitam, layar tidak ada gambar, komputer saya tidak menyala dan monitor hanya menampilkan layar hitam, komputer bunyi beep, layar hitam, sementara kipas masih berputar' WHERE id_gejala = 4;

-- G005: Kipas berputar tapi tidak POST (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'kipas berputar, fan nyala, tidak post, no post, nyala tapi tidak booting, hidup tapi tidak masuk bios, kipas jalan layar hitam, fan muter tapi gelap, kipas muter, kipas hidup tapi tidak booting, komputer nyala tapi tidak masuk bios, cpu hidup tapi layar hitam, komputer bunyi beep, layar hitam, sementara kipas masih berputar' WHERE id_gejala = 5;

-- G006: Restart sendiri (kata kunci asli + kombinasi dengan G007, G016)
UPDATE gejala SET kata_kunci = 'restart sendiri, restart otomatis, nyala mati sendiri, restart terus, restart berulang, nyala sebentar mati lagi, restart dan panas, restart blue screen, mati hidup mati hidup, restart terus menerus, komputer restart sendiri, pc restart terus, komputer menyala lalu restart, hidup sebentar lalu mati lagi, komputer saya sering restart sendiri lalu tiba-tiba muncul blue screen, komputer restart terus dan terasa panas sekali saat dipakai' WHERE id_gejala = 6;

-- G007: Blue Screen (kata kunci asli + kombinasi dengan G006)
UPDATE gejala SET kata_kunci = 'blue screen, bsod, layar biru, blue screen restart, bsod terus menerus, error biru, layar biru restart, bsod dan restart, komputer blue screen, muncul layar biru, error bsod, komputer saya sering restart sendiri lalu tiba-tiba muncul blue screen' WHERE id_gejala = 7;

-- G008: Lambat (kata kunci asli + kombinasi dengan G009)
UPDATE gejala SET kata_kunci = 'lambat, lemot, lelet, hang, sering hang, lambat dan freeze, lemot aplikasi macet, kinerja menurun, performa lemot, komputer lemot, komputer sangat lambat, pc terasa berat, komputer terasa lemot dan beberapa aplikasi jadi not responding' WHERE id_gejala = 8;

-- G009: Not responding (kata kunci asli + kombinasi dengan G008)
UPDATE gejala SET kata_kunci = 'not responding, aplikasi freeze, program macet, aplikasi tidak merespon, sering freeze, lambat dan freeze, aplikasi sering macet, aplikasi tidak bisa dibuka, program tidak merespon, komputer sering freeze, komputer terasa lemot dan beberapa aplikasi jadi not responding' WHERE id_gejala = 9;

-- G010: Hardisk bunyi (kata kunci asli + kombinasi dengan G011, G012)
UPDATE gejala SET kata_kunci = 'hardisk bunyi, hdd bunyi, bunyi klik, klik klik, hardisk klik, bunyi aneh dan tidak boot, bunyi klik tidak bisa booting, hardisk berbunyi tidak boot, klik klik os not found, hardisk bunyi klik terus, hardisk seperti berdecit, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device' WHERE id_gejala = 10;

-- G011: Tidak bisa booting (kata kunci asli + kombinasi dengan G010, G012, G013)
UPDATE gejala SET kata_kunci = 'tidak bisa booting, gagal boot, tidak masuk windows, booting error, hardisk bunyi tidak boot, loading lama tidak masuk, stuck di logo, tidak bisa masuk windows, booting gagal terus, komputer gagal boot, windows tidak mau masuk, komputer gagal boot karena proses loading windows terlalu lama, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device' WHERE id_gejala = 11;

-- G012: OS Not Found (kata kunci asli + kombinasi dengan G010, G011)
UPDATE gejala SET kata_kunci = 'operating system not found, os not found, sistem operasi tidak ditemukan, hardisk tidak terdeteksi, boot device not found, no bootable device, os hilang, komputer no bootable device, tidak ada sistem operasi, hardisk bunyi klik terus, komputer gagal boot, dan akhirnya muncul no bootable device' WHERE id_gejala = 12;

-- G013: Loading lama (kata kunci asli + kombinasi dengan G011, G014)
UPDATE gejala SET kata_kunci = 'loading lama, windows lama, booting lama, loading lama dan hang, startup lambat sekali, lama masuk windows, lama banget loadingnya, booting lambat sekali, komputer lama sekali masuk windows, startup sangat lama, komputer gagal boot karena proses loading windows terlalu lama, windows loading lama lalu hang saat masuk ke desktop' WHERE id_gejala = 13;

-- G014: Hang masuk Windows (kata kunci asli + kombinasi dengan G013)
UPDATE gejala SET kata_kunci = 'hang masuk windows, freeze windows, macet windows, loading lama hang, stuck starting windows, macet waktu login, freeze saat masuk windows, windows hang saat startup, komputer macet di logo windows, windows loading lama lalu hang saat masuk ke desktop' WHERE id_gejala = 14;

-- G015: Layar bergaris (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'layar bergaris, garis di layar, monitor bergaris, layar berkedip, artifact di layar, tampilan rusak, glitch layar, garis garis di monitor, monitor muncul garis, gambar di layar pecah, layar bergaris dan komputer terasa panas setelah beberapa menit dipakai' WHERE id_gejala = 15;

-- G016: Panas (kata kunci asli + kombinasi dengan G006, G007)
UPDATE gejala SET kata_kunci = 'panas, overheat, suhu tinggi, kepanasan, panas dan restart, overheat shutdown, panas blue screen, terlalu panas, kepanasan dan mati, panas restart sendiri, komputer terasa sangat panas, kipas berbunyi keras karena panas, komputer restart terus dan terasa panas sekali saat dipakai, layar bergaris dan komputer terasa panas setelah beberapa menit dipakai' WHERE id_gejala = 16;

-- G017: Internet lambat (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'internet lambat, koneksi putus, wifi error, jaringan lambat, putus nyambung, wifi disconnect, internet sering putus, wifi lemot, sinyal internet lemah, komputer susah konek internet, internet lambat sementara usb juga tidak terdeteksi di komputer saya' WHERE id_gejala = 17;

-- G018: USB tidak terdeteksi (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'usb tidak terdeteksi, usb tidak kebaca, flashdisk tidak terbaca, port usb mati, usb device not recognized, usb tidak terdeteksi sama sekali, flashdisk tidak terbaca di pc, usb gagal dikenali, internet lambat sementara usb juga tidak terdeteksi di komputer saya' WHERE id_gejala = 18;

-- G019: Keyboard/Mouse error (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'keyboard error, mouse error, keyboard tidak fungsi, mouse mati, keyboard tidak merespon, mouse tidak gerak, keyboard mouse mati, keyboard tidak terdeteksi, mouse tidak terdeteksi, keyboard tidak terdeteksi dan speaker tidak bunyi sama sekali' WHERE id_gejala = 19;

-- G020: Audio mati (kata kunci asli + kombinasi)
UPDATE gejala SET kata_kunci = 'tidak ada suara, audio mati, speaker mati, suara hilang, no sound, suara tidak keluar, speaker tidak bunyi, komputer tidak ada suara, audio tidak terdengar, keyboard tidak terdeteksi dan speaker tidak bunyi sama sekali' WHERE id_gejala = 20;

-- =====================================================
-- Verifikasi hasil update
-- =====================================================
SELECT id_gejala, kode_gejala, nama_gejala, kata_kunci FROM gejala LIMIT 5;

-- =====================================================
-- SELESAI
-- Kolom kata_kunci telah ditambahkan dan diisi
-- =====================================================
