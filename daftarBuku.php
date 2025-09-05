<?php
session_start();

// Anti-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
include 'koneksi.php';

// Cek apakah ada pencarian
$keyword = "";
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $sql = "SELECT * FROM buku 
            WHERE judul LIKE '%$keyword%' 
            OR penulis LIKE '%$keyword%' 
            OR penerbit LIKE '%$keyword%'
            ORDER BY id_buku DESC";
} else {
    $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perpustakaan Nomporejo</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css" />
    <script>
    // Cegah kembali ke history setelah logout
    if (performance.navigation.type === 2) {
        window.location.reload(true);
    }
  </script>

</head>

<script>
  window.addEventListener("pageshow", function (event) {
    // Cek apakah user menekan refresh (reload)
    if (
      performance.getEntriesByType("navigation")[0].type === "reload" &&
      window.location.search.includes("search=")
    ) {
      window.location.href = "daftarBuku.php";
    }
  });
</script>


<body>
  <div class="container">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Search -->
      <div class="search-bar">
        <form method="GET" action="daftarBuku.php">
          <input type="text" name="search" placeholder="Cari Buku..." value="<?= htmlspecialchars($keyword) ?>">
          <!-- <button type="submit">🔍 Cari</button> -->
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
