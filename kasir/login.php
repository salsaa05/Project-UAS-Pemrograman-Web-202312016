<?php
session_start();
include 'koneksi.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($username) || empty($password)) {
        $errors[] = "Username dan password wajib diisi.";
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' LIMIT 1");

        if ($user = mysqli_fetch_assoc($query)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Password salah.";
            }
        } else {
            $errors[] = "Username tidak ditemukan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kasir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333; 
        }

        .login-container {
            background: #f8f9fa;
            padding: 35px 40px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid #e0e0e0;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2575fc;
            font-size: 26px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: #fff;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        button:hover {
            background: #1a68e0;
        }

        .error {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .register-link {
            margin-top: 15px;
            text-align: center;
        }

        .register-link a {
            color: #2575fc; 
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Kasir</h2>

    <?php foreach ($errors as $e): ?>
        <div class="error"><?= $e; ?></div>
    <?php endforeach; ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</div>

</body>
</html>