<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = intval($_GET['id']);

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang=$id AND user_id='$user_id'"));

if (!$data) {
    echo "Data barang tidak ditemukan atau bukan milik Anda!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];

    mysqli_query($koneksi, "UPDATE barang SET 
        nama_barang='$nama', kategori='$kategori', satuan='$satuan', 
        stok='$stok', harga_beli='$beli', harga_jual='$jual' 
        WHERE id_barang=$id AND user_id='$user_id'");

    header("Location: master_data.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Barang</h3>
    <form method="post">
        <div class="mb-2">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= $data['nama_barang'] ?>" class="form-control">
        </div>
        <div class="mb-2">
            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= $data['kategori'] ?>" class="form-control">
        </div>
        <div class="mb-2">
            <label>Satuan</label>
            <input type="text" name="satuan" value="<?= $data['satuan'] ?>" class="form-control">
        </div>
        <div class="mb-2">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['stok'] ?>" class="form-control">
        </div>
        <div class="mb-2">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" value="<?= $data['harga_beli'] ?>" class="form-control">
        </div>
        <div class="mb-2">
            <label>Harga Jual</label>
            <input type="number" name="harga_jual" value="<?= $data['harga_jual'] ?>" class="form-control">
        </div>
        <button class="btn btn-warning">Update</button>
        <a href="master_data.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
