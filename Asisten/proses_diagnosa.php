<?php
/**
 * File: proses_diagnosa.php
 * Deskripsi: Memproses diagnosa dengan metode Forward Chaining
 * 
 * ALGORITMA FORWARD CHAINING:
 * 1. Ambil gejala yang dipilih user
 * 2. Ambil semua rule dari database
 * 3. Untuk setiap rule, cek apakah SEMUA gejala dalam rule tersebut ada di pilihan user
 * 4. Jika semua gejala rule cocok dengan pilihan user, maka rule tersebut "MATCH"
 * 5. Ambil kerusakan dari rule yang match
 * 6. Simpan hasil diagnosa ke database
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil gejala yang dipilih user (array)
    $gejala_dipilih = isset($_POST['gejala']) ? $_POST['gejala'] : array();
    
    // Validasi: minimal 1 gejala harus dipilih
    if (empty($gejala_dipilih)) {
        header("Location: diagnosa.php?error=no_gejala");
        exit();
    }
    
    // ==========================================
    // ALGORITMA FORWARD CHAINING (HARDWARE & SOFTWARE)
    // ==========================================
    
    $rule_cocok = null;
    $kerusakan_hasil = null;
    $max_match = 0;
    $tied_rules = 0;
    $jumlah_gejala_input = count($gejala_dipilih);
    
    // Ambil semua rule beserta data kerusakan dan kategori (Hardware/Software)
    $query_rules = "SELECT r.*, k.id_kerusakan, k.kode_kerusakan, k.nama_kerusakan, k.solusi, k.kategori 
                    FROM rule r 
                    JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan";
    $result_rules = mysqli_query($koneksi, $query_rules);
    
    // Loop setiap rule untuk pencocokan
    while ($rule = mysqli_fetch_assoc($result_rules)) {
        $id_rule = $rule['id_rule'];
        
        // Ambil semua gejala yang menjadi syarat rule ini
        $query_gejala_rule = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_rule'";
        $result_gejala_rule = mysqli_query($koneksi, $query_gejala_rule);
        
        $gejala_rule = array();
        while ($row = mysqli_fetch_assoc($result_gejala_rule)) {
            $gejala_rule[] = (int)$row['id_gejala'];
        }
        
        // Match intersection antara gejala rule dan gejala input user
        $matched = array_intersect($gejala_rule, array_map('intval', $gejala_dipilih));
        $match_count = count($matched);
        $total_rule_gejala = count($gejala_rule);
        
        if ($match_count == 0) continue;
        
        // Evaluasi Rule Match:
        // - Untuk 1 input gejala: rule cocok jika minimal 1 gejala ada di rule
        // - Untuk 2+ input gejala: rule cocok jika seluruh input ada di rule ATAU seluruh gejala rule ada di input
        $rule_match = false;
        if ($jumlah_gejala_input == 1) {
            $rule_match = ($match_count >= 1);
        } else {
            $rule_match = ($match_count === $jumlah_gejala_input) || ($match_count === $total_rule_gejala);
        }
        
        if ($rule_match && $match_count > 0) {
            if ($match_count > $max_match) {
                $max_match = $match_count;
                $rule_cocok = $rule;
                $kerusakan_hasil = [
                    'id_kerusakan' => $rule['id_kerusakan'],
                    'kode_kerusakan' => $rule['kode_kerusakan'],
                    'nama_kerusakan' => $rule['nama_kerusakan'],
                    'kategori' => !empty($rule['kategori']) ? $rule['kategori'] : 'Hardware',
                    'solusi' => $rule['solusi']
                ];
                $tied_rules = 1;
            } else if ($match_count === $max_match && $max_match > 0) {
                $tied_rules++;
            }
        }
    }
    
    // Jika ada ambiguitas (lebih dari 1 rule dengan tingkat match tertinggi yang sama)
    if ($tied_rules > 1) {
        $kerusakan_hasil = null;
    }
    
    // ==========================================
    // SIMPAN HASIL DIAGNOSA KE DATABASE
    // ==========================================
    
    if ($kerusakan_hasil) {
        // Ada kerusakan yang teridentifikasi
        
        $id_user = $_SESSION['id_user'];
        $tanggal = date('Y-m-d H:i:s');
        $hasil_kerusakan = mysqli_real_escape_string($koneksi, $kerusakan_hasil['nama_kerusakan']);
        
        // Insert ke tabel diagnosa
        $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) 
                        VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            // Ambil ID diagnosa yang baru diinsert
            $id_diagnosa = mysqli_insert_id($koneksi);
            
            // Insert detail gejala yang dipilih ke diagnosa_detail
            foreach ($gejala_dipilih as $id_gejala) {
                $id_gejala = mysqli_real_escape_string($koneksi, $id_gejala);
                mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) 
                                       VALUES ('$id_diagnosa', '$id_gejala')");
            }
            
            // Redirect ke halaman hasil dengan ID diagnosa
            header("Location: hasil_diagnosa.php?id=$id_diagnosa&success=1");
            exit();
            
        } else {
            // Gagal insert diagnosa
            header("Location: diagnosa.php?error=db_error");
            exit();
        }
        
    } else {
        // Tidak ada rule yang cocok
        // Tetap simpan diagnosa tapi dengan hasil "Tidak Teridentifikasi"
        
        $id_user = $_SESSION['id_user'];
        $tanggal = date('Y-m-d H:i:s');
        $hasil_kerusakan = "Kerusakan Tidak Teridentifikasi";
        
        // Insert ke tabel diagnosa
        $query_insert = "INSERT INTO diagnosa (id_user, tanggal, hasil_kerusakan) 
                        VALUES ('$id_user', '$tanggal', '$hasil_kerusakan')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $id_diagnosa = mysqli_insert_id($koneksi);
            
            // Insert detail gejala yang dipilih
            foreach ($gejala_dipilih as $id_gejala) {
                $id_gejala = mysqli_real_escape_string($koneksi, $id_gejala);
                mysqli_query($koneksi, "INSERT INTO diagnosa_detail (id_diagnosa, id_gejala) 
                                       VALUES ('$id_diagnosa', '$id_gejala')");
            }
            
            // Redirect dengan parameter tidak ditemukan
            header("Location: hasil_diagnosa.php?id=$id_diagnosa&not_found=1");
            exit();
            
        } else {
            header("Location: diagnosa.php?error=db_error");
            exit();
        }
    }
    
} else {
    // Jika bukan POST, redirect ke halaman diagnosa
    header("Location: diagnosa.php");
    exit();
}

?>
