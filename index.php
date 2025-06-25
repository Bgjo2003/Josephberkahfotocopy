<?php
session_start();

// Cek apakah admin sedang melihat dengan parameter 'admin_view'
$is_admin_view = isset($_GET['admin_view']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if ($is_admin_view) {
    echo "<p>Anda sedang melihat halaman sebagai admin.</p>";
} elseif (!isset($_SESSION['user_id'])) {
    // Jika bukan admin dan tidak login, tampilkan pesan biasa, tidak redirect
    echo "<p>Anda belum login. Beberapa fitur mungkin tidak tersedia.</p>";
}

// Sertakan file koneksi ke database
require_once('koneksi.php');

try {
    // Ambil data produk dari tabel barang
    $sql = "SELECT * FROM barang";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Simpan hasil query
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Terjadi kesalahan dalam mengambil data: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Berkah Fotocopy Tebing Tinggi</title>
    <link rel="stylesheet" href="css/style.css" />
    

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar">
      <div class="container">
      <a href="#" class="logo">
  <img src="img/logobf.jpg" alt="Logo" class="logo-img">
  Berkah Fotocopy
</a>
        <ul class="nav-list">
          <li><a href="#home">Home</a></li>
          <li><a href="#about">Tentang Kami</a></li>
          <li><a href="#produk">Produk</a></li>
          <li><a href="#layanan">Layanan Kami</a></li>
          <li><a href="#contact">Kontak Kami</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
  <li><a href="logout.php">Logout</a></li>
<?php else: ?>
  <li><a href="login.php">Login</a></li>
<?php endif; ?>
        </ul>
        <div class="navbar-extra">
        <a href="#" id="search" class="search-btn"><i data-feather="search"></i></a>
          <a href="#" id="shopping-cart"
            ><i data-feather="shopping-cart"></i
          ></a>
          <span id="cart-badge" class="cart-badge">0</span>
          <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
      </div>
    </nav>

<!-- Search Box -->
<div id="searchBox" class="search-box">
  <input type="text" id="searchInput" placeholder="Cari sesuatu...">
  <button id="searchSubmit" class="search-submit">Cari</button>
</div>

<!-- Modal Hamburger Menu -->
<div id="hamburgerOverlay" class="modal-overlay"></div>
<div id="hamburgerModal" class="modal">
  <div class="modal-content">
    <span id="close-hamburger" class="close">&times;</span>
    <nav class="navbar">
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#about">Tentang Kami</a></li>
        <li><a href="#produk">Produk</a></li>
        <li><a href="#layanan">Layanan Kami</a></li>
        <li><a href="#contact">Kontak Kami</a></li>
      </ul>
    </nav>
  </div>
</div>

<!-- Modal Keranjang -->
<div id="cartModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Keranjang Belanja</h2>

    <!-- Form Nama Pemesan -->
    <div class="form-pemesan">
      <label for="namaPemesan">Nama Pemesan:</label>
      <input type="text" id="namaPemesan" placeholder="Masukkan nama pemesan">
      <label for="kontakPemesan">Kontak:</label>
      <input type="text" id="kontakPemesan" placeholder="Masukkan kontak pemesan">
    </div>

    <!-- Tabel Produk -->
    <table id="cartTable">
      <thead>
        <tr>
          <th>Jumlah</th>
          <th>Nama Barang</th>
          <th>Merek</th>
          <th>Harga Barang</th>
          <th>Total Harga</th>
          <th>Hapus Barang</th> <!-- Kolom untuk tombol Hapus -->
        </tr>
      </thead>
      <tbody>
        <!-- Data keranjang akan dimuat oleh JavaScript -->
      </tbody>
    </table>

    <!-- Elemen untuk Grand Total -->
    <div class="grand-total">
      <strong>Grand Total Harga:</strong> 
      <span id="grandTotal">Rp0</span>
    </div>
    <button id="checkout" class="btn">Checkout</button>
  </div>
</div>

    <!-- Hero Section -->
    <section class="hero" id="home">
      <main class="content">
        <h1>Selamat Datang Di Berkah Fotocopy <span> Tebing Tinggi </span></h1>
        <p>Tempat terbaik untuk memenuhi kebutuhan cetak dan fotokopi Anda!</p>
        <a href="#produk" class="btn">Lihat Produk</a>
      </main>
    </section>

    <!-- Tentang Kami -->
<section id="about" class="about">
  <h2>Tentang Kami</h2>

  <div class="row">
    <div class="about-img">
      <img src="img/tentang-kami2.jpg" alt="Tentang Kami" />
    </div>
    <div class="content">
      <h3>Mengapa Memilih Layanan Cetak & Fotocopy Kami?</h3>
      <p>
        Toko Berkah Fotocopy Tebing Tinggi merupakan solusi terpercaya untuk
        kebutuhan fotokopi, cetak, dan alat tulis Anda. Kami berdiri dengan
        komitmen untuk memberikan pelayanan terbaik, harga terjangkau, serta
        hasil kerja yang rapi dan cepat.
      </p>
      <p>
        Karena kami selalu mengutamakan <strong>kepuasan pelanggan</strong>,
        menjunjung tinggi <strong>kejujuran</strong> dalam setiap transaksi, dan
        memberikan <strong>pelayanan prima</strong> yang cepat tanggap.
        Didukung oleh peralatan modern dan tenaga profesional, kami siap
        membantu Anda menyelesaikan berbagai kebutuhan dokumen, baik untuk
        sekolah, kuliah, maupun keperluan bisnis.
      </p>
      <p>
        Kami juga menyediakan layanan pemesanan online untuk memudahkan Anda
        dalam mengirim file cetakan tanpa harus datang langsung ke toko. Praktis,
        efisien, dan tepat waktu!
      </p>
    </div>
  </div>
</section>

    <!-- Daftar Barang -->
    <section id="produk" class="produk">
  <h2>Produk Kami</h2>
  <p>Silahkan Memesan Produk Kami, Terima kasih</p>
  <!-- Filter Kategori -->
  <form method="GET" action="">
    <label for="kategori">Filter Berdasarkan Kategori:</label>
    <select name="kategori" id="kategori" onchange="this.form.submit()">
      <option value="">Semua Kategori</option>
      <option value="Pulpen" <?= isset($_GET['kategori']) && $_GET['kategori'] == 'Pulpen' ? 'selected' : '' ?>>Pulpen</option>
      <option value="Lem" <?= isset($_GET['kategori']) && $_GET['kategori'] == 'Lem' ? 'selected' : '' ?>>Lem</option>
      <option value="Buku Tulis" <?= isset($_GET['kategori']) && $_GET['kategori'] == 'Buku Tulis' ? 'selected' : '' ?>>Buku Tulis</option>
      <option value="Spidol" <?= isset($_GET['kategori']) && $_GET['kategori'] == 'Spidol' ? 'selected' : '' ?>>Spidol</option>
      <option value="Tipex" <?= isset($_GET['kategori']) && $_GET['kategori'] == 'Tipex' ? 'selected' : '' ?>>Tipex</option>
      <!-- Tambahkan kategori lainnya -->
    </select>
  </form>

  <div class="barang-list">
    <?php
    // Ambil kategori dari parameter URL
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

    // Modifikasi query untuk join dengan tabel kategori
    $sql = "SELECT b.*, k.nama_kategori FROM barang b 
    JOIN kategori k ON b.id_kategori = k.id_kategori";
    
    if (!empty($kategori)) {
      $sql .= " WHERE k.nama_kategori = :kategori";
    }

    // Eksekusi query
    $stmt = $pdo->prepare($sql);
    if (!empty($kategori)) {
        $stmt->bindParam(':kategori', $kategori, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt;

    // Tampilkan produk
    if ($result->rowCount() > 0):
        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
    ?>
      <div class="barang-item">
        <img
          src="<?= htmlspecialchars($row['gambar']); ?>"
          alt="<?= htmlspecialchars($row['nama_barang']); ?>"
          class="barang-img"
        />
        <h2><?= htmlspecialchars($row['nama_barang']); ?></h2>
        <p class="barang-merek">
          Merek:
          <?= htmlspecialchars($row['merk']); ?>
        </p>
        <p class="barang-harga">
          Harga: Rp<?= number_format($row['harga_jual'], 0, ',', '.'); ?>
        </p>
        <?php if ($row['stok'] > 0): ?>
          <div class="stock-tersedia">
            <span>Stock Tersedia: <?= htmlspecialchars($row['stok']); ?></span>
          </div>
        <?php else: ?>
          <div class="stock-habis">
            <span>Stock Habis</span>
          </div>
        <?php endif; ?>
        <button 
          class="btn add-to-cart" 
          data-id="<?= htmlspecialchars($row['id_barang']); ?>" 
          data-nama="<?= htmlspecialchars($row['nama_barang']); ?>" 
          data-merek="<?= htmlspecialchars($row['merk']); ?>" 
          data-harga="<?= htmlspecialchars($row['harga_jual']); ?>" 
          data-stok="<?= htmlspecialchars($row['stok']); ?>">
          Pesan Sekarang
        </button>
      </div>
    <?php endwhile; ?>
    <?php else: ?>
      <p>Tidak ada produk yang tersedia saat ini.</p>
    <?php endif; ?>
  </div>
</section>

    <!-- Layanan Kami -->
    <section id="layanan" class="layanan">
    <h2>Layanan Kami</h2>
    <p>Silahkan Pilih Layanan Kami, Terima kasih</p>
    <div class="layanan-list">
      <div class="layanan-item">
        <h3>Fotocopy</h3>
        <p class="layanan-deskripsi">
          Layanan fotocopy dokumen dengan hasil berkualitas tinggi. Harga mulai dari Rp 200/lembar.
        </p>
        <button class="pesan-btn" data-layanan="Fotocopy">Pesan Sekarang</button>
      </div>
      <div class="layanan-item">
        <h3>Print</h3>
        <p class="layanan-deskripsi">
          Layanan print dokumen dengan pilihan warna atau hitam putih. Harga mulai dari Rp 500/lembar.
        </p>
        <button class="pesan-btn" data-layanan="Print">Pesan Sekarang</button>
      </div>
      <div class="layanan-item">
        <h3>Scan</h3>
        <p class="layanan-deskripsi">
          Layanan scan dokumen dengan resolusi tinggi. Harga mulai dari Rp 1000/dokumen.
        </p>
        <button class="pesan-btn" data-layanan="Scan">Pesan Sekarang</button>
      </div>
      <div class="layanan-item">
        <h3>Jilid</h3>
        <p class="layanan-deskripsi">
          Layanan jilid dokumen dengan berbagai pilihan model jilid. Harga mulai dari Rp 5000/dokumen.
        </p>
        <button class="pesan-btn" data-layanan="Jilid">Pesan Sekarang</button>
      </div>
    </div>
  </section>

 <!-- Form Pemesanan Layanan -->
<div id="orderModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" id="closeModalBtn">&times;</span>
    <h3>Form Pemesanan <span id="namaLayanan" style="color:#007bff;"></span></h3>

    <form id="orderForm" enctype="multipart/form-data">
      <!-- Informasi Pemesan -->
      <div class="form-group">
        <label for="namaPemesanLayanan">Nama Pemesan</label>
        <input
          type="text"
          id="namaPemesanLayanan"
          name="namaPemesanLayanan"
          required
          placeholder="Masukkan nama pemesan"
        >
      </div>

      <div class="form-group">
        <label for="kontakPemesanLayanan">Kontak Pemesan</label>
        <input
          type="text"
          id="kontakPemesanLayanan"
          name="kontakPemesanLayanan"
          required
          placeholder="Masukkan kontak pemesan"
        >
      </div>

      <!-- Hidden Input untuk Layanan -->
      <input type="hidden" id="layananInput" name="layanan">

      <div class="form-group">
        <label for="jumlahLembar">Jumlah Lembar</label>
        <input
          type="number"
          id="jumlahLembar"
          name="jumlahLembar"
          min="1"
          required
          placeholder="Masukkan jumlah lembar"
        >
      </div>

      <div class="form-group">
        <label for="pilihanWarna">Pilihan Warna</label>
        <select id="pilihanWarna" name="pilihanWarna">
          <option value="Hitam Putih">Hitam Putih</option>
          <option value="Warna">Warna</option>
        </select>
      </div>

      <div class="form-group">
        <label for="harga">Harga</label>
        <input
          type="text"
          id="harga"
          name="harga"
          value="Harga akan dihitung"
          readonly
        >
      </div>

      <div class="form-group">
  <label for="totalHarga">Total Harga</label>
  <input
    type="text"
    id="totalHarga"
    name="totalHarga"
    value="0"
    readonly
  >
</div>

      <div class="form-group">
        <label for="fileUpload">Upload File</label>
        <input
          type="file"
          id="fileUpload"
          name="fileUpload"
          accept=".jpg, .jpeg, .png, .pdf"
          multiple
        >
      </div>

      <div class="form-group">
        <button type="submit">Kirim Pesanan</button>
      </div>
    </form>
  </div>
</div>


    <!--Contact Section start-->
    <section id="contact" class="contact-fullpage">
  <div class="contact-container">
    <div class="contact-header">
      <h2><span>Kontak</span> Kami</h2>
      <p>Hubungi kami untuk pertanyaan, pemesanan, atau informasi lainnya. Kami siap membantu Anda!</p>
    </div>

    <div class="contact-wrapper">
      <!-- Kolom Kiri: Info Kontak & WhatsApp -->
      <div class="contact-left">
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <h4>Alamat</h4>
            <p>Jl. Diponegoro, Tebing Tinggi, Sumatera Utara</p>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div>
            <h4>Email</h4>
            <p>zakisitumeang31278@gmail.com</p>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-phone-alt"></i>
          <div>
            <h4>Telepon</h4>
            <p>+62 838-2994-8382</p>
          </div>
        </div>
        <a href="https://wa.me/6283829948382" class="whatsapp-btn" target="_blank">
          <i class="fab fa-whatsapp"></i> Chat via WhatsApp
        </a>
      </div>

      <!-- Kolom Tengah: Form Kontak -->
      <div class="contact-center">
        <form action="kirim_pesan.php" method="POST" class="contact-form">
          <input type="text" name="nama" placeholder="Nama Anda" required>
          <input type="email" name="email" placeholder="Email Anda" required>
          <textarea name="pesan" placeholder="Pesan Anda" rows="6" required></textarea>
          <button type="submit">Kirim Pesan</button>
        </form>
      </div>

      <!-- Kolom Kanan: Google Maps -->
      <div class="contact-right">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1846.3385408484987!2d99.16051565882903!3d3.322404299184333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30316186ec8d0c7f%3A0x82fcc66d3912f1d0!2sBERKAH%20FOTOCOPY!5e1!3m2!1sid!2sid!4v1748160751392!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>

    <!-- Footer -->
    <footer class="footer">
  <div class="footer-container">
    <div class="footer-socials">
      <a href="#" aria-label="Instagram" target="_blank" rel="noopener">
        <i data-feather="instagram"></i>
      </a>
      <a href="#" aria-label="Twitter" target="_blank" rel="noopener">
        <i data-feather="twitter"></i>
      </a>
      <a href="#" aria-label="Facebook" target="_blank" rel="noopener">
        <i data-feather="facebook"></i>
      </a>
    </div>

    <nav class="footer-links" aria-label="Footer Navigation">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#produk">Produk Kami</a>
      <a href="#layanan">Layanan Kami</a>
      <a href="#contact">Kontak</a>
    </nav>

    <div class="footer-copy">
      <p>&copy; <?= date('Y'); ?> Berkah Fotocopy. All rights reserved.</p>
    </div>
  </div>
</footer>

    <script>
      feather.replace();
    </script>
    <!-- Memuat file JavaScript eksternal -->
    <script src="js/cart.js"></script>

    <script src="js/layanan.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
  const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

  // Handle tombol 'add-to-cart' (jika ada di halaman)
  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function () {
      if (!isLoggedIn) {
        alert('Silakan login untuk melakukan pemesanan.');
        window.location.href = 'login.php';
        return;
      }

      // TODO: lanjutkan proses tambah ke keranjang (addToCart)
    });
  });

  // Handle tombol 'pesan-btn' untuk layanan
  document.querySelectorAll('.pesan-btn').forEach(button => {
    button.addEventListener('click', function () {
      if (!isLoggedIn) {
        alert('Silakan login untuk melakukan pemesanan layanan.');
        window.location.href = 'login.php';
        return;
      }

    });
  });

  // Handle tombol checkout (jika ada)
  const checkoutBtn = document.getElementById('checkout');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function () {
      if (!isLoggedIn) {
        alert('Silakan login terlebih dahulu untuk checkout.');
        window.location.href = 'login.php';
        return;
      }

      // TODO: lanjutkan proses checkout
    });
  }
});
</script>
  </body>
</html>
