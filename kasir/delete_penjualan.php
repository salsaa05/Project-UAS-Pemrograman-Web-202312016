<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "ID penjualan tidak ditemukan!";
    exit;
}

$id = intval($_GET['id']);

$cek = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE id_penjualan = $id AND user_id = '$user_id'");
if (mysqli_num_rows($cek) === 0) {
    echo "Data tidak ditemukan atau Anda tidak berhak menghapusnya!";
    exit;
}

mysqli_query($koneksi, "DELETE FROM detail_penjualan WHERE id_penjualan = $id");

$delete = mysqli_query($koneksi, "DELETE FROM penjualan WHERE id_penjualan = $id AND user_id = '$user_id'");

if ($delete) {
    header("Location: penjualan.php?msg=deleted");
    exit;
} else {
    echo "Gagal menghapus data penjualan!";
}
?>
