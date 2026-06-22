<?php
/**
 * File: export_laporan.php
 * Deskripsi: Export laporan diagnosa (HTML dengan fungsi print untuk PDF)
 * Note: Untuk export PDF sesungguhnya, bisa gunakan library TCPDF/FPDF
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

date_default_timezone_set('Asia/Jakarta');

$id_user = $_SESSION['id_user'];

// Filter berdasarkan tanggal
$filter_tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Build query
$query = "SELECT * FROM diagnosa WHERE id_user = '$id_user'";

if (!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
    $query .= " AND DATE(tanggal) BETWEEN '$filter_tanggal_mulai' AND '$filter_tanggal_akhir'";
}

$query .= " ORDER BY tanggal DESC";
$result_diagnosa = mysqli_query($koneksi, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Laporan - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .card { border: 1px solid #000 !important; }
            /* remove outer card border/rounding for the main laporan content */
            #laporanContent.card { border: none !important; box-shadow: none !important; border-radius: 0 !important; }
        }
        /* remove outer card border/rounding on screen for laporanContent */
        #laporanContent.card { border: none; box-shadow: none; border-radius: 0; }
        .report-header { border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 8px 0; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar_asisten.php'; ?>
        
        <div class="main-content">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom no-print">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">Export Laporan</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <!-- Judul halaman dihilangkan sesuai permintaan -->
                
                <!-- Filter -->
                <div class="card shadow mb-4 no-print">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" 
                                           name="tanggal_mulai" value="<?php echo $filter_tanggal_mulai; ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" 
                                           name="tanggal_akhir" value="<?php echo $filter_tanggal_akhir; ?>">
                                </div>
                                
                                <div class="col-md-4 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                            
                            <?php if(!empty($filter_tanggal_mulai)): ?>
                            <a href="export_laporan.php" class="btn btn-secondary btn-sm">
                                <i class="bi bi-x-circle"></i> Reset Filter
                            </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <div class="mb-3 no-print">
                    <button onclick="prepareAndPrint()" class="btn btn-success btn-lg">
                        <i class="bi bi-printer"></i> Cetak / Save as PDF
                    </button>
                </div>
                
                <!-- Laporan (desain disesuaikan seperti Admin print) -->
                <div class="card shadow" id="laporanContent">
                    <div class="card-body">
                        <div class="container">
                            <div class="text-center mb-3 report-header">
                                <h4 class="mb-0">LAPORAN DIAGNOSA TROUBLESHOOTING<br>KOMPUTER</h4>
                                <p class="mb-0">Pondok Pesantren Al-Gontory</p>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Nama Asisten</strong></td>
                                            <td>: <?php echo $_SESSION['nama_lengkap']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Username</strong></td>
                                            <td>: <?php echo $_SESSION['username']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Periode</strong></td>
                                            <td>: 
                                                <?php 
                                                if(!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
                                                    echo date('d/m/Y', strtotime($filter_tanggal_mulai)) . ' s/d ' . 
                                                         date('d/m/Y', strtotime($filter_tanggal_akhir));
                                                } else {
                                                    echo "Semua Data";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Cetak</strong></td>
                                            <td>: <span id="tanggalCetak"><?php echo date('d F Y, H:i:s'); ?> WIB</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Diagnosa</strong></td>
                                            <td>: <?php echo mysqli_num_rows($result_diagnosa); ?> diagnosa</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Ringkasan filter dihapus -->

                            <?php if(mysqli_num_rows($result_diagnosa) > 0): ?>
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="color:#000 !important">No</th>
                                        <th style="color:#000 !important">Tanggal</th>
                                        <th style="color:#000 !important">Hasil Kerusakan</th>
                                        <th style="color:#000 !important">Gejala yang Dipilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while($row = mysqli_fetch_assoc($result_diagnosa)): 
                                        $id_diagnosa = $row['id_diagnosa'];
                                        $query_gejala = "SELECT g.kode_gejala, g.nama_gejala 
                                                       FROM diagnosa_detail dd 
                                                       INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                                                       WHERE dd.id_diagnosa = '$id_diagnosa'";
                                        $result_gejala = mysqli_query($koneksi, $query_gejala);
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td><strong><?php echo $row['hasil_kerusakan']; ?></strong></td>
                                        <td>
                                            <ol style="margin: 0; padding-left: 20px; font-size: 12px;">
                                                <?php while($g = mysqli_fetch_assoc($result_gejala)): ?>
                                                <li><?php echo $g['kode_gejala'] . ' - ' . $g['nama_gejala']; ?></li>
                                                <?php endwhile; ?>
                                            </ol>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> 
                                Tidak ada data diagnosa pada periode yang dipilih.
                            </div>
                            <?php endif; ?>

                            <div class="mt-4 text-end">
                                <p class="mb-0">
                                    <strong>Mengetahui,</strong><br><br><br><br>
                                    ______________________<br>
                                    Asisten Lab
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
    <script>
        function getWibDateTimeText() {
            const now = new Date();
            const tanggal = now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            });
            const waktu = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            });

            return `${tanggal}, ${waktu} WIB`;
        }

        function updateTanggalCetak() {
            const tanggalCetak = document.getElementById('tanggalCetak');
            if (tanggalCetak) {
                tanggalCetak.textContent = getWibDateTimeText();
            }
        }

        function prepareAndPrint() {
            updateTanggalCetak();
            window.print();
        }

        // Pastikan waktu cetak selalu update selama halaman terbuka.
        updateTanggalCetak();
        setInterval(updateTanggalCetak, 1000);
        window.addEventListener('beforeprint', updateTanggalCetak);


    </script>
</body>
</html>
