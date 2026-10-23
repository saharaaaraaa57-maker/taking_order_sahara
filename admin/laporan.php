<?php
require_once '../config/koneksi.php';
requireAdmin();
$judul = "Laporan Order — " . BRAND_NAME;
include '../includes/header.php';
include 'includes/sidebar_admin.php';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = clean($tgl_awal);
$tk = clean($tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order DESC");
$total_pendapatan = 0;
$data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total_pendapatan += $row['total_harga'];
}
?>
<div class="flex min-h-screen">
    <div class="flex-1 min-w-0">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-accent">Laporan Order</h1>
                    <p class="text-sm text-accent/60 mt-1">Filter berdasarkan rentang tanggal</p>
                </div>
                <a href="cetak_laporan.php?tgl_awal=<?= urlencode($tgl_awal); ?>&tgl_akhir=<?= urlencode($tgl_akhir); ?>"
                   target="_blank" class="btn btn-secondary self-start">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak
                </a>
            </div>

            <form method="GET" class="card card-shadow p-5 mb-5 grid sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>" class="input">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>" class="input">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" class="w-4 h-4"></i> Filter
                </button>
            </form>

            <div class="card card-shadow p-5 mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-accent/50 font-semibold">Total Pendapatan (Order Selesai)</p>
                    <p class="text-2xl font-extrabold text-accent mt-1"><?= rupiah($total_pendapatan); ?></p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-soft flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-primary"></i>
                </div>
            </div>

            <div class="card card-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Meja</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $o): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="font-mono text-xs text-accent/80"><?= htmlspecialchars($o['kode_order']); ?></td>
                                <td><?= htmlspecialchars($o['nama']); ?></td>
                                <td><?= htmlspecialchars($o['no_meja']); ?></td>
                                <td class="font-semibold"><?= rupiah($o['total_harga']); ?></td>
                                <td><span class="badge badge--<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span></td>
                                <td class="text-accent/60 text-xs"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($data)): ?>
                            <tr><td colspan="7" class="text-center py-8 text-accent/50">Tidak ada data pada periode ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>