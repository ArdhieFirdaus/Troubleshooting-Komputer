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
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .report-header {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin-bottom: 15px;
        }
        .info-table td { padding: 4px 8px; }
    </style>
</head>
<body>
    <div class="container my-3 my-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 no-print">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-medical me-2"></i>Detail Diagnosa Komputer</h5>
                <span class="badge bg-light text-primary fs-6">ID: #<?php echo htmlspecialchars($diagnosa['id_diagnosa']); ?></span>
            </div>
            
            <div class="card-body p-3 p-md-4" id="printDetailArea" style="background: #ffffff;">
                <!-- Kop Surat untuk versi cetak/PDF -->
                <div class="text-center mb-3 report-header" style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 8px 0; margin-top: 0; margin-bottom: 15px;">
                    <h4 class="mb-1 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 1.2rem;">LAPORAN DIAGNOSA TROUBLESHOOTING KOMPUTER</h4>
                    <p class="mb-0 text-dark fs-6">Pondok Pesantren Al-Gontory</p>
                </div>


                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-8">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm info-table mb-0">
                                <tr>
                                    <td width="35%" class="text-muted fw-bold">ID Diagnosa</td>
                                    <td>: <span class="fw-semibold text-dark">#<?php echo htmlspecialchars($diagnosa['id_diagnosa']); ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Tanggal Diagnosa</td>
                                    <td>: <?php echo date('d F Y, H:i', strtotime($diagnosa['tanggal'])); ?> WIB</td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Asisten Lab</td>
                                    <td>: <strong><?php echo htmlspecialchars($diagnosa['nama_lengkap']); ?></strong> <span class="text-muted">(<?php echo htmlspecialchars($diagnosa['username']); ?>)</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clipboard-check text-primary me-2"></i>Gejala yang Dipilih:</h6>
                <div class="table-responsive mb-4">
                    <ol class="list-group list-group-numbered list-group-flush">
                        <?php while($gejala = mysqli_fetch_assoc($result_gejala)): ?>
                        <li class="list-group-item bg-transparent py-2 border-bottom-0">
                            <strong class="text-primary me-1"><?php echo htmlspecialchars($gejala['kode_gejala']); ?></strong> - 
                            <?php echo htmlspecialchars($gejala['nama_gejala']); ?>
                        </li>
                        <?php endwhile; ?>
                    </ol>
                </div>
                
                <hr class="my-3">
                
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Hasil Diagnosa:</h6>
                <div class="alert alert-info border-0 shadow-sm p-3 mb-4">
                    <small class="text-uppercase fw-bold text-muted d-block mb-1">Hasil Kerusakan Terdeteksi:</small>
                    <h5 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($diagnosa['hasil_kerusakan']); ?></h5>
                </div>

                <div class="row mt-5 pt-3 pe-3 text-end float-end" style="width: 250px; page-break-inside: avoid;">
                    <div class="col-12">
                        <p class="mb-0 text-center">
                            <strong>Administrator Lab,</strong><br><br><br><br>
                            <strong><u><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></u></strong><br>
                            <small class="text-muted">Admin Panel</small>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <!-- Tombol Aksi Responsive -->
            <div class="card-footer bg-light p-3 no-print border-top">
                <div class="d-flex flex-wrap gap-2 justify-content-end align-items-center">
                    <button id="btnDownloadDetail" onclick="downloadDetailPDF()" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak / Download PDF (A4)
                    </button>
                    <button onclick="window.close()" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-circle me-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/html2pdf.bundle.min.js"></script>
    <script>
        function downloadDetailPDF() {
            const btn = document.getElementById('btnDownloadDetail');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengunduh PDF...';

            const printArea = document.getElementById('printDetailArea');
            const idDiagnosa = "<?php echo addslashes($diagnosa['id_diagnosa']); ?>";
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const filename = `Detail_Diagnosa_${idDiagnosa}_${year}-${month}-${day}.pdf`;

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(printArea).save().then(function() {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }).catch(function(err) {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                window.print();
            });
        }
    </script>
</body>
</html>


