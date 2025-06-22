<?php 
session_start();
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
if (!isset($_SESSION["Admin"])){
    header("location:../index.php");
    exit;
}

  $page = "index";
  date_default_timezone_set('Asia/Jakarta');
  include('templetes/sidebar.php');
  include('templetes/topbar.php');
  require_once "../functions.php";

?>
<style type="text/css">
.latar-belakang2 {
    background-image: url('../assets/img/logo-new.png');
    background-repeat: no-repeat;
    background-size: cover;
}

.text-judul {
    color: #ffffff;
}
</style>
<!-- Begin Page Content -->
<div class="container-fluid ">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-judul">Selamat Datang <?=$_SESSION['nama'] ?></h1>
    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-h3 font-weight-bold text-primary text-uppercase mb-1">Pegawai</div>
                            <?php $pegawai = query("SELECT * FROM pegawai WHERE is_delete=0 ");
                        $jml_pegawai = count($pegawai); ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jml_pegawai?> Pegawai</div>
                        </div>
                        <div class="col-auto">
                            <a href="pegawai.php">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-h3 font-weight-bold text-primary text-uppercase mb-1">Kecamatan</div>
                            <?php $kecamatan = query("SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec != 'Tidak Ada'");
                        $jml_kecamatan = count($kecamatan); ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jml_kecamatan?> Kecamatan</div>
                        </div>
                        <div class="col-auto">
                            <a href="kecamatan.php">
                                <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->

        <!-- Earnings (Monthly) Card Example -->

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-h3 font-weight-bold text-primary text-uppercase mb-1">Program Bantuan</div>
                            <?php $program_bantuan = query("SELECT * FROM program_bantuan WHERE is_delete=0");
                        $jml_program_bantuan = count($program_bantuan); ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jml_program_bantuan?> Program
                                Bantuan</div>
                        </div>
                        <div class="col-auto">
                            <a href="program.php">
                                <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Earnings (Monthly) Card Example -->

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-h3 font-weight-bold text-primary text-uppercase mb-1">Kategori PMKS</div>
                            <?php $kat_pmks = query("SELECT * FROM kat_pmks WHERE is_delete=0");
                        $jml_kat_pmks = count($kat_pmks); ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jml_kat_pmks?> Kategori PMKS</div>
                        </div>
                        <div class="col-auto">
                            <a href="kategori.php">
                                <i class="fab fa-accessible-icon fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->



        <!-- Earnings (Monthly) Card Example -->

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-h3 font-weight-bold text-primary text-uppercase mb-1">PMKS</div>
                            <?php $pmks = query("SELECT * FROM pmks WHERE is_delete=0");
                        $jml_pmks = count($pmks); ?>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$jml_pmks?> PMKS</div>
                        </div>
                        <div class="col-auto">
                            <a href="laporan.php">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<?php include('templetes/footer.php');?>