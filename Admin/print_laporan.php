<?php
require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

$filter_tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';
$filter_asisten = isset($_GET['asisten']) ? $_GET['asisten'] : '';

$query = "SELECT d.*, u.nama_lengkap, u.username, k.kategori 
          FROM diagnosa d 
          INNER JOIN users u ON d.id_user = u.id_user 
          LEFT JOIN kerusakan k ON d.hasil_kerusakan = k.nama_kerusakan 
          WHERE 1=1";

if (!empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir)) {
    $query .= " AND DATE(d.tanggal) BETWEEN '$filter_tanggal_mulai' AND '$filter_tanggal_akhir'";
}

if (!empty($filter_asisten)) {
    $query .= " AND d.id_user = '$filter_asisten'";
}

$query .= " ORDER BY d.tanggal DESC";

$result = mysqli_query($koneksi, $query);

// Ambil nama asisten jika ada
$asisten_nama = '';
if (!empty($filter_asisten)) {
    $q = "SELECT nama_lengkap FROM users WHERE id_user = '".mysqli_real_escape_string($koneksi, $filter_asisten)."' LIMIT 1";
    $r = mysqli_query($koneksi, $q);
    if ($row = mysqli_fetch_assoc($r)) $asisten_nama = $row['nama_lengkap'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Diagnosa Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: #fff; }
            .container-print { width: 100% !important; padding: 0 !important; }
        }
        body { padding: 20px; background-color: #f8f9fa; }
        .container-print { background: #fff; padding: 15px 25px 25px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-top: 0; }
        .report-header {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin-top: 0;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mb-3 no-print">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center bg-white p-3 rounded shadow-sm border">
            <div>
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Export Laporan Diagnosa</h5>
                <small class="text-muted">Format Kertas: A4 (Portrait)</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button id="btnDownloadPDFAdmin" onclick="downloadPDFAdmin()" class="btn btn-success shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF (A4)
                </button>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="bi bi-printer me-1"></i> Cetak Browser
                </button>
            </div>
        </div>
    </div>

    <div class="container container-print" id="laporanContent">
        <div class="text-center mb-3 report-header" style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 8px 0; margin-top: 0; margin-bottom: 15px;">
            <h4 class="mb-1 fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 1.2rem;">LAPORAN DIAGNOSA TROUBLESHOOTING KOMPUTER</h4>
            <p class="mb-0 text-dark fs-6">Pondok Pesantren Al-Gontory</p>
        </div>
        
        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <table class="table table-borderless table-sm mb-0 fs-6">
                    <tr>
                        <td width="35%" class="fw-bold text-muted">Periode Filter</td>
                        <td>: <?php echo !empty($filter_tanggal_mulai) && !empty($filter_tanggal_akhir) ?
                                    date('d/m/Y', strtotime($filter_tanggal_mulai)).' s/d '.date('d/m/Y', strtotime($filter_tanggal_akhir)) : 'Semua Data'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Asisten Lab</td>
                        <td>: <?php echo !empty($asisten_nama) ? htmlspecialchars($asisten_nama) : 'Semua Asisten'; ?></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Tanggal Cetak</td>
                        <td>: <span id="tanggalCetakAdmin"><?php echo date('d F Y, H:i:s'); ?> WIB</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Total Diagnosa</td>
                        <td>: <strong><?php echo mysqli_num_rows($result); ?> data</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-4">
                <thead class="table-light">
                    <tr>
                        <th style="color:#000 !important" width="5%">No</th>
                        <th style="color:#000 !important" width="16%">Tanggal</th>
                        <th style="color:#000 !important" width="22%">Asisten Lab</th>
                        <th style="color:#000 !important" width="24%">Hasil Kerusakan</th>
                        <th style="color:#000 !important" width="13%">Kategori</th>
                        <th style="color:#000 !important" width="20%">Gejala</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id_diagnosa'];
                            $qg = "SELECT g.kode_gejala, g.nama_gejala FROM diagnosa_detail dd INNER JOIN gejala g ON dd.id_gejala = g.id_gejala WHERE dd.id_diagnosa = '$id'";
                            $rg = mysqli_query($koneksi, $qg);
                            $gejala_list = [];
                            while($gg = mysqli_fetch_assoc($rg)) $gejala_list[] = $gg['kode_gejala'].' - '.$gg['nama_gejala'];
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['nama_lengkap']); ?></strong><br><small class="text-muted">(<?php echo htmlspecialchars($row['username']); ?>)</small></td>
                                <td><strong><?php echo !empty($row['hasil_kerusakan']) ? htmlspecialchars($row['hasil_kerusakan']) : '-'; ?></strong></td>
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
                                <td style="font-size: 12px;"><?php echo !empty($gejala_list) ? implode('<br>', $gejala_list) : '-'; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data diagnosa</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4 pt-2 pe-3 float-end text-end" style="width: 250px; page-break-inside: avoid;">
            <div class="col-12">
                <p class="mb-0 text-center">
                    <strong>Administrator Lab,</strong><br><br><br><br>
                    <strong><u><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></u></strong><br>
                    <small class="text-muted">Admin Panel</small>
                </p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <script src="../Assets/js/html2pdf.bundle.min.js"></script>
    <script>
        function updateClockAdmin() {
            const elAdmin = document.getElementById('tanggalCetakAdmin');
            if (elAdmin) {
                const now = new Date();
                const tanggalWib = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' });
                const waktuWib = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' });
                elAdmin.textContent = tanggalWib + ', ' + waktuWib + ' WIB';
            }
        }

        function downloadPDFAdmin() {
            updateClockAdmin();
            const btn = document.getElementById('btnDownloadPDFAdmin');
            const originalHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengunduh PDF...';
            }

            const element = document.getElementById('laporanContent');
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const tglStr = `${year}-${month}-${day}`;
            
            const filename = `Laporan_Diagnosa_Admin_${tglStr}.pdf`;

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false, scrollY: 0, scrollX: 0 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            }).catch(function(err) {
                console.error("Gagal generate PDF admin, fallback ke window.print()", err);
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                window.print();
            });
        }

        window.onload = function() {
            updateClockAdmin();
            setInterval(updateClockAdmin, 1000);
        };
    </script>
</body>
</html>

