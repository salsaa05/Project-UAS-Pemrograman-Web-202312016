<?php
session_start();
include 'koneksi.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));

    if (empty($username) || empty($password) || empty($nama_lengkap)) {
        $errors[] = "Semua kolom wajib diisi!";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $errors[] = "Username sudah digunakan!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $simpan = mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap) VALUES ('$username', '$hash', '$nama_lengkap')");

            if ($simpan) {
                $_SESSION['success'] = "Registrasi berhasil, silakan login!";
                header("Location: login.php");
                exit;
            } else {
                $errors[] = "Gagal menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #ffffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2575fc;
            margin-bottom: 20px;
        }

        .form-box input[type="text"],
        .form-box input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        .form-box button {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }

        .form-box button:hover {
            background: #1a60e0;
        }

        .error {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #2575fc;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Registrasi</h2>

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= $e; ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
    </form>

    <div class="login-link">
        Sudah punya akun? <a href="login.php">Login</a>
    </div>
</div>

</body>
</html>
