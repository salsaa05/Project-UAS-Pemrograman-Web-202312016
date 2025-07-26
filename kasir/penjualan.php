<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Filter tanggal jika ada
$where = "user_id = $user_id";
if (!empty($_GET['tanggal'])) {
    $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
    $where .= " AND DATE(tanggal) = '$tanggal'";
}

$query = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE $where ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Penjualan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
        font-family: "Segoe UI", Arial, sans-serif;
    }
    .container {
        max-width: 1100px;
    }
    .header-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 20px;
    }
    .btn {
        border-radius: 6px;
    }
    .table {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .table thead {
        background: #212529;
        color: #fff;
        font-size: 0.95rem;
        text-transform: uppercase;
    }
    .table tbody tr:hover {
        background-color: #f1f3f5;
    }
    .action-buttons .btn {
        padding: 4px 10px;
        font-size: 0.85rem;
    }
    .filter-form input[type="date"] {
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .btn-filter {
        background: #343a40;
        color: #fff;
    }
    .btn-reset {
        background: #6c757d;
        color: #fff;
    }
    .btn-add {
        background: #0d6efd;
        color: #fff;
    }
    .btn-back {
        background: #adb5bd;
        color: #212529;
    }
    .top-bar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 15px;
    }
</style>
</head>
<body>
<div class="container mt-4">
    <h3 class="header-title">Data Penjualan</h3>

    <div class="top-bar">
        <div>
            <a href="dashboard.php" class="btn btn-back">Kembali</a>
            <a href="tambah_penjualan.php" class="btn btn-add">+ Tambah</a>
        </div>
        <form method="GET" class="filter-form d-flex gap-2">
            <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>">
            <button type="submit" class="btn btn-filter">Filter</button>
            <a href="penjualan.php" class="btn btn-reset">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Metode Bayar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $grand_total = 0;
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $total = $row['qty'] * $row['harga'];
                    $grand_total += $total;
                    echo "<tr>
                        <td>{$no}</td>
                        <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                        <td>".htmlspecialchars($row['nama_produk'])."</td>
                        <td>{$row['qty']}</td>
                        <td>Rp ".number_format($row['harga'],0,',','.')."</td>
                        <td>Rp ".number_format($total,0,',','.')."</td>
                        <td>".($row['metode_bayar'] ?: '-')."</td>
                        <td class='text-center action-buttons'>
                            <a href='edit_penjualan.php?id={$row['id_penjualan']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='hapus_penjualan.php?id={$row['id_penjualan']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                        </td>
                    </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data penjualan</td></tr>";
            }
            ?>
            </tbody>
            <?php if ($grand_total > 0): ?>
            <tfoot>
                <tr class="table-warning">
                    <td colspan="5" class="text-end"><strong>Total Penjualan</strong></td>
                    <td colspan="3"><strong>Rp <?= number_format($grand_total, 0, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
</body>
</html>
