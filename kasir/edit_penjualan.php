<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Validasi ID
if (!isset($_GET['id'])) {
    die("ID penjualan tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data penjualan
$query = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE id_penjualan = $id AND user_id = $user_id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data penjualan tidak ditemukan atau bukan milik Anda.");
}

// Ambil metode pembayaran dari tabel transaksi
$tanggal_penjualan = date('Y-m-d', strtotime($data['tanggal']));
$q_transaksi = mysqli_query($koneksi, "SELECT metode_bayar FROM transaksi 
    WHERE DATE(tanggal) = '$tanggal_penjualan' AND user_id = $user_id LIMIT 1");
$t_data = mysqli_fetch_assoc($q_transaksi);
$metode_bayar = $t_data['metode_bayar'] ?? $data['metode_bayar']; // fallback ke penjualan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $qty = intval($_POST['qty']);
    $harga = intval($_POST['harga']);
    $metode_baru = mysqli_real_escape_string($koneksi, $_POST['metode_bayar']);

    // Update penjualan
    $update_penjualan = mysqli_query($koneksi, "UPDATE penjualan 
        SET tanggal='$tanggal', 
            nama_produk='$nama_produk', 
            qty='$qty', 
            harga='$harga',
            metode_bayar='$metode_baru'
        WHERE id_penjualan = $id AND user_id = $user_id");

    // Update transaksi (jika ada transaksi di tanggal itu)
    $tanggal_only = date('Y-m-d', strtotime($tanggal));
    mysqli_query($koneksi, "UPDATE transaksi 
        SET metode_bayar = '$metode_baru' 
        WHERE DATE(tanggal) = '$tanggal_only' AND user_id = $user_id");

    if ($update_penjualan) {
        header("Location: penjualan.php?success=update");
        exit;
    } else {
        echo "Gagal update data: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="mb-4">Edit Penjualan</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d', strtotime($data['tanggal'])) ?>" required>
        </div>
        <div class="mb-3">
            <label>Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Jumlah (Qty)</label>
            <input type="number" name="qty" class="form-control" value="<?= $data['qty'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Harga Satuan</label>
            <input type="number" name="harga" class="form-control" value="<?= $data['harga'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Metode Pembayaran</label>
            <select name="metode_bayar" class="form-control" required>
                <option value="">- Pilih Metode -</option>
                <option value="Cash" <?= $metode_bayar == 'Cash' ? 'selected' : '' ?>>Cash</option>
                <option value="Transfer" <?= $metode_bayar == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                <option value="QRIS" <?= $metode_bayar == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="penjualan.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
