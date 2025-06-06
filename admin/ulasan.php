<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require '../koneksi.php';



// Ambil data ulasan buku
// $query = "
//     SELECT ub.UlasanID, u.namalengkap AS UserNama, b.Judul AS BukuJudul, ub.Ulasan, ub.Rating 
//     FROM ulasanbuku ub 
//     JOIN user u ON ub.UserID = u.UserID 
//     JOIN buku b ON ub.BukuID = b.BukuID
// ";
$query = mysqli_query($koneksi, "
    SELECT 
        ulasanbuku.UlasanID,
        user.namalengkap AS NamaUser,
        buku.Judul AS JudulBuku,
        ulasanbuku.Ulasan,
        ulasanbuku.Rating
    FROM ulasanbuku
    JOIN user ON ulasanbuku.UserID = user.UserID
    JOIN buku ON ulasanbuku.BukuID = buku.BukuID
") or die("Query gagal: " . mysqli_error($koneksi));

// $result = mysqli_query($koneksi, $query) or die("Query gagal: " . mysqli_error($koneksi));

// Layout
include '../layout/sidebar-navbar-footbar.php';
include '../layout/alert.php';
?>

<style>
  @media (min-width: 992px) {
    body { margin-left: 240px; }
  }
</style>

<div class="mx-5 mt-4">
  <h1 class="mb-3">Ulasan Buku</h1>
  <a href="ulasan-add.php" class="btn btn-success mb-4">+ Tambah</a>
  <br>

  <div class="card shadow-sm mb-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0">
          <thead>
            <tr>
              <th style="width:5%">No</th>
              <th>Nama User</th>
              <th>Judul Buku</th>
              <th>Ulasan</th>
              <th>Rating</th>
              <th style="width:20%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($query) === 0): ?>
              <tr><td colspan="6" class="text-center">Belum ada ulasan.</td></tr>
            <?php else:
              $no = 1;
              while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['NamaUser']) ?></td>
                  <td><?= htmlspecialchars($row['JudulBuku']) ?></td>
                  <td><?= htmlspecialchars($row['Ulasan']) ?></td>
                  <td><?= htmlspecialchars($row['Rating']) ?></td>
                  <td>
                    <a href="ulasan-edit.php?id=<?= $row['UlasanID'] ?>" class="btn btn-info btn-sm me-1">Ubah</a>
                    <a href="crud-delete-ulasan.php?id=<?= $row['UlasanID'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</a>
                  </td>
                </tr>
            <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
