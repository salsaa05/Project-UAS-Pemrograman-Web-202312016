<?php
session_start();
include 'koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data servis milik user yang login saja
$servis = mysqli_query($koneksi, "SELECT * FROM servis WHERE user_id = $user_id ORDER BY tgl_masuk DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Servis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            margin-top: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
        }

        h2 i {
            margin-right: 10px;
            color: var(--accent-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
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
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #eee;
        }

        .btn-sm {
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e07f0c;
            border-color: #e07f0c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(248, 150, 30, 0.3);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2><i class="fas fa-tools"></i> Data Servis</h2>
    <a href="tambah_servis.php" class="btn btn-primary mb-4">
        <i class="fas fa-plus-circle me-2"></i>Tambah Servis
    </a>

    <a href="dashboard.php" class="btn btn-secondary mb-4">Kembali</a>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Pelanggan</th>
                <th>No HP</th>
                <th>Barang</th>
                <th>Kerusakan</th>
                <th>Status</th>
                <th>Tgl Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($servis)): 
            $statusClass = '';
            if ($row['status'] == 'Pending') $statusClass = 'status-pending';
            if ($row['status'] == 'Selesai') $statusClass = 'status-completed';
            if ($row['status'] == 'Proses') $statusClass = 'status-processing';
        ?>
            <tr>
                <td><strong><?= $row['kode_servis'] ?></strong></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= $row['jenis_barang'] ?></td>
                <td><?= $row['kerusakan'] ?></td>
                <td><span class="status-badge <?= $statusClass ?>"><?= $row['status'] ?></span></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_masuk'])) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="ubah_status_servis.php?id=<?= $row['id_servis'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit me-1"></i>Ubah
                        </a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
