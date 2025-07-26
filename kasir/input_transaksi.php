<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data barang untuk dropdown
$barang_result = mysqli_query($koneksi, "SELECT id_barang, nama_barang FROM barang");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $qty = (int) $_POST['qty'];
    $harga = (float) $_POST['harga'];
    $metode_bayar = $_POST['metode_bayar'];

    // Hitung total
    $total = $qty * $harga;

    // Ambil nama produk
    $result_nama = mysqli_query($koneksi, "SELECT nama_barang FROM barang WHERE id_barang = '$id_barang'");
    $data_barang = mysqli_fetch_assoc($result_nama);
    $nama_produk = $data_barang['nama_barang'];

    // Simpan ke tabel penjualan
    mysqli_query($koneksi, "INSERT INTO penjualan (tanggal, nama_produk, qty, harga, user_id) 
        VALUES ('$tanggal', '$nama_produk', $qty, $harga, $user_id)");
    $id_penjualan = mysqli_insert_id($koneksi);

    // Simpan ke detail_penjualan
    mysqli_query($koneksi, "INSERT INTO detail_penjualan (id_penjualan, id_barang, jumlah, harga, user_id) 
        VALUES ($id_penjualan, $id_barang, $qty, $harga, $user_id)");

    // Simpan ke transaksi
    mysqli_query($koneksi, "INSERT INTO transaksi (tanggal, total, metode_bayar, user_id) 
        VALUES ('$tanggal', $total, '$metode_bayar', $user_id)");

    // Redirect
    header("Location: invoice.php"); // Atau halaman invoice sesuai
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Input Transaksi Penjualan</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Produk</label>
            <select name="id_barang" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                <?php while($b = mysqli_fetch_assoc($barang_result)): ?>
                    <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Jumlah (Qty)</label>
            <input type="number" name="qty" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga (per pcs)</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Metode Bayar</label>
            <select name="metode_bayar" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        <a href="penjualan.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
