<?php
include 'koneksi.php';
session_start();

$user_id = $_SESSION['user_id']; // Ambil user ID dari session

$id_barang = $_POST['id_barang'];
$asal = $_POST['lokasi_asal'];
$tujuan = $_POST['lokasi_tujuan'];
$jumlah = $_POST['jumlah'];

// Kurangi stok dari lokasi asal (khusus milik user)
mysqli_query($koneksi, "UPDATE stok_lokasi 
    SET jumlah = jumlah - $jumlah 
    WHERE id_barang = $id_barang AND id_lokasi = $asal AND user_id = $user_id");

// Tambah stok ke lokasi tujuan (khusus milik user)
$cek = mysqli_query($koneksi, "SELECT * FROM stok_lokasi 
    WHERE id_barang = $id_barang AND id_lokasi = $tujuan AND user_id = $user_id");

if (mysqli_num_rows($cek) > 0) {
    // Lokasi tujuan sudah ada → update
    mysqli_query($koneksi, "UPDATE stok_lokasi 
        SET jumlah = jumlah + $jumlah 
        WHERE id_barang = $id_barang AND id_lokasi = $tujuan AND user_id = $user_id");
} else {
    // Lokasi tujuan belum ada → insert baru
    mysqli_query($koneksi, "INSERT INTO stok_lokasi (id_barang, id_lokasi, jumlah, user_id) 
        VALUES ($id_barang, $tujuan, $jumlah, $user_id)");
}

// Simpan riwayat transfer
mysqli_query($koneksi, "INSERT INTO transfer_stok (id_barang, lokasi_asal, lokasi_tujuan, jumlah, tanggal, user_id) 
    VALUES ($id_barang, $asal, $tujuan, $jumlah, NOW(), $user_id)");

header("Location: transfer_stok.php?status=sukses");
exit;
?>
