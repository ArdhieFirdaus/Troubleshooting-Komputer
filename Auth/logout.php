<?php
/**
 * File: logout.php
 * Deskripsi: Menghancurkan session dan logout user
 */

// Mulai session
session_start();

// Hapus semua session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login dengan pesan sukses
header("Location: login.php?success=logged_out");
exit();

?>
