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
    // ALGORITMA FORWARD CHAINING
    // ==========================================
    
    $rule_cocok = null;
    $kerusakan_hasil = null;
    
    // Ambil semua rule dari database
    $query_rules = "SELECT * FROM rule";
    $result_rules = mysqli_query($koneksi, $query_rules);
    
    // Loop setiap rule untuk pencocokan
    while ($rule = mysqli_fetch_assoc($result_rules)) {
        $id_rule = $rule['id_rule'];
        
        // Ambil semua gejala yang menjadi syarat rule ini
        $query_gejala_rule = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_rule'";
        $result_gejala_rule = mysqli_query($koneksi, $query_gejala_rule);
        
        $gejala_rule = array();
        while ($row = mysqli_fetch_assoc($result_gejala_rule)) {
            $gejala_rule[] = $row['id_gejala'];
        }
        
        // PENCOCOKAN: Cek apakah SEMUA gejala di rule ada di pilihan user
        // Menggunakan array_diff untuk cek apakah semua elemen $gejala_rule ada di $gejala_dipilih
        $difference = array_diff($gejala_rule, $gejala_dipilih);
        
        // Jika $difference kosong, berarti semua gejala rule ada di pilihan user
        if (empty($difference)) {
            // Rule ini COCOK!
            $rule_cocok = $rule;
            
            // Ambil data kerusakan
            $id_kerusakan = $rule['id_kerusakan'];
            $query_kerusakan = "SELECT * FROM kerusakan WHERE id_kerusakan = '$id_kerusakan'";
            $result_kerusakan = mysqli_query($koneksi, $query_kerusakan);
            $kerusakan_hasil = mysqli_fetch_assoc($result_kerusakan);
            
            // Keluar dari loop (ambil rule pertama yang cocok)
            // Bisa diubah jika ingin mengambil semua rule yang cocok
            break;
        }
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
