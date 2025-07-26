<?php
session_start();
include 'koneksi.php';
$_SESSION['user_id'] = $data_user['id']; // Ambil dari tabel users
$_SESSION['username'] = $data_user['username'];

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' LIMIT 1");
$user = mysqli_fetch_assoc($query);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['level'] = $user['level'];
    header("Location: dashboard.php");
} else {
    $_SESSION['error'] = "Login gagal! Periksa username/password.";
    header("Location: login.php");
}
