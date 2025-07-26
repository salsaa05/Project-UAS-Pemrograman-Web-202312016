<?php
include 'koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query data transfer milik user saja
$query = mysqli_query($koneksi, "
    SELECT ts.*, b.nama_barang, l1.nama_lokasi AS asal, l2.nama_lokasi AS tujuan
    FROM transfer_stok ts
    JOIN barang b ON ts.id_barang = b.id_barang
    JOIN lokasi l1 ON ts.lokasi_asal = l1.id
    JOIN lokasi l2 ON ts.lokasi_tujuan = l2.id
    WHERE ts.user_id = $user_id
    ORDER BY ts.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transfer Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 30px;
        }

        h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
        }

        h3 i {
            margin-right: 12px;
            color: var(--secondary-color);
        }

        .table {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table thead th {
            padding: 15px;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            border-bottom: none;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f8ff;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #eee;
        }

        .btn-back {
            margin-top: 30px;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3><i class="fas fa-exchange-alt"></i> Riwayat Transfer Stok</h3>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Dari Lokasi</th>
                <th>Ke Lokasi</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
                <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['asal']) ?></td>
                        <td><?= htmlspecialchars($row['tujuan']) ?></td>
                        <td class="text-center"><?= number_format($row['jumlah']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-results">
                        <i class="fas fa-info-circle fa-2x mb-3"></i><br>
                        Belum ada riwayat transfer stok
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="text-end">
        <a href="dashboard.php" class="btn btn-primary btn-back">
            Kembali
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
