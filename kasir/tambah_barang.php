<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];
    $beli = $_POST['harga_beli'];
    $jual = $_POST['harga_jual'];
    $user_id = $_SESSION['user_id']; // Ambil ID user yang login

    mysqli_query($koneksi, "INSERT INTO barang (kode_barang, nama_barang, kategori, satuan, stok, harga_beli, harga_jual, user_id)
        VALUES ('$kode', '$nama', '$kategori', '$satuan', '$stok', '$beli', '$jual', '$user_id')");

    header("Location: master_data.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            max-width: 800px;
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

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }

        .btn i {
            margin-right: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 8px 0 0 8px;
        }

        .currency-input {
            padding-left: 40px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3><i class="fas fa-box-open"></i> Tambah Barang</h3>
    <form method="post">
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="0" min="0">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga Beli</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" class="form-control" required min="0">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga Jual</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control" required min="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="master_data.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
