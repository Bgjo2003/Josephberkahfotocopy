<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkSql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':username' => $username, ':email' => $email]);

    if ($checkStmt->fetchColumn() > 0) {
        $error = "Username atau email sudah digunakan!";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([':username' => $username, ':email' => $email, ':password' => $password])) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/costumer.css">
</head>
<body>
<div class="container">
    <div class="logo-header">
      <img src="img/logobf.jpg" alt="Logo Berkah Fotocopy" class="logo-img">
            <div class="welcome-text">Daftar Akun Pelanggan</div>
        </div>

        <?php if (isset($error)): ?>
            <p class="error-message" style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>

        <p class="register-to-login">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>