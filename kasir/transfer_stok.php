<?php
include 'koneksi.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses tambah lokasi
if (isset($_POST['tambah_lokasi'])) {
    $lokasi_baru = mysqli_real_escape_string($koneksi, $_POST['nama_lokasi_baru']);
    $cek = mysqli_query($koneksi, "SELECT * FROM lokasi WHERE nama_lokasi = '$lokasi_baru'");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($koneksi, "INSERT INTO lokasi (nama_lokasi) VALUES ('$lokasi_baru')");
        header("Location: transfer_stok.php");
        exit;
    } else {
        $error = "Lokasi sudah ada!";
    }
}

// Ambil data barang dan lokasi
$barang = mysqli_query($koneksi, "SELECT * FROM barang");
$lokasi = mysqli_query($koneksi, "SELECT * FROM lokasi");

$barang_ada = mysqli_num_rows($barang) > 0;
$lokasi_ada = mysqli_num_rows($lokasi) > 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transfer Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Transfer Stok Antar Lokasi</h3>
        <div>
            <a href="riwayat_transfer.php" class="btn btn-primary me-2">Lihat Riwayat Transfer</a>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-warning"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Transfer -->
    <form action="proses_transfer.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label>Barang</label>
            <?php if ($barang_ada): ?>
                <select name="id_barang" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php while($b = mysqli_fetch_assoc($barang)): ?>
                        <option value="<?= $b['id_barang'] ?>"><?= $b['nama_barang'] ?></option>
                    <?php endwhile; ?>
                </select>
            <?php else: ?>
                <div class="text-danger">Tidak menemukan barang tersebut.</div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Dari Lokasi</label>
            <?php if ($lokasi_ada): ?>
                <select name="lokasi_asal" class="form-control" required>
                    <option value="">-- Pilih Lokasi Asal --</option>
                    <?php mysqli_data_seek($lokasi, 0); while($l = mysqli_fetch_assoc($lokasi)): ?>
                        <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
                    <?php endwhile; ?>
                </select>
            <?php else: ?>
                <div class="text-danger">Tidak menemukan lokasi tersebut.</div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Ke Lokasi</label>
            <?php if ($lokasi_ada): ?>
                <select name="lokasi_tujuan" class="form-control" required>
                    <option value="">-- Pilih Lokasi Tujuan --</option>
                    <?php mysqli_data_seek($lokasi, 0); while($l = mysqli_fetch_assoc($lokasi)): ?>
                        <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
                    <?php endwhile; ?>
                </select>
            <?php else: ?>
                <div class="text-danger">Tidak menemukan lokasi tersebut.</div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Jumlah Transfer</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>

        <!-- Tambahkan user_id sebagai hidden input -->
        <input type="hidden" name="user_id" value="<?= $user_id ?>">

        <button type="submit" class="btn btn-primary">Transfer</button>
    </form>

    <!-- Form Tambah Lokasi -->
    <hr>
    <h5>Tambah Lokasi Baru</h5>
    <form action="" method="POST" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="nama_lokasi_baru" class="form-control" placeholder="Nama Lokasi Baru" required>
        </div>
        <div class="col-md-4">
            <button type="submit" name="tambah_lokasi" class="btn btn-success w-100">Tambah Lokasi</button>
        </div>
    </form>
</div>

</body>
</html>
