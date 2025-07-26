<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil tanggal dari input, default hari ini
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query total barang terjual berdasarkan tanggal dan user
$queryTotal = "
    SELECT SUM(detail_penjualan.jumlah) AS total_terjual
    FROM detail_penjualan
    INNER JOIN penjualan ON detail_penjualan.id_penjualan = penjualan.id_penjualan
    WHERE DATE(penjualan.tanggal) = '$tanggal' AND penjualan.user_id = '$user_id'
";
$resultTotal = mysqli_query($koneksi, $queryTotal);
$dataTotal = mysqli_fetch_assoc($resultTotal);
$total_terjual = $dataTotal['total_terjual'] ?? 0;

// Query daftar barang terjual lengkap dengan metode pembayaran
$queryDetail = "
    SELECT barang.nama_barang, detail_penjualan.jumlah, detail_penjualan.harga, 
           (detail_penjualan.jumlah * detail_penjualan.harga) AS subtotal,
           detail_penjualan.metode_pembayaran
    FROM detail_penjualan
    INNER JOIN penjualan ON detail_penjualan.id_penjualan = penjualan.id_penjualan
    INNER JOIN barang ON detail_penjualan.id_barang = barang.id_barang
    WHERE DATE(penjualan.tanggal) = '$tanggal' AND penjualan.user_id = '$user_id'
";
$resultDetail = mysqli_query($koneksi, $queryDetail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 30px; }
        .card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        table { background: #fff; border-radius: 10px; overflow: hidden; }
        th { background-color: #343a40; color: #fff; }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4">
        <h3 class="mb-4">Detail Penjualan</h3>

        <!-- Filter Tanggal -->
        <form method="GET" class="mb-3 d-flex">
            <input type="date" name="tanggal" class="form-control me-2" value="<?= $tanggal ?>">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <div class="alert alert-info">
            Total Barang Terjual pada <strong><?= $tanggal ?></strong>: 
            <span class="fw-bold"><?= $total_terjual ?> pcs</span>
        </div>

        <!-- Tabel Detail -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($resultDetail)): ?>
                <tr>
                    <td><?= $row['nama_barang'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($row['metode_pembayaran']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tombol Kembali -->
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>
