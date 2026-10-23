<?php
require_once '../config/koneksi.php';
unset($_SESSION['pelanggan_id']);
unset($_SESSION['pelanggan_nama']);
unset($_SESSION['pelanggan_meja']);
unset($_SESSION['keranjang']);
unset($_SESSION['last_order_id']);
session_destroy();
header("Location: " . base_url('index.php'));
exit;