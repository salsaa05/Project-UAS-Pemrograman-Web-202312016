<?php
include 'koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil ID servis dari parameter
$id = $_GET['id'];

// Cek apakah data servis milik user ini
$cek = mysqli_query($koneksi, "SELECT * FROM servis WHERE id_servis = $id AND user_id = $user_id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "<div class='alert alert-danger m-4'>Data tidak ditemukan atau Anda tidak memiliki akses.</div>";
    exit;
}

// Proses update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $tgl_selesai = ($status === 'Selesai' || $status === 'Diambil') ? date('Y-m-d H:i:s') : null;

    $tgl_selesai_sql = $tgl_selesai ? "'$tgl_selesai'" : "NULL";

    mysqli_query($koneksi, "UPDATE servis SET status = '$status', tgl_selesai = $tgl_selesai_sql WHERE id_servis = $id AND user_id = $user_id");

    header("Location: servis.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Status Servis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Ubah Status Servis</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Masuk" <?= $data['status']=='Masuk' ? 'selected' : '' ?>>Masuk</option>
                <option value="Proses" <?= $data['status']=='Proses' ? 'selected' : '' ?>>Proses</option>
                <option value="Selesai" <?= $data['status']=='Selesai' ? 'selected' : '' ?>>Selesai</option>
                <option value="Diambil" <?= $data['status']=='Diambil' ? 'selected' : '' ?>>Diambil</option>
            </select>
        </div>
        <button class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
