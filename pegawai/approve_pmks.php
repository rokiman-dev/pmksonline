<?php
session_start();
if (!isset($_SESSION["Pegawai"])) {
  header("location:../index.php");
  exit;
}

require_once '../config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>alert('ID tidak valid'); window.location.href='pmks.php';</script>";
  exit;
}

$id = intval($_GET['id']);

$update = mysqli_query($koneksi, "UPDATE pmks SET status = 'Selesai' WHERE id_pmks = $id");

if ($update) {
  echo "<script>alert('Status berhasil diubah menjadi Selesai.'); window.location.href='pmks.php';</script>";
} else {
  echo "<script>alert('Gagal mengubah status.'); window.location.href='pmks.php';</script>";
}