<?php
require_once '../config/koneksi.php';
requireAdmin();
$judul = "Dashboard Admin — " . BRAND_NAME;

$total_menu       = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM menu"))['j'];
$total_order      = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM orders"))['j'];
$total_pending    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS j FROM orders WHERE status='pending'"))['j'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) AS j FROM orders WHERE status='selesai'"))['j'];
$recent = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                                  JOIN pelanggan p ON o.pelanggan_id = p.id
                                  ORDER BY o.waktu_order DESC LIMIT 5");

include '../includes/header.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar_admin.php'; ?>
    <div class="flex-1 min-w-0 flex flex-col">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8 flex-1">
            <div class="mb-6">
                <h1 class="text-2xl font-extrabold text-accent">Dashboard</h1>
                <p class="text-sm text-accent/60 mt-1">Ringkasan performa cafe hari ini</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
                <?php
                $cards = [
                    ['label' => 'Total Menu',   'value' => $total_menu,                 'icon' => 'utensils',   'is_money' => false],
                    ['label' => 'Total Order',  'value' => $total_order,                'icon' => 'receipt',    'is_money' => false],
                    ['label' => 'Pending',      'value' => $total_pending,              'icon' => 'clock',      'is_money' => false],
                    ['label' => 'Pendapatan',   'value' => $total_pendapatan,           'icon' => 'wallet',     'is_money' => true],
                ];
                foreach ($cards as $c):
                ?>
                <div class="card card-shadow p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="w-9 h-9 rounded-lg bg-soft flex items-center justify-center">
                            <i data-lucide="<?= $c['icon']; ?>" class="w-4 h-4 text-primary"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-accent leading-tight">
                        <?= $c['is_money'] ? rupiah($c['value']) : (int)$c['value']; ?>
                    </p>
                    <p class="text-xs text-accent/50 mt-1"><?= $c['label']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="card card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary/40 flex items-center justify-between">
                    <h2 class="font-bold text-accent text-sm">Pesanan Terbaru</h2>
                    <a href="order.php" class="text-xs font-semibold text-primary hover:underline inline-flex items-center gap-1">
                        Lihat semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = mysqli_fetch_assoc($recent)): ?>
                            <tr>
                                <td class="font-mono text-xs text-accent/80"><?= htmlspecialchars($o['kode_order']); ?></td>
                                <td><?= htmlspecialchars($o['nama']); ?></td>
                                <td><?= htmlspecialchars($o['no_meja']); ?></td>
                                <td class="font-semibold"><?= rupiah($o['total_harga']); ?></td>
                                <td>
                                    <span class="badge badge--<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span>
                                </td>
                                <td class="text-accent/60 text-xs"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($recent) === 0): ?>
                            <tr><td colspan="6" class="text-center py-8 text-accent/50">Belum ada pesanan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>