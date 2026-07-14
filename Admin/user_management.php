<?php
/**
 * File: user_management.php
 * Deskripsi: Halaman CRUD User dan Ubah Password untuk Admin
 */

require_once '../Auth/cek_session.php';
cek_role('admin');
require_once '../Config/koneksi.php';

// Proses Hapus User
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Cek apakah user yang akan dihapus bukan admin
    $query_check = "SELECT role FROM users WHERE id_user = '$id'";
    $result_check = mysqli_query($koneksi, $query_check);
    $user_data = mysqli_fetch_assoc($result_check);
    
    if ($user_data['role'] == 'asisten_lab') {
        $query_delete = "DELETE FROM users WHERE id_user = '$id'";
        if (mysqli_query($koneksi, $query_delete)) {
            $success_message = "User berhasil dihapus!";
        } else {
            $error_message = "Gagal menghapus user: " . mysqli_error($koneksi);
        }
    } else {
        $error_message = "Tidak bisa menghapus user admin!";
    }
}

// Proses Ubah Password User (Admin mengubah password user lain)
if (isset($_POST['ubah_password_user'])) {
    $id_user_target = mysqli_real_escape_string($koneksi, $_POST['id_user_target']);
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    
    if (empty($password_baru) || empty($konfirmasi_password)) {
        $error_password = "Password baru dan konfirmasi harus diisi!";
        $selected_user_id = $id_user_target;
    } elseif ($password_baru !== $konfirmasi_password) {
        $error_password = "Password baru dan konfirmasi password tidak cocok!";
        $selected_user_id = $id_user_target;
    } elseif (strlen($password_baru) < 6) {
        $error_password = "Password baru minimal 6 karakter!";
        $selected_user_id = $id_user_target;
    } else {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $query_update = "UPDATE users SET password = '$password_hash' WHERE id_user = '$id_user_target'";
        
        if (mysqli_query($koneksi, $query_update)) {
            $success_password = "Password user berhasil diubah!";
        } else {
            $error_password = "Gagal mengubah password: " . mysqli_error($koneksi);
            $selected_user_id = $id_user_target;
        }
    }
}

// Proses Tambah/Edit User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['ubah_password_user'])) {
    if (isset($_POST['username'])) {
        $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
        $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
        $role = mysqli_real_escape_string($koneksi, $_POST['role']);
        $is_duplicate_username = false;
        
        if (isset($_POST['id_user']) && !empty($_POST['id_user'])) {
            // Update User
            $id = mysqli_real_escape_string($koneksi, $_POST['id_user']);
            $query_check_username = "SELECT id_user FROM users WHERE username = '$username' AND id_user <> '$id' LIMIT 1";
            $result_check_username = mysqli_query($koneksi, $query_check_username);
            if ($result_check_username && mysqli_num_rows($result_check_username) > 0) {
                $is_duplicate_username = true;
                $error_message = "Username sudah digunakan. Silakan pilih username lain.";
            }
            
            if (!$is_duplicate_username) {
                // Cek apakah password diubah
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $query = "UPDATE users SET username='$username', password='$password', role='$role', nama_lengkap='$nama_lengkap' WHERE id_user='$id'";
                } else {
                    $query = "UPDATE users SET username='$username', role='$role', nama_lengkap='$nama_lengkap' WHERE id_user='$id'";
                }
                $success_msg = "User berhasil diupdate!";
            }
        } else {
            // Insert User Baru
            if (empty($_POST['password'])) {
                $error_message = "Password harus diisi untuk user baru!";
            } else {
                $query_check_username = "SELECT id_user FROM users WHERE username = '$username' LIMIT 1";
                $result_check_username = mysqli_query($koneksi, $query_check_username);

                if ($result_check_username && mysqli_num_rows($result_check_username) > 0) {
                    $error_message = "Username sudah digunakan. Silakan pilih username lain.";
                } else {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $query = "INSERT INTO users (username, password, role, nama_lengkap) VALUES ('$username', '$password', '$role', '$nama_lengkap')";
                    $success_msg = "User berhasil ditambahkan!";
                }
            }
        }
        
        if (isset($query) && mysqli_query($koneksi, $query)) {
            $success_message = $success_msg;
        } elseif (isset($query)) {
            $error_message = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}

// Ambil semua data user
$query_users = "SELECT * FROM users ORDER BY created_at DESC";
$result_users = mysqli_query($koneksi, $query_users);

// Ambil data untuk edit jika ada
$edit_data = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $query_edit = "SELECT * FROM users WHERE id_user = '$id_edit'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    if (mysqli_num_rows($result_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
        $selected_user_id = $id_edit; // Untuk highlight dan form password
    }
}

// Set selected user ID jika ada dari GET parameter
if (!isset($selected_user_id) && isset($_GET['edit'])) {
    $selected_user_id = $_GET['edit'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Sistem Pakar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../Assets/css/style.css?v=20260713">
    <style>
        .table-row-selected {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
        }
        .scroll-target {
            scroll-margin-top: 100px;
        }
    </style>

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
                    <span class="navbar-brand mb-0 h1 ms-3">Manajemen User</span>
                    <div class="ms-auto">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama_lengkap']; ?>
                        </span>
                        <!-- Logout moved to sidebar -->
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <h2 class="mb-4"><i class="bi bi-people"></i> Manajemen User Sistem</h2>
                
                <?php if(isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle"></i> <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <!-- BAGIAN 1: DAFTAR USER -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar User</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Role</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    mysqli_data_seek($result_users, 0); // Reset pointer
                                    while($row = mysqli_fetch_assoc($result_users)): 
                                        $is_selected = isset($selected_user_id) && $selected_user_id == $row['id_user'];
                                    ?>
                                    <tr class="<?php echo $is_selected ? 'table-row-selected' : ''; ?>" id="user-row-<?php echo $row['id_user']; ?>">
                                        <td><?php echo $no++; ?></td>
                                        <td><i class="bi bi-person-circle"></i> <?php echo $row['username']; ?></td>
                                        <td><?php echo $row['nama_lengkap']; ?></td>
                                        <td>
                                            <?php if($row['role'] == 'admin'): ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Asisten Lab</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $row['id_user']; ?>#form-section" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <?php if($row['role'] == 'asisten_lab'): ?>
                                                <a href="?action=delete&id=<?php echo $row['id_user']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                   title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- BAGIAN 2: FORM EDIT USER (Hanya muncul saat mode edit) -->
                <?php if($edit_data): ?>
                <div class="card shadow-sm mb-4 scroll-target" id="form-section">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square"></i> Edit User
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="id_user" value="<?php echo $edit_data['id_user']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="username" 
                                           value="<?php echo $edit_data['username']; ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap" 
                                           value="<?php echo $edit_data['nama_lengkap']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" name="role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin" <?php echo ($edit_data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="asisten_lab" <?php echo ($edit_data['role'] == 'asisten_lab') ? 'selected' : ''; ?>>Asisten Lab</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Password <small class="text-muted">(kosongkan jika tidak diubah)</small>
                                    </label>
                                    <input type="password" class="form-control" name="password">
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save"></i> Update User
                                </button>
                                <a href="user_management.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- BAGIAN 3: FORM UBAH PASSWORD USER (Hanya muncul saat edit) -->
                <?php endif; ?>
                
                <?php if($edit_data): ?>
                <div class="card shadow-sm mb-4 scroll-target" id="password-section">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-key"></i> Ubah Password User: <strong><?php echo $edit_data['username']; ?></strong>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($success_password)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?php echo $success_password; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($error_password)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-circle"></i> <?php echo $error_password; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="ubah_password_user" value="1">
                            <input type="hidden" name="id_user_target" value="<?php echo $edit_data['id_user']; ?>">
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                Anda sedang mengubah password untuk user: <strong><?php echo $edit_data['nama_lengkap']; ?> (<?php echo $edit_data['username']; ?>)</strong>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" name="password_baru" 
                                               id="password_baru" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('password_baru')">
                                            <i class="bi bi-eye" id="toggleIcon_password_baru"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" name="konfirmasi_password" 
                                               id="konfirmasi_password" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('konfirmasi_password')">
                                            <i class="bi bi-eye" id="toggleIcon_konfirmasi_password"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-key"></i> Ubah Password
                                </button>
                                <a href="user_management.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- BAGIAN 4: FORM TAMBAH USER BARU (Selalu tampil, posisi berubah saat mode edit) -->
                <div class="card shadow-sm mb-4 <?php echo !$edit_data ? 'scroll-target' : ''; ?>" 
                     id="<?php echo !$edit_data ? 'form-section' : 'form-tambah-section'; ?>">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-plus"></i> Tambah User Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" name="role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="admin">Admin</option>
                                        <option value="asisten_lab">Asisten Lab</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" name="password" required>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/js/script.js?v=20260713"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('toggleIcon_' + fieldId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
        
        // Auto scroll to form when edit button is clicked
        <?php if(isset($_GET['edit'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const formSection = document.getElementById('form-section');
                if (formSection) {
                    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 100);
        });
        <?php endif; ?>
    </script>
</body>
</html>
