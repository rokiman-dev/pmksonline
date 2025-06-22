<?php 
 session_start();
if (!isset($_SESSION["Admin"])){
    header("location:../index.php");
    exit;
}

  $page  = "program";
  date_default_timezone_set('Asia/Jakarta');
  include('templetes/sidebar.php');
  include('templetes/topbar.php');
  require_once "../functions.php";

  $id_pegawai=$_SESSION['id_pegawai'];
  $pegawai=query("SELECT id_pegawai FROM pegawai WHERE id_pegawai='$id_pegawai'")[0];

  $jml_DataHalaman = 5;
  $jml_responden = count(query("SELECT * FROM program_bantuan  WHERE is_delete=0"));
  $jml_Halaman = ceil($jml_responden / $jml_DataHalaman);

  $pageAktif = (isset($_GET["page"]) ) ? $_GET["page"] : 1;
  $awaldata = ( $jml_DataHalaman * $pageAktif ) - $jml_DataHalaman;

  $prog=query("SELECT * FROM program_bantuan  WHERE is_delete=0 ORDER BY id_program LIMIT $awaldata, $jml_DataHalaman");
  // var_dump($kecamatan);die;

  if(isset($_POST["tambah"])){
  // var_dump($_POST);die;
  if(tambahDataProgram($_POST)>0){
    echo"
       <script>
       alert('data berhasil di tambah');
       document.location.href='program.php';
       </script>
       ";
      }else{
        echo"
       <script>
       alert('data gagal di tambah');
       document.location.href='program.php';
       </script>
       ";
      }
}

  if(isset($_POST["hapus"])){
  // var_dump($_POST);die;
  if(hapusDataProgram($_POST)>0){
    echo"
       <script>
       alert('data berhasil di hapus');
       document.location.href='program.php';
       </script>
       ";
      }else{
        echo"
       <script>
       alert('data gagal di hapus');
       document.location.href='program.php';
       </script>
       ";
      }
}

  if(isset($_POST["edit"])){
  // var_dump($_POST);die;
  if(editDataProgram($_POST)>0){
    echo"
       <script>
       alert('data berhasil di ubah');
       document.location.href='program.php';
       </script>
       ";
      }else{
        echo"
       <script>
       alert('data gagal di ubah');
       document.location.href='program.php';
       </script>
       ";
      }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-judul">Program Bantuan</h1>

    <!-- isi content -->
    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                Tambah Program
            </button>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Program Bantuan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <?php $i=$awaldata+1; ?>
                        <?php foreach ($prog as $row):?>

                        <tbody>
                            <tr>
                                <td class="text-center"><?=$i;?></td>
                                <td class="text-left"><?=$row['nm_program'];?></td>
                                <td class="text-center">
                                    <form method="POST">
                                        <button type="button" id="edit" name="edit" class="btn btn-warning btn-sm"
                                            data-toggle="modal" data-target="#modalEdit<?= $row['id_program']; ?>">
                                            <i class="fas fa-edit"></i> Edit</button>

                                        <input type="hidden" name="id_program" value="<?=$row['id_program'];?>">
                                        <button type="submit" name="hapus" class="btn btn-danger btn-sm"
                                            onclick="return confirm('yakin hapus <?=$row['nm_program'] ?>?');">
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

            <!-- Modal Tambah -->
            <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Tambah Program Bantuan</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <label>Nama Program Bantuan</label>
                                <input type="text" name="program" class="form-control" required>

                                <input type="hidden" name="is_delete" value="0">
                                <input type="hidden" name="id_pegawai" value="<?= $pegawai['id_pegawai'] ?>">
                                <input type="hidden" name="time_input" value="<?= date("Y-m-d H:i:s"); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Modal Edit Data -->
            <?php foreach ($prog as $row)  : ?>
            <div class="modal fade" id="modalEdit<?= $row['id_program'] ?>" tabindex="-1" role="dialog"
                aria-labelledby="modalEditTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="modalEditTitle">Edit Program Bantuan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <!-- Hidden Data -->
                                <input type="hidden" name="id_program" value="<?= $row['id_program'] ?>">
                                <input type="hidden" name="id_pegawai" value="<?= $_SESSION['id_pegawai'] ?>">
                                <input type="hidden" name="time_input" value="<?= date('Y-m-d H:i:s') ?>">
                                <input type="hidden" name="is_delete" value="0">

                                <!-- Nama Program -->
                                <div class="form-group">
                                    <label for="program">Nama Program Bantuan:</label>
                                    <input type="text" class="form-control mt-1" id="program" name="program"
                                        value="<?= htmlspecialchars($row['nm_program']) ?>" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" name="edit" class="btn btn-warning">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <?php endforeach; ?>


        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->


<?php include('templetes/footer.php');?>