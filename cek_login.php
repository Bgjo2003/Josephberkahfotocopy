<?php
session_start();
include('koneksi.php');

if (isset($_SESSION['user_id'])) {
    $activityUpdateSql = "UPDATE users SET last_activity = NOW() WHERE id_user = :user_id";
    $stmt = $pdo->prepare($activityUpdateSql);
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
}
?>