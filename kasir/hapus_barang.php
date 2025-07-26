<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id_barang']) && is_numeric($_GET['id_barang'])) {
    $id = intval($_GET['id_barang']);

    // Pastikan barang ini milik user yang login
    $cek = $koneksi->prepare("SELECT * FROM barang WHERE id_barang = ? AND user_id = ?");
    $cek->bind_param("ii", $id, $user_id);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        // Jika milik user, lanjut hapus
        $stmt = $koneksi->prepare("DELETE FROM barang WHERE id_barang = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);

        if ($stmt->execute()) {
            echo "
            <div style='font-family:Arial;text-align:center;margin-top:50px;'>
                <h2 style='color:green;'>Data berhasil dihapus!</h2>
                <a href='master_data.php' style='display:inline-block;margin-top:20px;padding:10px 20px;background:#3498db;color:#fff;text-decoration:none;border-radius:5px;'>Kembali ke Master Data</a>
            </div>
            ";
        } else {
            echo "Gagal menghapus data!";
        }
    } else {
        echo "Data tidak ditemukan atau bukan milik Anda!";
    }
} else {
    echo "ID Barang tidak ditemukan!";
}
?>
