<?php
/**
 * File: rule_management.php
 * Deskripsi: Halaman CRUD Rule Forward Chaining untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Proses Hapus Rule
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Hapus rule_detail terlebih dahulu (foreign key constraint)
    mysqli_query($koneksi, "DELETE FROM rule_detail WHERE id_rule = '$id'");
    
    // Hapus rule
    $query_delete = "DELETE FROM rule WHERE id_rule = '$id'";
    
    if (mysqli_query($koneksi, $query_delete)) {
        $success_message = "Rule berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus rule: " . mysqli_error($koneksi);
    }
}

// Proses Tambah/Edit Rule
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kerusakan = mysqli_real_escape_string($koneksi, $_POST['id_kerusakan']);
    $gejala_array = isset($_POST['gejala']) ? $_POST['gejala'] : array();
    
    // Validasi: minimal 1 gejala harus dipilih
    if (empty($gejala_array)) {
        $error_message = "Minimal pilih 1 gejala untuk rule!";
    } else {
        if (isset($_POST['id_rule']) && !empty($_POST['id_rule'])) {
            // Update Rule
            $id_rule = mysqli_real_escape_string($koneksi, $_POST['id_rule']);
            
            // Update rule
            $query_update = "UPDATE rule SET id_kerusakan='$id_kerusakan' WHERE id_rule='$id_rule'";
            mysqli_query($koneksi, $query_update);
            
            // Hapus semua rule_detail lama
            mysqli_query($koneksi, "DELETE FROM rule_detail WHERE id_rule='$id_rule'");
            
            // Insert rule_detail baru
            foreach ($gejala_array as $id_gejala) {
                $id_gejala = mysqli_real_escape_string($koneksi, $id_gejala);
                mysqli_query($koneksi, "INSERT INTO rule_detail (id_rule, id_gejala) VALUES ('$id_rule', '$id_gejala')");
            }
            
            $success_message = "Rule berhasil diupdate!";
            
        } else {
            // Insert Rule Baru
            $query_rule = "INSERT INTO rule (id_kerusakan) VALUES ('$id_kerusakan')";
            
            if (mysqli_query($koneksi, $query_rule)) {
                $id_rule = mysqli_insert_id($koneksi);
                
                // Insert rule_detail
                foreach ($gejala_array as $id_gejala) {
                    $id_gejala = mysqli_real_escape_string($koneksi, $id_gejala);
                    mysqli_query($koneksi, "INSERT INTO rule_detail (id_rule, id_gejala) VALUES ('$id_rule', '$id_gejala')");
                }
                
                $success_message = "Rule berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menyimpan rule: " . mysqli_error($koneksi);
            }
        }
    }
}

// Ambil semua data rule dengan join
$query_rules = "SELECT r.*, k.kode_kerusakan, k.nama_kerusakan 
                FROM rule r 
                INNER JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan 
                ORDER BY r.id_rule ASC";
$result_rules = mysqli_query($koneksi, $query_rules);

// Ambil semua kerusakan untuk dropdown
$query_kerusakan_list = "SELECT * FROM kerusakan ORDER BY kode_kerusakan ASC";
$result_kerusakan_list = mysqli_query($koneksi, $query_kerusakan_list);

// Ambil semua gejala untuk checkbox
$query_gejala_list = "SELECT * FROM gejala ORDER BY kode_gejala ASC";
$result_gejala_list = mysqli_query($koneksi, $query_gejala_list);

// Ambil data untuk edit jika ada
$edit_data = null;
$edit_gejala = array();
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query_edit = "SELECT * FROM rule WHERE id_rule = '$id_edit'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if (mysqli_num_rows($result_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
        
        // Ambil gejala yang dipilih untuk rule ini
        $query_edit_gejala = "SELECT id_gejala FROM rule_detail WHERE id_rule = '$id_edit'";
        $result_edit_gejala = mysqli_query($koneksi, $query_edit_gejala);
        while ($row_gejala = mysqli_fetch_assoc($result_edit_gejala)) {
            $edit_gejala[] = $row_gejala['id_gejala'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Rule - Sistem Pakar</title>
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
                    <span class="navbar-brand mb-0 h1 ms-3">Manajemen Rule</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">Manajemen Rule Forward Chaining</h2>
                
                <?php if(isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- Form Tambah/Edit -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i> 
                            <?php echo $edit_data ? 'Edit Rule' : 'Tambah Rule Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="formRule">
                            <?php if($edit_data): ?>
                            <input type="hidden" name="id_rule" value="<?php echo $edit_data['id_rule']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="id_kerusakan" class="form-label">Pilih Kerusakan <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_kerusakan" name="id_kerusakan" required>
                                    <option value="">-- Pilih Kerusakan --</option>
                                    <?php 
                                    mysqli_data_seek($result_kerusakan_list, 0);
                                    while($k = mysqli_fetch_assoc($result_kerusakan_list)): 
                                        $selected = ($edit_data && $edit_data['id_kerusakan'] == $k['id_kerusakan']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $k['id_kerusakan']; ?>" <?php echo $selected; ?>>
                                        <?php echo $k['kode_kerusakan'] . ' - ' . $k['nama_kerusakan']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Gejala (Minimal 1) <span class="text-danger">*</span></label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    <?php 
                                    mysqli_data_seek($result_gejala_list, 0);
                                    while($g = mysqli_fetch_assoc($result_gejala_list)): 
                                        $checked = in_array($g['id_gejala'], $edit_gejala) ? 'checked' : '';
                                    ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                               name="gejala[]" value="<?php echo $g['id_gejala']; ?>" 
                                               id="gejala_<?php echo $g['id_gejala']; ?>" <?php echo $checked; ?>>
                                        <label class="form-check-label" for="gejala_<?php echo $g['id_gejala']; ?>">
                                            <strong><?php echo $g['kode_gejala']; ?></strong> - <?php echo $g['nama_gejala']; ?>
                                        </label>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    Rule akan cocok jika SEMUA gejala yang dipilih ditemukan pada diagnosa
                                </small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if($edit_data): ?>
                                <a href="rule_management.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tabel Data Rule -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Rule</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-warning">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">ID Rule</th>
                                        <th width="25%">Kerusakan</th>
                                        <th width="40%">Gejala yang Dipilih</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (mysqli_num_rows($result_rules) > 0):
                                        $no = 1;
                                        while($row = mysqli_fetch_assoc($result_rules)): 
                                            // Ambil gejala untuk rule ini
                                            $id_rule = $row['id_rule'];
                                            $query_gejala = "SELECT g.kode_gejala, g.nama_gejala 
                                                           FROM rule_detail rd 
                                                           INNER JOIN gejala g ON rd.id_gejala = g.id_gejala 
                                                           WHERE rd.id_rule = '$id_rule'";
                                            $result_gejala = mysqli_query($koneksi, $query_gejala);
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong>R-<?php echo $row['id_rule']; ?></strong></td>
                                        <td>
                                            <strong><?php echo $row['kode_kerusakan']; ?></strong><br>
                                            <small><?php echo $row['nama_kerusakan']; ?></small>
                                        </td>
                                        <td>
                                            <ul class="mb-0" style="font-size: 13px;">
                                                <?php while($gejala = mysqli_fetch_assoc($result_gejala)): ?>
                                                <li>
                                                    <strong><?php echo $gejala['kode_gejala']; ?></strong> - 
                                                    <?php echo $gejala['nama_gejala']; ?>
                                                </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                            <a href="?edit=<?php echo $row['id_rule']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['id_rule']; ?>" 
                                               class="btn btn-danger btn-sm btn-delete"
                                               onclick="return confirm('Yakin ingin menghapus rule ini?');">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <em>Belum ada rule. Silakan tambah rule baru.</em>
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
    <script src="../Assets/js/script.js?v=20260713"></script>
    <script>
        // Validasi form: minimal 1 checkbox gejala harus dipilih
        document.getElementById('formRule').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="gejala[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Minimal pilih 1 gejala untuk rule!');
                return false;
            }
        });
    </script>
</body>
</html>
