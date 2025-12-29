<?php
/**
 * File: gejala_management.php
 * Deskripsi: Halaman CRUD Gejala untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Proses Hapus Gejala
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query_delete = "DELETE FROM gejala WHERE id_gejala = '$id'";
    
    if (mysqli_query($koneksi, $query_delete)) {
        $success_message = "Gejala berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus gejala: " . mysqli_error($koneksi);
    }
}

// Proses Tambah/Edit Gejala
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_gejala = mysqli_real_escape_string($koneksi, trim($_POST['kode_gejala']));
    $nama_gejala = mysqli_real_escape_string($koneksi, trim($_POST['nama_gejala']));
    
    if (isset($_POST['id_gejala']) && !empty($_POST['id_gejala'])) {
        // Update
        $id = mysqli_real_escape_string($koneksi, $_POST['id_gejala']);
        $query = "UPDATE gejala SET kode_gejala='$kode_gejala', nama_gejala='$nama_gejala' WHERE id_gejala='$id'";
        $success_msg = "Gejala berhasil diupdate!";
    } else {
        // Insert
        $query = "INSERT INTO gejala (kode_gejala, nama_gejala) VALUES ('$kode_gejala', '$nama_gejala')";
        $success_msg = "Gejala berhasil ditambahkan!";
    }
    
    if (mysqli_query($koneksi, $query)) {
        $success_message = $success_msg;
    } else {
        $error_message = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}

// Ambil semua data gejala
$query_gejala = "SELECT * FROM gejala ORDER BY kode_gejala ASC";
$result_gejala = mysqli_query($koneksi, $query_gejala);

// Ambil data untuk edit jika ada
$edit_data = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query_edit = "SELECT * FROM gejala WHERE id_gejala = '$id_edit'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if (mysqli_num_rows($result_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Gejala - Sistem Pakar</title>
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
                    <span class="navbar-brand mb-0 h1 ms-3">Manajemen Gejala</span>
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
                <h2 class="mb-4">Manajemen Data Gejala</h2>
                
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
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i> 
                            <?php echo $edit_data ? 'Edit Gejala' : 'Tambah Gejala Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="formGejala">
                            <?php if($edit_data): ?>
                            <input type="hidden" name="id_gejala" value="<?php echo $edit_data['id_gejala']; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="kode_gejala" class="form-label">Kode Gejala <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kode_gejala" name="kode_gejala" 
                                           value="<?php echo $edit_data ? $edit_data['kode_gejala'] : ''; ?>" 
                                           placeholder="Contoh: G001" required>
                                </div>
                                
                                <div class="col-md-9 mb-3">
                                    <label for="nama_gejala" class="form-label">Nama Gejala <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_gejala" name="nama_gejala" 
                                           value="<?php echo $edit_data ? $edit_data['nama_gejala'] : ''; ?>" 
                                           placeholder="Masukkan nama/deskripsi gejala" required>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if($edit_data): ?>
                                <a href="gejala_management.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tabel Data Gejala -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Gejala</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Kode</th>
                                        <th width="60%">Nama Gejala</th>
                                        <th width="25%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (mysqli_num_rows($result_gejala) > 0):
                                        $no = 1;
                                        while($row = mysqli_fetch_assoc($result_gejala)): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $row['kode_gejala']; ?></strong></td>
                                        <td><?php echo $row['nama_gejala']; ?></td>
                                        <td class="text-center">
                                            <a href="?edit=<?php echo $row['id_gejala']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['id_gejala']; ?>" 
                                               class="btn btn-danger btn-sm btn-delete"
                                               onclick="return confirm('Yakin ingin menghapus gejala ini?');">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <em>Belum ada data gejala</em>
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
    <script src="../Assets/js/script.js"></script>
</body>
</html>
