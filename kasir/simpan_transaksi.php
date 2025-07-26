<?php
include 'koneksi.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$produk = $_POST['produk']; // array: ['nama'=>'', 'qty'=>'', 'harga'=>'', 'id_barang'=>'']
$metode_bayar = $_POST['metode_bayar'];

$total = 0;
$id_penjualan_list = []; // untuk menyimpan semua ID penjualan

foreach ($produk as $item) {
    $nama_produk = mysqli_real_escape_string($koneksi, $item['nama']);
    $qty = (int) $item['qty'];
    $harga = (float) $item['harga'];
    $id_barang = (int) $item['id_barang'];
    $subtotal = $qty * $harga;
    $total += $subtotal;

    // 1. Simpan ke tabel penjualan
    mysqli_query($koneksi, "INSERT INTO penjualan (tanggal, nama_produk, qty, harga, user_id) 
        VALUES (NOW(), '$nama_produk', $qty, $harga, $user_id)");
    $id_penjualan = mysqli_insert_id($koneksi);
    $id_penjualan_list[] = $id_penjualan;

    // 2. Simpan ke detail_penjualan
    mysqli_query($koneksi, "INSERT INTO detail_penjualan (id_penjualan, id_barang, qty, harga, user_id) 
        VALUES ($id_penjualan, $id_barang, $qty, $harga, $user_id)");
}

// 3. Simpan ke transaksi
mysqli_query($koneksi, "INSERT INTO transaksi (tanggal, total, metode_bayar, user_id) 
    VALUES (NOW(), $total, '$metode_bayar', $user_id)");
$id_transaksi = mysqli_insert_id($koneksi);

// Simpan ID ke session untuk invoice
$_SESSION['id_transaksi'] = $id_transaksi;

// Redirect ke invoice
header("Location: invoice.php?id=$id_transaksi");
exit;
?>
