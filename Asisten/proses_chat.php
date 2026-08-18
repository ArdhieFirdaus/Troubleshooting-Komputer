<?php
/**
 * File: proses_chat.php
 * Deskripsi: Memproses input diagnosa chatbot berbasis pilihan gejala (Guided Option) & text search
 * Algoritma Forward Chaining:
 * 1. Menerima array id_gejala dari pilihan pengguna (atau pencarian teks)
 * 2. Mencocokkan dengan rule & rule_detail di database
 * 3. Menghasilkan diagnosa kerusakan, kategori (Hardware/Software), dan solusi perbaikan
 * 4. Menyimpan hasil ke tabel diagnosa & diagnosa_detail
 */

session_start();
require_once '../Config/koneksi.php';

header('Content-Type: application/json');

// Cek autentikasi
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'asisten_lab') {
    echo json_encode([
        'success' => false,
        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
    ]);
    exit();
}

$gejala_teridentifikasi = [];

// Method 1: Menerima array gejala_ids dari pilihan interaktif tombol chatbot
if (isset($_POST['gejala_ids'])) {
    if (is_array($_POST['gejala_ids'])) {
        $gejala_teridentifikasi = array_filter(array_map('intval', $_POST['gejala_ids']));
    } else if (is_string($_POST['gejala_ids'])) {
        $decoded = json_decode($_POST['gejala_ids'], true);
        if (is_array($decoded)) {
            $gejala_teridentifikasi = array_filter(array_map('intval', $decoded));
        }
    }
}

// Method 2: Fallback pencarian kata kunci jika user mengetik teks
if (empty($gejala_teridentifikasi) && isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $input_user = trim($_POST['message']);
    $input_lower = strtolower($input_user);
    
    $query_gejala = "SELECT id_gejala, kata_kunci FROM gejala ORDER BY id_gejala ASC";
    $result_gejala = mysqli_query($koneksi, $query_gejala);
    $all_keywords = [];
    
    while ($gejala = mysqli_fetch_assoc($result_gejala)) {
        $id_gejala = (int)$gejala['id_gejala'];
        $kata_kunci_db = $gejala['kata_kunci'];
        if (empty($kata_kunci_db)) continue;
        
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
    
    usort($all_keywords, function($a, $b) {
        return $b['length'] - $a['length'];
    });
    
    $input_temp = $input_lower;
    foreach ($all_keywords as $item) {
        if (stripos($input_temp, $item['keyword']) !== false) {
            if (!in_array($item['id_gejala'], $gejala_teridentifikasi)) {
                $gejala_teridentifikasi[] = $item['id_gejala'];
            }
            $input_temp = str_ireplace($item['keyword'], '***', $input_temp);
        }
    }
}

if (empty($gejala_teridentifikasi)) {
    echo json_encode([
        'success' => false,
        'message' => '⚠️ Silakan pilih setidaknya satu gejala untuk menjalankan diagnosa.',
        'gejala_found' => false
    ]);
    exit();
}

// Pastikan nilai gejala unik
$gejala_teridentifikasi = array_values(array_unique($gejala_teridentifikasi));
$jumlah_gejala_input = count($gejala_teridentifikasi);

// ==========================================
// FORWARD CHAINING INFERENCING ENGINE
// ==========================================

$query_rules = "SELECT r.*, k.kode_kerusakan, k.nama_kerusakan, k.solusi, k.kategori 
                FROM rule r 
                JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan";
$result_rules = mysqli_query($koneksi, $query_rules);

$max_match = 0;
$diagnosa_hasil = null;
$tied_rules = 0;

while ($rule = mysqli_fetch_assoc($result_rules)) {
    $id_rule = $rule['id_rule'];
    
    // Ambil semua gejala yang menjadi syarat untuk rule ini
    $query_gejala_rule = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_rule'";
    $result_gejala_rule = mysqli_query($koneksi, $query_gejala_rule);
    
    $gejala_rule = [];
    while ($row = mysqli_fetch_assoc($result_gejala_rule)) {
        $gejala_rule[] = (int)$row['id_gejala'];
    }
    
    $matched = array_intersect($gejala_rule, $gejala_teridentifikasi);
    $match_count = count($matched);
    $total_rule_gejala = count($gejala_rule);
    
    $match_percentage = ($total_rule_gejala > 0) ? ($match_count / $total_rule_gejala) * 100 : 0;
    
    // Evaluasi Rule Match
    $rule_match = false;
    if ($jumlah_gejala_input == 1) {
        $rule_match = ($match_count >= 1);
    } else {
        $rule_match = ($match_count === $jumlah_gejala_input) || ($match_count === $total_rule_gejala);
    }

    if ($rule_match && $match_count > 0) {
        if ($match_count > $max_match) {
            $max_match = $match_count;
            $diagnosa_hasil = [
                'id_kerusakan' => $rule['id_kerusakan'],
                'kode_kerusakan' => $rule['kode_kerusakan'],
                'nama_kerusakan' => $rule['nama_kerusakan'],
                'kategori' => !empty($rule['kategori']) ? $rule['kategori'] : 'Hardware',
                'solusi' => $rule['solusi'],
                'match_percentage' => round($match_percentage, 2),
                'matched_symptoms' => $match_count,
                'total_symptoms' => $total_rule_gejala
            ];
            $tied_rules = 1;
        } elseif ($match_count === $max_match && $max_match > 0) {
            $tied_rules++;
        }
    }
}

// Ambil detail rincian nama gejala terpilih untuk respon
$gejala_terpilih_details = [];
foreach ($gejala_teridentifikasi as $id_g) {
    $qg = mysqli_query($koneksi, "SELECT id_gejala, kode_gejala, nama_gejala FROM gejala WHERE id_gejala = '$id_g'");
    if ($row = mysqli_fetch_assoc($qg)) {
        $gejala_terpilih_details[] = [
            'id_gejala' => $row['id_gejala'],
            'kode_gejala' => $row['kode_gejala'],
            'nama_gejala' => $row['nama_gejala']
        ];
    }
}

// ==========================================
// SIMPAN HASIL KE DATABASE
// ==========================================
$id_user = $_SESSION['id_user'];
$tanggal = date('Y-m-d H:i:s');

if ($diagnosa_hasil && $tied_rules <= 1) {
    $hasil_kerusakan = mysqli_real_escape_string($koneksi, $diagnosa_hasil['nama_kerusakan']);
    $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $id_diagnosa = mysqli_insert_id($koneksi);
        foreach ($gejala_teridentifikasi as $id_g) {
            mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) VALUES ('$id_diagnosa', '$id_g')");
        }
    }
    
    echo json_encode([
        'success' => true,
        'gejala_found' => true,
        'message' => 'Diagnosa Forward Chaining berhasil diproses.',
        'diagnosa' => [
            'kode_kerusakan' => $diagnosa_hasil['kode_kerusakan'],
            'nama_kerusakan' => $diagnosa_hasil['nama_kerusakan'],
            'kategori' => $diagnosa_hasil['kategori'],
            'solusi' => $diagnosa_hasil['solusi']
        ],
        'gejala_terpilih' => $gejala_terpilih_details,
        'detail' => [
            'kecocokan' => $diagnosa_hasil['match_percentage'] . '%',
            'gejala_cocok' => $diagnosa_hasil['matched_symptoms'],
            'total_gejala_rule' => $diagnosa_hasil['total_symptoms']
        ]
    ]);
} else {
    // Tidak teridentifikasi atau ambiguitas rule
    $hasil_kerusakan = "Kerusakan Tidak Teridentifikasi";
    $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        $id_diagnosa = mysqli_insert_id($koneksi);
        foreach ($gejala_teridentifikasi as $id_g) {
            mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) VALUES ('$id_diagnosa', '$id_g')");
        }
    }

    echo json_encode([
        'success' => true,
        'gejala_found' => true,
        'diagnosis_status' => 'tidak_teridentifikasi',
        'diagnosa' => [
            'kode_kerusakan' => '-',
            'nama_kerusakan' => 'Kerusakan Tidak Teridentifikasi',
            'kategori' => 'Tidak Diketahui',
            'solusi' => 'Kombinasi gejala yang Anda pilih belum memiliki aturan (rule) yang persis di dalam basis pengetahuan (Knowledge Base). Disarankan untuk menguji gejala yang paling dominan atau menghubungi teknisi untuk penanganan lebih lanjut.'
        ],
        'gejala_terpilih' => $gejala_terpilih_details
    ]);
}
?>
