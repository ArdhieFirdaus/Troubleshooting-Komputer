<?php
session_start();

// Jika sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['id_user']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../Admin/dashboard_admin.php");
    } else if ($_SESSION['role'] == 'asisten_lab') {
        header("Location: ../Asisten/dashboard_asisten.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pakar Troubleshooting Komputer</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Assets/css/style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            max-width: 450px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="mb-0">
                        <i class="bi bi-pc-display"></i>
                        Sistem Pakar
                    </h3>
                    <p class="mb-0 mt-2" style="font-size: 14px;">Troubleshooting Komputer & Software</p>
                    <small>Pondok Pesantren Al-Gontory</small>
                </div>
                <div class="card-body p-4">
                    <?php
                    // Tampilkan pesan error jika ada
                    if (isset($_GET['error'])) {
                        $error_message = '';
                        switch ($_GET['error']) {
                            case 'invalid_credentials':
                                $error_message = 'Username atau password salah!';
                                break;
                            case 'empty_fields':
                                $error_message = 'Semua field harus diisi!';
                                break;
                            case 'not_logged_in':
                                $error_message = 'Silakan login terlebih dahulu!';
                                break;
                            case 'session_expired':
                                $error_message = 'Session telah berakhir. Silakan login kembali!';
                                break;
                            default:
                                $error_message = 'Terjadi kesalahan. Silakan coba lagi!';
                        }
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> ' . $error_message . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                              </div>';
                    }
                    
                    // Tampilkan pesan sukses jika ada
                    if (isset($_GET['success'])) {
                        if ($_GET['success'] == 'logged_out') {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill"></i> Anda telah berhasil logout.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                  </div>';
                        }
                    }
                    ?>
                    
                    <form action="proses_login.php" method="POST" id="loginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Masukkan username" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Masukkan password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <small class="text-muted">
                    
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom JS -->
    <script src="../Assets/js/script.js"></script>
    
    <script>
        // Validasi form login
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Cek apakah field kosong
            if (username === '' || password === '') {
                e.preventDefault();
                alert('Username dan password harus diisi!');
                return false;
            }
            
            // Cek panjang minimum
            if (username.length < 3) {
                e.preventDefault();
                alert('Username minimal 3 karakter!');
                return false;
            }
            
            if (password.length < 3) {
                e.preventDefault();
                alert('Password minimal 3 karakter!');
                return false;
            }
        });
    </script>
</body>
</html>
