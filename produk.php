<?php
// Sertakan file koneksi
include('koneksi.php');

// Query untuk mengambil data produk
$sql = "SELECT * FROM barang"; // Ganti "barang" dengan nama tabel Anda
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Simpan hasil query dalam variabel $result
$result = $stmt;
?>