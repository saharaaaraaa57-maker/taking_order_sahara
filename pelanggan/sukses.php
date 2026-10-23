<?php
require_once '../config/koneksi.php';
if (!isPelangganLoggedIn() || !isset($_SESSION['last_order_id'])) {
    header("Location: ../index.php"); exit;
}
$oid = (int)$_SESSION['last_order_id'];

$q_order = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
                                   JOIN pelanggan p ON o.pelanggan_id = p.id
                                   WHERE o.id = $oid");
$order = mysqli_fetch_assoc($q_order);
if (!$order) { header("Location: ../index.php"); exit; }

$q_detail = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $oid");
$detail = [];
while ($d = mysqli_fetch_assoc($q_detail)) $detail[] = $d;

$judul = "Struk Pesanan — " . BRAND_NAME;
include '../includes/header.php';
?>
<main class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-5 no-print">
            <div class="w-14 h-14 mx-auto rounded-full bg-[#EDF7F0] flex items-center justify-center mb-3">
                <i data-lucide="check" class="w-7 h-7 text-[#2F7A4D]"></i>
            </div>
            <h1 class="text-lg font-extrabold text-accent">Pesanan Berhasil</h1>
            <p class="text-sm text-accent/60 mt-1">Tunjukkan struk ini ke kasir atau pelayan</p>
        </div>

        <div class="card card-shadow p-6 sm:p-8">
            <div class="text-center border-b border-dashed border-secondary/60 pb-4 mb-4">
                <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo mx-auto mb-2">
                <p class="text-[11px] text-accent/60"><?= BRAND_ADDRESS; ?></p>
                <p class="text-[11px] text-accent/60">Telp: <?= BRAND_PHONE; ?></p>
            </div>

            <div class="space-y-1.5 text-xs">
                <div class="flex justify-between"><span class="text-accent/60">Kode Order</span><span class="font-mono font-semibold"><?= htmlspecialchars($order['kode_order']); ?></span></div>
                <div class="flex justify-between"><span class="text-accent/60">Tanggal</span><span><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span></div>
                <div class="flex justify-between"><span class="text-accent/60">Nama</span><span class="font-semibold"><?= htmlspecialchars($order['nama']); ?></span></div>
                <div class="flex justify-between"><span class="text-accent/60">No. Meja</span><span><?= htmlspecialchars($order['no_meja']); ?></span></div>
                <?php if (!empty($order['no_hp'])): ?>
                <div class="flex justify-between"><span class="text-accent/60">No. HP</span><span><?= htmlspecialchars($order['no_hp']); ?></span></div>
                <?php endif; ?>
            </div>

            <div class="border-t border-b border-dashed border-secondary/60 my-4 py-3 space-y-2 text-sm">
                <?php foreach ($detail as $d): ?>
                <div>
                    <p class="font-semibold"><?= htmlspecialchars($d['nama_menu']); ?></p>
                    <div class="flex justify-between text-accent/70 text-xs">
                        <span><?= (int)$d['jumlah']; ?> × <?= number_format((float)$d['harga'], 0, ',', '.'); ?></span>
                        <span><?= number_format((float)$d['subtotal'], 0, ',', '.'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-between font-extrabold text-lg text-accent">
                <span>Total</span>
                <span class="text-primary"><?= rupiah($order['total_harga']); ?></span>
            </div>

            <div class="flex justify-between text-xs mt-1.5">
                <span class="text-accent/60">Status</span>
                <span class="font-semibold"><?= ucfirst($order['status']); ?></span>
            </div>

            <?php if (!empty($order['catatan'])): ?>
            <div class="mt-4 p-3 rounded-lg bg-soft border border-secondary/40 text-xs">
                <p class="font-semibold text-accent/70 mb-1">Catatan:</p>
                <p><?= nl2br(htmlspecialchars($order['catatan'])); ?></p>
            </div>
            <?php endif; ?>

            <div class="text-center mt-5 pt-4 border-t border-dashed border-secondary/60 text-xs text-accent/60">
                <p class="font-semibold text-accent">Terima kasih atas kunjungan Anda</p>
                <p>Selamat menikmati</p>
            </div>
        </div>

        <div class="text-center mt-5 no-print flex gap-3 justify-center">
            <a href="menu.php" class="btn btn-secondary">
                <i data-lucide="plus" class="w-4 h-4"></i> Order Lagi
            </a>
            <a href="<?= base_url('pelanggan/logout_pelanggan.php'); ?>" class="btn btn-primary">
                <i data-lucide="log-out" class="w-4 h-4"></i> Selesai
            </a>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>