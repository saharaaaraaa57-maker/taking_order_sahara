<?php
require_once '../config/koneksi.php';
if (!isPost()) { header("Location: " . base_url('index.php')); exit; }

$nama    = clean($_POST['nama'] ?? '');
$no_meja = clean($_POST['no_meja'] ?? '');
$no_hp   = clean($_POST['no_hp'] ?? '');

if ($nama === '' || $no_meja === '') {
    $_SESSION['error'] = "Nama dan nomor meja wajib diisi.";
    header("Location: " . base_url('index.php')); exit;
}

$q = "INSERT INTO pelanggan (nama, no_meja, no_hp) VALUES ('$nama', '$no_meja', '$no_hp')";
if (mysqli_query($koneksi, $q)) {
    $_SESSION['pelanggan_id']   = mysqli_insert_id($koneksi);
    $_SESSION['pelanggan_nama'] = $nama;
    $_SESSION['pelanggan_meja'] = $no_meja;
    $_SESSION['keranjang']      = [];
    header("Location: " . base_url('pelanggan/menu.php')); exit;
}
$_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
header("Location: " . base_url('index.php')); exit;