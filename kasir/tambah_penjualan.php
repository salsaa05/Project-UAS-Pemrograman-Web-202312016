<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Ambil data barang untuk dropdown
$barang_result = mysqli_query($koneksi, "SELECT id_barang, nama_barang FROM barang");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $qty = $_POST['qty'];
    $harga = $_POST['harga'];
    $metode_bayar = $_POST['metode_bayar'];

    // Ambil nama barang dari tabel barang
    $result_nama = mysqli_query($koneksi, "SELECT nama_barang FROM barang WHERE id_barang = '$id_barang'");
    $data_barang = mysqli_fetch_assoc($result_nama);
    $nama_produk = $data_barang['nama_barang'];

    // Simpan ke tabel penjualan (dengan user_id dan metode bayar)
    $query_penjualan = "INSERT INTO penjualan (tanggal, nama_produk, qty, harga, metode_bayar, user_id) 
                        VALUES ('$tanggal', '$nama_produk', '$qty', '$harga', '$metode_bayar', '$user_id')";
    mysqli_query($koneksi, $query_penjualan);

    // Ambil id_penjualan terakhir
    $id_penjualan = mysqli_insert_id($koneksi);

    // Simpan ke tabel detail_penjualan
    $query_detail = "INSERT INTO detail_penjualan (id_penjualan, id_barang, jumlah, harga)
                    VALUES ('$id_penjualan', '$id_barang', '$qty', '$harga')";
    mysqli_query($koneksi, $query_detail);

    // Redirect ke halaman penjualan
    header("Location: penjualan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h3 {
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-secondary {
            background: #6c757d;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3>Tambah Transaksi Penjualan</h3>
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
            <label>Metode Pembayaran</label>
            <select name="metode_bayar" class="form-control" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="penjualan.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
