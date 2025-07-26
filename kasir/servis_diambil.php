<?php
session_start();
include 'koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses update status jika ada request POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Validasi bahwa id_servis tersebut milik user login
    $cek = mysqli_query($koneksi, "SELECT * FROM servis WHERE id_servis = $id AND user_id = $user_id");
    if (mysqli_num_rows($cek) === 0) {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
        exit;
    }

    // Update status
    $update = mysqli_query($koneksi, "UPDATE servis SET status = 'Diambil' WHERE id_servis = $id");
    if ($update) {
        echo json_encode(['success' => true, 'status' => 'Diambil']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal update']);
    }
    exit;
}

// Ambil semua data servis milik user login
$servis = mysqli_query($koneksi, "SELECT * FROM servis WHERE user_id = $user_id ORDER BY id_servis DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Servis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .page-title {
            font-weight: 600;
            color: #343a40;
            margin: 0;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
        .table {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
            font-weight: 500;
            padding: 15px;
            text-align: center;
        }
        .table tbody tr {
            transition: all 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateX(3px);
        }
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .btn-action {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            transition: all 0.2s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .badge {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
        }
        .status-pending {
            color: #856404;
            background-color: #fff3cd;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        .status-completed {
            color: #155724;
            background-color: #d4edda;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tools me-2"></i>Data Servis
        </h1>
        <a href="dashboard.php" class="btn-back">
            Kembali
        </a>
    </div>

    <table class="table table-bordered table-hover">
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
            <?php while ($row = mysqli_fetch_assoc($servis)) : ?>
            <tr id="row-<?= $row['id_servis']; ?>">
                <td><?= $row['kode_servis']; ?></td>
                <td><?= $row['nama_pelanggan']; ?></td>
                <td><?= $row['no_hp']; ?></td>
                <td><?= $row['jenis_barang']; ?></td>
                <td><?= $row['kerusakan']; ?></td>
                <td id="status-<?= $row['id_servis']; ?>" class="<?= $row['status'] === 'Diambil' ? 'status-completed' : 'status-pending' ?>">
                    <?= $row['status']; ?>
                </td>
                <td><?= date('d/m/Y', strtotime($row['tgl_masuk'])); ?></td>
                <td class="text-center">
                    <?php if ($row['status'] !== 'Diambil') : ?>
                        <button class="btn btn-success btn-action" onclick="tandaiDiambil(<?= $row['id_servis']; ?>)">
                            <i class="fas fa-check me-1"></i>Tandai Diambil
                        </button>
                    <?php else : ?>
                        <span class="badge bg-secondary">
                            <i class="fas fa-check-circle me-1"></i>Selesai
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function tandaiDiambil(id) {
    if (confirm("Yakin servis sudah diambil oleh pelanggan?")) {
        fetch('servis_diambil.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusCell = document.getElementById('status-' + id);
                statusCell.innerText = data.status;
                statusCell.className = 'status-completed';

                const btn = document.querySelector(`#row-${id} button`);
                if (btn) {
                    btn.outerHTML = `<span class="badge bg-secondary">
                        <i class="fas fa-check-circle me-1"></i>Selesai
                    </span>`;
                }
            } else {
                alert('Gagal update: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghubungi server.');
        });
    }
}
</script>

</body>
</html>
