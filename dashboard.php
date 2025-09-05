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

// Ambil data buku dari database
$sql = "SELECT * FROM buku ORDER BY id_buku DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Perpustakaan Nomporejo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css" />
  <script>
  // Deteksi jika user kembali via tombol back/forward
    window.addEventListener("pageshow", function(event) {
      if (event.persisted || (window.performance && performance.getEntriesByType("navigation")[0].type === "back_forward")) {
      // Paksa reload agar session dicek ulang di server
      window.location.reload();
    }
  });
</script>


</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="assets/logo.png" alt="Logo" />
        <h2>Perpustakaan<br>Nomporejo</h2>
      </div>
      <nav>
        <ul>
          <li class="active">
            <a href="dashboard.php"><span>📚</span> Daftar Buku</a>
          </li>
          <li>
            <a href="tambahBuku.php"><span>➕</span> Tambah Buku</a>
          </li>
          <li>
            <a href="pinjamBuku.php"><span>📖</span> Pinjam Buku</a>
          </li>
          <li>
            <a href="pengaturan.php"><span>⚙️</span> Pengaturan</a>
          </li>
          <li>
            <a href="logout.php"><span>🚪</span> Logout</a>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Search -->
      <div class="search-bar">
        <form method="GET" action="daftarBuku.php">
          <input type="text" name="search" placeholder="Cari Buku..." />
        </form>
      </div>

      <!-- Daftar Buku -->
      <h1>Daftar Buku</h1>
      <div class="book-list">
        <?php if (mysqli_num_rows($result) > 0) { ?>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <a href="detailBuku.php?id=<?= $row['id_buku'] ?>" class="book-card">
              <?php if ($row['gambar']) { ?>
                <img src="assets/<?= $row['gambar'] ?>" alt="<?= htmlspecialchars($row['judul']) ?>" />
              <?php } else { ?>
                <img src="assets/default.jpg" alt="No Cover" />
              <?php } ?>
              <h3><?= htmlspecialchars($row['judul']) ?></h3>
              <p><?= htmlspecialchars($row['penulis']) ?></p>
            </a>
          <?php } ?>
        <?php } else { ?>
          <p><em>Tidak ada buku ditemukan.</em></p>
        <?php } ?>
      </div>
    </main>
  </div>
</body>
</html>
