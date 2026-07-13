<?php
/**
 * File: dashboard_admin.php
 * Deskripsi: Halaman dashboard untuk Admin/Ketua Lab
 */

// Include session checker
require_once '../Auth/cek_session.php';
cek_role('admin'); // Pastikan hanya admin yang bisa akses

// Include koneksi database
require_once '../Config/koneksi.php';

// Hitung statistik
$query_gejala = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM gejala");
$total_gejala = mysqli_fetch_assoc($query_gejala)['total'];

$query_kerusakan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kerusakan");
$total_kerusakan = mysqli_fetch_assoc($query_kerusakan)['total'];

$query_rule = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM rule");
$total_rule = mysqli_fetch_assoc($query_rule)['total'];

$query_diagnosa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM diagnosa");
$total_diagnosa = mysqli_fetch_assoc($query_diagnosa)['total'];

$query_asisten = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='asisten_lab'");
$total_asisten = mysqli_fetch_assoc($query_asisten)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pakar</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar_admin.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Dashboard Admin</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> 
                            <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <!-- Content -->
            <div class="container-fluid p-4">
                <h2 class="mb-4">Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h2>
                
                <?php if(isset($_GET['error']) && $_GET['error'] == 'access_denied'): ?>
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> Anda tidak memiliki akses ke halaman tersebut.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Gejala</h6>
                                        <h2 class="mb-0"><?php echo $total_gejala; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Kerusakan</h6>
                                        <h2 class="mb-0"><?php echo $total_kerusakan; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-warning text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Rule</h6>
                                        <h2 class="mb-0"><?php echo $total_rule; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-info text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Diagnosa</h6>
                                        <h2 class="mb-0"><?php echo $total_diagnosa; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-file-medical"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card bg-secondary text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Total Asisten Lab</h6>
                                        <h2 class="mb-0"><?php echo $total_asisten; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Sistem -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <h6>Sistem Pakar Troubleshooting Komputer dan Software</h6>
                                <p class="mb-2">
                                    Metode: <strong>Forward Chaining</strong><br>
                                    Lokasi: <strong>Pondok Pesantren Al-Gontory</strong><br>
                                    Role Anda: <strong><?php echo ucfirst(str_replace('_', ' ', $_SESSION['role'])); ?></strong>
                                </p>
                                <hr>
                                <h6>Menu yang Tersedia:</h6>
                                <ul>
                                    <li><strong>Manajemen Gejala:</strong> Kelola data gejala kerusakan komputer</li>
                                    <li><strong>Manajemen Kerusakan:</strong> Kelola data kerusakan dan solusinya</li>
                                    <li><strong>Manajemen Rule:</strong> Kelola aturan forward chaining</li>
                                    <li><strong>Laporan Diagnosa:</strong> Lihat rekap semua diagnosa yang dilakukan asisten</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
</body>
</html>
