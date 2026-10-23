<?php
require_once '../config/koneksi.php';
if (!isPost()) { header("Location: login.php"); exit; }

$username = clean($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$q = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username'");
$admin = mysqli_fetch_assoc($q);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama_lengkap'];
    header("Location: dashboard.php"); exit;
}
$_SESSION['error'] = "Username atau password salah.";
header("Location: login.php"); exit;