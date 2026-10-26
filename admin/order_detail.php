<?php
require_once '../config/koneksi.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);

if (isPost()) {
    $status = $_POST['status'] ?? 'pending';
    if (in_array($status, ['pending','diproses','selesai','dibatalkan'])) {
        $st = clean($status);
        mysqli_query($koneksi, "UPDATE orders SET status='$st' WHERE id=$id");
        $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    }
    header("Location: order_detail.php?id=$id"); exit;
}

$judul = "Detail Order — " . BRAND_NAME;
include '../includes/header.php';

$q = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE o.id = $id");
$o = mysqli_fetch_assoc($q);
if (!$o) { header("Location: order.php"); exit; }

$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $id");
?>
<div class="flex min-h-screen">
       <?php include 'includes/sidebar_admin.php'; ?>
    <div class="flex-1 min-w-0">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8">
            <a href="order.php" class="inline-flex items-center gap-1 text-sm font-medium text-accent/60 hover:text-primary mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>

            <div class="mb-6">
                <h1 class="text-2xl font-extrabold text-accent">Detail Order</h1>
                <p class="font-mono text-sm text-primary mt-1"><?= htmlspecialchars($o['kode_order']); ?></p>
            </div>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#EDF7F0] border border-[#B7DBC3] text-[#2F7A4D] text-sm">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></span>
                </div>
            <?php endif; ?>

            <div class="grid lg:grid-cols-5 gap-5">
                <div class="lg:col-span-2 space-y-5">
                    <div class="card card-shadow p-6">
                        <h2 class="font-bold text-accent text-sm mb-4">Informasi Pelanggan</h2>
                        <dl class="space-y-2.5 text-sm">
                            <div class="flex justify-between"><dt class="text-accent/60">Nama</dt><dd class="font-semibold"><?= htmlspecialchars($o['nama']); ?></dd></div>
                            <div class="flex justify-between"><dt class="text-accent/60">No. HP</dt><dd class="font-semibold"><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></dd></div>
                            <div class="flex justify-between"><dt class="text-accent/60">No. Meja</dt><dd class="font-semibold"><?= htmlspecialchars($o['no_meja']); ?></dd></div>
                            <div class="flex justify-between"><dt class="text-accent/60">Waktu</dt><dd class="font-semibold"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></dd></div>
                        </dl>
                        <?php if (!empty($o['catatan'])): ?>
                            <div class="mt-4 p-3 rounded-lg bg-soft border border-secondary/40 text-sm">
                                <p class="text-xs font-semibold text-accent/60 mb-1">Catatan:</p>
                                <p><?= nl2br(htmlspecialchars($o['catatan'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card card-shadow p-6">
                        <h2 class="font-bold text-accent text-sm mb-4">Update Status</h2>
                        <form method="POST" class="space-y-3">
                            <select name="status" class="input">
                                <?php foreach (['pending','diproses','selesai','dibatalkan'] as $s): ?>
                                    <option value="<?= $s; ?>" <?= $o['status']===$s?'selected':''; ?>><?= ucfirst($s); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary w-full">
                                <i data-lucide="save" class="w-4 h-4"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <div class="card card-shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-secondary/40">
                            <h2 class="font-bold text-accent text-sm">Rincian Item</h2>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['nama_menu']); ?></td>
                                    <td class="text-right"><?= rupiah($d['harga']); ?></td>
                                    <td class="text-center"><?= (int)$d['jumlah']; ?></td>
                                    <td class="text-right font-semibold"><?= rupiah($d['subtotal']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="bg-soft">
                                <tr>
                                    <th colspan="3" class="px-5 py-4 text-right font-bold text-sm">Total</th>
                                    <th class="px-5 py-4 text-right text-primary text-lg font-extrabold"><?= rupiah($o['total_harga']); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>