<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = 'SRV' . time();
    $nama = $_POST['nama'];
    $hp = $_POST['hp'];
    $barang = $_POST['barang'];
    $kerusakan = $_POST['kerusakan'];

    // Simpan data ke tabel servis, termasuk user_id
    $query = "INSERT INTO servis (kode_servis, nama_pelanggan, no_hp, jenis_barang, kerusakan, user_id)
                VALUES ('$kode', '$nama', '$hp', '$barang', '$kerusakan', '$user_id')";
    mysqli_query($koneksi, $query);
    header("Location: servis.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Servis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Tambah Servis</h2>
    <form method="POST">
        <div class="mb-2">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>No. HP</label>
            <input type="text" name="hp" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Jenis Barang</label>
            <input type="text" name="barang" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Kerusakan</label>
            <textarea name="kerusakan" class="form-control" required></textarea>
        </div>
        <button class="btn btn-success">Simpan</button>
    </form>
</div>
</body>
</html>
