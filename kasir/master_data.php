<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data barang milik user yang login
$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE user_id = $user_id ORDER BY id_barang DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Master Data Barang</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 90%;
        margin: 30px auto;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    .top-actions {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 15px;
    }
    .btn {
        padding: 8px 14px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        margin: 2px;
        display: inline-block;
    }
    .btn-back {
        background: #3498db;
        color: white;
    }
    .btn-back:hover {
        background: #2980b9;
    }
    .btn-edit {
        background: #f39c12;
        color: white;
    }
    .btn-hapus {
        background: #e74c3c;
        color: white;
    }
    .btn-edit:hover {
        background: #d68910;
    }
    .btn-hapus:hover {
        background: #c0392b;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    table th, table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    table th {
        background: #3498db;
        color: white;
    }
    table tr:nth-child(even) {
        background: #f9f9f9;
    }
</style>
<script>
function konfirmasiHapus(nama) {
    return confirm("Yakin ingin menghapus barang: " + nama + " ?");
}
</script>
</head>
<body>
<div class="container">
    <h2>Master Data Barang</h2>

    <div class="top-actions">
        <a href="dashboard.php" class="btn btn-back">Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $row['id_barang']; ?></td>
                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>
                <td><?= $row['stok']; ?></td>
                <td>
                    <a class="btn btn-edit" href="edit_barang.php?id_barang=<?= $row['id_barang']; ?>">Edit</a>
                    <a class="btn btn-hapus" href="hapus_barang.php?id_barang=<?= $row['id_barang']; ?>" onclick="return konfirmasiHapus('<?= htmlspecialchars($row['nama_barang']); ?>')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
