<?php
// Pastikan koneksi ke database sudah disiapkan
require 'koneksi.php'; // File konfigurasi koneksi database

// Ambil ID produk dari URL
$id_barang = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data produk dari database
$query = $pdo->prepare("SELECT * FROM barang WHERE id_barang = :id");
$query->execute(['id' => $id_barang]);
$produk = $query->fetch(PDO::FETCH_ASSOC);

// Jika produk tidak ditemukan
if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
?> 

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/costumer.css">
    <title>Order Produk</title>
</head>
<body>
<div class="order-container">
    <h2>Pesan Produk</h2>
    <p>Nama Produk: <?= htmlspecialchars($produk['nama_barang']); ?></p>
    <p>Harga: Rp<?= number_format($produk['harga_jual'], 0, ',', '.'); ?></p>
    <p>Stok Tersedia: <?= htmlspecialchars($produk['stok']); ?></p>

    <!-- Form Pemesanan -->
    <form action="proses_order.php" method="POST" id="formPesanan">
    <input type="hidden" name="id_barang" value="<?= $produk['id_barang']; ?>">
    <input type="hidden" name="harga" id="hargaSatuan" value="<?= $produk['harga_jual']; ?>">
    
    <label for="nama">Nama Pemesan:</label>
    <input type="text" id="nama" name="nama" required>
    
    <label for="kontak">Kontak Pemesan:</label>
    <input type="text" id="kontak" name="kontak" required>
    
    <label for="jumlah">Jumlah:</label>
    <input type="number" id="jumlah" name="jumlah" min="1" max="<?= $produk['stok']; ?>" required>
    
    <p><strong>Total Harga:</strong> Rp<span id="totalHarga">0</span></p>
    
    <button type="submit">Pesan Sekarang</button>
</form>
</div>
<!-- Memuat file JavaScript eksternal -->
<script src="js/order.js"></script>
</body>
</html>