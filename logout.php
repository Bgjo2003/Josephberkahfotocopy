<?php
session_start();
include('koneksi.php');

// Hanya logout jika user yang login (bukan admin)
if (isset($_SESSION['user_id'])) {
    // Update status logout di tabel users
    $logoutUpdateSql = "UPDATE users SET is_logged_in = 0 WHERE id_user = :user_id";
    $stmt = $pdo->prepare($logoutUpdateSql);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);

    // Hapus hanya session milik user
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    
    // Jika role-nya user, unset juga
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
        unset($_SESSION['role']);
    }
}

// Jangan destroy seluruh session agar admin tetap login
session_write_close();
header("Location: login.php");
exit();
?>