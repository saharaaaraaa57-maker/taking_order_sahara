<?php
require_once '../config/koneksi.php';
requirePelanggan();

$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $menu_id AND status = 'tersedia'");
$menu = mysqli_fetch_assoc($q);
if (!$menu) {
    $_SESSION['pesan'] = "Menu tidak ditemukan.";
    header("Location: " . base_url('pelanggan/menu.php')); exit;
}

if ($jumlah > (int)$menu['stok']) $jumlah = (int)$menu['stok'];
if ($jumlah <= 0) {
    $_SESSION['pesan'] = "Stok habis.";
    header("Location: " . base_url('pelanggan/menu.php')); exit;
}

if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
$current = $_SESSION['keranjang'][$menu_id] ?? 0;
$_SESSION['keranjang'][$menu_id] = min($current + $jumlah, (int)$menu['stok']);

$_SESSION['pesan'] = $menu['nama_menu'] . " ditambahkan ke keranjang.";
header("Location: " . base_url('pelanggan/menu.php')); exit;