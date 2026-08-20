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

// Ambil solusi & kategori dari kerusakan (jika teridentifikasi)
$solusi = '';
$kode_kerusakan = '';
$kategori = '';
if ($diagnosa['hasil_kerusakan'] != 'Kerusakan Tidak Teridentifikasi') {
    $nama_kerusakan = mysqli_real_escape_string($koneksi, $diagnosa['hasil_kerusakan']);
    $query_solusi = "SELECT * FROM kerusakan WHERE nama_kerusakan = '$nama_kerusakan'";
    $result_solusi = mysqli_query($koneksi, $query_solusi);
    
    if (mysqli_num_rows($result_solusi) > 0) {
        $kerusakan_data = mysqli_fetch_assoc($result_solusi);
        $solusi = $kerusakan_data['solusi'];
        $kode_kerusakan = $kerusakan_data['kode_kerusakan'];
        $kategori = !empty($kerusakan_data['kategori']) ? $kerusakan_data['kategori'] : 'Hardware';
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
    <style>
        .report-header {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin-bottom: 12px;
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
                    <span class="navbar-brand mb-0 h1 ms-3">Hasil Diagnosa</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar; kept user name only -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                    <h2 class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>Hasil Diagnosa Komputer
                    </h2>
                </div>
                
                <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show no-print">
                    <i class="bi bi-check-circle me-1"></i> Diagnosa berhasil diproses!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div id="printableHasilArea" style="background:#ffffff; padding: 15px; border-radius: 8px;">
                    <!-- Kop Surat Cetak PDF -->
                    <div class="text-center mb-3 report-header" style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 8px 0; margin-top: 0; margin-bottom: 15px;">
                        <h4 class="mb-1 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 1.2rem;">LAPORAN DIAGNOSA TROUBLESHOOTING KOMPUTER</h4>
                        <p class="mb-0 text-dark fs-6">Pondok Pesantren Al-Gontory</p>
                    </div>

                
                    <!-- Informasi Diagnosa -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Diagnosa</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tr>
                                                <td width="35%" class="fw-bold text-muted">ID Diagnosa</td>
                                                <td>: <strong>#<?php echo htmlspecialchars($diagnosa['id_diagnosa']); ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold text-muted">Tanggal</td>
                                                <td>: <?php echo date('d F Y, H:i', strtotime($diagnosa['tanggal'])); ?> WIB</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold text-muted">Asisten Lab</td>
                                                <td>: <strong><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gejala yang Dipilih -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Gejala yang Dipilih</h5>
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
                                            <strong class="text-primary"><?php echo htmlspecialchars($gejala['kode_gejala']); ?></strong> - 
                                            <?php echo htmlspecialchars($gejala['nama_gejala']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <hr class="my-2">
                            <p class="mb-0 text-muted">
                                <strong>Total Gejala:</strong> <?php echo $counter; ?> gejala terpilih
                            </p>
                        </div>
                    </div>
                    
                    <!-- Hasil Diagnosa -->
                    <?php if($not_found): ?>
                    <div class="card shadow-sm mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>Hasil Diagnosa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning alert-permanent">
                                <h5><i class="bi bi-exclamation-triangle me-2"></i>Kerusakan Tidak Teridentifikasi</h5>
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
                    <div class="card shadow-sm mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-check-circle me-2"></i>Hasil Diagnosa Teridentifikasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-muted mb-0 fw-bold">DIAGNOSA KERUSAKAN:</h6>
                                        <?php if(!empty($kategori)): ?>
                                            <?php if($kategori == 'Hardware'): ?>
                                                <span class="badge bg-primary fs-6"><i class="bi bi-cpu me-1"></i> Kategori: Hardware</span>
                                            <?php else: ?>
                                                <span class="badge bg-success fs-6"><i class="bi bi-window-stack me-1"></i> Kategori: Software</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="alert alert-success border-0 shadow-sm p-3 mb-0">
                                        <h4 class="mb-0 fw-bold">
                                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> 
                                            <?php if(!empty($kode_kerusakan)) echo "[$kode_kerusakan] "; ?>
                                            <?php echo htmlspecialchars($diagnosa['hasil_kerusakan']); ?>
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-2 fw-bold">SOLUSI PENANGANAN:</h6>
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <?php if(!empty($solusi)): ?>
                                            <div style="white-space: pre-line; line-height: 1.8; color: #212529;">
                                                <?php echo htmlspecialchars($solusi); ?>
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

                    <!-- Tanda tangan untuk PDF -->
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
                
                <!-- Tombol Aksi Responsive -->
                <div class="card shadow-sm border-0 no-print mt-4">
                    <div class="card-body p-3">
                        <div class="d-flex flex-wrap gap-2 justify-content-center align-items-center">
                            <a href="diagnosa.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-1"></i> Diagnosa Baru
                            </a>
                            <a href="riwayat_diagnosa.php" class="btn btn-info btn-lg text-white">
                                <i class="bi bi-clock-history me-1"></i> Riwayat Diagnosa
                            </a>
                            <button id="btnDownloadHasil" onclick="downloadHasilPDF()" class="btn btn-success btn-lg">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak / Download PDF (A4)
                            </button>
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
        function downloadHasilPDF() {
            const btn = document.getElementById('btnDownloadHasil');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengunduh PDF...';

            const printArea = document.getElementById('printableHasilArea');
            const idDiagnosa = "<?php echo addslashes($diagnosa['id_diagnosa']); ?>";
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const filename = `Hasil_Diagnosa_${idDiagnosa}_${year}-${month}-${day}.pdf`;

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


