<!-- Sidebar -->
<aside class="sidebar">
  <div class="logo">
    <img src="assets/logo.png" alt="Logo" />
    <h2>Perpustakaan<br>Nomporejo</h2>
  </div>
  <nav>
    <ul>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'daftarBuku.php' ? 'active' : '' ?>">
        <a href="daftarBuku.php"><span>📚</span> Daftar Buku</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'tambahBuku.php' ? 'active' : '' ?>">
        <a href="tambahBuku.php"><span>➕</span> Tambah Buku</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'pinjamBuku.php' ? 'active' : '' ?>">
        <a href="pinjamBuku.php"><span>📖</span> Pinjam Buku</a>
      </li>
      <li class="<?= basename($_SERVER['PHP_SELF']) == 'pengaturan.php' ? 'active' : '' ?>">
        <a href="pengaturan.php"><span>⚙️</span> Pengaturan</a>
      </li>
       <li>
            <a href="logout.php"><span>🚪</span> Logout</a>
        </li>
    </ul>
  </nav>
</aside>
