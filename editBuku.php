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

// Ambil id dari URL
if (!isset($_GET['id'])) {
    header("Location: daftarBuku.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = $id");
$buku = mysqli_fetch_assoc($query);

if (!$buku) {
    die("Buku tidak ditemukan!");
}

// Proses update buku
if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $no_inventaris = $_POST['no_inventaris'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $status_buku = $_POST['status_buku'];

    // cek apakah ada upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $namaFile = $_FILES['gambar']['name'];
        $tmpFile = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmpFile, "assets/" . $namaFile);

        $sql = "UPDATE buku SET 
                judul='$judul', 
                no_inventaris='$no_inventaris', 
                penulis='$penulis', 
                penerbit='$penerbit', 
                tahun_terbit='$tahun_terbit', 
                status_buku='$status_buku', 
                gambar='$namaFile'
                WHERE id_buku='$id'";
    } else {
        $sql = "UPDATE buku SET 
                judul='$judul', 
                no_inventaris='$no_inventaris', 
                penulis='$penulis', 
                penerbit='$penerbit', 
                tahun_terbit='$tahun_terbit', 
                status_buku='$status_buku'
                WHERE id_buku='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: detailBuku.php?id=$id");
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
  <title>Edit Buku - Perpustakaan Nomporejo</title>
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
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
      <div class="top-bar">
        <button class="btn-back" onclick="window.history.back()">⬅ Kembali</button>
      </div>

      <div class="form-container">
        <h2>Edit Buku</h2>
        <form method="POST" enctype="multipart/form-data">
          <label>Judul Buku</label>
          <input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>

          <label>No. Inventaris</label>
          <input type="text" name="no_inventaris" value="<?= htmlspecialchars($buku['no_inventaris']) ?>" required>

          <label>Penulis</label>
          <input type="text" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" required>

          <label>Penerbit</label>
          <input type="text" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>

          <label>Tahun Terbit</label>
          <input type="text" name="tahun_terbit" value="<?= htmlspecialchars($buku['tahun_terbit']) ?>" required>

       <label>Status Buku</label><br>
        <input type="radio" name="status_buku" value="Tersedia" 
            <?= $buku['status_buku'] == "Tersedia" ? "checked" : "" ?>> Tersedia
        <br>
        <br>
        <input type="radio" name="status_buku" value="Tidak Tersedia" 
            <?= $buku['status_buku'] == "Tidak Tersedia" ? "checked" : "" ?>> Tidak Tersedia


          <label>Gambar (opsional)</label>
          <input type="file" name="gambar">
          <?php if ($buku['gambar']) { ?>
            <p><img src="assets/<?= $buku['gambar'] ?>" width="120" ></p>
          <?php } ?>

          <div class="form-actions">
            <a href="detailBuku.php?id=<?= $id ?>" class="btn-cancel"> Batal</a>
            <button type="submit" name="update" class="btn-save"> Simpan</button>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
