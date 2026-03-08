<?php
/**
 * File: proses_chat.php
 * Deskripsi: Memproses input chat user dan melakukan diagnosa forward chaining
 * 
 * ALGORITMA:
 * 1. Terima input chat dari user
 * 2. Cari kata kunci yang cocok di tabel gejala
 * 3. Jalankan forward chaining berdasarkan gejala yang teridentifikasi
 * 4. Return hasil diagnosa dalam format JSON
 */

session_start();
require_once '../Config/koneksi.php';

// Set header JSON
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'asisten_lab') {
    echo json_encode([
        'success' => false,
        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
    ]);
    exit();
}

// Cek apakah ada message dari POST
if (!isset($_POST['message']) || empty(trim($_POST['message']))) {
    echo json_encode([
        'success' => false,
        'message' => 'Pesan tidak boleh kosong.'
    ]);
    exit();
}

$input_user = trim($_POST['message']);
$input_lower = strtolower($input_user); // Convert ke lowercase untuk pencarian

// ==========================================
// STEP 1: IDENTIFIKASI GEJALA DARI INPUT
// ==========================================

$gejala_teridentifikasi = [];

// Ambil semua gejala dari database
$query_gejala = "SELECT * FROM gejala ORDER BY id_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);

if (!$result_gejala) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan database. Silakan coba lagi.'
    ]);
    exit();
}

// Definisi kata kunci untuk setiap gejala (mapping manual)
// Kata kunci asli + Kata kunci kombinasi 2-3 gejala
$kata_kunci_gejala = [
    1 => ['tidak menyala', 'mati total', 'tidak bisa nyala', 'mati sama sekali', 'tidak hidup', 'komputer mati', 'pc mati', 'tidak ada tanda kehidupan', 'mati dan tidak ada lampu', 'mati total lampu mati'],
    2 => ['lampu power', 'lampu indikator', 'led mati', 'indikator tidak menyala', 'lampu mati semua', 'indikator power mati'],
    3 => ['bunyi beep', 'beep berulang', 'bunyi tut', 'beep berbunyi', 'beep dan layar hitam', 'beep tidak ada tampilan', 'beep kipas nyala', 'beep nyala tapi gelap'],
    4 => ['tidak ada tampilan', 'layar hitam', 'no display', 'layar mati', 'monitor hitam', 'tidak tampil', 'nyala tapi gelap', 'hidup layar hitam', 'bunyi beep layar hitam', 'kipas nyala layar hitam', 'nyala tapi tidak ada gambar'],
    5 => ['kipas berputar', 'fan nyala', 'tidak post', 'no post', 'nyala tapi tidak booting', 'hidup tapi tidak masuk bios', 'kipas jalan layar hitam', 'fan muter tapi gelap'],
    6 => ['restart sendiri', 'restart otomatis', 'nyala mati sendiri', 'restart terus', 'restart berulang', 'nyala sebentar mati lagi', 'restart dan panas', 'restart blue screen', 'mati hidup mati hidup', 'restart terus menerus'],
    7 => ['blue screen', 'bsod', 'layar biru', 'blue screen restart', 'bsod terus menerus', 'error biru', 'layar biru restart', 'bsod dan restart'],
    8 => ['lambat', 'lemot', 'lelet', 'hang', 'sering hang', 'lambat dan freeze', 'lemot aplikasi macet', 'kinerja menurun', 'performa lemot'],
    9 => ['not responding', 'aplikasi freeze', 'program macet', 'aplikasi tidak merespon', 'sering freeze', 'lambat dan freeze', 'aplikasi sering macet'],
    10 => ['hardisk bunyi', 'hdd bunyi', 'bunyi klik', 'klik klik', 'hardisk klik', 'bunyi aneh dan tidak boot', 'bunyi klik tidak bisa booting', 'hardisk berbunyi tidak boot', 'klik klik os not found'],
    11 => ['tidak bisa booting', 'gagal boot', 'tidak masuk windows', 'booting error', 'hardisk bunyi tidak boot', 'loading lama tidak masuk', 'stuck di logo', 'tidak bisa masuk windows', 'booting gagal terus'],
    12 => ['operating system not found', 'os not found', 'sistem operasi tidak ditemukan', 'hardisk tidak terdeteksi', 'boot device not found', 'no bootable device', 'os hilang'],
    13 => ['loading lama', 'windows lama', 'booting lama', 'loading lama dan hang', 'startup lambat sekali', 'lama masuk windows', 'lama banget loadingnya', 'booting lambat sekali'],
    14 => ['hang masuk windows', 'freeze windows', 'macet windows', 'loading lama hang', 'stuck starting windows', 'macet waktu login', 'freeze saat masuk windows'],
    15 => ['layar bergaris', 'garis di layar', 'monitor bergaris', 'layar berkedip', 'artifact di layar', 'tampilan rusak', 'glitch layar', 'garis garis di monitor'],
    16 => ['panas', 'overheat', 'suhu tinggi', 'kepanasan', 'panas dan restart', 'overheat shutdown', 'panas blue screen', 'terlalu panas', 'kepanasan dan mati', 'panas restart sendiri'],
    17 => ['internet lambat', 'koneksi putus', 'wifi error', 'jaringan lambat', 'putus nyambung', 'wifi disconnect', 'internet sering putus'],
    18 => ['usb tidak terdeteksi', 'usb tidak kebaca', 'flashdisk tidak terbaca', 'port usb mati', 'usb device not recognized', 'usb tidak terdeteksi sama sekali'],
    19 => ['keyboard error', 'mouse error', 'keyboard tidak fungsi', 'mouse mati', 'keyboard tidak merespon', 'mouse tidak gerak', 'keyboard mouse mati'],
    20 => ['tidak ada suara', 'audio mati', 'speaker mati', 'suara hilang', 'no sound', 'suara tidak keluar', 'speaker tidak bunyi']
];

// Cari kata kunci yang cocok dengan input user
foreach ($kata_kunci_gejala as $id_gejala => $keywords) {
    foreach ($keywords as $keyword) {
        if (stripos($input_lower, $keyword) !== false) {
            // Kata kunci ditemukan!
            if (!in_array($id_gejala, $gejala_teridentifikasi)) {
                $gejala_teridentifikasi[] = $id_gejala;
            }
            break; // Lanjut ke gejala berikutnya
        }
    }
}

// ==========================================
// STEP 2: CEK APAKAH ADA GEJALA YANG COCOK
// ==========================================

if (empty($gejala_teridentifikasi)) {
    // Tidak ada gejala yang teridentifikasi
    echo json_encode([
        'success' => false,
        'message' => '❓ Maaf, saya belum bisa memahami masalah yang Anda jelaskan. Coba gunakan kata kunci seperti: "tidak menyala", "layar hitam", "hardisk bunyi", "restart sendiri", dll.',
        'gejala_found' => false
    ]);
    exit();
}

// ==========================================
// STEP 3: FORWARD CHAINING
// Cari kerusakan berdasarkan gejala teridentifikasi
// ==========================================

$diagnosa_hasil = null;

// Ambil semua rule dari database
$query_rules = "SELECT r.*, k.kode_kerusakan, k.nama_kerusakan, k.solusi 
                FROM rule r 
                JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan";
$result_rules = mysqli_query($koneksi, $query_rules);

$max_match = 0; // Untuk mencari rule dengan kecocokan terbanyak

// Loop setiap rule untuk pencocokan
while ($rule = mysqli_fetch_assoc($result_rules)) {
    $id_rule = $rule['id_rule'];
    
    // Ambil semua gejala yang menjadi syarat rule ini
    $query_gejala_rule = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_rule'";
    $result_gejala_rule = mysqli_query($koneksi, $query_gejala_rule);
    
    $gejala_rule = [];
    while ($row = mysqli_fetch_assoc($result_gejala_rule)) {
        $gejala_rule[] = $row['id_gejala'];
    }
    
    // PENCOCOKAN: Cek berapa banyak gejala yang cocok
    $matched = array_intersect($gejala_rule, $gejala_teridentifikasi);
    $match_count = count($matched);
    $total_rule_gejala = count($gejala_rule);
    
    // Hitung persentase kecocokan
    $match_percentage = ($match_count / $total_rule_gejala) * 100;
    
    // Ambil rule dengan kecocokan terbesar (minimal 40% gejala cocok)
    // Atau jika hanya 1 gejala teridentifikasi, ambil rule yang paling cocok
    $threshold = (count($gejala_teridentifikasi) == 1) ? 1 : 40;
    $min_percentage = ($match_count >= 1 && count($gejala_teridentifikasi) == 1) ? 1 : 40;
    
    if ($match_percentage >= $min_percentage && $match_count > $max_match) {
        $max_match = $match_count;
        $diagnosa_hasil = [
            'id_kerusakan' => $rule['id_kerusakan'],
            'kode_kerusakan' => $rule['kode_kerusakan'],
            'nama_kerusakan' => $rule['nama_kerusakan'],
            'solusi' => $rule['solusi'],
            'match_percentage' => round($match_percentage, 2),
            'matched_symptoms' => $match_count,
            'total_symptoms' => $total_rule_gejala
        ];
    }
}

// ==========================================
// STEP 4: SIMPAN HASIL DIAGNOSA KE DATABASE
// ==========================================

$id_user = $_SESSION['id_user'];
$tanggal = date('Y-m-d H:i:s');

if ($diagnosa_hasil) {
    // Ada diagnosa yang ditemukan
    $hasil_kerusakan = mysqli_real_escape_string($koneksi, $diagnosa_hasil['nama_kerusakan']);
    
    // Insert ke tabel diagnosa
    $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) 
                    VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        // Ambil ID diagnosa yang baru diinsert
        $id_diagnosa = mysqli_insert_id($koneksi);
        
        // Insert detail gejala yang teridentifikasi
        foreach ($gejala_teridentifikasi as $id_gejala) {
            mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) 
                                   VALUES ('$id_diagnosa', '$id_gejala')");
        }
    }
    
    // Return hasil diagnosa
    echo json_encode([
        'success' => true,
        'gejala_found' => true,
        'message' => 'Diagnosa berhasil diidentifikasi.',
        'diagnosa' => [
            'kode_kerusakan' => $diagnosa_hasil['kode_kerusakan'],
            'nama_kerusakan' => $diagnosa_hasil['nama_kerusakan'],
            'solusi' => $diagnosa_hasil['solusi']
        ],
        'detail' => [
            'kecocokan' => $diagnosa_hasil['match_percentage'] . '%',
            'gejala_cocok' => $diagnosa_hasil['matched_symptoms'],
            'total_gejala_rule' => $diagnosa_hasil['total_symptoms']
        ]
    ]);
    
} else {
    // Gejala ditemukan tapi tidak ada rule yang cocok
    $hasil_kerusakan = "Kerusakan Tidak Teridentifikasi";
    
    // Tetap simpan ke database
    $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) 
                    VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $id_diagnosa = mysqli_insert_id($koneksi);
        
        foreach ($gejala_teridentifikasi as $id_gejala) {
            mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) 
                                   VALUES ('$id_diagnosa', '$id_gejala')");
        }
    }
    
    // Ambil nama gejala yang teridentifikasi
    $gejala_names = [];
    foreach ($gejala_teridentifikasi as $id_gejala) {
        $q = mysqli_query($koneksi, "SELECT nama_gejala FROM gejala WHERE id_gejala = '$id_gejala'");
        if ($row = mysqli_fetch_assoc($q)) {
            $gejala_names[] = $row['nama_gejala'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'gejala_found' => true,
        'message' => "🔍 Saya menemukan gejala berikut:\n\n" . 
                    "• " . implode("\n• ", $gejala_names) . 
                    "\n\n⚠️ Namun, kombinasi gejala ini belum ada di dalam sistem knowledge base kami. Silakan hubungi teknisi untuk pemeriksaan lebih lanjut atau coba jelaskan dengan lebih detail.",
        'gejala_teridentifikasi' => $gejala_names
    ]);
}

?>
