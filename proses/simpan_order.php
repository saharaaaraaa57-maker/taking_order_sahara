<?php
require_once '../config/koneksi.php';
requirePelanggan();

$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: " . base_url('pelanggan/keranjang.php')); exit; }

$pelanggan_id = (int)$_SESSION['pelanggan_id'];
$no_meja      = clean($_SESSION['pelanggan_meja']);
$catatan      = clean($_POST['catatan'] ?? '');
$kode_order   = generateKodeOrder();

$total = 0;
$items = [];
foreach ($keranjang as $mid => $jml) {
    $mid = (int)$mid;
    $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid");
    $m = mysqli_fetch_assoc($q);
    if (!$m) continue;
    $sub = $m['harga'] * $jml;
    $total += $sub;
    $items[] = ['menu' => $m, 'jml' => $jml, 'sub' => $sub];
}

if (empty($items)) { header("Location: " . base_url('pelanggan/keranjang.php')); exit; }

mysqli_begin_transaction($koneksi);
try {
    $q = "INSERT INTO orders (kode_order, pelanggan_id, no_meja, total_harga, catatan)
          VALUES ('$kode_order', $pelanggan_id, '$no_meja', $total, '$catatan')";
    if (!mysqli_query($koneksi, $q)) throw new Exception(mysqli_error($koneksi));
    $order_id = mysqli_insert_id($koneksi);

    foreach ($items as $it) {
        $m = $it['menu'];
        $jml = (int)$it['jml'];
        $sub = (float)$it['sub'];
        $mid = (int)$m['id'];
        $nama_menu = clean($m['nama_menu']);
        $harga = (float)$m['harga'];

        $q2 = "INSERT INTO order_detail (order_id, menu_id, nama_menu, harga, jumlah, subtotal)
               VALUES ($order_id, $mid, '$nama_menu', $harga, $jml, $sub)";
        if (!mysqli_query($koneksi, $q2)) throw new Exception(mysqli_error($koneksi));

        $q3 = "UPDATE menu SET stok = stok - $jml WHERE id = $mid";
        if (!mysqli_query($koneksi, $q3)) throw new Exception(mysqli_error($koneksi));
    }
    mysqli_commit($koneksi);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die("Gagal menyimpan order: " . $e->getMessage());
}

$_SESSION['keranjang']     = [];
$_SESSION['last_order_id'] = $order_id;
header("Location: " . base_url('pelanggan/sukses.php')); exit;