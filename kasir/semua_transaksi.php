<?php
session_start();
include 'koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua data penjualan milik user
$query = "
    SELECT 
        p.id_penjualan,
        p.tanggal,
        p.metode_bayar,
        SUM(p.qty * p.harga) AS total
    FROM penjualan p
    WHERE p.user_id = $user_id
    GROUP BY p.id_penjualan, p.tanggal, p.metode_bayar
    ORDER BY p.tanggal DESC, p.id_penjualan DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Semua Transaksi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .container {
            max-width: 1000px;
        }
        h3 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #343a40;
        }
        .table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .table thead {
            background: #212529;
            color: #fff;
        }
        .table tfoot {
            font-weight: bold;
            background: #ffc107;
        }
        .btn {
            border-radius: 6px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="container mt-5">

    <div class="top-bar">
        <h3>Daftar Transaksi Penjualan</h3>
        <div>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            <button onclick="window.print()" class="btn btn-success">🖨️ Cetak</button>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Penjualan</th>
                <th>Tanggal</th>
                <th>Metode Bayar</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['id_penjualan']}</td>
                        <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
                        <td>{$row['metode_bayar']}</td>
                        <td>" . number_format($row['total'], 0, ',', '.') . "</td>
                        </tr>";
                $grand_total += $row['total'];
                $no++;
            }

            if ($no === 1) {
                echo "<tr><td colspan='5' class='text-center'>Tidak ada data transaksi.</td></tr>";
            }
            ?>
        </tbody>
        <?php if ($no > 1): ?>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end">Grand Total</td>
                <td><?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>
