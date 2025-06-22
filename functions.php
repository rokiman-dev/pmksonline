<?php

function query($query)
{
	include('config.php');

	$result = mysqli_query($koneksi, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function register($data)
{
	include('config.php');

	$nama 		= htmlspecialchars($data["nama"]);
	$nip 		= htmlspecialchars($data["nip"]);
	$no_tlp 	= htmlspecialchars($data["no_tlp"]);
	$alamat 	= htmlspecialchars($data["alamat"]);
	$username 	= strtolower(stripcslashes($data["username"]));
	$password 	= mysqli_real_escape_string($koneksi, $data["password"]);
	$password2 	= mysqli_real_escape_string($koneksi, $data["password2"]);
	$role		= "Pegawai";
	$foto		= "default.png";
	$time_input = $data["time_input"];

	// Cek NIP
	$cek = mysqli_query($koneksi, "SELECT nip FROM pegawai WHERE nip = '$nip'");
	if (mysqli_fetch_assoc($cek)) {
		echo "<script>alert('NIP sudah terdaftar!');</script>";
		return false;
	}

	// Cek konfirmasi password
	if ($password !== $password2) {
		echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
		return false;
	}

	$password = password_hash($password, PASSWORD_DEFAULT);

	$query = "INSERT INTO pegawai 
	(nm_pegawai, nip, no_telepon, alamat, username, password, role, is_delete, row_edit, time_input, foto) 
	VALUES 
	('$nama', '$nip', '$no_tlp', '$alamat', '$username', '$password', '$role', 0, 0, '$time_input', '$foto')";

	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function tambahDataKecamatan($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$nm_kec 	= htmlspecialchars($data["nm_kec"]);
	$creator	= $data["id_pegawai"];
	$time_input = $data["time_input"];

	//cek kecamatan sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_kec FROM kecamatan WHERE nm_kec = '$nm_kec' AND is_delete=1");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Kecamatan sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "INSERT INTO kecamatan VAlUES ('','$nm_kec',0,0,'$creator','$time_input')";
	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function hapusDataKecamatan($data)
{
	include('config.php');

	$id_kec = $data["id_kec"];

	$query 	= "UPDATE kecamatan SET is_delete = 1 WHERE id_kec='$id_kec'";
	mysqli_query($koneksi, $query);

	return mysqli_affected_rows($koneksi);
}

function editDataKecamatan($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$id_kec  = $data["id_kec"];

	$nm_kec 	= htmlspecialchars($data["nm_kec"]);
	$is_delete	= 0;
	$row_edit 	= $data["row_edit"];
	$creator    = $data["id_pegawai"];
	$time_input = $data["time_input"];

	// cek kecamatan sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_kec FROM kecamatan WHERE nm_kec = '$nm_kec' AND is_delete=0");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Kecamatan sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "UPDATE kecamatan SET 
 				   	 nm_kec='$nm_kec', is_delete='$is_delete', 
 				   	 row_edit='$row_edit', creator='$creator',
 				   	 time_input='$time_input' WHERE id_kec='$id_kec'";

	mysqli_query($koneksi, $query);

	return mysqli_affected_rows($koneksi);
}

function tambahDataProgram($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$program 	= htmlspecialchars($data["program"]);
	$is_delete	= 0;
	$row_edit 	= $data["row_edit"];
	$creator	= $data["id_pegawai"];
	$time_input = $data["time_input"];

	//cek program bantuan sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_program FROM program_bantuan WHERE nm_program = '$program' AND is_delete=0");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Program Bantuan sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "INSERT INTO program_bantuan VAlUES ('','$program','$is_delete','$row_edit','$creator','$time_input')";
	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function hapusDataProgram($data)
{
	include('config.php');

	$id_program = $data["id_program"];

	$query 	= "UPDATE program_bantuan SET is_delete = 0 WHERE id_program='$id_program'";
	mysqli_query($koneksi, $query);

	$query1 	= "UPDATE pmks SET is_delete = 0 WHERE id_program='$id_program'";
	mysqli_query($koneksi, $query1);

	return mysqli_affected_rows($koneksi);
}

function editDataProgram($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$id_program  = $data["id_program"];

	$program2 	 = $data["program2"];
	$is_delete2	 = $data["is_delete2"];
	$row_edit2 	 = $data["row_edit2"];
	$creator2    = $data["id_pegawai2"];
	$time_input2 = $data["time_input2"];

	$program 	= htmlspecialchars($data["program"]);
	$is_delete	= $data["is_delete"];
	$row_edit 	= $data["row_edit"];
	$creator    = $data["id_pegawai"];
	$time_input = $data["time_input"];


	// cek program_bantuan sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_program FROM program_bantuan WHERE nm_program = '$program' AND is_delete=1");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Program Bantuan sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "UPDATE program_bantuan SET 
 				   	 nm_program='$program', is_delete='$is_delete', 
 				   	 row_edit='$row_edit', creator='$creator',
 				   	 time_input='$time_input' WHERE id_program='$id_program'";

	mysqli_query($koneksi, $query);
	$query2 = "INSERT INTO program_bantuan VAlUES ('','$program2','$is_delete2','$row_edit2','$creator2','$time_input2')";
	mysqli_query($koneksi, $query2);

	return mysqli_affected_rows($koneksi);
}

function tambahDataKategori($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$nm_kat 	= htmlspecialchars($data["nm_kat"]);
	$is_delete	= 0;
	$row_edit 	= $data["row_edit"];
	$creator	= $data["id_pegawai"];
	$time_input = $data["time_input"];

	//cek kategori PMKS sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_kat FROM kat_pmks WHERE nm_kat = '$nm_kat' AND is_delete=0");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Kategori PMKS sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "INSERT INTO kat_pmks VAlUES ('','$nm_kat','$is_delete','$row_edit','$creator','$time_input')";
	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function hapusDataKategori($data)
{
	include('config.php');

	$id_kat_pmks = $data["id_kat_pmks"];

	$query 	= "UPDATE kat_pmks SET is_delete = 1 WHERE id_kat_pmks='$id_kat_pmks'";
	mysqli_query($koneksi, $query);

	return mysqli_affected_rows($koneksi);
}

function editDataKategori($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$id_kat_pmks  = $data["id_kat_pmks"];

	$nm_kat 	= htmlspecialchars($data["nm_kat"]);
	$is_delete	= 0;
	$row_edit 	= $data["row_edit"];
	$creator    = $data["id_pegawai"];
	$time_input = $data["time_input"];


	// cek kategori PMKS sudah ada atau belum
	$result = mysqli_query($koneksi, "SELECT nm_kat FROM kat_pmks WHERE nm_kat = '$nm_kat' AND is_delete=0");

	if (mysqli_fetch_assoc($result)) {
		echo "<script>
 				alert('Kategori PMKS sudah Ada!')
 			  </script>";
		return false;
	}

	$query = "UPDATE kat_pmks SET 
 				   	 nm_kat='$nm_kat', is_delete='$is_delete', 
 				   	 row_edit='$row_edit', creator='$creator',
 				   	 time_input='$time_input' WHERE id_kat_pmks='$id_kat_pmks'";

	mysqli_query($koneksi, $query);

	return mysqli_affected_rows($koneksi);
}

function tambahDataPmks($data)
{
    include('config.php');

    $nama        = mysqli_real_escape_string($koneksi, $data['nm_pmks']);
    $alamat      = mysqli_real_escape_string($koneksi, $data['alamat']);
    $id_kec      = (int) $data['id_kec'];
    $no_telepon  = mysqli_real_escape_string($koneksi, $data['no_telepon']);
    $id_kat      = (int) $data['id_kat_pmks'];
    $id_program  = (int) $data['id_program'];
    $creator     = (int) $data['id_pegawai'];
    $time_input  = $data['time_input'];
    $is_delete   = (int) $data['is_delete'];
    $status      = mysqli_real_escape_string($koneksi, $data['status']);

    $query = "INSERT INTO pmks 
        (nm_pmks, alamat, id_kec, no_telepon, id_kat_pmks, id_program, creator, time_input, is_delete, status)
        VALUES 
        ('$nama', '$alamat', $id_kec, '$no_telepon', $id_kat, $id_program, $creator, '$time_input', $is_delete, '$status')";

    mysqli_query($koneksi, $query);
    return mysqli_affected_rows($koneksi);
}

function editDataPmks($data)
{
    include('config.php');

    $id_pmks     = (int) $data['id_pmks'];
    $nm_pmks     = mysqli_real_escape_string($koneksi, $data['nm_pmks']);
    $alamat      = mysqli_real_escape_string($koneksi, $data['alamat']);
    $id_kec      = (int) $data['id_kec'];
    $no_telepon  = mysqli_real_escape_string($koneksi, $data['no_telepon']);
    $id_kat_pmks = (int) $data['id_kat_pmks'];
    $id_program  = (int) $data['id_program'];
	$time_input  = $data['time_input'];
	$status = mysqli_real_escape_string($koneksi, $data['status']);

    $query = "UPDATE pmks SET 
                nm_pmks = '$nm_pmks',
                alamat = '$alamat',
                id_kec = $id_kec,
                no_telepon = '$no_telepon',
                id_kat_pmks = $id_kat_pmks,
                id_program = $id_program,
				time_input = '$time_input',
				status = '$status'
              WHERE id_pmks = $id_pmks";

    mysqli_query($koneksi, $query);
    return mysqli_affected_rows($koneksi);
}

function hapusDataPmks($data)
{
	include('config.php');

	$id_pmks = $data["id_pmks"];

	$query 	= "UPDATE pmks SET is_delete = 1 WHERE id_pmks='$id_pmks'";
	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function editDataRole($data)
{
	include('config.php');

	$id_pegawai = $data["id_pegawai"];
	$role = $data["role"];

	$query = "UPDATE pegawai SET role='$role' WHERE id_pegawai='$id_pegawai'";

	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function editDataPegawai($data)
{
	include('config.php');
	// var_dump($_POST);die;

	$id_pegawai  = $data["id_pegawai"];

	$nm_pegawai  = htmlspecialchars($data["nm_pegawai"]);
	$nip 	     = htmlspecialchars($data["nip"]);
	$no_telepon  = htmlspecialchars($data["no_telepon"]);
	$alamat	     = htmlspecialchars($data["alamat"]);
	$username	 = htmlspecialchars($data["username"]);

$query = "UPDATE pegawai SET 
 				   	 nm_pegawai='$nm_pegawai', nip='$nip',
 				   	 no_telepon='$no_telepon', alamat='$alamat',
 				   	 username='$username' WHERE id_pegawai='$id_pegawai'";

	mysqli_query($koneksi, $query);

	return mysqli_affected_rows($koneksi);
}

function hapusDataPegawai($data)
{
	include('config.php');

	$id_pegawai = $data["id_pegawai"];
	$query = "UPDATE pegawai SET is_delete = 1 WHERE id_pegawai='$id_pegawai'";

	mysqli_query($koneksi, $query);
	return mysqli_affected_rows($koneksi);
}

function ubahpass($data)
{
	include('config.php');

	$id_pegawai         = $data["id_pegawai"];
	$password1       = htmlspecialchars($data["password1"]);
	$password2       = htmlspecialchars($data["password2"]);
	
	$result = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE id_pegawai = '$id_pegawai'");

	if( mysqli_num_rows($result) == 1 ) {
	    	// cek kesamaan password
	    	if ($password1 !== $password2) {
	    		echo "<script>
		    		  alert('Konfirmasi password tidak sesuai!');
		    		  </script>";
	    		return false;
	    	}

	    	$password = password_hash($password1, PASSWORD_DEFAULT);
	    	mysqli_query($koneksi, "UPDATE pegawai SET password ='$password' WHERE id_pegawai = $id_pegawai");
	    	return mysqli_affected_rows($koneksi);

	}
}