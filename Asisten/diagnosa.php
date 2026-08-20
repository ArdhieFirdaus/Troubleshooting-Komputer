<?php
/**
 * File: diagnosa.php
 * Deskripsi: Halaman input diagnosa (checklist gejala) untuk Asisten Lab
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

// Ambil semua gejala beserta kategori kerusakan terkait (Hardware / Software)
$query_gejala = "SELECT g.*, COALESCE(k.kategori, 'Hardware') AS kategori 
                 FROM gejala g 
                 LEFT JOIN rule_detail rd ON g.id_gejala = rd.id_gejala 
                 LEFT JOIN rule r ON rd.id_rule = r.id_rule 
                 LEFT JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan 
                 GROUP BY g.id_gejala 
                 ORDER BY g.kode_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa Kerusakan - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
    <style>
        .filter-btn.active {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            font-weight: bold;
        }
        .gejala-card {
            transition: all 0.2s ease;
        }
        .gejala-card:hover {
            border-color: #0d6efd !important;
            transform: translateY(-2px);
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
                    <span class="navbar-brand mb-0 h1 ms-3">Diagnosa Kerusakan</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">
                    <i class="bi bi-clipboard-pulse"></i> 
                    Diagnosa Troubleshooting Komputer & Software
                </h2>
                
                <!-- Informasi -->
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Petunjuk Diagnosa:</h6>
                    <ol class="mb-0">
                        <li>Centang <strong>semua gejala</strong> yang dialami oleh komputer (Hardware / Software)</li>
                        <li>Gunakan filter kategori (Semua, Hardware, Software) untuk mempermudah pencarian gejala</li>
                        <li>Minimal pilih <strong>1 gejala</strong></li>
                        <li>Klik tombol <strong>"Proses Diagnosa"</strong> untuk mengeksekusi inferensi Forward Chaining</li>
                    </ol>
                </div>
                
                <!-- Filter Kategori & Pencarian -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-md-7">
                                <label class="form-label font-weight-bold me-2"><i class="bi bi-funnel-fill text-primary"></i> Filter Kategori:</label>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">Semua Gejala</button>
                                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="Hardware"><i class="bi bi-cpu"></i> Hardware</button>
                                    <button type="button" class="btn btn-outline-success filter-btn" data-filter="Software"><i class="bi bi-window-stack"></i> Software</button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                    <input type="text" id="inputSearchGejala" class="form-control" placeholder="Cari nama atau kode gejala...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Diagnosa -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check"></i> 
                            Pilih Gejala Kerusakan
                        </h5>
                        <span class="badge bg-light text-primary" id="visibleGejalaCount">Menampilkan gejala...</span>
                    </div>
                    <div class="card-body">
                        <form action="proses_diagnosa.php" method="POST" id="formDiagnosa">
                            <div class="row" id="gejalaContainer">
                                <?php 
                                if (mysqli_num_rows($result_gejala) > 0):
                                    while($gejala = mysqli_fetch_assoc($result_gejala)): 
                                        $kat = !empty($gejala['kategori']) ? $gejala['kategori'] : 'Hardware';
                                ?>
                                <div class="col-md-6 mb-3 gejala-item" data-category="<?php echo $kat; ?>" data-search="<?php echo strtolower($gejala['kode_gejala'] . ' ' . $gejala['nama_gejala']); ?>">
                                    <div class="card h-100 border-secondary gejala-card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input gejala-checkbox" 
                                                       type="checkbox" 
                                                       name="gejala[]" 
                                                       value="<?php echo $gejala['id_gejala']; ?>" 
                                                       id="gejala_<?php echo $gejala['id_gejala']; ?>">
                                                <label class="form-check-label w-100" 
                                                       for="gejala_<?php echo $gejala['id_gejala']; ?>">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <strong class="text-primary"><?php echo $gejala['kode_gejala']; ?></strong>
                                                        <?php if($kat == 'Hardware'): ?>
                                                            <span class="badge bg-primary"><i class="bi bi-cpu"></i> Hardware</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success"><i class="bi bi-window-stack"></i> Software</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span><?php echo $gejala['nama_gejala']; ?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        Belum ada data gejala. Silakan hubungi admin.
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (mysqli_num_rows($result_gejala) > 0): ?>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info p-2 fs-6" id="selectedCount">0 gejala dipilih</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="btnReset">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-cpu"></i> Proses Diagnosa
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
    <script>
        // Counter & Filtering
        let activeCategory = 'all';

        function updateCounter() {
            const checkboxes = document.querySelectorAll('.gejala-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count + ' gejala dipilih';
        }

        function filterGejalaList() {
            const query = document.getElementById('inputSearchGejala').value.toLowerCase();
            const items = document.querySelectorAll('.gejala-item');
            let visible = 0;

            items.forEach(function(item) {
                const itemCat = item.getAttribute('data-category');
                const itemText = item.getAttribute('data-search');

                const matchesCat = (activeCategory === 'all' || itemCat === activeCategory);
                const matchesSearch = (!query || itemText.includes(query));

                if (matchesCat && matchesSearch) {
                    item.style.display = 'block';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });

            document.getElementById('visibleGejalaCount').textContent = visible + ' gejala ditampilkan';
        }
        
        // Event listeners checkbox
        document.querySelectorAll('.gejala-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', updateCounter);
        });
        
        // Reset button
        document.getElementById('btnReset').addEventListener('click', function() {
            document.querySelectorAll('.gejala-checkbox').forEach(function(checkbox) {
                checkbox.checked = false;
            });
            updateCounter();
        });

        // Filter button click
        document.querySelectorAll('.filter-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeCategory = this.getAttribute('data-filter');
                filterGejalaList();
            });
        });

        // Search input keyup
        document.getElementById('inputSearchGejala').addEventListener('keyup', filterGejalaList);

        // Validasi form submit
        document.getElementById('formDiagnosa').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.gejala-checkbox:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Minimal pilih 1 gejala untuk diagnosa!');
                return false;
            }
        });

        // Initial count & filter
        filterGejalaList();
        updateCounter();
    </script>
</body>
</html>
