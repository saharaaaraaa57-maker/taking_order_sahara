<?php
require_once '../config/koneksi.php';
requirePelanggan();

$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$q = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id = $menu_id");
$m = mysqli_fetch_assoc($q);
if ($m) {
    $jumlah = min($jumlah, (int)$m['stok']);
    if (isset($_SESSION['keranjang'][$menu_id])) {
        $_SESSION['keranjang'][$menu_id] = $jumlah;
    }
}
header("Location: " . base_url('pelanggan/keranjang.php')); exit;