<?php
require_once '../config/koneksi.php';
requirePelanggan();
$judul = "Keranjang — " . BRAND_NAME;
include '../includes/header.php';
include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>
<main class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-accent mb-6">Keranjang</h1>

    <?php if (empty($keranjang)): ?>
        <div class="card card-shadow p-10 text-center">
            <div class="w-14 h-14 mx-auto rounded-lg bg-soft flex items-center justify-center mb-4">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-accent/40"></i>
            </div>
            <p class="text-accent/70 mb-4 text-sm">Keranjang masih kosong.</p>
            <a href="menu.php" class="btn btn-primary">
                <i data-lucide="utensils" class="w-4 h-4"></i> Lihat Menu
            </a>
        </div>
    <?php else: ?>
        <div class="card card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">Gambar</th>
                            <th>Menu</th>
                            <th class="text-right">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keranjang as $mid => $jml):
                            $mid = (int)$mid;
                            $mq = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $mid");
                            $m  = mysqli_fetch_assoc($mq);
                            if (!$m) continue;
                            $sub = $m['harga'] * $jml;
                            $total += $sub;
                            $hasImg = !empty($m['gambar']) && is_file(UPLOAD_DIR . $m['gambar']);
                        ?>
                        <tr>
                            <td>
                                <?php if ($hasImg): ?>
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($m['gambar']); ?>"
                                         alt="" class="w-12 h-12 rounded-lg object-cover border border-secondary/40">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-lg bg-soft border border-secondary/40 flex items-center justify-center">
                                        <i data-lucide="image" class="w-4 h-4 text-accent/30"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="font-semibold"><?= htmlspecialchars($m['nama_menu']); ?></td>
                            <td class="text-right"><?= rupiah($m['harga']); ?></td>
                            <td class="text-center">
                                <form action="../proses/update_keranjang.php" method="POST" class="inline-flex gap-1">
                                    <input type="hidden" name="menu_id" value="<?= $mid; ?>">
                                    <input type="number" name="jumlah" value="<?= (int)$jml; ?>" min="1" max="<?= (int)$m['stok']; ?>"
                                           class="w-16 px-2 py-1 rounded-lg border border-secondary/60 text-sm text-center focus:border-primary outline-none">
                                    <button type="submit" class="w-8 h-8 rounded-lg hover:bg-soft text-accent/70 flex items-center justify-center">
                                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-right font-semibold"><?= rupiah($sub); ?></td>
                            <td class="text-center">
                                <a href="../proses/hapus_keranjang.php?id=<?= $mid; ?>"
                                   class="w-8 h-8 rounded-lg hover:bg-[#FBEAEA] text-[#A32E2E] inline-flex items-center justify-center">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-soft">
                        <tr>
                            <th colspan="4" class="px-5 py-4 text-right font-bold text-sm">Total</th>
                            <th class="px-5 py-4 text-right text-primary text-lg font-extrabold"><?= rupiah($total); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="menu.php" class="btn btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Lanjut Pesan
            </a>
            <a href="checkout.php" class="btn btn-primary">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i> Checkout
            </a>
        </div>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>