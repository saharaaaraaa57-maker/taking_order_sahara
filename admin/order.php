<?php
require_once '../config/koneksi.php';
requireAdmin();
$judul = "Data Order — " . BRAND_NAME;
include '../includes/header.php';

$filter = $_GET['status'] ?? '';
$where  = "";
if ($filter !== '') {
    $f = clean($filter);
    $where = "WHERE o.status = '$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             $where ORDER BY o.waktu_order DESC");

$tabs = [
    ''           => 'Semua',
    'pending'    => 'Pending',
    'diproses'   => 'Diproses',
    'selesai'    => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
];
?>
<div class="flex min-h-screen">
       <?php include 'includes/sidebar_admin.php'; ?>
    <div class="flex-1 min-w-0">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-extrabold text-accent">Data Order</h1>
                <p class="text-sm text-accent/60 mt-1">Kelola semua pesanan pelanggan</p>
            </div>

            <div class="flex flex-wrap gap-2 mb-5">
                <?php foreach ($tabs as $key => $label): $active = ($filter === $key); ?>
                    <a href="order.php<?= $key ? '?status=' . $key : ''; ?>"
                       class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition
                              <?= $active
                                    ? 'bg-primary text-white'
                                    : 'bg-surface border border-secondary/40 text-accent/70 hover:border-primary'; ?>">
                        <?= $label; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="card card-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Meja</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td class="font-mono text-xs text-accent/80"><?= htmlspecialchars($o['kode_order']); ?></td>
                                <td><?= htmlspecialchars($o['nama']); ?></td>
                                <td><?= htmlspecialchars($o['no_meja']); ?></td>
                                <td class="font-semibold"><?= rupiah($o['total_harga']); ?></td>
                                <td><span class="badge badge--<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span></td>
                                <td class="text-accent/60 text-xs"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                                <td class="text-right">
                                    <a href="order_detail.php?id=<?= (int)$o['id']; ?>"
                                       class="w-8 h-8 rounded-lg hover:bg-soft text-accent/70 inline-flex items-center justify-center">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($q) === 0): ?>
                                <tr><td colspan="7" class="text-center py-8 text-accent/50">Tidak ada order pada filter ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>