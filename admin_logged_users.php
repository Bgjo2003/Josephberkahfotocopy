<?php
session_start();
include('koneksi.php');

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

try {
    // Ambil data pengguna yang sedang login
    $sql = "SELECT username, email, login_time, last_activity 
            FROM users WHERE is_logged_in = 1";
    $stmt = $pdo->query($sql);
    $loggedInUsers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna yang Sedang Login</title>
</head>
<body>
    <h1>Pengguna yang Sedang Login</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Login Time</th>
                <th>Last Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($loggedInUsers) > 0): ?>
                <?php foreach ($loggedInUsers as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['login_time']); ?></td>
                        <td><?= htmlspecialchars($user['last_activity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada pengguna yang sedang login.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>