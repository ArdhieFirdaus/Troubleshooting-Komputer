<?php
/**
 * File: hasil_diagnosa.php
 * Deskripsi: Menampilkan hasil diagnosa dan solusi
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

// Ambil ID diagnosa
$id_diagnosa = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id_diagnosa)) {
    header("Location: diagnosa.php");
    exit();
}

// Ambil data diagnosa
$id_user = $_SESSION['id_user'];
$query_diagnosa = "SELECT * FROM diagnosa WHERE id_diagnosa = '$id_diagnosa' AND id_user = '$id_user'";
$result_diagnosa = mysqli_query($koneksi, $query_diagnosa);

if (mysqli_num_rows($result_diagnosa) == 0) {
    header("Location: diagnosa.php");
    exit();
}

$diagnosa = mysqli_fetch_assoc($result_diagnosa);

// Ambil gejala yang dipilih
$query_gejala = "SELECT g.* FROM diagnosa_detail dd 
                 INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                 WHERE dd.id_diagnosa = '$id_diagnosa'";
$result_gejala = mysqli_query($koneksi, $query_gejala);

// Ambil solusi dari kerusakan (jika teridentifikasi)
$solusi = '';
$kode_kerusakan = '';
if ($diagnosa['hasil_kerusakan'] != 'Kerusakan Tidak Teridentifikasi') {
    $nama_kerusakan = mysqli_real_escape_string($koneksi, $diagnosa['hasil_kerusakan']);
    $query_solusi = "SELECT * FROM kerusakan WHERE nama_kerusakan = '$nama_kerusakan'";
    $result_solusi = mysqli_query($koneksi, $query_solusi);
    
    if (mysqli_num_rows($result_solusi) > 0) {
        $kerusakan_data = mysqli_fetch_assoc($result_solusi);
        $solusi = $kerusakan_data['solusi'];
        $kode_kerusakan = $kerusakan_data['kode_kerusakan'];
    }
}

$not_found = isset($_GET['not_found']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
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
                    <span class="navbar-brand mb-0 h1 ms-3">Hasil Diagnosa</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar; kept user name only -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">
                    <i class="bi bi-check-circle"></i> 
                    Hasil Diagnosa
                </h2>
                
                <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> Diagnosa berhasil diproses!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Informasi Diagnosa -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Diagnosa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>ID Diagnosa</strong></td>
                                        <td>: <?php echo $diagnosa['id_diagnosa']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>: <?php echo date('d F Y, H:i', strtotime($diagnosa['tanggal'])); ?> WIB</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Asisten Lab</strong></td>
                                        <td>: <?php echo $_SESSION['nama_lengkap']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gejala yang Dipilih -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Gejala yang Dipilih</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php 
                            $counter = 0;
                            while($gejala = mysqli_fetch_assoc($result_gejala)): 
                                $counter++;
                            ?>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                    <div>
                                        <strong><?php echo $gejala['kode_gejala']; ?></strong> - 
                                        <?php echo $gejala['nama_gejala']; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <hr>
                        <p class="mb-0">
                            <strong>Total Gejala:</strong> <?php echo $counter; ?> gejala
                        </p>
                    </div>
                </div>
                
                <!-- Hasil Diagnosa -->
                <?php if($not_found): ?>
                <div class="card shadow mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Hasil Diagnosa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning alert-permanent">
                            <h5><i class="bi bi-exclamation-triangle"></i> Kerusakan Tidak Teridentifikasi</h5>
                            <p class="mb-0">
                                Maaf, sistem tidak dapat mengidentifikasi kerusakan berdasarkan kombinasi gejala yang Anda pilih. 
                                Kemungkinan:
                            </p>
                            <ul class="mt-2 mb-0">
                                <li>Kombinasi gejala tidak sesuai dengan rule yang ada di sistem</li>
                                <li>Kerusakan yang dialami belum terdaftar dalam database</li>
                                <li>Perlu konsultasi lebih lanjut dengan teknisi ahli</li>
                            </ul>
                        </div>
                        
                        <h6 class="mt-3">Saran Tindakan:</h6>
                        <ol>
                            <li>Coba pilih gejala yang lebih spesifik</li>
                            <li>Konsultasikan dengan admin/ketua lab untuk menambahkan rule baru</li>
                            <li>Hubungi teknisi komputer profesional</li>
                        </ol>
                    </div>
                </div>
                <?php else: ?>
                <div class="card shadow mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-check-circle"></i> 
                            Hasil Diagnosa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="text-muted mb-2">DIAGNOSA:</h6>
                                <div class="alert alert-success alert-permanent mb-0">
                                    <h4 class="mb-0">
                                        <i class="bi bi-exclamation-triangle-fill"></i> 
                                        <?php if(!empty($kode_kerusakan)) echo "[$kode_kerusakan] "; ?>
                                        <?php echo $diagnosa['hasil_kerusakan']; ?>
                                    </h4>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <h6 class="text-muted mb-2">SOLUSI PENANGANAN:</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <?php if(!empty($solusi)): ?>
                                        <div style="white-space: pre-line; line-height: 1.8;">
                                            <?php echo $solusi; ?>
                                        </div>
                                        <?php else: ?>
                                        <em class="text-muted">Solusi tidak tersedia</em>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Tombol Aksi -->
                <div class="card shadow">
                    <div class="card-body text-center">
                        <a href="diagnosa.php" class="btn btn-primary btn-lg me-2">
                            <i class="bi bi-plus-circle"></i> Diagnosa Baru
                        </a>
                        <a href="riwayat_diagnosa.php" class="btn btn-info btn-lg me-2">
                            <i class="bi bi-clock-history"></i> Riwayat Diagnosa
                        </a>
                        <button onclick="window.print()" class="btn btn-success btn-lg">
                            <i class="bi bi-printer"></i> Cetak Hasil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
</body>
</html>
