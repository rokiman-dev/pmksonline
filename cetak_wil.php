<?php
date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/vendor/autoload.php';
require_once "functions.php";
require_once "config.php";

// Fungsi aman untuk encode gambar ke base64
function imageToBase64($path) {
    if (file_exists($path)) {
        $data = file_get_contents($path);
        if ($data !== false) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            return 'data:image/' . $ext . ';base64,' . base64_encode($data);
        }
    }
    return '';
}

$kecamatan = query("SELECT * FROM kecamatan WHERE is_delete=0 AND nm_kec != 'Tidak Ada' ORDER BY nm_kec ASC");

// Load logo sebagai base64
$logoPmks = imageToBase64(__DIR__ . '/assets/img/logo-cetak.jpg');
$logoBlank = imageToBase64(__DIR__ . '/assets/img/logo-blank.png');

// Debug jika logo tidak ditemukan
if (empty($logoPmks)) {
    file_put_contents('debug_logo.txt', "Logo PMKS gagal dimuat dari: " . __DIR__ . '/assets/img/logo-cetak.jpg');
}

$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
$data = '
<!DOCTYPE html>
<html>
<head>
   <title>Cetak Wilayah PMKS</title>
</head>
<body>
   <table margin="auto">
      <tr>
         <td width="22.5%" style="text-align: right; padding-right: 10px;">
   <img src="' . $logoPmks . '" width="100px" height="100px">
</td>
         <td width="55%">
         <center>
            <font size="5"><b>PEMERINTAH KABUPATEN TEGAL<b></font><br>
            <font size="5"><b>JAWA TENGAH<b></font><br>
            <font size="3">Jl. Ahmad Yani No. 3 Slawi Telp. (0234) 2772205, 272327</font><br>
            <font size="3">Fax. (0234) 272797 Kode Pos 45212 E-mail dinsoskabtegal@tegalkab.go.id</font><br>
         </center>
         </td>
         <td width="22.5%" style="padding-right:10px;"><img src="' . $logoBlank . '" width="100px" height="100px"></td> 
      </tr>
      <tr>
         <td colspan="3"><hr></td>
      </tr>
   </table>

   <table margin="auto">
         <tr>
            <td width="30%">
               <center>
                     <font size="5">Data PMKS per Kecamatan</font><br>
               </center>
            </td>
         </tr>
   </table>
   <br>

   <table border="1" cellpadding="10" cellspacing="0" autosize="1" width="100%">
      <tr>
         <th><font size="3">No</font></th>
         <th><font size="3">Kecamatan</font></th>
         <th><font size="3">Jumlah PMKS</font></th>
      </tr>';

$i = 1;
foreach ($kecamatan as $row) {
    $id_kec = $row['id_kec'];
    $jml_pmks = count(query("SELECT id_pmks FROM pmks WHERE is_delete=0 AND id_kec = $id_kec"));

    $data .= '<tr>
        <td style="text-align:center;"><font size="3">'. $i++ .'</font></td>
        <td style="text-align:center;"><font size="3">'. $row["nm_kec"] .'</font></td>
        <td style="text-align:center;"><font size="3">'. $jml_pmks .'</font></td>
    </tr>';
}

$total_pmks = count(query("SELECT id_pmks FROM pmks WHERE is_delete=0"));

$data .= '<tr>
      <td colspan="2" style="text-align:center;"><font size="3"><b>Jumlah</b></font></td>
      <td style="text-align:center;"><font size="3"><b>'. $total_pmks .'</b></font></td>
</tr>';

$data .= '</table>
<br>
<table border="0" autosize="1" width="100%">
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3">Tegal, '.date("d M Y").' </font></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3">A/n. Kepala Bidang Dinas Sosial Kab.Tegal </font></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3">Kepala Bidang Sekretariat</font></td>
   </tr>
 </table>
 <br><br>
 <table border="0" autosize="1" width="100%">
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3"><b>Ibnu Fajar As Syukron<b></font></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3">Penata Tk. I, III/d</font></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td width="50%" style="text-align:center;"><font size="3">NIP. 19650309 198903 1 012</font></td>
   </tr>
 </table>

</body>
</html>
';

$mpdf->Image('assets/img/logo.png', 0, 0, 210, 297, 'png', '', true, false);
$mpdf->WriteHTML($data);
$mpdf->Output();