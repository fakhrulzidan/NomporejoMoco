<?php
session_start();

// Anti-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';

// Jika form disubmit
if (isset($_POST['simpan'])) {
    $noInventaris = $_POST['noInventaris'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];

    // Upload Gambar
    $namaFile = $_FILES['gambar']['name'];
    $tmpFile = $_FILES['gambar']['tmp_name'];
    $folder = "assets/";

    if ($namaFile != "") {
        $path = $folder . basename($namaFile);
        move_uploaded_file($tmpFile, $path);
    } else {
        $namaFile = NULL;
    }

    // Insert ke database
    $sql = "INSERT INTO buku (judul, no_inventaris, penulis, penerbit, tahun_terbit, gambar, status_buku) 
            VALUES ('$judul', '$noInventaris', '$penulis', '$penerbit', '$tahun', '$namaFile', 'Tersedia')";

    if (mysqli_query($conn, $sql)) {
        header("Location: daftarBuku.php?msg=added");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Buku - Perpustakaan Nomporejo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
  <script>
    // Cegah kembali ke history setelah logout
    if (performance.navigation.type === 2) {
        window.location.reload(true);
    }
  </script>
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <div class="form-container">
        <h1>Form Tambah Buku</h1>
        <form method="POST" enctype="multipart/form-data">
          <label for="noInventaris">No Inventaris</label>
          <input type="text" name="noInventaris" id="noInventaris" placeholder="Masukan No Inventaris" required>

          <label for="judul">Judul Buku</label>
          <input type="text" name="judul" id="judul" placeholder="Masukan Judul Buku" required>

          <label for="penulis">Nama Penulis</label>
          <input type="text" name="penulis" id="penulis" placeholder="Masukan Nama Penulis">

          <div class="form-row">
            <div class="form-group">
              <label for="penerbit">Penerbit</label>
              <input type="text" name="penerbit" id="penerbit" placeholder="Masukan Asal Penerbit">
            </div>
            <div class="form-group">
              <label for="tahun">Tahun Penerbit</label>
              <input type="text" name="tahun" id="tahun" placeholder="Masukan Tahun Terbit">
            </div>
          </div>

          <label for="gambar">Gambar</label>
          <div class="upload-box">
            <input type="file" name="gambar" id="gambar">
            <p>Masukan Gambar</p>
          </div>

          <div class="form-actions">
            <a href="daftarBuku.php" class="btn-cancel">Batal</a>
            <button type="submit" name="simpan" class="btn-save">Simpan</button>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
