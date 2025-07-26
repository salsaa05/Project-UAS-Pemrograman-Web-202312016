<?php
session_start();
include 'koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Set zona waktu dan tanggal hari ini
date_default_timezone_set('Asia/Jakarta');
$tanggal_hari_ini = date("Y-m-d");

// Ambil data penjualan 'Cash' milik user login untuk hari ini
$query = "
    SELECT 
        id_penjualan,
        tanggal,
        nama_produk,
        qty,
        harga,
        metode_bayar
    FROM penjualan
    WHERE user_id = $user_id AND metode_bayar = 'Cash' AND DATE(tanggal) = '$tanggal_hari_ini'
    ORDER BY id_penjualan DESC
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Penjualan Cash Hari Ini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3 class="mb-3">Invoice Penjualan Cash Hari Ini (<?= date("d-m-Y") ?>)</h3>

    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn btn-success">🖨️ Cetak</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>ID Penjualan</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga Satuan (Rp)</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $total = $row['qty'] * $row['harga'];
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['id_penjualan']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['nama_produk']}</td>
                        <td>{$row['qty']}</td>
                        <td>" . number_format($row['harga'], 0, ',', '.') . "</td>
                        <td>" . number_format($total, 0, ',', '.') . "</td>
                        </tr>";
                $grand_total += $total;
                $no++;
            }

            if ($no === 1) {
                echo "<tr><td colspan='7' class='text-center'>Tidak ada transaksi cash hari ini.</td></tr>";
            }
            ?>
        </tbody>
        <?php if ($no > 1): ?>
        <tfoot>
            <tr class="table-warning">
                <td colspan="6" class="text-end"><strong>Grand Total</strong></td>
                <td><strong><?= number_format($grand_total, 0, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>
