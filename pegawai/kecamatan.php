<?php 
 session_start();
if (!isset($_SESSION["Pegawai"])){
    header("location:../index.php");
    exit;
}

  $page  = "wilayah";
  $page1 = "kecamatan";
  date_default_timezone_set('Asia/Jakarta');
  include('templetes/sidebar.php');
  include('templetes/topbar.php');
  require_once "../functions.php";

  $id_pegawai=$_SESSION['id_pegawai'];
  $pegawai=query("SELECT id_pegawai FROM pegawai WHERE id_pegawai='$id_pegawai'")[0];

  $jml_DataHalaman = 5;
  $jml_responden = count(query( "SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec!= 'Tidak Ada' ORDER BY id_kec"));
  $jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

  $pageAktif = (isset($_GET["page"]) ) ? $_GET["page"] : 1;
  $awaldata = ( $jml_DataHalaman * $pageAktif ) - $jml_DataHalaman;

  $kec=query("SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec!= 'Tidak Ada' ORDER BY id_kec LIMIT $awaldata, $jml_DataHalaman");
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-judul">Kecamatan</h1>

    <!-- isi content -->
    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Kecamatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <?php $i=$awaldata+1; ?>
                        <?php foreach ($kec as $row):?>
                        <tbody>
                            <tr>
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-left"><?=$row['nm_kec'];?></td>
                                <td class="text-center">
                                    <form method="POST">
                                        <button type="button" id="edit" name="edit" class="btn btn-warning btn-sm"
                                            data-toggle="modal" data-target="#modalEdit<?= $row['id_kec']; ?>">
                                            <i class="fas fa-edit"></i> Edit</button>

                                        <input type="hidden" name="id_kec" value="<?=$row['id_kec'];?>">
                                        <button type="submit" name="hapus" class="btn btn-danger btn-sm"
                                            onclick="return confirm('yakin hapus <?=$row['nm_kec'] ?>?');">
                                            <i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php $i++;?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination float-right">
                            <?php if ($pageAktif > 1) : ?>
                            <li class="page-item"><a class="page-link" href="?page=<?=$pageAktif-1; ?>">Previous</a>
                            </li>
                            <?php endif; ?>
                            <?php for ($i=1; $i <= $jml_Halaman; $i++) : ?>
                            <?php if ($i == $pageAktif) : ?>
                            <li class="page-item active"><a class="page-link" href="?page=<?=$i; ?>"><?=$i; ?></a></li>
                            <?php else : ?>
                            <li class="page-item"><a class="page-link" href="?page=<?=$i; ?>"><?=$i; ?></a></li>
                            <?php endif; ?>
                            <?php endfor; ?>
                            <?php if ($pageAktif < $jml_Halaman) : ?>
                            <li class="page-item"><a class="page-link" href="?page=<?=$pageAktif+1; ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->


<?php include('templetes/footer.php');?>