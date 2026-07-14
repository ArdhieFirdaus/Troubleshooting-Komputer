<?php
/**
 * File: proses_chat_v2.php
 * Deskripsi: Versi dinamis - membaca kata kunci dari database
 */

session_start();
require_once '../Config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'asisten_lab') {
    echo json_encode([
        'success' => false,
        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
    ]);
    exit();
}

if (!isset($_POST['message']) || empty(trim($_POST['message']))) {
    echo json_encode([
        'success' => false,
        'message' => 'Pesan tidak boleh kosong.'
    ]);
    exit();
}

$input_user = trim($_POST['message']);
$input_lower = strtolower($input_user);

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

$gejala_teridentifikasi = [];
$query_gejala = "SELECT id_gejala, kode_gejala, nama_gejala, kata_kunci FROM gejala ORDER BY id_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);

if (!$result_gejala) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan database. Silakan coba lagi.'
    ]);
    exit();
}

while ($gejala = mysqli_fetch_assoc($result_gejala)) {
    $id_gejala = $gejala['id_gejala'];
    $kata_kunci = $gejala['kata_kunci'];

    if (empty($kata_kunci)) {
        continue;
    }

    $keywords = explode(',', $kata_kunci);
    foreach ($keywords as $keyword) {
        $keyword = trim($keyword);
        if (!empty($keyword) && stripos($input_lower, strtolower($keyword)) !== false) {
            if (!in_array($id_gejala, $gejala_teridentifikasi)) {
                $gejala_teridentifikasi[] = $id_gejala;
            }
            break;
        }
    }
}

if (empty($gejala_teridentifikasi)) {
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

$diagnosa_hasil = null;
$all_matches = [];
$max_match = 0;
$jumlah_gejala_input = count($gejala_teridentifikasi);

$query_rules = "SELECT r.*, k.kode_kerusakan, k.nama_kerusakan, k.solusi 
                FROM rule r 
                JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan";
$result_rules = mysqli_query($koneksi, $query_rules);

while ($rule = mysqli_fetch_assoc($result_rules)) {
    $id_rule = $rule['id_rule'];

    $query_gejala_rule = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_rule'";
    $result_gejala_rule = mysqli_query($koneksi, $query_gejala_rule);

    $gejala_rule = [];
    while ($row = mysqli_fetch_assoc($result_gejala_rule)) {
        $gejala_rule[] = $row['id_gejala'];
    }

    $matched = array_intersect($gejala_rule, $gejala_teridentifikasi);
    $match_count = count($matched);
    $total_rule_gejala = count($gejala_rule);

    if ($match_count == 0) {
        continue;
    }

    $match_percentage = ($match_count / $total_rule_gejala) * 100;

    $all_matches[] = [
        'kerusakan' => $rule['nama_kerusakan'],
        'percentage' => round($match_percentage, 2),
        'match_count' => $match_count,
        'total_gejala' => $total_rule_gejala
    ];

    $rule_match = false;
    if ($jumlah_gejala_input == 1) {
        $rule_match = ($match_count >= 1);
    } else {
        $rule_match = ($match_count === $jumlah_gejala_input && $match_count === $total_rule_gejala);
    }

    if ($rule_match && $match_count > $max_match) {
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

$id_user = $_SESSION['id_user'];
$tanggal = date('Y-m-d H:i:s');

if ($diagnosa_hasil) {
    $hasil_kerusakan = mysqli_real_escape_string($koneksi, $diagnosa_hasil['nama_kerusakan']);
    $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) 
                    VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";

    if (mysqli_query($koneksi, $query_insert)) {
        $id_diagnosa = mysqli_insert_id($koneksi);
        foreach ($gejala_teridentifikasi as $id_gejala) {
            mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) 
                                   VALUES ('$id_diagnosa', '$id_gejala')");
        }
    }

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
    $hasil_kerusakan = "Kerusakan Tidak Teridentifikasi";
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
        'all_matches' => $all_matches,
        'diagnosa' => [
            'kode_kerusakan' => '-',
            'nama_kerusakan' => 'Kerusakan Tidak Teridentifikasi',
            'solusi' => 'Kombinasi gejala belum memiliki rule yang sesuai. Silakan jelaskan gejala yang paling dominan atau hubungi teknisi untuk pemeriksaan lebih lanjut.'
        ]
    ]);
}

?>