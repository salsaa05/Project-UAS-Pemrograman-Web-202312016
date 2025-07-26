<?php
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    // Jika sudah login, arahkan ke dashboard
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}
?>
