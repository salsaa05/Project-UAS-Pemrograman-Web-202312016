<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Ambil data filter tanggal jika ada
$filter = '';
$param_filter = '';
if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filter = $_GET['filter'];
    // Validasi tanggal sederhana
    if (DateTime::createFromFormat('Y-m-d', $filter)) {
        $param_filter = "AND tanggal = '$filter'";
    }
}

// Query data pembelian
$sql = "SELECT * FROM pembelian WHERE user_id = '$user_id' $param_filter ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pembelian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-link.active { font-weight: 600; color: #0d6efd !important; }
        .container { padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px; }
        h3 { color: #343a40; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; padding: 1rem; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .table { margin-top: 1rem; }
        .table thead th { background-color: #343a40; color: white; font-weight: 500; }
        .table tbody tr:hover { background-color: #f8f9fa; }
        .btn { font-weight: 500; border-radius: 6px; }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
        .btn-outline-secondary { border-color: #dee2e6; }
        .form-control { border-radius: 6px; padding: 0.5rem 0.75rem; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top px-3">
    <a class="navbar-brand me-4" href="#">KasirOne</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="penjualan.php">Penjualan</a></li>
            <li class="nav-item"><a class="nav-link active" href="pembelian.php">Pembelian</a></li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item me-3 text-white"><?= htmlspecialchars($username) ?></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <h3 class="mb-4">Data Pembelian</h3>

    <div class="top-bar">
        <div class="left-buttons">
            <a href="dashboard.php" class="btn btn-outline-secondary">Kembali</a>
            <a href="tambah_pembelian.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Tambah</a>
        </div>

        <form method="GET" class="d-flex align-items-center gap-2">
            <input type="date" name="filter" class="form-control" value="<?= htmlspecialchars($filter) ?>">
            <button type="submit" class="btn btn-dark">Filter</button>
            <a href="pembelian.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)):
            $total = $row['qty'] * $row['harga'];
        ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td class="text-center"><?= $row['qty'] ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
                <td>Rp <?= number_format($total,0,',','.') ?></td>
                <td class="text-center">
                    <a href="edit_pembelian.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_pembelian.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
