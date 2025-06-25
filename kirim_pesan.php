<?php
// koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "db_toko");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

// ambil data dari form
$nama = $koneksi->real_escape_string($_POST['nama']);
$email = $koneksi->real_escape_string($_POST['email']);
$pesan = $koneksi->real_escape_string($_POST['pesan']);

// validasi sederhana (pastikan field tidak kosong)
if(empty($nama) || empty($email) || empty($pesan)){
    echo "<script>alert('Harap isi semua field!'); window.history.back();</script>";
    exit;
}

// simpan ke database
$sql = "INSERT INTO pesan_kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";

if ($koneksi->query($sql) === TRUE) {
  echo "<script>
          alert('Terima kasih, pesan Anda sudah terkirim dan segera ditindaklanjuti.');
          window.location.href = 'index.php'; // ganti sesuai halaman tujuan
        </script>";
} else {
  echo "<script>
          alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
          window.history.back();
        </script>";
}

$koneksi->close();
?>