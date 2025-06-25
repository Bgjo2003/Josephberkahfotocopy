<?php
// Konfigurasi database untuk hosting
$host     = 'localhost'; // Tetap localhost di cPanel
$dbname   = 'zqcqxvqj_db_toko'; // Ganti dengan nama database dari cPanel
$username = 'zqcqxvqj_zqcqxvqj';  // Ganti dengan username database dari cPanel
$password = 'Q1uQ_hJ2XgaK';  // Ganti dengan password database yang dibuat di cPanel

// Membuat koneksi menggunakan PDO
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT         => false
    ]);
} catch (PDOException $e) {
    die('Koneksi gagal: ' . $e->getMessage());
}
