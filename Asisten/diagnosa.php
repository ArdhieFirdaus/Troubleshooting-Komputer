<?php
/**
 * File: diagnosa.php
 * Deskripsi: Halaman input diagnosa (checklist gejala) untuk Asisten Lab
 */

require_once '../Auth/cek_session.php';
cek_role('asisten_lab');
require_once '../Config/koneksi.php';

// Ambil semua gejala
$query_gejala = "SELECT * FROM gejala ORDER BY kode_gejala ASC";
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
    <link rel="stylesheet" href="../Assets/css/style.css">
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
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">
                    <i class="bi bi-clipboard-pulse"></i> 
                    Diagnosa Troubleshooting Komputer
                </h2>
                
                <!-- Informasi -->
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Petunjuk:</h6>
                    <ol class="mb-0">
                        <li>Centang <strong>semua gejala</strong> yang dialami oleh komputer</li>
                        <li>Minimal pilih <strong>1 gejala</strong></li>
                        <li>Klik tombol <strong>"Proses Diagnosa"</strong> untuk mendapatkan hasil dan solusi</li>
                        <li>Sistem akan menggunakan metode <strong>Forward Chaining</strong> untuk mencocokkan gejala dengan kerusakan</li>
                    </ol>
                </div>
                
                <!-- Form Diagnosa -->
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check"></i> 
                            Pilih Gejala Kerusakan
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="proses_diagnosa.php" method="POST" id="formDiagnosa">
                            <div class="row">
                                <?php 
                                if (mysqli_num_rows($result_gejala) > 0):
                                    $counter = 0;
                                    while($gejala = mysqli_fetch_assoc($result_gejala)): 
                                        $counter++;
                                ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input gejala-checkbox" 
                                                       type="checkbox" 
                                                       name="gejala[]" 
                                                       value="<?php echo $gejala['id_gejala']; ?>" 
                                                       id="gejala_<?php echo $gejala['id_gejala']; ?>">
                                                <label class="form-check-label w-100" 
                                                       for="gejala_<?php echo $gejala['id_gejala']; ?>">
                                                    <strong class="text-primary"><?php echo $gejala['kode_gejala']; ?></strong><br>
                                                    <?php echo $gejala['nama_gejala']; ?>
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
                                    <span class="badge bg-info" id="selectedCount">0 gejala dipilih</span>
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
    <script src="../Assets/js/script.js"></script>
    <script>
        // Counter gejala yang dipilih
        function updateCounter() {
            const checkboxes = document.querySelectorAll('.gejala-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count + ' gejala dipilih';
        }
        
        // Event listener untuk semua checkbox
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
        
        // Validasi form
        document.getElementById('formDiagnosa').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('.gejala-checkbox:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Minimal pilih 1 gejala untuk diagnosa!');
                return false;
            }
            
            // Konfirmasi sebelum proses
            if (!confirm('Apakah Anda yakin ingin memproses diagnosa dengan ' + checkboxes.length + ' gejala yang dipilih?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
