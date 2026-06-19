<?php
/**
 * File: dashboard_asisten.php
 * Deskripsi: Halaman dashboard untuk Asisten Lab
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

$id_user = $_SESSION['id_user'];

// Hitung statistik untuk asisten ini
$query_diagnosa = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM diagnosa WHERE id_user='$id_user'");
$total_diagnosa = mysqli_fetch_assoc($query_diagnosa)['total'];

// Diagnosa hari ini
$query_today = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM diagnosa WHERE id_user='$id_user' AND DATE(tanggal) = CURDATE()");
$total_today = mysqli_fetch_assoc($query_today)['total'];

// Diagnosa minggu ini
$query_week = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM diagnosa WHERE id_user='$id_user' AND YEARWEEK(tanggal) = YEARWEEK(NOW())");
$total_week = mysqli_fetch_assoc($query_week)['total'];

// Diagnosa terbaru (5 terakhir)
$query_recent = "SELECT d.*, k.nama_kerusakan 
                 FROM diagnosa d 
                 LEFT JOIN kerusakan k ON d.hasil_kerusakan = k.nama_kerusakan 
                 WHERE d.id_user='$id_user' 
                 ORDER BY d.tanggal DESC 
                 LIMIT 5";
$result_recent = mysqli_query($koneksi, $query_recent);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Asisten - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar_asisten.php'; ?>
        
        <div class="main-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Dashboard Asisten</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h2>
                
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white shadow">
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
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Diagnosa Hari Ini</h6>
                                        <h2 class="mb-0"><?php echo $total_today; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info text-white shadow">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">Diagnosa Minggu Ini</h6>
                                        <h2 class="mb-0"><?php echo $total_week; ?></h2>
                                    </div>
                                    <div class="display-4">
                                        <i class="bi bi-calendar-week"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Action -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card shadow border-primary">
                            <div class="card-body text-center p-4">
                                <h4 class="mb-3">
                                    <i class="bi bi-clipboard-pulse"></i> 
                                    Mulai Diagnosa Kerusakan Komputer
                                </h4>
                                <p class="text-muted mb-3">
                                    Klik tombol di bawah untuk memulai proses diagnosa troubleshooting komputer
                                </p>
                                <a href="diagnosa.php" class="btn btn-primary btn-lg">
                                    <i class="bi bi-arrow-right-circle"></i> Mulai Diagnosa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Diagnosa Terbaru -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Diagnosa Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <?php if(mysqli_num_rows($result_recent) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Tanggal</th>
                                                <th width="55%">Hasil Kerusakan</th>
                                                <th width="20%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            while($row = mysqli_fetch_assoc($result_recent)): 
                                            ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                                <td><?php echo $row['hasil_kerusakan']; ?></td>
                                                <td class="text-center">
                                                    <a href="hasil_diagnosa.php?id=<?php echo $row['id_diagnosa']; ?>" 
                                                       class="btn btn-info btn-sm">
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="riwayat_diagnosa.php" class="btn btn-outline-primary">
                                        Lihat Semua Riwayat <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 48px;"></i>
                                    <p class="mt-2">Belum ada diagnosa. Mulai diagnosa sekarang!</p>
                                    <a href="diagnosa.php" class="btn btn-primary">
                                        <i class="bi bi-plus-circle"></i> Buat Diagnosa
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Info Panel -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card shadow border-info">
                            <div class="card-body">
                                <h6><i class="bi bi-info-circle"></i> Informasi</h6>
                                <p class="mb-1">
                                    <strong>Sistem Pakar Troubleshooting Komputer dan Software</strong><br>
                                    Metode: Forward Chaining | Lokasi: Pondok Pesantren Al-Gontory
                                </p>
                                <hr>
                                <p class="mb-0">
                                    <strong>Cara Penggunaan:</strong><br>
                                    1. Pilih menu "Diagnosa Kerusakan"<br>
                                    2. Centang gejala-gejala yang dialami komputer<br>
                                    3. Klik "Proses Diagnosa" untuk mendapatkan hasil dan solusi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js"></script>
</body>
</html>
