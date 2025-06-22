<?php 
session_start();
if (!isset($_SESSION["Admin"])){
    header("location:../index.php");
    exit;
}

$page = "laporan";
$page1 = "wil";
date_default_timezone_set('Asia/Jakarta');
include('templetes/sidebar.php');
include('templetes/topbar.php');
require_once "../functions.php";

// Pagination Setup
$jml_DataHalaman = 5;
$jml_responden = count(query("SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec != 'Tidak Ada'"));
$jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

$pageAktif = (isset($_GET["page"])) ? $_GET["page"] : 1;
$awaldata = ($jml_DataHalaman * $pageAktif) - $jml_DataHalaman;

$kecamatan = query("SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec != 'Tidak Ada' ORDER BY nm_kec ASC LIMIT $awaldata, $jml_DataHalaman");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-judul">Data PMKS per Kecamatan</h1>

    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <a href="../cetak_wil.php" target="_blank" class="btn btn-info"> Cetak</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kecamatan</th>
                            <th class="text-center">Jumlah PMKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $awaldata + 1; ?>
                        <?php foreach ($kecamatan as $row): ?>
                        <?php
                                $id_kec = $row['id_kec'];
                                $jml_pmks = count(query("SELECT id_pmks FROM pmks WHERE is_delete=0 AND id_kec = $id_kec"));
                            ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td class="text-left"><?= $row['nm_kec']; ?></td>
                            <td class="text-center"><?= $jml_pmks; ?></td>
                        </tr>
                        <?php $i++; endforeach; ?>

                        <?php if ($pageAktif == $jml_Halaman): ?>
                        <tr>
                            <td colspan="2" class="text-center"><b>Jumlah</b></td>
                            <?php 
                                $total_pmks = count(query("SELECT id_pmks FROM pmks WHERE is_delete=0"));
                            ?>
                            <td class="text-center"><b><?= $total_pmks ?></b></td>
                        </tr>
                        <?php endif ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination float-right">
                        <?php if ($pageAktif > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif - 1 ?>">Previous</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $jml_Halaman; $i++): ?>
                        <li class="page-item <?= ($i == $pageAktif) ? 'active' : '' ?>"><a class="page-link"
                                href="?page=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <?php if ($pageAktif < $jml_Halaman): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $pageAktif + 1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</div>

<?php include('templetes/footer.php'); ?>