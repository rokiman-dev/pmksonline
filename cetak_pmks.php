<?php
// laporan_pmks.php

date_default_timezone_set('Asia/Jakarta');
require_once __DIR__ . '/vendor/autoload.php';
require_once "functions.php";
require_once "config.php";

$statusFilter = '';
if (isset($_GET['status']) && is_array($_GET['status'])) {
    $statusArr = array_map('addslashes', $_GET['status']);
    $statusStr = "'" . implode("','", $statusArr) . "'";
    $statusFilter = "AND a.status IN ($statusStr)";
}

$pmks = query("SELECT a.*, b.nm_kat AS jenis_akses, c.nm_program AS sub_menu, d.nm_kec AS kecamatan
               FROM pmks a
               LEFT JOIN kat_pmks b ON a.id_kat_pmks = b.id_kat_pmks
               LEFT JOIN program_bantuan c ON a.id_program = c.id_program
               LEFT JOIN kecamatan d ON a.id_kec = d.id_kec
               WHERE a.is_delete=0 $statusFilter
               ORDER BY a.time_input DESC");

$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
$data = '
<!DOCTYPE html>
<html>
<head>
   <title>Cetak Laporan Data PMKS</title>
</head>
<body>
   <table margin="auto">
      <tr>
         <td width="20%"><img src="assets/img/pmks.png" width="100px" height="100px"></td>
         <td width="85%">
         <center>
            <font size="5"><b>PEMERINTAH KABUPATEN TEGAL<b></font><br>
            <font size="5"><b>JAWA TENGAH<b></font><br>
            <font size="3">Jl. Ahmad Yani No. 3 Slawi Telp. (0234) 2772205, 272327</font><br>
            <font size="3">Fax. (0234) 272797 Kode Pos 45212 E-mail dinsoskabtegal@tegalkab.go.id</font><br>
         </center>
         </td>
         <td width="20%"><img src="assets/img/logo-blank.png" alt="logo-umc" width="100px" height="100px"></td>
      </tr>
      <tr>
         <td colspan="3"><hr></td>
      </tr>
   </table>

   <div style="text-align:center">
      <h4><b>LAPORAN DATA PMKS ONLINE</b></h4>
   </div>

   <table border="1" cellpadding="10" cellspacing="0" autosize="1" width="100%">
      <thead>
         <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kecamatan</th>
            <th>No Telepon</th>
            <th>Jenis Akses</th>
            <th>Sub Menu</th>
            <th>Tanggal Akses</th>
            <th>Status</th>
         </tr>
      </thead>
      <tbody>';

$i = 1;
foreach ($pmks as $row) {
    $data .= '<tr>
        <td style="text-align:center">' . $i++ . '</td>
        <td>' . $row['nm_pmks'] . '</td>
        <td>' . $row['alamat'] . '</td>
        <td>' . $row['kecamatan'] . '</td>
        <td>' . $row['no_telepon'] . '</td>
        <td>' . $row['jenis_akses'] . '</td>
        <td>' . $row['sub_menu'] . '</td>
        <td>' . date('d-m-Y', strtotime($row['time_input'])) . '</td>
        <td style="text-align:center">' . $row['status'] . '</td>
    </tr>';
}

$data .= '</tbody>
   </table>

   <br><br>
   <table width="100%">
      <tr><td></td><td></td><td style="text-align:center">Tegal, ' . date("d M Y") . '</td></tr>
      <tr><td></td><td></td><td style="text-align:center">A/n. Kepala Bidang Dinas Sosial Kab.Tegal</td></tr>
      <tr><td></td><td></td><td style="text-align:center">Kepala Bidang Sekretariat</td></tr>
      <tr><td colspan="3"><br><br><br></td></tr>
      <tr><td></td><td></td><td style="text-align:center"><b>Ibnu Fajar As Syukron</b></td></tr>
      <tr><td></td><td></td><td style="text-align:center">Penata Tk. I, III/d</td></tr>
      <tr><td></td><td></td><td style="text-align:center">NIP. 19650309 198903 1 012</td></tr>
   </table>
</body>
</html>';

$mpdf->WriteHTML($data);
$mpdf->Output('laporan_pmks.pdf', \Mpdf\Output\Destination::INLINE);