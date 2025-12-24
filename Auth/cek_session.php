<?php
/**
 * File: cek_session.php
 * Deskripsi: Helper untuk validasi session dan autentikasi pengguna
 */

// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: ../Auth/login.php?error=not_logged_in");
    exit();
}

// Fungsi untuk cek role user
function cek_role($role_required) {
    if ($_SESSION['role'] != $role_required) {
        // Jika role tidak sesuai, redirect sesuai role yang dimiliki
        if ($_SESSION['role'] == 'admin') {
            header("Location: ../Admin/dashboard_admin.php?error=access_denied");
        } else if ($_SESSION['role'] == 'asisten_lab') {
            header("Location: ../Asisten/dashboard_asisten.php?error=access_denied");
        } else {
            header("Location: ../Auth/login.php?error=invalid_role");
        }
        exit();
    }
}

?>
