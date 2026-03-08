<?php
/**
 * File: proses_chat_v2.php
 * Deskripsi: Versi dinamis - membaca kata kunci dari database
 * 
 * ALGORITMA:
 * 1. Terima input chat dari user
 * 2. Cari kata kunci yang cocok di tabel gejala (dari kolom kata_kunci)
 * 3. Jalankan forward chaining berdasarkan gejala yang teridentifikasi
 * 4. Return hasil diagnosa dalam format JSON
 * 
 * CATATAN: Pastikan sudah menjalankan update_kata_kunci.sql terlebih dahulu!
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
// Membaca kata kunci dari database
// ==========================================

$gejala_teridentifikasi = [];

// Ambil semua gejala dari database beserta kata kuncinya
$query_gejala = "SELECT id_gejala, kode_gejala, nama_gejala, kata_kunci FROM gejala ORDER BY id_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);

if (!$result_gejala) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan database. Silakan coba lagi.'
    ]);
    exit();
}

// Loop setiap gejala dan cek kata kuncinya
while ($gejala = mysqli_fetch_assoc($result_gejala)) {
    $id_gejala = $gejala['id_gejala'];
    $kata_kunci = $gejala['kata_kunci'];
    
    if (empty($kata_kunci)) {
        continue; // Skip jika tidak ada kata kunci
    }
    
    // Split kata kunci by comma
    $keywords = explode(',', $kata_kunci);
    
    // Cek setiap keyword
    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        
        // Cari keyword dalam input user
        if (!empty($keyword) && stripos($input_lower, strtolower($keyword)) !== false) {
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
$all_matches = []; // Simpan semua kecocokan untuk debugging

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
    
    // Skip jika tidak ada kecocokan
    if ($match_count == 0) {
        continue;
    }
    
    // Hitung persentase kecocokan
    $match_percentage = ($match_count / $total_rule_gejala) * 100;
    
    // Simpan untuk debugging
    $all_matches[] = [
        'kerusakan' => $rule['nama_kerusakan'],
        'percentage' => round($match_percentage, 2),
        'match_count' => $match_count,
        'total_gejala' => $total_rule_gejala
    ];
    
    // Ambil rule dengan kecocokan terbesar
    // Jika hanya 1 gejala teridentifikasi: ambil rule yang mengandung gejala tersebut
    // Jika multiple gejala: minimal 40% gejala cocok
    $min_percentage = (count($gejala_teridentifikasi) == 1) ? 1 : 40;
    
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
            'total_gejala_rule' => $diagnosa_hasil['total_symptoms'],
            'gejala_teridentifikasi' => count($gejala_teridentifikasi)
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
        'gejala_teridentifikasi' => $gejala_names,
        'all_matches' => $all_matches // Untuk debugging
    ]);
}

?>
