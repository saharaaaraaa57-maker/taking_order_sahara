<?php
require_once '../config/koneksi.php';
requirePelanggan();
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: keranjang.php"); exit; }

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $mid = (int)$mid;
    $q = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id = $mid");
    $m = mysqli_fetch_assoc($q);
    if ($m) $total += $m['harga'] * $jml;
}

$judul = "Checkout — " . BRAND_NAME;
include '../includes/header.php';
include '../includes/navbar.php';
?>
<main class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-accent mb-6">Konfirmasi Pesanan</h1>

    <div class="grid lg:grid-cols-5 gap-5">
        <div class="lg:col-span-3 space-y-5">
            <div class="card card-shadow p-6">
                <h2 class="font-bold text-accent text-sm mb-4">Data Pelanggan</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-accent/60">Nama</dt><dd class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></dd></div>
                    <div class="flex justify-between"><dt class="text-accent/60">No. Meja</dt><dd class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></dd></div>
                </dl>
            </div>

            <form action="../proses/simpan_order.php" method="POST">
                <div class="card card-shadow p-6 mb-4">
                    <h2 class="font-bold text-accent text-sm mb-4">Catatan Tambahan</h2>
                    <textarea name="catatan" rows="3" placeholder="Contoh: tanpa gula, extra shot (opsional)" class="input"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full py-3">
                    <i data-lucide="send" class="w-4 h-4"></i> Kirim Pesanan
                </button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="card card-shadow p-6 lg:sticky lg:top-20">
                <h2 class="font-bold text-accent text-sm mb-4">Rincian Pesanan</h2>
                <div class="space-y-2 text-sm">
                    <?php foreach ($keranjang as $mid => $jml):
                        $mid = (int)$mid;
                        $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid");
                        $m = mysqli_fetch_assoc($q);
                        if (!$m) continue;
                    ?>
                    <div class="flex justify-between">
                        <span><?= htmlspecialchars($m['nama_menu']); ?> <span class="text-accent/40">× <?= (int)$jml; ?></span></span>
                        <span class="font-semibold"><?= rupiah($m['harga'] * $jml); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="border-t border-secondary/40 mt-3 pt-3 flex justify-between items-center">
                    <span class="font-bold text-accent">Total</span>
                    <span class="text-primary font-extrabold text-xl"><?= rupiah($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>