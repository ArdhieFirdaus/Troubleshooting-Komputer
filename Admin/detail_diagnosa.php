<?php
/**
 * File: detail_diagnosa.php
 * Deskripsi: Halaman detail diagnosa untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Ambil ID diagnosa
$id_diagnosa = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id_diagnosa)) {
    header("Location: laporan_diagnosa.php");
    exit();
}

// Ambil data diagnosa
$query_diagnosa = "SELECT d.*, u.nama_lengkap, u.username 
                   FROM diagnosa d 
                   INNER JOIN users u ON d.id_user = u.id_user 
                   WHERE d.id_diagnosa = '$id_diagnosa'";
$result = mysqli_query($koneksi, $query_diagnosa);

if (mysqli_num_rows($result) == 0) {
    header("Location: laporan_diagnosa.php");
    exit();
}

$diagnosa = mysqli_fetch_assoc($result);

// Ambil gejala yang dipilih
$query_gejala = "SELECT g.* FROM diagnosa_detail dd 
                 INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                 WHERE dd.id_diagnosa = '$id_diagnosa'";
$result_gejala = mysqli_query($koneksi, $query_gejala);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-file-medical"></i> Detail Diagnosa</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
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
                                <td>: <?php echo $diagnosa['nama_lengkap']; ?> (<?php echo $diagnosa['username']; ?>)</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <h5 class="mb-3"><i class="bi bi-clipboard-check"></i> Gejala yang Dipilih:</h5>
                <ol>
                    <?php while($gejala = mysqli_fetch_assoc($result_gejala)): ?>
                    <li>
                        <strong><?php echo $gejala['kode_gejala']; ?></strong> - 
                        <?php echo $gejala['nama_gejala']; ?>
                    </li>
                    <?php endwhile; ?>
                </ol>
                
                <hr>
                
                <h5 class="mb-3"><i class="bi bi-exclamation-triangle"></i> Hasil Diagnosa:</h5>
                <div class="alert alert-info">
                    <h6>Kerusakan:</h6>
                    <p class="mb-0"><strong><?php echo $diagnosa['hasil_kerusakan']; ?></strong></p>
                </div>
                
                <div class="no-print mt-4">
                    <button onclick="window.print()" class="btn btn-success">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <button onclick="window.close()" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
