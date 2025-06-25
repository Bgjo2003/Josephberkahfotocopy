<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil semua kategori
$kategori_result = $koneksi->query("SELECT * FROM kategori_roti");
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Ambil parameter pencarian, kolom order, arah sort, dan halaman
$cari = isset($_GET['cari']) ? $koneksi->real_escape_string($_GET['cari']) : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'id';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc';
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Validasi kolom order
$allowed_order = ['id', 'nama_roti', 'harga', 'harga_diskon', 'deskripsi', 'updated_at'];
if (!in_array($order, $allowed_order)) {
    $order = 'id';
}

// Validasi arah sort
$sort = strtolower($sort) === 'desc' ? 'desc' : 'asc';

// Hitung total data
if (!empty($cari)) {
    $sql_count = "SELECT COUNT(*) as total 
                  FROM produk_roti 
                  WHERE nama_roti LIKE '%$cari%' 
                     OR id LIKE '%$cari%' 
                     OR deskripsi LIKE '%$cari%' 
                     OR harga LIKE '%$cari%' 
                     OR harga_diskon LIKE '%$cari%'";
} else {
    $sql_count = "SELECT COUNT(*) as total FROM produk_roti";
}

$result_count = $koneksi->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_data = $row_count['total'];
$total_page = ceil($total_data / $limit);

// Query data produk dengan kategori
$where_clauses = [];
if (!empty($cari)) {
    $where_clauses[] = "(produk_roti.nama_roti LIKE '%$cari%' 
                         OR produk_roti.id LIKE '%$cari%' 
                         OR produk_roti.deskripsi LIKE '%$cari%' 
                         OR produk_roti.harga LIKE '%$cari%' 
                         OR produk_roti.harga_diskon LIKE '%$cari%')";
}

if (!empty($kategori_filter)) {
    $where_clauses[] = "produk_roti.kategori_id = '$kategori_filter'";
}

$where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

$sql = "SELECT produk_roti.*, kategori_roti.nama_kategori 
        FROM produk_roti 
        LEFT JOIN kategori_roti ON produk_roti.kategori_id = kategori_roti.kategori_id
        $where_sql
        ORDER BY produk_roti.$order $sort 
        LIMIT $limit OFFSET $offset";

$data = $koneksi->query($sql);

// Fungsi untuk membuat link sort
function sort_link($column, $label, $current_sort, $current_order, $cari, $page, $kategori = '')
{
    $sort_icon = '';
    $new_sort = 'asc';
    if ($current_order === $column) {
        if ($current_sort === 'asc') {
            $sort_icon = ' ▲';
            $new_sort = 'desc';
        } else {
            $sort_icon = ' ▼';
            $new_sort = 'asc';
        }
    }
    $url = "data_produk.php?order=$column&sort=$new_sort&page=$page";
    if (!empty($cari)) {
        $url .= "&cari=" . urlencode($cari);
    }
    if (!empty($kategori)) {
        $url .= "&kategori=" . urlencode($kategori);
    }
    return "<a href=\"$url\" style=\"color: inherit; text-decoration: none;\">$label$sort_icon</a>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Data Produk Roti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        img.thumb {
            width: 80px;
            height: auto;
            object-fit: cover;
        }

        th a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="p-3">
                <h4 class="text-white"><i class="bi bi-shop"></i> Admin Pabrik Roti Gembira</h4>
                <hr class="text-secondary" />
                <a href="index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="data_produk.php" class="active"><i class="bi bi-box-seam"></i> Data Produk</a>
                <a href="data_kategori.php">
                    <i class="bi bi-tags"></i> Data Kategori Produk
                </a>
                <a href="data_pesanan.php"><i class="bi bi-receipt"></i> Data Pesanan</a>
                <a href="visitor_report.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-bar-chart-line"></i> Laporan Kunjungan Pengguna Website
                </a>
                <a href="data_kontak.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-chat-dots"></i> Pesan Pengunjung
                </a>
                <a href="../index.php"><i class="bi bi-house"></i> Halaman Pengguna</a>
                <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Data Produk Roti</h2>
            </div>

            <!-- Tombol + Pencarian dalam satu baris -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <a href="tambah_produk.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </a>

                <!-- Form Pencarian -->
                <form method="GET" class="d-flex" style="max-width: 400px;">
                    <div class="input-group border border-primary rounded">
                        <input type="text" name="cari" class="form-control border-0" placeholder="Cari sesuatu..." value="<?= htmlspecialchars($cari) ?>" />
                        <button type="submit" class="btn btn-outline-primary border-0">Cari</button>
                    </div>
                </form>
            </div>

            <!-- Pesan sukses / error -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <table class="table table-striped table-bordered mt-2 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><?= sort_link('id', 'ID', $sort, $order, $cari, $page) ?></th>
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th><?= sort_link('nama_roti', 'Nama Roti', $sort, $order, $cari, $page) ?></th>
                        <th><?= sort_link('harga', 'Harga', $sort, $order, $cari, $page) ?></th>
                        <th><?= sort_link('harga_diskon', 'Diskon', $sort, $order, $cari, $page) ?></th>
                        <th><?= sort_link('deskripsi', 'Deskripsi', $sort, $order, $cari, $page) ?></th>
                        <th><?= sort_link('updated_at', 'Terakhir Update', $sort, $order, $cari, $page) ?></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filter Kategori -->
                    <form method="GET" class="mb-3" style="max-width: 300px;">
                        <div class="input-group">
                            <select name="kategori" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Semua Kategori --</option>
                                <?php while ($row_kat = $kategori_result->fetch_assoc()): ?>
                                    <option value="<?= $row_kat['kategori_id'] ?>" <?= ($kategori_filter == $row_kat['kategori_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row_kat['nama_kategori']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <?php if (!empty($kategori_filter)): ?>
                                <a href="data_produk.php" class="btn btn-outline-secondary">Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php if ($data->num_rows > 0): ?>
                        <?php while ($row = $data->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <img src="/rotigembirabakery2025/<?= htmlspecialchars($row['img']) ?>" class="thumb" alt="<?= htmlspecialchars($row['nama_roti']) ?>" /></td>
                                <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nama_roti']) ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['harga_diskon'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['updated_at'])) ?></td>
                                <td>
                                    <a href="detail_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>&cari=<?= urlencode($cari) ?>">Previous</a>
                    </li>

                    <!-- Numbered page links -->
                    <?php for ($i = 1; $i <= $total_page; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&order=<?= $order ?>&sort=<?= $sort ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&order=<?= $order ?>&sort=<?= $sort ?>&cari=<?= urlencode($cari) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>