<?php
session_start();
include 'koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Tambah Barang
if (isset($_POST['tambah'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO barang (nama_barang, stok, harga, user_id)
              VALUES ('$nama_barang', '$stok', '$harga', '$user_id')";
    mysqli_query($koneksi, $query);
    header('Location: master_barang.php');
    exit;
}

// Edit Barang
if (isset($_POST['edit'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "UPDATE barang 
              SET nama_barang='$nama_barang', stok='$stok', harga='$harga' 
              WHERE id_barang='$id_barang' AND user_id='$user_id'";
    mysqli_query($koneksi, $query);
    header('Location: master_barang.php');
    exit;
}

// Hapus Barang
if (isset($_GET['hapus'])) {
    $id_barang = $_GET['hapus'];
    $query = "DELETE FROM barang 
              WHERE id_barang='$id_barang' AND user_id='$user_id'";
    mysqli_query($koneksi, $query);
    header('Location: master_barang.php');
    exit;
}

// Ambil data barang milik user login
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query_barang = "SELECT * FROM barang 
                 WHERE nama_barang LIKE '%$search%' AND user_id='$user_id' 
                 ORDER BY id_barang DESC";
$result_barang = mysqli_query($koneksi, $query_barang);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        <h3 class="mb-3">Master Barang</h3>

        <!-- Tombol Kembali -->
        <div class="mb-3 text-start">
            <a href="dashboard.php" class="btn btn-primary">Kembali</a>
        </div>

        <!-- Form Pencarian -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari Barang..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <!-- Tombol Tambah -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Barang</button>

        <!-- Tabel Barang -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result_barang)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_barang'] ?>">Edit</button>
                        <a href="?hapus=<?= $row['id_barang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit<?= $row['id_barang'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
                                    <div class="mb-3">
                                        <label>Nama Barang</label>
                                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($row['nama_barang']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Stok</label>
                                        <input type="number" name="stok" class="form-control" value="<?= $row['stok'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Harga</label>
                                        <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
