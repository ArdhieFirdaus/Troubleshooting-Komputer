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

// Kata kunci untuk kondisi user belum tahu kerusakannya atau belum bisa menjelaskan gejalanya
$kata_kunci_kerusakan_tidak_teridentifikasi = [
    'tidak tahu rusaknya apa',
    'tidak tahu kerusakannya',
    'belum tahu rusaknya apa',
    'belum tahu kerusakannya',
    'kerusakannya tidak diketahui',
    'kerusakannya tidak teridentifikasi',
    'saya tidak tahu masalahnya',
    'saya belum tahu masalahnya',
    'belum bisa memastikan kerusakan',
    'belum bisa dipastikan',
    'belum jelas kerusakannya',
    'masih belum jelas',
    'masih tidak diketahui',
    'tidak jelas kerusakannya',
    'mohon bantu identifikasi',
    'tolong identifikasi kerusakannya'
];

// Kata kunci untuk kombinasi gejala yang tidak sesuai dengan rule yang tersedia
$kata_kunci_kombinasi_gejala_tidak_sesuai_rule = [
    'kombinasi gejalanya salah',
    'kombinasi gejala tidak cocok',
    'kombinasi gejala tidak sesuai rule',
    'gejalanya campur',
    'gejala tidak sinkron',
    'campuran gejala tidak jelas',
    'gabungan gejalanya tidak pas',
    'gejalanya tidak sesuai rule',
    'tidak ada rule yang cocok',
    'kombinasi gejala ini tidak cocok'
];

$input_mengarah_ke_tidak_teridentifikasi = false;
foreach ($kata_kunci_kerusakan_tidak_teridentifikasi as $keyword) {
    if (stripos($input_lower, $keyword) !== false) {
        $input_mengarah_ke_tidak_teridentifikasi = true;
        break;
    }
}

$input_mengarah_ke_kombinasi_tidak_sesuai_rule = false;
foreach ($kata_kunci_kombinasi_gejala_tidak_sesuai_rule as $keyword) {
    if (stripos($input_lower, $keyword) !== false) {
        $input_mengarah_ke_kombinasi_tidak_sesuai_rule = true;
        break;
    }
}

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

$all_keywords = [];
// Kumpulkan semua kata kunci
while ($gejala = mysqli_fetch_assoc($result_gejala)) {
    $id_gejala = $gejala['id_gejala'];
    $kata_kunci_db = $gejala['kata_kunci'];

    if (empty($kata_kunci_db)) {
        continue;
    }

    $keywords = explode(',', $kata_kunci_db);
    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (!empty($keyword)) {
            $all_keywords[] = [
                'id_gejala' => $id_gejala,
                'keyword' => strtolower($keyword),
                'length' => strlen($keyword)
            ];
        }
    }
}

// Urutkan kata kunci dari yang paling panjang ke paling pendek
// Ini mencegah kata kunci pendek (misal "lambat") bertabrakan dengan kata kunci panjang (misal "jaringan lambat")
usort($all_keywords, function($a, $b) {
    return $b['length'] - $a['length'];
});

$gejala_teridentifikasi = [];
$input_temp = $input_lower;

// Cari kata kunci yang cocok dengan input user
foreach ($all_keywords as $item) {
    if (stripos($input_temp, $item['keyword']) !== false) {
        // Kata kunci ditemukan!
        if (!in_array($item['id_gejala'], $gejala_teridentifikasi)) {
            $gejala_teridentifikasi[] = $item['id_gejala'];
        }
        // Timpa kata yang sudah cocok dengan bintang agar tidak terdeteksi dua kali oleh kata kunci yang lebih pendek
        $input_temp = str_ireplace($item['keyword'], '***', $input_temp);
    }
}

// ==========================================
// STEP 2: CEK APAKAH ADA GEJALA YANG COCOK
// ==========================================

if (empty($gejala_teridentifikasi)) {
    // Tidak ada gejala yang teridentifikasi
    if ($input_mengarah_ke_tidak_teridentifikasi) {
        echo json_encode([
            'success' => false,
            'message' => '❓ Saya tangkap Anda belum mengetahui kerusakannya. Coba jelaskan gejala yang muncul, misalnya: tidak menyala, layar hitam, bunyi beep, restart sendiri, atau hardisk bunyi klik.',
            'gejala_found' => false,
            'diagnosis_status' => 'tidak_teridentifikasi'
        ]);
        exit();
    }

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
$jumlah_gejala_input = count($gejala_teridentifikasi);

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
    
    $rule_match = false;
    if ($jumlah_gejala_input == 1) {
        $rule_match = ($match_count >= 1);
    } else {
        // Jika user memasukkan kombinasi gejala, asalkan semua gejala yang diinputkan 
        // terdapat di dalam rule ini (match_count == jumlah_gejala_input), maka rule ini valid
        $rule_match = ($match_count === $jumlah_gejala_input);
    }

    if ($rule_match) {
        if ($match_count > $max_match) {
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
            $tied_rules = 1;
        } elseif ($match_count === $max_match) {
            $tied_rules++;
        }
    }
}

// Jika ada lebih dari satu rule yang memiliki jumlah kecocokan yang sama tingginya,
// berarti gejala tersebut ambigu (lintas rule) dan tidak bisa diidentifikasi.
if (isset($tied_rules) && $tied_rules > 1) {
    $diagnosa_hasil = null;
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

    if ($input_mengarah_ke_kombinasi_tidak_sesuai_rule) {
        echo json_encode([
            'success' => true,
            'message' => '🔎 Saya menemukan gejala, tetapi kombinasi gejala tersebut belum cocok dengan rule yang tersedia.',
            'gejala_found' => true,
            'diagnosis_status' => 'kombinasi_tidak_sesuai_rule',
            'diagnosa' => [
                'kode_kerusakan' => '-',
                'nama_kerusakan' => 'Kerusakan Tidak Teridentifikasi',
                'solusi' => 'Kombinasi gejala belum memiliki rule yang sesuai. Silakan jelaskan gejala yang paling dominan atau hubungi teknisi untuk pemeriksaan lebih lanjut.'
            ]
        ]);
        exit();
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
                    "\n\n⚠️ Namun, kombinasi gejala ini belum ada di dalam sistem knowledge base kami.",
        'gejala_teridentifikasi' => $gejala_names,
        'diagnosis_status' => 'tidak_teridentifikasi',
        'diagnosa' => [
            'kode_kerusakan' => '-',
            'nama_kerusakan' => 'Kerusakan Tidak Teridentifikasi',
            'solusi' => 'Kombinasi gejala belum memiliki rule yang sesuai. Silakan jelaskan gejala yang paling dominan atau hubungi teknisi untuk pemeriksaan lebih lanjut.'
        ]
    ]);
}

?>
