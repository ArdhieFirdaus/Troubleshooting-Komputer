<?php
/**
 * File: proses_login.php
 * Deskripsi: Memproses login user (admin & asisten lab)
 */

session_start();

// Include file koneksi database
require_once '../Config/koneksi.php';

// Cek apakah form disubmit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    
    // Validasi: cek apakah field kosong
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }
    
    // Query untuk mengambil data user berdasarkan username
    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    
    // Cek apakah username ditemukan
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password menggunakan password_verify()
        // Password di database harus sudah di-hash menggunakan password_hash()
        if (password_verify($password, $user['password'])) {
            
            // Login berhasil - Set session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            
            // Redirect sesuai role
            if ($user['role'] == 'admin') {
                // Redirect ke dashboard admin
                header("Location: ../Admin/dashboard_admin.php");
            } else if ($user['role'] == 'asisten_lab') {
                // Redirect ke dashboard asisten
                header("Location: ../Asisten/dashboard_asisten.php");
            } else {
                // Role tidak dikenal
                session_destroy();
                header("Location: login.php?error=invalid_role");
            }
            exit();
            
        } else {
            // Password salah
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
        
    } else {
        // Username tidak ditemukan
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
    
} else {
    // Jika bukan metode POST, redirect ke login
    header("Location: login.php");
    exit();
}

?>
