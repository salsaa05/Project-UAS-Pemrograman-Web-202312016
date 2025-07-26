<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$q_penjualan = mysqli_query($koneksi, "SELECT COUNT(*) as total 
    FROM penjualan 
    WHERE DATE(tanggal) = CURDATE() AND user_id = '$user_id'");
$d_penjualan = mysqli_fetch_assoc($q_penjualan);

$tanggal_hari_ini = date("Y-m-d");
$q_invoice = mysqli_query($koneksi, "SELECT COUNT(*) as total 
    FROM transaksi 
    WHERE metode_bayar = 'cash' AND DATE(tanggal) = '$tanggal_hari_ini' AND user_id = '$user_id'");
$d_invoice = mysqli_fetch_assoc($q_invoice);

$q_barang = mysqli_query($koneksi, "
    SELECT SUM(dp.jumlah) as total 
    FROM detail_penjualan dp
    JOIN penjualan p ON dp.id_penjualan = p.id_penjualan
    WHERE DATE(p.tanggal) = CURDATE() AND p.user_id = '$user_id'
");
$d_barang_terjual = mysqli_fetch_assoc($q_barang);

$q_jml_barang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM barang WHERE user_id = '$user_id'");
$d_jml_barang = mysqli_fetch_assoc($q_jml_barang);

$q_total_invoice = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan WHERE user_id = '$user_id'");
$d_total_invoice = mysqli_fetch_assoc($q_total_invoice);

$q_servis_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM servis WHERE status = 'Masuk' AND user_id = '$user_id'");
$d_servis_masuk = mysqli_fetch_assoc($q_servis_masuk);

$q_servis_selesai = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM servis WHERE status = 'Selesai' AND user_id = '$user_id'");
$d_servis_selesai = mysqli_fetch_assoc($q_servis_selesai);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir Single-User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-blue: #2563eb;
        --light-blue: #3b82f6;
        --dark-blue: #1e40af;
        --teal: #0d9488;
        --cyan: #06b6d4;
        --indigo: #4f46e5;
        --violet: #7c3aed;
        --light-bg: #f8fafc;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: var(--light-bg);
        margin: 0;
        padding: 0;
    }

    .navbar-custom {
    background: var(--primary-blue); 
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 0.8rem 1rem;
}


    .navbar-custom .navbar-brand {
        font-weight: 700;
        font-size: 1.3rem;
        color: white;
        display: flex;
        align-items: center;
    }

    .navbar-custom .nav-link {
        color: rgba(255, 255, 255, 0.9);
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .navbar-custom .nav-link i {
        margin-right: 8px;
    }

    .content {
        padding: 30px;
        margin-top: 80px;
    }

    .dashboard-box {
        padding: 25px;
        border-radius: 12px;
        color: white;
        margin-bottom: 25px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        border: none;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .dashboard-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to bottom right,
            rgba(255, 255, 255, 0.15) 0%,
            rgba(255, 255, 255, 0) 60%
        );
        transform: rotate(30deg);
    }

    .dashboard-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .dashboard-box i {
        font-size: 2.2rem;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .dashboard-box h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .dashboard-box h5 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
    }

    .bg-primary {
        background: linear-gradient(135deg, var(--light-blue) 0%, var(--primary-blue) 100%);
    }

    .bg-warning {
        background: linear-gradient(135deg, var(--cyan) 0%, var(--teal) 100%);
    }

    .bg-success {
        background: linear-gradient(135deg, #10b981 0%, var(--teal) 100%);
    }

    .bg-info {
        background: linear-gradient(135deg, #38bdf8 0%, var(--cyan) 100%);
    }

    .bg-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .bg-secondary {
        background: linear-gradient(135deg, var(--violet) 0%, var(--indigo) 100%);
    }

    .bg-dark {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    }

    .stats-container {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stats-title {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
        font-size: 1.3rem;
    }

    .stats-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, var(--cyan) 100%);
        border-radius: 4px;
    }

    .header-title {
        color: #1e293b;
        font-weight: 800;
        margin-bottom: 30px;
        font-size: 2rem;
        position: relative;
        padding-bottom: 15px;
    }

    .header-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, var(--cyan) 100%);
        border-radius: 4px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .content {
            padding: 20px;
            margin-top: 70px;
        }
        
        .dashboard-box {
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .dashboard-box i {
            font-size: 1.8rem;
        }
        
        .header-title {
            font-size: 1.6rem;
            margin-bottom: 20px;
        }
    }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top px-3">
    <div class="container-fluid">
        <a class="navbar-brand me-4" href="#">
            <i class="fas fa-cash-register me-2"></i><strong>KasirOne</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="penjualan.php"><i class="fas fa-shopping-cart"></i> Penjualan</a></li>
                <li class="nav-item"><a class="nav-link" href="pembelian.php"><i class="fas fa-truck"></i> Pembelian</a></li>
                <li class="nav-item"><a class="nav-link" href="transfer_stok.php"><i class="fas fa-exchange-alt"></i> Transfer Stok</a></li>
                <li class="nav-item"><a class="nav-link" href="servis.php"><i class="fas fa-tools"></i> Servis</a></li>
                <li class="nav-item"><a class="nav-link" href="master_data.php"><i class="fas fa-database"></i> Master Data</a></li>
                <li class="nav-item"><a class="nav-link" href="semua_transaksi.php"><i class="fas fa-list-alt"></i> Semua Transaksi</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item me-3">
                    <div class="d-flex align-items-center text-white">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <span><?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Pengguna' ?></span>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="content">
    <h3 class="header-title">Dashboard</h3>

    <div class="stats-container mb-4">
    <h5 class="stats-title">Ringkasan Hari Ini</h5>
    <div class="row">
        <div class="col-md-4">
            <a href="penjualan.php" class="text-decoration-none">
                <div class="dashboard-box bg-primary">
                    <i class="fas fa-money-bill-wave"></i>
                    <h4>Penjualan Hari Ini</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="invoice.php" class="text-decoration-none">
                <div class="dashboard-box bg-warning">
                    <i class="fas fa-file-invoice"></i>
                    <h4>Invoice Cash</h4>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="penjualan_detail.php" class="text-decoration-none">
                <div class="dashboard-box bg-success">
                    <i class="fas fa-cart-arrow-down"></i>
                    <h4>Barang Terjual</h4>
                </div>
            </a>
        </div>
    </div>
</div>
<div class="stats-container">
    <h5 class="stats-title">Ringkasan Lainnya</h5>
    <div class="row">
        <div class="col-md-3">
            <a href="master_barang.php" class="text-decoration-none">
                <div class="dashboard-box bg-info">
                    <i class="fas fa-boxes"></i>
                    <h4>Jumlah Barang</h4>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="penjualan.php" class="text-decoration-none">
                <div class="dashboard-box bg-danger">
                    <i class="fas fa-receipt"></i>
                    <h4>Total Invoice</h4>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="servis.php" class="text-decoration-none">
                <div class="dashboard-box bg-secondary">
                    <i class="fas fa-user-friends"></i>
                    <h4>Servis Masuk</h4>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="servis_diambil.php" class="text-decoration-none">
                <div class="dashboard-box bg-dark">
                    <i class="fas fa-check-circle"></i>
                    <h4>Servis Selesai</h4>
                </div>
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Modal Input Transaksi -->
<div class="modal fade" id="modalTransaksi" tabindex="-1" aria-labelledby="modalTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="simpan_transaksi.php">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="modalTransaksiLabel">Input Transaksi Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total Transaksi</label>
                    <input type="number" name="total" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="metode_bayar" class="form-label">Metode Bayar</label>
                    <select name="metode_bayar" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="text-end mt-4">
    <a href="riwayat_transfer.php" class="btn btn-primary">
        <i class="fas fa-history me-1"></i> Lihat Riwayat Transfer
    </a>
</div>
</body>
</html>