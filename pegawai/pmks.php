<?php 
session_start();
if (!isset($_SESSION["Pegawai"])){
    header("location:../index.php");
    exit;
}

$page = "pmks";
date_default_timezone_set('Asia/Jakarta');
require_once '../config.php';

  include('templetes/sidebar.php');
  include('templetes/topbar.php');
  require_once "../functions.php";

if (isset($_POST['tambah'])) {
    if (tambahDataPmks($_POST) > 0) {
        echo "<script>alert('Data berhasil ditambahkan'); window.location.href='pmks.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');</script>";
    }
}


$id_pegawai = $_SESSION['id_pegawai'];
$pegawai = query("SELECT id_pegawai FROM pegawai WHERE id_pegawai='$id_pegawai'")[0];

$jml_DataHalaman = 5;
$jml_responden = count(query("SELECT * FROM pmks WHERE is_delete=0"));
$jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

$pageAktif = (isset($_GET["page"])) ? $_GET["page"] : 1;
$awaldata = ($jml_DataHalaman * $pageAktif) - $jml_DataHalaman;

$where = "a.is_delete = 0";

if (isset($_GET['status']) && is_array($_GET['status']) && count($_GET['status']) > 0) {
    $filtered_status = array_map(function ($status) use ($koneksi) {
        return "'" . mysqli_real_escape_string($koneksi, $status) . "'";
    }, $_GET['status']);
    $where .= " AND a.status IN (" . implode(",", $filtered_status) . ")";
}

$queryTotal = "SELECT COUNT(*) as total FROM pmks a WHERE $where";
$resultTotal = mysqli_query($koneksi, $queryTotal);
$totalRow = mysqli_fetch_assoc($resultTotal)['total'];
$jml_responden = $totalRow;
$jml_Halaman = ceil($jml_responden / $jml_DataHalaman);
$awaldata = ($jml_DataHalaman * $pageAktif) - $jml_DataHalaman;

$pmks = query("SELECT 
    a.id_pmks,
    a.id_kec,
    a.id_kat_pmks,
    a.id_program,
    a.nm_pmks,
    a.alamat,
    d.nm_kec AS kecamatan,
    b.nm_kat AS jenis_akses,
    c.nm_program AS sub_menu,
    a.no_telepon,
    a.time_input AS tanggal_akses,
    a.status,
    e.nm_pegawai AS petugas_layanan
FROM pmks a
LEFT JOIN kat_pmks b ON a.id_kat_pmks = b.id_kat_pmks
LEFT JOIN program_bantuan c ON a.id_program = c.id_program
LEFT JOIN kecamatan d ON a.id_kec = d.id_kec
LEFT JOIN pegawai e ON a.creator = e.id_pegawai
WHERE $where
ORDER BY a.id_pmks DESC
LIMIT $awaldata, $jml_DataHalaman");

if (isset($_POST["hapus"])) {
    if (hapusDataPmks($_POST) > 0) {
        echo "<script>alert('Data berhasil dihapus'); location.href='pmks.php';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus'); location.href='pmks.php';</script>";
    }
}

if (isset($_POST['edit'])) {
    if (editDataPmks($_POST) > 0) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='pmks.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}

?>

<!-- Begin Page Content -->
<div class="content-wrapper">
    <div class="container-fluid content">
        <h1 class="h3 mb-4 text-judul">Data PMKS</h1>

        <div class="card shadow mb-2">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                    Tambah PMKS
                </button>

                <!-- Filter Status -->
                <form method="GET" class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="status[]" value="Menunggu"
                            <?= (isset($_GET['status']) && in_array("Menunggu", $_GET['status'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label">Menunggu</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="status[]" value="Diproses"
                            <?= (isset($_GET['status']) && in_array("Diproses", $_GET['status'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label">Diproses</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="status[]" value="Selesai"
                            <?= (isset($_GET['status']) && in_array("Selesai", $_GET['status'])) ? 'checked' : ''; ?>>
                        <label class="form-check-label">Selesai</label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary ml-2">Terapkan</button>
                </form>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Alamat</th>
                                <th class="text-center">Kecamatan</th>
                                <th class="text-center">No Telepon</th>
                                <th class="text-center">Jenis Akses</th>
                                <th class="text-center">Sub Menu</th>
                                <th class="text-center">Tanggal Akses</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Petugas Layanan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = $awaldata + 1; ?>
                            <?php foreach ($pmks as $row): ?>
                            <tr>
                                <td class="text-center"><?= $i++; ?></td>
                                <td><?= $row['nm_pmks']; ?></td>
                                <td><?= $row['alamat']; ?></td>
                                <td><?= $row['kecamatan']; ?></td>
                                <td><?= $row['no_telepon']; ?></td>
                                <td><?= $row['jenis_akses']; ?></td>
                                <td><?= $row['sub_menu']; ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal_akses'])); ?></td>
                                <!-- Kolom Status -->
                                <td class="text-center">
                                    <?php
        $status = $row['status'];
        $class = ($status === 'Selesai') ? 'badge-success' :
         (($status === 'Menunggu') ? 'badge-warning' :
         (($status === 'Diproses') ? 'badge-info' : 'badge-secondary'));
    ?>
                                    <span class="badge <?= $class ?> d-inline-block text-white"
                                        style="min-width: 80px;">
                                        <?= $status; ?>
                                    </span>
                                </td>
                                <td><?= $row['petugas_layanan']; ?></td>

                                <!-- Kolom Tombol Aksi -->
                                <td class="text-center">
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#modalEdit<?= $row['id_pmks']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_pmks" value="<?= $row['id_pmks']; ?>">
                                        <button type="submit" name="hapus" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus data PMKS ini?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_pmks']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">Edit PMKS</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id_pmks" value="<?= $row['id_pmks']; ?>">
                                                <div class="form-group">
                                                    <label>Nama</label>
                                                    <input type="text" name="nm_pmks" class="form-control"
                                                        value="<?= $row['nm_pmks']; ?>" required>

                                                    <label>Alamat</label>
                                                    <input type="text" name="alamat" class="form-control"
                                                        value="<?= $row['alamat']; ?>" required>

                                                    <label>Kecamatan</label>
                                                    <select name="id_kec" class="form-control" required>
                                                        <?php
              $kecamatan = query("SELECT * FROM kecamatan WHERE is_delete = 0");
              foreach ($kecamatan as $kec) {
                $selected = $row['id_kec'] == $kec['id_kec'] ? "selected" : "";
                echo "<option value='{$kec['id_kec']}' $selected>{$kec['nm_kec']}</option>";
              }
              ?>
                                                    </select>

                                                    <label>No Telepon</label>
                                                    <input type="text" name="no_telepon" class="form-control"
                                                        value="<?= $row['no_telepon']; ?>">

                                                    <label>Jenis Akses</label>
                                                    <select name="id_kat_pmks" class="form-control" required>
                                                        <?php
              $kategori = query("SELECT * FROM kat_pmks WHERE is_delete = 0");
              foreach ($kategori as $kat) {
                $selected = $row['id_kat_pmks'] == $kat['id_kat_pmks'] ? "selected" : "";
                echo "<option value='{$kat['id_kat_pmks']}' $selected>{$kat['nm_kat']}</option>";
              }
              ?>
                                                    </select>

                                                    <label>Sub Menu</label>
                                                    <select name="id_program" class="form-control" required>
                                                        <?php
              $programs = query("SELECT * FROM program_bantuan WHERE is_delete = 0");
              foreach ($programs as $prog) {
                $selected = $row['id_program'] == $prog['id_program'] ? "selected" : "";
                echo "<option value='{$prog['id_program']}' $selected>{$prog['nm_program']}</option>";
              }
              ?>
                                                    </select>

                                                    <label>Tanggal Akses</label>
                                                    <input type="hidden" name="time_input"
                                                        value="<?= date('Y-m-d H:i:s') ?>">
                                                    <input type="text" class="form-control"
                                                        value="<?= date('Y-m-d H:i:s') ?>" disabled>
                                                    <label>Status</label>
                                                    <input disabled name="status" value="Menunggu" class="form-control">
                                                    <?php
                        $id_pegawai = $_SESSION['id_pegawai'];
                        $pegawai = query("SELECT nm_pegawai FROM pegawai WHERE id_pegawai = $id_pegawai")[0];
                        ?>
                                                    <label>Petugas Layanan</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $row['petugas_layanan']; ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" name="edit"
                                                    class="btn btn-warning">Simpan</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php
// query string tambahan dari checkbox status
$statusQuery = '';
if (isset($_GET['status']) && is_array($_GET['status'])) {
    foreach ($_GET['status'] as $s) {
        $statusQuery .= '&status[]=' . urlencode($s);
    }
}
?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <?php if ($pageAktif > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pageAktif - 1 . $statusQuery ?>">Previous</a>
                            </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $jml_Halaman; $i++): ?>
                            <li class="page-item <?= ($i == $pageAktif) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i . $statusQuery ?>"><?= $i; ?></a>
                            </li>
                            <?php endfor; ?>

                            <?php if ($pageAktif < $jml_Halaman): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pageAktif + 1 . $statusQuery ?>">Next</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah PMKS</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nm_pmks" class="form-control" required>

                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>

                        <label>Kecamatan</label>
                        <select name="id_kec" class="form-control" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            <?php
              $kecamatan = query("SELECT * FROM kecamatan WHERE is_delete = 0");
              foreach ($kecamatan as $kec) {
                  echo "<option value='{$kec['id_kec']}'>{$kec['nm_kec']}</option>";
              }
              ?>
                        </select>

                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control">

                        <label>Jenis Akses</label>
                        <select name="id_kat_pmks" class="form-control" required>
                            <option value="">-- Pilih Jenis Akses --</option>
                            <?php
              $kat = query("SELECT * FROM kat_pmks WHERE is_delete = 0");
              foreach ($kat as $k) {
                  echo "<option value='{$k['id_kat_pmks']}'>{$k['nm_kat']}</option>";
              }
              ?>
                        </select>

                        <label>Sub Menu</label>
                        <select name="id_program" class="form-control" required>
                            <option value="">-- Pilih Program Bantuan --</option>
                            <?php
              $prog = query("SELECT * FROM program_bantuan WHERE is_delete = 0");
              foreach ($prog as $p) {
                  echo "<option value='{$p['id_program']}'>{$p['nm_program']}</option>";
              }
              ?>
                        </select>

                        <!-- Hidden input -->
                        <input type="hidden" name="id_pegawai" value="<?= $_SESSION['id_pegawai'] ?>">
                        <input type="hidden" name="time_input" value="<?= date('Y-m-d H:i:s') ?>">
                        <input type="hidden" name="is_delete" value="0">
                        <input type="hidden" name="status" value="Menunggu">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


<?php include('templetes/footer.php'); ?>