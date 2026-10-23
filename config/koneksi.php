<?php
/**
 * Koneksi Database & Helper — nono SPACE
 * Version: 2.1
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- Konfigurasi Aplikasi ---------- */
define('BASE_URL', 'http://localhost/taking_order_sahara/');
define('BRAND_NAME', 'nono SPACE');
define('BRAND_TAGLINE', 'Cafe & More');
define('BRAND_ADDRESS', 'Jl. Contoh Alamat No. 123, Kota Anda');
define('BRAND_PHONE', '021-1234567');

/* ---------- Konfigurasi Database ---------- */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_cafe');

/* ---------- Konfigurasi Upload ---------- */
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/menu/');
define('UPLOAD_URL', BASE_URL . 'assets/uploads/menu/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024);
define('ALLOWED_MIME', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_EXT',  ['jpg', 'jpeg', 'png', 'webp']);

/* ---------- Koneksi ---------- */
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');
date_default_timezone_set('Asia/Jakarta');

/* ---------- Helper: URL ---------- */
function base_url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

/* ---------- Helper: Format ---------- */
function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

function generateKodeOrder() {
    return "ORD-" . date("YmdHis") . "-" . strtoupper(substr(uniqid(), -4));
}

/* ---------- Helper: Auth ---------- */
function isAdminLoggedIn()     { return isset($_SESSION['admin_id']); }
function isPelangganLoggedIn() { return isset($_SESSION['pelanggan_id']); }

function requireAdmin() {
    if (!isAdminLoggedIn()) { header("Location: " . base_url('admin/login.php')); exit; }
}
function requirePelanggan() {
    if (!isPelangganLoggedIn()) { header("Location: " . base_url('index.php')); exit; }
}

/* ---------- Helper: Upload Gambar ---------- */
function uploadGambarMenu($file) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Parameter file tidak valid.'];
    }
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'Tidak ada file.', 'no_file' => true];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Gagal upload (kode ' . $file['error'] . ').'];
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'Ukuran file maksimal 2MB.'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ALLOWED_MIME)) {
        return ['success' => false, 'error' => 'Format harus JPG, PNG, atau WEBP.'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXT)) {
        return ['success' => false, 'error' => 'Ekstensi file tidak diizinkan.'];
    }
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

    $filename = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest     = UPLOAD_DIR . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => false, 'error' => 'Gagal menyimpan file.'];
    }
    return ['success' => true, 'filename' => $filename];
}

function hapusGambarMenu($filename) {
    if (empty($filename)) return;
    $path = UPLOAD_DIR . $filename;
    if (is_file($path)) @unlink($path);
}

/* ---------- Helper: Sanitasi ---------- */
function clean($str) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim((string)$str));
}
function isPost() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }