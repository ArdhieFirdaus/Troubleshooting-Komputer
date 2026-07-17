    <?php
/**
 * File: proses_login.php
 * Deskripsi: Memproses login user (admin & asisten lab)
 */

session_start();

// Include file koneksi database
require_once '../Config/koneksi.php';

    // Cek metode POST (Jika bukan POST, langsung lemparkan)
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: login.php");
        exit();
    }
    
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    
    // Cek apakah ada input kosong
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }
    
    // Query ambil user
    $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    
    // Cek apakah Username ADA di database
    if (mysqli_num_rows($result) == 0) {
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
    
    // Extract data user
    $user = mysqli_fetch_assoc($result);
    
    // Verifikasi kecocokan password hash
    if (!password_verify($password, $user['password'])) {
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
    
    // JIKA LOLOS SEMUA VALIDASI DI ATAS -> SET SESSION
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    
    // Cek Role: Admin
    if ($user['role'] == 'admin') {
        header("Location: ../Admin/dashboard_admin.php");
        exit();
    }
    
    // Cek Role: Asisten Lab
    if ($user['role'] == 'asisten_lab') {
        header("Location: ../Asisten/dashboard_asisten.php");
        exit();
    }
    
    // Error Default (Jika role di database tidak terdaftar / di-hack)
    session_destroy();
    header("Location: login.php?error=invalid_role");
    exit();

?>
