<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }
        h1 {
            color: #4CAF50;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            margin-right: 15px;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            padding: 10px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            background: #f9f9f9;
            transition: background 0.3s;
        }
        .nav a:hover {
            background: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Role Anda: <?= htmlspecialchars($_SESSION['role']); ?></p>
        <p>Ini adalah halaman dashboard untuk admin. Anda dapat mengelola data pengguna, produk, atau pesanan di sini.</p>

        <!-- Navigasi Admin -->
        <div class="nav">
            <a href="admin_logged_users.php">Lihat Pengguna yang Login</a>
            <a href="index.php?admin_view=true" target="_blank">Lihat Halaman Pengguna</a>
            <a href="/adminfotocopy/login.php" title="Admin Only">Login Admin</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Bagian tambahan -->
        <h2>Ringkasan Data</h2>
        <p>Tambahkan fitur ringkasan seperti jumlah pengguna, produk, atau pesanan terbaru di sini.</p>
    </div>
</body>
</html>