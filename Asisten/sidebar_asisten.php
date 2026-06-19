<!-- Sidebar untuk Asisten Lab -->
<?php
// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4><i class="bi bi-pc-display-horizontal"></i> Sistem Pakar</h4>
        <p class="mb-0">Asisten Lab Panel</p>
    </div>
    
    <nav class="sidebar-menu">
        <a href="dashboard_asisten.php" class="sidebar-link <?php echo ($current_page == 'dashboard_asisten.php') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="diagnosa.php" class="sidebar-link <?php echo ($current_page == 'diagnosa.php' || $current_page == 'proses_diagnosa.php' || $current_page == 'hasil_diagnosa.php') ? 'active' : ''; ?>">
            <i class="bi bi-clipboard-pulse"></i>
            <span>Diagnosa Kerusakan</span>
        </a>
        
        <a href="diagnosa_chat.php" class="sidebar-link <?php echo ($current_page == 'diagnosa_chat.php' || $current_page == 'proses_chat.php' || $current_page == 'proses_chat_v2.php') ? 'active' : ''; ?>">
            <i class="bi bi-chat-dots"></i>
            <span>Chat Bot Diagnosa</span>
        </a>
        
        <a href="riwayat_diagnosa.php" class="sidebar-link <?php echo ($current_page == 'riwayat_diagnosa.php') ? 'active' : ''; ?>">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat Diagnosa</span>
        </a>
        
        <a href="export_laporan.php" class="sidebar-link <?php echo ($current_page == 'export_laporan.php') ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>Export Laporan</span>
        </a>
        
        <hr class="sidebar-divider">
        
                <a href="#" class="sidebar-link text-danger" onclick="showLogoutConfirm('dashboard_asisten.php'); return false;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                </a>
    </nav>
</div>

<!-- SweetAlert2 logout confirmation (Asisten) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showLogoutConfirm(dashboardUrl){
        Swal.fire({
            title: 'Konfirmasi',
            html: 'Apakah Anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                htmlContainer: 'swal2-html-container',
                confirmButton: 'swal2-confirm swal2-styled',
                cancelButton: 'swal2-cancel swal2-styled'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../Auth/logout.php';
            } else {
                window.location.href = dashboardUrl;
            }
        });
    }
</script>
