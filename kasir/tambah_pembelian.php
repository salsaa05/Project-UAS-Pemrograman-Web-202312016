<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $supplier = $_POST['nama_supplier'];
    $barang = $_POST['nama_barang'];
    $qty = $_POST['qty'];
    $harga = $_POST['harga'];
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session

    $query = "INSERT INTO pembelian (tanggal, nama_supplier, nama_barang, qty, harga, user_id)
                VALUES ('$tanggal', '$supplier', '$barang', '$qty', '$harga', '$user_id')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: pembelian.php");
        exit;
    } else {
        echo "Gagal simpan: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Tambah Data Pembelian</h3>
    <form method="POST">
        <div class="mb-3"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
        <div class="mb-3"><label>Nama Supplier</label><input type="text" name="nama_supplier" class="form-control" required></div>
        <div class="mb-3"><label>Nama Barang</label><input type="text" name="nama_barang" class="form-control" required></div>
        <div class="mb-3"><label>Qty</label><input type="number" name="qty" class="form-control" required></div>
        <div class="mb-3"><label>Harga</label><input type="number" name="harga" class="form-control" required></div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="pembelian.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
