-- Update Kata Kunci agar jauh lebih natural dan variatif untuk semua gejala (G001 - G022)

-- G001
UPDATE gejala SET kata_kunci = 'tidak menyala, mati total, tidak bisa nyala, mati sama sekali, tidak hidup, komputer mati, pc mati, tidak ada tanda kehidupan, mati dan tidak ada lampu, mati total lampu mati, ga bisa nyala, komputer saya mati, pc saya mati total, laptop mati total, mati mendadak dan ga mau nyala, nggak bisa dihidupkan, tombol power ditekan ga nyala' WHERE kode_gejala = 'G001';

-- G002
UPDATE gejala SET kata_kunci = 'lampu power, lampu indikator, led mati, indikator tidak menyala, lampu mati semua, indikator power mati, lampu di pc mati, led power ga nyala, lampu powernya mati, lampu indikatornya nggak nyala' WHERE kode_gejala = 'G002';

-- G003
UPDATE gejala SET kata_kunci = 'bunyi beep, beep berulang, bunyi tut, beep berbunyi, beep dan layar hitam, beep tidak ada tampilan, beep kipas nyala, beep nyala tapi gelap, bunyi tit tit, pas dinyalain bunyi beep, bunyi beep terus terusan, komputer bunyi aneh pas nyala, ada suara beep panjang' WHERE kode_gejala = 'G003';

-- G004
UPDATE gejala SET kata_kunci = 'tidak ada tampilan, layar hitam, no display, layar mati, monitor hitam, tidak tampil, nyala tapi gelap, hidup layar hitam, bunyi beep layar hitam, kipas nyala layar hitam, nyala tapi tidak ada gambar, monitor ga nyala, layar gelap gulita, blank screen, layar ngga muncul apa apa, ga ada gambar di monitor' WHERE kode_gejala = 'G004';

-- G005
UPDATE gejala SET kata_kunci = 'kipas berputar, fan nyala, tidak post, no post, nyala tapi tidak booting, hidup tapi tidak masuk bios, kipas jalan layar hitam, fan muter tapi gelap, kipas nyala kenceng, kipasnya muter tapi layarnya mati, fan vga muter doang, kipas hidup layar mati' WHERE kode_gejala = 'G005';

-- G006
UPDATE gejala SET kata_kunci = 'restart sendiri, restart otomatis, nyala mati sendiri, restart terus, restart berulang, nyala sebentar mati lagi, restart dan panas, restart blue screen, mati hidup mati hidup, restart terus menerus, komputer suka restart, tiba tiba restart, pc ngerestart sendiri, sering mati sendiri lalu nyala lagi, komputer restart mendadak' WHERE kode_gejala = 'G006';

-- G007
UPDATE gejala SET kata_kunci = 'blue screen, bsod, layar biru, blue screen restart, bsod terus menerus, error biru, layar biru restart, bsod dan restart, tiba tiba layar biru, muncul tulisan di layar biru, bluescreen of death, komputer kena bsod' WHERE kode_gejala = 'G007';

-- G008
UPDATE gejala SET kata_kunci = 'lambat, lemot, lelet, hang, sering hang, lambat dan freeze, lemot aplikasi macet, kinerja menurun, performa lemot, komputer jadi super lemot, buka aplikasi lama banget, windowsnya lelet, lemot parah, sangat lambat saat dipakai, lag banget' WHERE kode_gejala = 'G008';

-- G009
UPDATE gejala SET kata_kunci = 'not responding, aplikasi freeze, program macet, aplikasi tidak merespon, sering freeze, lambat dan freeze, aplikasi sering macet, programnya not responding, layar macet, tiba tiba ngefreeze, aplikasi ditutup paksa, force close, game tiba tiba macet' WHERE kode_gejala = 'G009';

-- G010
UPDATE gejala SET kata_kunci = 'hardisk bunyi, hdd bunyi, bunyi klik, klik klik, hardisk klik, bunyi aneh dan tidak boot, bunyi klik tidak bisa booting, hardisk berbunyi tidak boot, klik klik os not found, ada suara kasar dari dalam pc, hddnya bunyi krek krek, suara cetek cetek, bunyi asing dari hardisk' WHERE kode_gejala = 'G010';

-- G011
UPDATE gejala SET kata_kunci = 'tidak bisa booting, gagal boot, tidak masuk windows, booting error, hardisk bunyi tidak boot, loading lama tidak masuk, stuck di logo, tidak bisa masuk windows, booting gagal terus, gagal loading windows, macet saat booting, berhenti di logo windows, mentok di logo, ga bisa masuk menu utama, gagal masuk os' WHERE kode_gejala = 'G011';

-- G012
UPDATE gejala SET kata_kunci = 'operating system not found, os not found, sistem operasi tidak ditemukan, hardisk tidak terdeteksi, boot device not found, no bootable device, os hilang, tulisan no bootable device, muncul pesan os not found, tidak ada sistem operasi, hdd ga kebaca di bios' WHERE kode_gejala = 'G012';

-- G013
UPDATE gejala SET kata_kunci = 'loading lama, windows lama, booting lama, loading lama dan hang, startup lambat sekali, lama masuk windows, lama banget loadingnya, booting lambat sekali, windows loading lama, proses masuk windows lama, pas dinyalain lama banget, loadingnya muter muter terus, nunggu lama buat masuk desktop' WHERE kode_gejala = 'G013';

-- G014
UPDATE gejala SET kata_kunci = 'hang masuk windows, freeze windows, macet windows, loading lama hang, stuck starting windows, macet waktu login, freeze saat masuk windows, windows loading lama dan freeze saat masuk windows, macet di tampilan awal windows, hang di logo windows, baru masuk windows langsung macet, pas login langsung freeze' WHERE kode_gejala = 'G014';

-- G015
UPDATE gejala SET kata_kunci = 'layar bergaris, garis di layar, monitor bergaris, layar berkedip, artifact di layar, tampilan rusak, glitch layar, garis garis di monitor, layar kedap kedip, muncul garis hijau, layarnya patah patah, gambarnya berbayang, monitor pecah gambarnya, vga artifact' WHERE kode_gejala = 'G015';

-- G016
UPDATE gejala SET kata_kunci = 'panas, overheat, suhu tinggi, kepanasan, panas dan restart, overheat shutdown, panas blue screen, terlalu panas, kepanasan dan mati, panas restart sendiri, casingnya panas banget, suhunya tinggi sekali, komputer cepet panas, processornya overheat, hawanya panas' WHERE kode_gejala = 'G016';

-- G017
UPDATE gejala SET kata_kunci = 'internet lambat, koneksi putus, wifi error, jaringan lambat, putus nyambung, wifi disconnect, internet sering putus, internetnya lemot banget, browsing lambat sekali, koneksi lelet parah, lemot buat buka web, jaringan internet ga stabil' WHERE kode_gejala = 'G017';

-- G018
UPDATE gejala SET kata_kunci = 'usb tidak terdeteksi, usb tidak kebaca, flashdisk tidak terbaca, port usb mati, usb device not recognized, usb tidak terdeteksi sama sekali, colokan usb ga fungsi, pasang flashdisk ga muncul, port usb rusak, usb ga ngerespon' WHERE kode_gejala = 'G018';

-- G019
UPDATE gejala SET kata_kunci = 'keyboard error, mouse error, keyboard tidak fungsi, mouse mati, keyboard tidak merespon, mouse tidak gerak, keyboard mouse mati, ga bisa ngetik, kursor ga jalan, mouse ga bisa digerakin, tombol keyboard rusak, peripheral mati' WHERE kode_gejala = 'G019';

-- G020
UPDATE gejala SET kata_kunci = 'tidak ada suara, audio mati, speaker mati, suara hilang, no sound, suara tidak keluar, speaker tidak bunyi, ga ada suaranya, audionya ga fungsi, speaker eksternal mati, bunyi ga keluar, suara di komputer bisu' WHERE kode_gejala = 'G020';

-- G021
UPDATE gejala SET kata_kunci = 'tidak bisa konek wifi, wifi tidak nyambung, gagal terhubung wifi, wifi silang, jaringan wifi bermasalah, tidak bisa terhubung ke wifi, wifi no internet, gabisa connect wifi, icon wifi tanda seru, wifi menolak koneksi' WHERE kode_gejala = 'G021';

-- G022
UPDATE gejala SET kata_kunci = 'internet putus nyambung, koneksi sering putus, wifi sering disconnect, jaringan sering rto, internet mati nyala, koneksi tidak stabil, internet sering terputus, ping besar dan putus, wifi drop terus, jaringan sering ilang' WHERE kode_gejala = 'G022';
