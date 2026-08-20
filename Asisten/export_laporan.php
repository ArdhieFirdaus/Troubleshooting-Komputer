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
$query = "SELECT d.*, k.kategori 
          FROM diagnosa d 
          LEFT JOIN kerusakan k ON d.hasil_kerusakan = k.nama_kerusakan 
          WHERE d.id_user = '$id_user'";

if (!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
    $query .= " AND DATE(d.tanggal) BETWEEN '$filter_tanggal_mulai' AND '$filter_tanggal_akhir'";
}

$query .= " ORDER BY d.tanggal DESC";
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
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
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
                    <button id="btnDownloadPDF" onclick="downloadPDF()" class="btn btn-success btn-lg shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak / Download PDF (A4)
                    </button>
                </div>
                
                <div class="card shadow-sm" id="laporanContent" style="background-color: #ffffff; padding: 20px; border-radius: 8px;">
                    <div class="card-body p-2">
                        <div class="container-fluid">
                            <div class="text-center mb-3 report-header" style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 8px 0; margin-top: 0; margin-bottom: 15px;">
                                <h4 class="mb-1 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 1.2rem;">LAPORAN DIAGNOSA TROUBLESHOOTING KOMPUTER</h4>
                                <p class="mb-0 text-dark fs-6">Pondok Pesantren Al-Gontory</p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <table class="table table-borderless table-sm mb-3">
                                        <tr>
                                            <td width="35%" class="fw-bold text-muted">Nama Asisten</td>
                                            <td>: <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Username</td>
                                            <td>: <?php echo htmlspecialchars($_SESSION['username']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Periode Filter</td>
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
                                            <td class="fw-bold text-muted">Tanggal Cetak</td>
                                            <td>: <span id="tanggalCetak"><?php echo date('d F Y, H:i:s'); ?> WIB</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Total Diagnosa</td>
                                            <td>: <strong><?php echo mysqli_num_rows($result_diagnosa); ?> data</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <?php if(mysqli_num_rows($result_diagnosa) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-4">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="color:#000 !important" width="5%" class="text-center">No</th>
                                            <th style="color:#000 !important" width="18%">Tanggal</th>
                                            <th style="color:#000 !important" width="27%">Hasil Kerusakan</th>
                                            <th style="color:#000 !important" width="15%">Kategori</th>
                                            <th style="color:#000 !important" width="35%">Gejala yang Dipilih</th>
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
                                            <td class="text-center"><?php echo $no++; ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                            <td><strong><?php echo htmlspecialchars($row['hasil_kerusakan']); ?></strong></td>
                                            <td>
                                                <?php 
                                                if ($row['hasil_kerusakan'] == 'Kerusakan Tidak Teridentifikasi'):
                                                    echo '<span class="badge bg-secondary">Tidak Diketahui</span>';
                                                else:
                                                    $kat = isset($row['kategori']) && !empty($row['kategori']) ? $row['kategori'] : 'Hardware';
                                                    if ($kat == 'Hardware'): 
                                                        echo '<span class="badge bg-primary">Hardware</span>';
                                                    else: 
                                                        echo '<span class="badge bg-success">Software</span>';
                                                    endif;
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <ol style="margin: 0; padding-left: 20px; font-size: 12px;">
                                                    <?php while($g = mysqli_fetch_assoc($result_gejala)): ?>
                                                    <li><?php echo htmlspecialchars($g['kode_gejala']) . ' - ' . htmlspecialchars($g['nama_gejala']); ?></li>
                                                    <?php endwhile; ?>
                                                </ol>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i> 
                                Tidak ada data diagnosa pada periode yang dipilih.
                            </div>
                            <?php endif; ?>

                            <div class="row mt-4 pt-2 pe-3 float-end text-end" style="width: 250px; page-break-inside: avoid;">
                                <div class="col-12">
                                    <p class="mb-0 text-center">
                                        <strong>Mengetahui,</strong><br><br><br><br>
                                        <strong><u><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></u></strong><br>
                                        <small class="text-muted">Asisten Lab</small>
                                    </p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
    <script src="../Assets/js/html2pdf.bundle.min.js"></script>
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

        function downloadPDF() {
            updateTanggalCetak();
            const btn = document.getElementById('btnDownloadPDF');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengunduh PDF...';

            const element = document.getElementById('laporanContent');
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const tglStr = `${year}-${month}-${day}`;
            
            const rawNama = "<?php echo addslashes($_SESSION['nama_lengkap']); ?>";
            const cleanNama = rawNama.trim().replace(/[^a-zA-Z0-9]/g, '_');
            const filename = `Laporan_Diagnosa_Asisten_${cleanNama}_${tglStr}.pdf`;

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false, scrollY: 0, scrollX: 0 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };


            html2pdf().set(opt).from(element).save().then(function() {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }).catch(function(err) {
                console.error("Gagal generate PDF, fallback ke window.print()", err);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                window.print();
            });
        }

        // Pastikan waktu cetak selalu update selama halaman terbuka.
        updateTanggalCetak();
        setInterval(updateTanggalCetak, 1000);
        window.addEventListener('beforeprint', updateTanggalCetak);
    </script>
</body>
</html>

