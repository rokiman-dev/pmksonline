<?php 
session_start();

require_once "../config.php";
require_once "../functions.php";
$page = "profile";
include('templetes/sidebar.php');
include('templetes/topbar.php');

$id = $_SESSION["id_pegawai"];
$user = query("SELECT * FROM pegawai WHERE is_delete=0 AND id_pegawai = $id")[0];

// UPLOAD FOTO
if (isset($_POST["uploadFoto"])) {
    $id = $_POST["id_pegawai"];
    $file = $_FILES["foto"];

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $fileExt = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowedTypes) && $file["size"] <= 5 * 1024 * 1024) {
        $fileName = "foto_" . $id . "_" . time() . "." . $fileExt;
        $uploadPath = "../assets/img/profile/" . $fileName;

        // Pindahkan file
        if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
            // Update DB
            mysqli_query($koneksi, "UPDATE pegawai SET foto = '$fileName' WHERE id_pegawai = $id");
            echo "<script>alert('Foto berhasil diupload!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan foto!');</script>";
        }
    } else {
        echo "<script>alert('Format tidak didukung atau ukuran file terlalu besar!');</script>";
    }
}

// EDIT PROFILE
if (isset($_POST["editProfile"])) {
    if (editDataPegawai($_POST) > 0) {
        echo "<script>alert('Profil berhasil diubah!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Tidak ada perubahan data');</script>";
    }
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Akun</h1>

    <div class="row">
        <!-- Foto Profil -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Foto Profil</div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="../assets/img/profile/<?= $user['foto'] ?>"
                        width="120" height="120" alt="User">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_pegawai" value="<?= $user['id_pegawai'] ?>">
                        <input type="file" name="foto" class="form-control-file mb-2" accept="image/*" required>
                        <button type="submit" name="uploadFoto" class="btn btn-sm btn-primary">Upload Foto Baru</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Data Akun -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">Data Akun</div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_pegawai" value="<?= $user['id_pegawai'] ?>">
                        <input type="hidden" name="nm_pegawai2" value="<?= $user['nm_pegawai'] ?>">
                        <input type="hidden" name="nip2" value="<?= $user['nip'] ?>">
                        <input type="hidden" name="no_telepon2" value="<?= $user['no_telepon'] ?>">
                        <input type="hidden" name="alamat2" value="<?= $user['alamat'] ?>">
                        <input type="hidden" name="username2" value="<?= $user['username'] ?>">
                        <input type="hidden" name="password2" value="<?= $user['password'] ?>">
                        <input type="hidden" name="role2" value="<?= $user['role'] ?>">
                        <input type="hidden" name="is_delete2" value="0">
                        <input type="hidden" name="row_edit2" value="<?= $user['id_pegawai'] ?>">
                        <input type="hidden" name="time_input2" value="<?= date("Y-m-d H:i:s") ?>">

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" name="nm_pegawai" value="<?= $user['nm_pegawai'] ?>"
                                required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>NIP</label>
                                <input type="text" class="form-control" name="nip" value="<?= $user['nip'] ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>No. HP</label>
                                <input type="text" class="form-control" name="no_telepon"
                                    value="<?= $user['no_telepon'] ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="<?= $user['alamat'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Hak Akses</label>
                            <input type="text" class="form-control" value="<?= $user['role'] ?>" disabled>
                        </div>
                        <button type="submit" name="editProfile" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('templetes/footer.php'); ?>