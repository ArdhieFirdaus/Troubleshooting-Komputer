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
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
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
                        <!-- Logout moved to sidebar -->
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

                <div class="mb-3 no-print">
                    <button id="btnPrintAdmin" class="btn btn-success btn-lg shadow-sm">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak / Download PDF (A4)
                    </button>
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
                                        <th width="25%">Hasil Kerusakan</th>
                                        <th width="12%">Kategori</th>
                                        <th width="15%">Gejala yang Dipilih</th>
                                        <th width="13%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (mysqli_num_rows($result_diagnosa) > 0):
                                        $no = 1;
                                        $all_diagnosa = []; // Simpan semua data untuk modal
                                        while($row = mysqli_fetch_assoc($result_diagnosa)): 
                                            // Ambil gejala yang dipilih
                                            $id_diagnosa = $row['id_diagnosa'];
                                            $query_gejala = "SELECT g.kode_gejala, g.nama_gejala 
                                                           FROM diagnosa_detail dd 
                                                           INNER JOIN gejala g ON dd.id_gejala = g.id_gejala 
                                                           WHERE dd.id_diagnosa = '$id_diagnosa'";
                                            $result_gejala = mysqli_query($koneksi, $query_gejala);
                                            
                                            // Simpan gejala ke array
                                            $gejala_array = [];
                                            while($temp_gejala = mysqli_fetch_assoc($result_gejala)) {
                                                $gejala_array[] = $temp_gejala;
                                            }
                                            $gejala_count = count($gejala_array);
                                            
                                            // Simpan ke array utama
                                            $row['gejala_list'] = $gejala_array;
                                            $all_diagnosa[] = $row;
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['nama_lengkap']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['username']); ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                            $hasil = htmlspecialchars($row['hasil_kerusakan']);
                                            echo !empty($hasil) ? $hasil : '<em class="text-muted">Tidak ada hasil</em>'; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($row['hasil_kerusakan'] == 'Kerusakan Tidak Teridentifikasi'):
                                            ?>
                                                <span class="badge bg-secondary"><i class="bi bi-question-circle"></i> Tidak Diketahui</span>
                                            <?php 
                                            else:
                                                $kat = isset($row['kategori']) && !empty($row['kategori']) ? $row['kategori'] : 'Hardware';
                                                if ($kat == 'Hardware'): 
                                                ?>
                                                    <span class="badge bg-primary"><i class="bi bi-cpu"></i> Hardware</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><i class="bi bi-window-stack"></i> Software</span>
                                                <?php endif; 
                                            endif;
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalGejala<?php echo $row['id_diagnosa']; ?>">
                                                <i class="bi bi-eye"></i> Lihat <?php echo $gejala_count; ?> Gejala
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDetail<?php echo $row['id_diagnosa']; ?>">
                                                <i class="bi bi-file-text"></i> Detail
                                            </button>
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

    <!-- Modal untuk preview cetak -->
    <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-file-earmark-pdf me-2"></i>Preview Cetak Laporan (A4)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="printModalBody">Memuat...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="printFromModalBtn" class="btn btn-success">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF (A4)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals - Letakkan di luar tabel -->
    <?php if (!empty($all_diagnosa)): ?>
        <?php foreach($all_diagnosa as $diagnosa_item): ?>
            <!-- Modal Detail Gejala -->
            <div class="modal fade" id="modalGejala<?php echo $diagnosa_item['id_diagnosa']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-clipboard-check"></i> Detail Gejala
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="mb-3">Gejala yang dipilih:</h6>
                            <?php if (!empty($diagnosa_item['gejala_list'])): ?>
                                <ol>
                                    <?php foreach($diagnosa_item['gejala_list'] as $g): ?>
                                    <li class="mb-2">
                                        <strong><?php echo htmlspecialchars($g['kode_gejala']); ?></strong> - 
                                        <?php echo htmlspecialchars($g['nama_gejala']); ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada gejala yang tercatat.</p>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Detail Diagnosa -->
            <div class="modal fade" id="modalDetail<?php echo $diagnosa_item['id_diagnosa']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-file-medical"></i> Detail Diagnosa
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%"><strong>ID Diagnosa</strong></td>
                                            <td>: <?php echo htmlspecialchars($diagnosa_item['id_diagnosa']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal</strong></td>
                                            <td>: <?php echo date('d F Y, H:i', strtotime($diagnosa_item['tanggal'])); ?> WIB</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Asisten Lab</strong></td>
                                            <td>: <?php echo htmlspecialchars($diagnosa_item['nama_lengkap']); ?> (<?php echo htmlspecialchars($diagnosa_item['username']); ?>)</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h6><i class="bi bi-clipboard-check"></i> Gejala yang Dipilih:</h6>
                            <?php if (!empty($diagnosa_item['gejala_list'])): ?>
                                <ol>
                                    <?php foreach($diagnosa_item['gejala_list'] as $gd): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($gd['kode_gejala']); ?></strong> - 
                                        <?php echo htmlspecialchars($gd['nama_gejala']); ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada gejala yang tercatat.</p>
                            <?php endif; ?>
                            
                            
                            <hr>
                            
                            <h6><i class="bi bi-exclamation-triangle"></i> Hasil Diagnosa:</h6>
                            <div class="alert alert-info alert-permanent">
                                <h6>Kerusakan:</h6>
                                <p class="mb-0">
                                    <strong>
                                        <?php 
                                        if (isset($diagnosa_item['hasil_kerusakan']) && !empty($diagnosa_item['hasil_kerusakan'])) {
                                            echo htmlspecialchars($diagnosa_item['hasil_kerusakan']);
                                        } else {
                                            echo "Data hasil kerusakan tidak tersedia";
                                        }
                                        ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
    <script src="../Assets/js/html2pdf.bundle.min.js"></script>
    <script>
        function buildPrintUrl() {
            const tglMulai = document.getElementById('tanggal_mulai').value;
            const tglAkhir = document.getElementById('tanggal_akhir').value;
            const asisten = document.getElementById('asisten').value;
            const params = new URLSearchParams();
            if (tglMulai) params.append('tanggal_mulai', tglMulai);
            if (tglAkhir) params.append('tanggal_akhir', tglAkhir);
            if (asisten) params.append('asisten', asisten);
            return 'print_laporan.php?' + params.toString();
        }

        async function downloadAdminPDFDirectly() {
            const btn = document.getElementById('btnPrintAdmin');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengunduh PDF...';

            const url = buildPrintUrl();
            try {
                const res = await fetch(url, { credentials: 'same-origin' });
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.getElementById('laporanContent');

                if (!content) {
                    throw new Error("Elemen konten laporan tidak ditemukan.");
                }

                // Temporary container offscreen with fixed 0 top position
                const tempDiv = document.createElement('div');
                tempDiv.style.position = 'fixed';
                tempDiv.style.left = '-9999px';
                tempDiv.style.top = '0px';
                tempDiv.style.width = '210mm'; // A4 width
                tempDiv.style.backgroundColor = '#ffffff';
                tempDiv.style.margin = '0';
                tempDiv.style.padding = '0';
                tempDiv.innerHTML = content.outerHTML;
                document.body.appendChild(tempDiv);

                // Update timestamp WIB
                const tanggalEl = tempDiv.querySelector('#tanggalCetakAdmin');
                if (tanggalEl) {
                    const now = new Date();
                    const tanggal = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' });
                    const waktu = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' });
                    tanggalEl.textContent = `${tanggal}, ${waktu} WIB`;
                }

                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const filename = `Laporan_Diagnosa_Admin_${year}-${month}-${day}.pdf`;

                const opt = {
                    margin:       [8, 8, 8, 8],
                    filename:     filename,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true, logging: false, scrollY: 0, scrollX: 0 },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
                };

                await html2pdf().set(opt).from(tempDiv.firstChild).save();
                document.body.removeChild(tempDiv);

                btn.disabled = false;
                btn.innerHTML = originalHtml;
            } catch (err) {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                // Fallback to opening print preview in new window/tab if pdf generation encounters error
                window.open(url, '_blank');
            }
        }

        document.getElementById('btnPrintAdmin').addEventListener('click', downloadAdminPDFDirectly);

        // PDF download option inside preview modal if opened
        document.getElementById('printFromModalBtn').addEventListener('click', function() {
            const modalBody = document.getElementById('printModalBody');
            const targetEl = modalBody.querySelector('#laporanContent') || modalBody;
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const filename = `Laporan_Diagnosa_Admin_${year}-${month}-${day}.pdf`;

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false, scrollY: 0, scrollX: 0 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(targetEl).save();
        });

    </script>
</body>
</html>

