<?php
/**
 * File: kerusakan_management.php
 * Deskripsi: Halaman CRUD Kerusakan dan Solusi untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Proses Hapus Kerusakan
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query_delete = "DELETE FROM kerusakan WHERE id_kerusakan = '$id'";
    
    if (mysqli_query($koneksi, $query_delete)) {
        $success_message = "Kerusakan berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus kerusakan: " . mysqli_error($koneksi);
    }
}

// Proses Tambah/Edit Kerusakan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_kerusakan = mysqli_real_escape_string($koneksi, trim($_POST['kode_kerusakan']));
    $nama_kerusakan = mysqli_real_escape_string($koneksi, trim($_POST['nama_kerusakan']));
    $solusi = mysqli_real_escape_string($koneksi, trim($_POST['solusi']));
    
    if (isset($_POST['id_kerusakan']) && !empty($_POST['id_kerusakan'])) {
        // Update
        $id = mysqli_real_escape_string($koneksi, $_POST['id_kerusakan']);
        $query = "UPDATE kerusakan SET kode_kerusakan='$kode_kerusakan', 
                  nama_kerusakan='$nama_kerusakan', solusi='$solusi' 
                  WHERE id_kerusakan='$id'";
        $success_msg = "Kerusakan berhasil diupdate!";
    } else {
        // Insert
        $query = "INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, solusi) 
                  VALUES ('$kode_kerusakan', '$nama_kerusakan', '$solusi')";
        $success_msg = "Kerusakan berhasil ditambahkan!";
    }
    
    if (mysqli_query($koneksi, $query)) {
        $success_message = $success_msg;
    } else {
        $error_message = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}

// Ambil data untuk edit jika ada
$edit_data = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query_edit = "SELECT * FROM kerusakan WHERE id_kerusakan = '$id_edit'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if (mysqli_num_rows($result_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
    }
}

// Ambil semua data kerusakan
$query_kerusakan = "SELECT * FROM kerusakan ORDER BY kode_kerusakan ASC";
$result_kerusakan = mysqli_query($koneksi, $query_kerusakan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kerusakan - Sistem Pakar</title>
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
                    <span class="navbar-brand mb-0 h1 ms-3">Manajemen Kerusakan</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4">Manajemen Data Kerusakan & Solusi</h2>
                
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
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-plus-circle"></i> 
                            <?php echo $edit_data ? 'Edit Kerusakan' : 'Tambah Kerusakan Baru'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="formKerusakan">
                            <?php if($edit_data): ?>
                            <input type="hidden" name="id_kerusakan" value="<?php echo $edit_data['id_kerusakan']; ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="kode_kerusakan" class="form-label">Kode Kerusakan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kode_kerusakan" name="kode_kerusakan" 
                                           value="<?php echo $edit_data ? $edit_data['kode_kerusakan'] : ''; ?>" 
                                           placeholder="Contoh: K001" required>
                                </div>
                                
                                <div class="col-md-9 mb-3">
                                    <label for="nama_kerusakan" class="form-label">Nama Kerusakan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_kerusakan" name="nama_kerusakan" 
                                           value="<?php echo $edit_data ? $edit_data['nama_kerusakan'] : ''; ?>" 
                                           placeholder="Masukkan nama kerusakan" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="solusi" class="form-label">Solusi Penanganan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="solusi" name="solusi" 
                                          rows="6" placeholder="Masukkan langkah-langkah solusi penanganan" required><?php echo $edit_data ? $edit_data['solusi'] : ''; ?></textarea>
                                <small class="text-muted">Tip: Gunakan numbering (1., 2., dst.) untuk langkah-langkah solusi</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                                </button>
                                <?php if($edit_data): ?>
                                <a href="kerusakan_management.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tabel Data Kerusakan -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Kerusakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="table-success">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="8%">Kode</th>
                                        <th width="25%">Nama Kerusakan</th>
                                        <th width="42%">Solusi</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (mysqli_num_rows($result_kerusakan) > 0):
                                        $no = 1;
                                        while($row = mysqli_fetch_assoc($result_kerusakan)): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $row['kode_kerusakan']; ?></strong></td>
                                        <td><?php echo $row['nama_kerusakan']; ?></td>
                                        <td>
                                            <div style="max-height: 100px; overflow-y: auto;">
                                                <?php echo nl2br($row['solusi']); ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="?edit=<?php echo $row['id_kerusakan']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="?action=delete&id=<?php echo $row['id_kerusakan']; ?>" 
                                               class="btn btn-danger btn-sm btn-delete"
                                               onclick="return confirm('Yakin ingin menghapus kerusakan ini?');">
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
                                            <em>Belum ada data kerusakan</em>
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
</body>
</html>
