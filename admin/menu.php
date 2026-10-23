<?php
require_once '../config/koneksi.php';
requireAdmin();
$judul = "Kelola Menu — " . BRAND_NAME;

$q = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY kategori, nama_menu");
include '../includes/header.php';
include 'includes/sidebar_admin.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar_admin.php'; ?>
    <div class="flex-1 min-w-0 flex flex-col">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8 flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-accent">Kelola Menu</h1>
                    <p class="text-sm text-accent/60 mt-1">Tambah, ubah, dan atur ketersediaan menu</p>
                </div>
                <a href="menu_tambah.php" class="btn btn-primary self-start">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Menu
                </a>
            </div>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#EDF7F0] border border-[#B7DBC3] text-[#2F7A4D] text-sm">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></span>
                </div>
            <?php endif; ?>

            <div class="card card-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-16">Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($m = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($m['gambar']) && is_file(UPLOAD_DIR . $m['gambar'])): ?>
                                        <img src="<?= UPLOAD_URL . htmlspecialchars($m['gambar']); ?>"
                                             alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                                             class="w-12 h-12 rounded-lg object-cover border border-secondary/40">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-lg bg-soft border border-secondary/40 flex items-center justify-center">
                                            <i data-lucide="image-off" class="w-4 h-4 text-accent/30"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <p class="font-semibold text-accent"><?= htmlspecialchars($m['nama_menu']); ?></p>
                                    <p class="text-xs text-accent/50 line-clamp-1 max-w-xs"><?= htmlspecialchars($m['deskripsi']); ?></p>
                                </td>
                                <td>
                                    <span class="inline-flex px-2 py-0.5 rounded-md bg-soft text-accent/70 text-xs font-medium border border-secondary/40">
                                        <?= htmlspecialchars($m['kategori']); ?>
                                    </span>
                                </td>
                                <td class="font-semibold"><?= rupiah($m['harga']); ?></td>
                                <td><?= (int)$m['stok']; ?></td>
                                <td>
                                    <span class="badge badge--<?= $m['status']; ?>">
                                        <?= $m['status'] === 'tersedia' ? 'Tersedia' : 'Habis'; ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="inline-flex gap-1">
                                        <a href="menu_edit.php?id=<?= (int)$m['id']; ?>"
                                           class="w-8 h-8 rounded-lg hover:bg-soft flex items-center justify-center text-accent/70"
                                           title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        <a href="menu_hapus.php?id=<?= (int)$m['id']; ?>"
                                           onclick="return confirm('Hapus menu ini?')"
                                           class="w-8 h-8 rounded-lg hover:bg-[#FBEAEA] text-[#A32E2E] flex items-center justify-center"
                                           title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($q) === 0): ?>
                            <tr><td colspan="7" class="text-center py-8 text-accent/50">Belum ada menu.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>