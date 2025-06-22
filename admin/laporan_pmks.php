<?php
session_start();
if (!isset($_SESSION["Admin"])) {
    header("location:../index.php");
    exit;
}

$page = "laporan";
$page1 = "all";
include('templetes/sidebar.php');
include('templetes/topbar.php');
require_once "../functions.php";

$jml_DataHalaman = 5;
$jml_responden = count(query("SELECT id_pmks FROM pmks WHERE is_delete = 0"));
$jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

$pageAktif = (isset($_GET["page"])) ? $_GET["page"] : 1;
$awaldata = ($jml_DataHalaman * $pageAktif) - $jml_DataHalaman;

$pmks = query("SELECT a.*, b.nm_kat AS jenis_akses, c.nm_program AS sub_menu, d.nm_kec AS kecamatan 
               FROM pmks a
               LEFT JOIN kat_pmks b ON a.id_kat_pmks = b.id_kat_pmks
               LEFT JOIN program_bantuan c ON a.id_program = c.id_program
               LEFT JOIN kecamatan d ON a.id_kec = d.id_kec
               WHERE a.is_delete = 0 
               ORDER BY d.nm_kec ASC 
               LIMIT $awaldata, $jml_DataHalaman");
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-judul">Data PMKS Online</h1>

    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <a href="../cetak_pmks.php" target="_blank" class="btn btn-info"> Cetak</a>
        </div>
        <div class="card-header py-3"></div>
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
                            <td><?= date('d-m-Y', strtotime($row['time_input'])); ?></td>
                            <td class="text-center"><?= $row['status']; ?></td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination float-right">
                        <?php if ($pageAktif > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif - 1; ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $jml_Halaman; $i++): ?>
                        <li class="page-item <?= ($i == $pageAktif) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($pageAktif < $jml_Halaman): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif + 1; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<?php include('templetes/footer.php'); ?>