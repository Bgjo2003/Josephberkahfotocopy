<?php
session_start();
include('koneksi.php'); // File koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        $sql = "SELECT id_user, username, email, password, role FROM users 
                WHERE username = :username AND email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username, ':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Update status login di tabel users
            $loginUpdateSql = "UPDATE users 
                   SET is_logged_in = 1, 
                       login_time = NOW(), 
                       last_activity = NOW(),
                       login_count = login_count + 1
                   WHERE id_user = :user_id";
            $stmt = $pdo->prepare($loginUpdateSql);
            $stmt->execute([':user_id' => $user['id_user']]);

            // Simpan session umum (untuk admin maupun user)
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan ke dashboard sesuai role
            if ($user['role'] === 'admin') {
                // Tambahan session khusus admin (opsional)
                $_SESSION['admin_id'] = $user['id_user'];
                $_SESSION['admin_username'] = $user['username'];
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Username, email, atau password salah!";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan pada server. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/costumer.css">
</head>
<body>
<div class="container">
    <div class="logo-header">
        <img src="img/logobf.jpg" alt="Logo Berkah Fotocopy" class="logo-img">
        <h1 class="welcome-text">Selamat Datang di<br>Berkah Fotocopy Tebing Tinggi</h1>
    </div>

    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>
    
    <p class="register-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>