<?php 
session_start();
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
if (!isset($_SESSION["Pegawai"])){
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
    background-image: url('../assets/img/logopegawai.png');
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
                            <a href="pmks.php">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- <img src="../assets/img/logo.png" height="250px" align="center"> -->


    </div>

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<?php include('templetes/footer.php');?>