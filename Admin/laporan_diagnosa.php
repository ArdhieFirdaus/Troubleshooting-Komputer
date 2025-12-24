<?php
/**
 * File: laporan_diagnosa.php
 * Deskripsi: Halaman laporan diagnosa untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Filter berdasarkan tanggal dan asisten
$filter_tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
$filter_asisten = isset($_GET['asisten']) ? $_GET['asisten'] : '';

// Build query dengan filter
$query = "SELECT d.*, u.nama_lengkap, u.username 
          FROM diagnosa d 
          INNER JOIN users u ON d.id_user = u.id_user 
          WHERE 1=1";

if (!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
    $query .= " AND DATE(d.tanggal) BETWEEN '$filter_tanggal_mulai' AND '$filter_tanggal_akhir'";
}

if (!empty($filter_asisten)) {
    $query .= " AND d.id_user = '$filter_asisten'";
}

$query .= " ORDER BY d.tanggal DESC";

$result_diagnosa = mysqli_query($koneksi, $query);

// Ambil daftar asisten untuk filter
$query_asisten = "SELECT * FROM users WHERE role='asisten_lab' ORDER BY nama_lengkap ASC";
$result_asisten = mysqli_query($koneksi, $query_asisten);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Diagnosa - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar_admin.php'; ?>
        
        <div class="main-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Laporan Diagnosa</span>
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
                <h2 class="mb-4">Laporan & Rekap Diagnosa</h2>
                
                <!-- Filter -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" 
                                           name="tanggal_mulai" value="<?php echo $filter_tanggal_mulai; ?>">
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" 
                                           name="tanggal_akhir" value="<?php echo $filter_tanggal_akhir; ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="asisten" class="form-label">Asisten Lab</label>
                                    <select class="form-select" id="asisten" name="asisten">
                                        <option value="">-- Semua Asisten --</option>
                                        <?php while($asisten = mysqli_fetch_assoc($result_asisten)): ?>
                                        <option value="<?php echo $asisten['id_user']; ?>" 
                                                <?php echo ($filter_asisten == $asisten['id_user']) ? 'selected' : ''; ?>>
                                            <?php echo $asisten['nama_lengkap']; ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                            
                            <?php if(!empty($filter_tanggal_mulai) || !empty($filter_asisten)): ?>
                            <a href="laporan_diagnosa.php" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Reset Filter
                            </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <!-- Tabel Laporan -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-table"></i> Data Diagnosa
                            <span class="badge bg-light text-dark float-end">
                                Total: <?php echo mysqli_num_rows($result_diagnosa); ?> diagnosa
                            </span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-info">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="12%">Tanggal</th>
                                        <th width="18%">Asisten Lab</th>
                                        <th width="30%">Hasil Kerusakan</th>
                                        <th width="20%">Gejala yang Dipilih</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (mysqli_num_rows($result_diagnosa) > 0):
                                        $no = 1;
                                        while($row = mysqli_fetch_assoc($result_diagnosa)): 
                                            // Ambil gejala yang dipilih
                                            $id_diagnosa = $row['id_diagnosa'];
                                            $query_gejala = "SELECT g.kode_gejala, g.nama_gejala 
                                                           FROM diagnosa_detail dd 
                                                           INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                                                           WHERE dd.id_diagnosa = '$id_diagnosa'";
                                            $result_gejala = mysqli_query($koneksi, $query_gejala);
                                            $gejala_count = mysqli_num_rows($result_gejala);
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td>
                                            <strong><?php echo $row['nama_lengkap']; ?></strong><br>
                                            <small class="text-muted"><?php echo $row['username']; ?></small>
                                        </td>
                                        <td><?php echo $row['hasil_kerusakan']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalGejala<?php echo $row['id_diagnosa']; ?>">
                                                <i class="bi bi-eye"></i> Lihat <?php echo $gejala_count; ?> Gejala
                                            </button>
                                            
                                            <!-- Modal Detail Gejala -->
                                            <div class="modal fade" id="modalGejala<?php echo $row['id_diagnosa']; ?>" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Detail Gejala</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h6>Gejala yang dipilih:</h6>
                                                            <ol>
                                                                <?php 
                                                                mysqli_data_seek($result_gejala, 0);
                                                                while($g = mysqli_fetch_assoc($result_gejala)): 
                                                                ?>
                                                                <li>
                                                                    <strong><?php echo $g['kode_gejala']; ?></strong> - 
                                                                    <?php echo $g['nama_gejala']; ?>
                                                                </li>
                                                                <?php endwhile; ?>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="detail_diagnosa.php?id=<?php echo $row['id_diagnosa']; ?>" 
                                               class="btn btn-info btn-sm" target="_blank">
                                                <i class="bi bi-file-text"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <em>Belum ada data diagnosa</em>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
