<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Cek apakah data penjualan ini milik user yang sedang login
    $cek = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE id_penjualan = $id AND user_id = $user_id");

    if (mysqli_num_rows($cek) > 0) {
        // Hapus detail penjualan terlebih dahulu jika ada relasi
        mysqli_query($koneksi, "DELETE FROM detail_penjualan WHERE id_penjualan = $id");

        // Hapus penjualan
        mysqli_query($koneksi, "DELETE FROM penjualan WHERE id_penjualan = $id AND user_id = $user_id");

        header("Location: penjualan.php?msg=deleted");
        exit;
    } else {
        echo "Data tidak ditemukan atau bukan milik Anda!";
    }
} else {
    echo "ID tidak valid!";
}
?>
