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

                <!-- Tombol Cetak / Export (sama dengan Asisten) -->
                <div class="mb-3 no-print">
                    <button id="btnPrintAdmin" class="btn btn-success btn-lg">
                        <i class="bi bi-printer"></i> Cetak / Save as PDF
                    </button>
                    <button id="btnExportCsvAdmin" class="btn btn-info btn-lg ms-2">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
                    </button>
                </div>

                <!-- Modal untuk preview cetak (tanpa membuka tab baru) -->
                <div class="modal fade" id="printPreviewModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Preview Cetak Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="printModalBody">Memuat...</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" id="printFromModalBtn" class="btn btn-primary">Cetak</button>
                            </div>
                        </div>
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
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js"></script>
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

        async function prepareAndPrintAdmin() {
            const url = buildPrintUrl();
            try {
                const res = await fetch(url, { credentials: 'same-origin' });
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.getElementById('laporanContent');
                const modalBody = document.getElementById('printModalBody');
                if (content) {
                    modalBody.innerHTML = content.outerHTML;
                } else {
                    modalBody.innerHTML = html;
                }

                // hapus elemen label "Filter:" jika ada (tidak perlu ditampilkan di preview karena sudah ada info di atas)
                const strongs = modalBody.querySelectorAll('strong');
                strongs.forEach(function(s){
                    if (s.textContent && s.textContent.trim().toLowerCase() === 'filter:') {
                        const parent = s.closest('.mb-3') || s.parentElement;
                        if (parent) parent.remove();
                    }
                });

                // update timestamp inside modal to current WIB
                const tanggalEl = modalBody.querySelector('#tanggalCetakAdmin');
                if (tanggalEl) {
                    const now = new Date();
                    const tanggal = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' });
                    const waktu = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' });
                    tanggalEl.textContent = `${tanggal}, ${waktu} WIB`;
                }

                const modalEl = document.getElementById('printPreviewModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

            } catch (err) {
                alert('Gagal memuat preview cetak: ' + err.message);
            }
        }

        function exportToCSVAdmin() {
            alert('Fitur export CSV belum tersedia. Preview cetak akan dibuka sebagai gantinya.');
            prepareAndPrintAdmin();
        }

        document.getElementById('btnPrintAdmin').addEventListener('click', prepareAndPrintAdmin);
        document.getElementById('btnExportCsvAdmin').addEventListener('click', exportToCSVAdmin);

        // Cetak isi modal tanpa membuka tab baru menggunakan iframe tersembunyi
        document.getElementById('printFromModalBtn').addEventListener('click', function() {
            const modalBody = document.getElementById('printModalBody');
            const printContent = modalBody.innerHTML;
            const iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            iframe.id = 'printIframe';
            document.body.appendChild(iframe);
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`<!doctype html><html><head><meta charset="utf-8"><title>Print</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>${printContent}</body></html>`);
            doc.close();
            // give browser time to render
            setTimeout(function() {
                iframe.contentWindow.focus();
                try {
                    iframe.contentWindow.print();
                } catch (e) {
                    alert('Gagal memulai print: ' + e.message);
                }
                // remove iframe after a short delay
                setTimeout(function(){ document.body.removeChild(iframe); }, 1000);
            }, 500);
        });
    </script>
</body>
</html>
