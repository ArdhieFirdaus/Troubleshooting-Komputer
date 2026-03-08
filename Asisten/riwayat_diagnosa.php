<?php
/**
 * File: riwayat_diagnosa.php
 * Deskripsi: Halaman riwayat diagnosa untuk Asisten Lab
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

$id_user = $_SESSION['id_user'];

// Ambil semua diagnosa milik user ini
$query = "SELECT * FROM diagnosa WHERE id_user = '$id_user' ORDER BY tanggal DESC";
$result_diagnosa = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <style>
        /* Fix z-index agar modal tidak tertutup backdrop */
        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
    </style>
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
                    <span class="navbar-brand mb-0 h1 ms-3">Riwayat Diagnosa</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <a href="../Auth/logout.php" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">
                    <i class="bi bi-clock-history"></i> 
                    Riwayat Diagnosa Saya
                </h2>
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-table"></i> Daftar Diagnosa
                            <span class="badge bg-light text-dark float-end">
                                Total: <?php echo mysqli_num_rows($result_diagnosa); ?> diagnosa
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($result_diagnosa) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">ID</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="40%">Hasil Kerusakan</th>
                                        <th width="12%">Jumlah Gejala</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while($row = mysqli_fetch_assoc($result_diagnosa)): 
                                        // Hitung jumlah gejala
                                        $id_diagnosa = $row['id_diagnosa'];
                                        $query_count = "SELECT COUNT(*) as total FROM diagnosa_detail WHERE id_diagnosa = '$id_diagnosa'";
                                        $result_count = mysqli_query($koneksi, $query_count);
                                        $count = mysqli_fetch_assoc($result_count)['total'];
                                        
                                        // Tentukan badge warna
                                        $badge_class = ($row['hasil_kerusakan'] == 'Kerusakan Tidak Teridentifikasi') ? 'bg-warning' : 'bg-success';
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $row['id_diagnosa']; ?></strong></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $badge_class; ?>">
                                                <?php echo $row['hasil_kerusakan']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo $count; ?> gejala</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="hasil_diagnosa.php?id=<?php echo $row['id_diagnosa']; ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalGejala<?php echo $row['id_diagnosa']; ?>">
                                                <i class="bi bi-list-ul"></i> Gejala
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 64px;"></i>
                            <h5 class="mt-3">Belum Ada Riwayat Diagnosa</h5>
                            <p>Anda belum pernah melakukan diagnosa. Mulai sekarang!</p>
                            <a href="diagnosa.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Buat Diagnosa Baru
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if(mysqli_num_rows($result_diagnosa) > 0): ?>
                <div class="mt-3">
                    <a href="diagnosa.php" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Diagnosa Baru
                    </a>
                    <a href="export_laporan.php" class="btn btn-info">
                        <i class="bi bi-file-earmark-pdf"></i> Export Laporan
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Gejala: diletakkan di luar .wrapper agar tidak terjebak stacking context dari transform CSS -->
    <?php 
    if(mysqli_num_rows($result_diagnosa) > 0):
        mysqli_data_seek($result_diagnosa, 0);
        while($row = mysqli_fetch_assoc($result_diagnosa)): 
            $id_diagnosa = $row['id_diagnosa'];
    ?>
    <div class="modal fade" id="modalGejala<?php echo $id_diagnosa; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $id_diagnosa; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel<?php echo $id_diagnosa; ?>">
                        <i class="bi bi-list-check"></i> Gejala - Diagnosa #<?php echo $id_diagnosa; ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $query_gejala = "SELECT g.* FROM diagnosa_detail dd 
                                   INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                                   WHERE dd.id_diagnosa = '$id_diagnosa'";
                    $result_gejala = mysqli_query($koneksi, $query_gejala);
                    ?>
                    <h6>Gejala yang dipilih:</h6>
                    <ol>
                        <?php while($g = mysqli_fetch_assoc($result_gejala)): ?>
                        <li>
                            <strong><?php echo $g['kode_gejala']; ?></strong> - 
                            <?php echo $g['nama_gejala']; ?>
                        </li>
                        <?php endwhile; ?>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php 
        endwhile;
    endif;
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js"></script>
    <script>
    // Pastikan modal bisa ditutup & bersihkan backdrop yang menumpuk
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-close, .modal-footer .btn-secondary').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var openModal = document.querySelector('.modal.show');
                if (openModal) {
                    var instance = bootstrap.Modal.getInstance(openModal);
                    if (instance) {
                        instance.hide();
                    }
                }
            });
        });

        // Bersihkan backdrop ganda jika ada
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                var backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 1) {
                    for (var i = 1; i < backdrops.length; i++) {
                        backdrops[i].remove();
                    }
                }
                if (!document.querySelector('.modal.show')) {
                    document.querySelectorAll('.modal-backdrop').forEach(function(b) { b.remove(); });
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('overflow');
                    document.body.style.removeProperty('padding-right');
                }
            });
        });
    });
    </script>
</body>
</html>
