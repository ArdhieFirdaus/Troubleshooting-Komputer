<!-- Sidebar untuk Admin -->
<?php
// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4><i class="bi bi-pc-display-horizontal"></i> Sistem Pakar</h4>
        <p class="mb-0">Admin Panel</p>
    </div>
    
    <nav class="sidebar-menu">
        <a href="dashboard_admin.php" class="sidebar-link <?php echo ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="gejala_management.php" class="sidebar-link <?php echo ($current_page == 'gejala_management.php') ? 'active' : ''; ?>">
            <i class="bi bi-clipboard-check"></i>
            <span>Manajemen Gejala</span>
        </a>
        
        <a href="kerusakan_management.php" class="sidebar-link <?php echo ($current_page == 'kerusakan_management.php') ? 'active' : ''; ?>">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Manajemen Kerusakan</span>
        </a>
        
        <a href="rule_management.php" class="sidebar-link <?php echo ($current_page == 'rule_management.php') ? 'active' : ''; ?>">
            <i class="bi bi-gear"></i>
            <span>Manajemen Rule</span>
        </a>
        
        <a href="laporan_diagnosa.php" class="sidebar-link <?php echo ($current_page == 'laporan_diagnosa.php' || $current_page == 'detail_diagnosa.php') ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-text"></i>
            <span>Laporan Diagnosa</span>
        </a>
        
        <a href="user_management.php" class="sidebar-link <?php echo ($current_page == 'user_management.php') ? 'active' : ''; ?>">
            <i class="bi bi-people"></i>
            <span>Manajemen User</span>
        </a>
        
        <hr class="sidebar-divider">
        
        <a href="../Auth/logout.php" class="sidebar-link text-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </nav>
</div>
