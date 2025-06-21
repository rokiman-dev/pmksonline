<?php 
session_start();
if (!isset($_SESSION["admin"])){
    header("location:../index.php");
    exit;
}

$page = "pmks";
date_default_timezone_set('Asia/Jakarta');
require_once '../config.php';
require_once "../functions.php";
include('templetes/sidebar.php');
include('templetes/topbar.php');


if (isset($_POST['tambah'])) {
  $nama        = $_POST['nm_pmks'];
  $alamat      = $_POST['alamat'];
  $id_kec      = $_POST['id_kec'];
  $no_telepon  = $_POST['no_telepon'];
  $id_kat      = $_POST['id_kat_pmks'];
  $id_program  = $_POST['id_program'];
  $creator     = $_POST['id_pegawai'];
  $time_input  = $_POST['time_input'];
  $is_delete   = $_POST['is_delete'];
  $status      = $_POST['status'];

  $query = "INSERT INTO pmks 
    (nm_pmks, alamat, id_kec, no_telepon, id_kat_pmks, id_program, creator, time_input, is_delete, status)
    VALUES 
    ('$nama', '$alamat', '$id_kec', '$no_telepon', '$id_kat', '$id_program', '$creator', '$time_input', '$is_delete', '$status')";

  if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data berhasil ditambahkan'); window.location.href='pmks_trial.php';</script>";
    exit;
  } else {
    echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');</script>";
  }
}

$id_pegawai = $_SESSION['id_pegawai'];
$pegawai = query("SELECT id_pegawai FROM pegawai WHERE id_pegawai='$id_pegawai'")[0];

$jml_DataHalaman = 5;
$jml_responden = count(query("SELECT * FROM pmks WHERE is_delete=1"));
$jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

$pageAktif = (isset($_GET["page"])) ? $_GET["page"] : 1;
$awaldata = ($jml_DataHalaman * $pageAktif) - $jml_DataHalaman;

$pmks = query("SELECT 
    a.id_pmks,
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
  WHERE a.is_delete = 1
  ORDER BY a.id_pmks DESC
  LIMIT $awaldata, $jml_DataHalaman");

if (isset($_POST["hapus"])) {
    if (hapusDataPmks($_POST) > 0) {
        echo "<script>alert('Data berhasil dihapus'); location.href='pmks.php';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus'); location.href='pmks.php';</script>";
    }
}

if (isset($_POST["approve"])) {
    $id = $_POST['id_pmks'];
    $update = mysqli_query($koneksi, "UPDATE pmks SET status = 'Selesai' WHERE id_pmks = $id");
    if ($update) {
        echo "<script>alert('PMKS berhasil diapprove'); location.href='pmks.php';</script>";
    } else {
        echo "<script>alert('Gagal approve PMKS'); location.href='pmks.php';</script>";
    }
}

if (isset($_POST["edit"])){ 
    if(editDataPmks($_POST) > 0){
        echo "<script>alert('Data berhasil diubah'); location.href='pmks.php';</script>";
    } else {
        echo "<script>alert('Data gagal diubah'); location.href='pmks.php';</script>";
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-judul">Data PMKS</h1>

    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                Tambah PMKS
            </button>
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
                            <td class="text-center"><?= $row['status']; ?></td>
                            <td class="text-center">
                                <!-- Tombol Approve -->
                                <?php if ($row['status'] === 'Menunggu') : ?>
                                <a href="approve_pmks.php?id=<?= $row['id_pmks']; ?>" class="btn btn-success btn-sm"
                                    onclick="return confirm('Setujui PMKS ini?');">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <?php endif; ?>

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
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <?php if ($pageAktif > 1) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif - 1; ?>">Previous</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $jml_Halaman; $i++) : ?>
                        <li class="page-item <?= ($i == $pageAktif) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <?php if ($pageAktif < $jml_Halaman) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif + 1; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

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
              $kecamatan = query("SELECT * FROM kecamatan WHERE is_delete = 1");
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
              $kat = query("SELECT * FROM kat_pmks WHERE is_delete = 1");
              foreach ($kat as $k) {
                  echo "<option value='{$k['id_kat_pmks']}'>{$k['nm_kat']}</option>";
              }
              ?>
                        </select>

                        <label>Sub Menu</label>
                        <select name="id_program" class="form-control" required>
                            <option value="">-- Pilih Program Bantuan --</option>
                            <?php
              $prog = query("SELECT * FROM program_bantuan WHERE is_delete = 1");
              foreach ($prog as $p) {
                  echo "<option value='{$p['id_program']}'>{$p['nm_program']}</option>";
              }
              ?>
                        </select>

                        <!-- Hidden input -->
                        <input type="hidden" name="id_pegawai" value="<?= $_SESSION['id_pegawai'] ?>">
                        <input type="hidden" name="time_input" value="<?= date('Y-m-d H:i:s') ?>">
                        <input type="hidden" name="is_delete" value="1">
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit<?= $row['id_pmks']; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit PMKS</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pmks" value="<?= $row['id_pmks']; ?>">
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik_pmks" class="form-control" value="<?= $row['nik_pmks']; ?>">
                        <label>Nama</label>
                        <input type="text" name="nm_pmks" class="form-control" value="<?= $row['nm_pmks']; ?>">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= $row['no_telepon']; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-warning">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('templetes/footer.php'); ?>