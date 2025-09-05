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
$id = $_GET['id'];

// Ambil data buku berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = $id");
$buku = mysqli_fetch_assoc($query);

if (!$buku) {
    die("Buku tidak ditemukan!");
}

// Jika tombol delete ditekan
if (isset($_POST['delete'])) {
    mysqli_query($conn, "DELETE FROM buku WHERE id_buku = $id");
    header("Location: daftarBuku.php?msg=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Buku - Perpustakaan Nomporejo</title>
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
      <!-- Header dengan tombol kembali -->
      <div class="top-bar">
        <button class="btn-back" onclick="window.location.href='daftarBuku.php'">⬅ Kembali</button>
      </div>

      <div class="detail-container">
        <div class="book-cover">
          <?php if ($buku['gambar']) { ?>
            <img src="assets/<?= $buku['gambar'] ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
          <?php } else { ?>
            <img src="assets/default.jpg" alt="No Cover">
          <?php } ?>
        </div>
        <div class="book-info">
          <p><strong>Judul Buku</strong> : <?= htmlspecialchars($buku['judul']) ?></p>
          <p><strong>No. Inventaris</strong> : <?= htmlspecialchars($buku['no_inventaris']) ?></p>
          <p><strong>Nama Penulis</strong> : <?= htmlspecialchars($buku['penulis']) ?></p>
          <p><strong>Penerbit</strong> : <?= htmlspecialchars($buku['penerbit']) ?></p>
          <p><strong>Tahun Terbit</strong> : <?= htmlspecialchars($buku['tahun_terbit']) ?></p>
          <p><strong>Status Buku</strong> : 
            <?php if ($buku['status_buku'] == "Tersedia") { ?>
              <span class="status available">Tersedia</span>
            <?php } else { ?>
              <span class="status unavailable">Tidak Tersedia</span>
            <?php } ?>
          </p>
        </div>
      </div>

      <!-- Tombol Aksi -->
      <div class="edit-btn-container">
        <a href="editBuku.php?id=<?= $buku['id_buku'] ?>" class="btn-edit">Edit Buku</a>
        &nbsp;&nbsp;&nbsp;
        <form method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
          <button type="submit" name="delete" class="btn-delete">🗑 Hapus Buku</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
