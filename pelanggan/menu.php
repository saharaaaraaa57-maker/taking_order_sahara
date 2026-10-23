<?php
require_once '../config/koneksi.php';
requirePelanggan();
$judul = "Menu — " . BRAND_NAME;
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = $_GET['kategori'] ?? '';
$where = "WHERE status = 'tersedia'";
if ($kategori_filter !== '') {
    $kf = clean($kategori_filter);
    $where .= " AND kategori = '$kf'";
}
$query = mysqli_query($koneksi, "SELECT * FROM menu $where ORDER BY kategori, nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>
<main class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-accent">Menu</h1>
            <p class="text-sm text-accent/60 mt-1">Pilih menu favorit, pesan langsung dari meja.</p>
        </div>
        <a href="keranjang.php" class="btn btn-primary self-start">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Keranjang
        </a>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#EDF7F0] border border-[#B7DBC3] text-[#2F7A4D] text-sm">
            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0"></i>
            <span><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="menu.php"
           class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition
                  <?= $kategori_filter===''
                        ? 'bg-primary text-white'
                        : 'bg-surface border border-secondary/40 text-accent/70 hover:border-primary'; ?>">
            Semua
        </a>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
            <a href="menu.php?kategori=<?= urlencode($k['kategori']); ?>"
               class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition
                      <?= $kategori_filter===$k['kategori']
                            ? 'bg-primary text-white'
                            : 'bg-surface border border-secondary/40 text-accent/70 hover:border-primary'; ?>">
                <?= htmlspecialchars($k['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php if (mysqli_num_rows($query) === 0): ?>
            <div class="col-span-full card card-shadow p-8 text-center text-accent/60">
                Belum ada menu tersedia untuk kategori ini.
            </div>
        <?php endif; ?>

        <?php while ($m = mysqli_fetch_assoc($query)): ?>
            <?php $hasImg = !empty($m['gambar']) && is_file(UPLOAD_DIR . $m['gambar']); ?>
            <article class="card card-shadow overflow-hidden flex flex-col">
                <div class="relative aspect-[4/3] bg-soft overflow-hidden">
                    <?php if ($hasImg): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($m['gambar']); ?>"
                             alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center text-accent/30">
                            <i data-lucide="image" class="w-8 h-8"></i>
                            <span class="text-[11px] mt-1">No image</span>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 px-2 py-0.5 rounded-md bg-surface/95 text-accent text-[11px] font-medium border border-secondary/40">
                        <?= htmlspecialchars($m['kategori']); ?>
                    </span>
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-accent line-clamp-1 text-sm"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                    <p class="text-xs text-accent/60 line-clamp-2 mt-1 flex-1"><?= htmlspecialchars($m['deskripsi']); ?></p>
                    <div class="mt-3">
                        <p class="text-base font-extrabold text-primary"><?= rupiah($m['harga']); ?></p>
                        <p class="text-[11px] text-accent/50">Stok: <?= (int)$m['stok']; ?></p>
                    </div>

                    <form action="../proses/tambah_keranjang.php" method="POST" class="mt-3 flex gap-2">
                        <input type="hidden" name="menu_id" value="<?= (int)$m['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                               class="w-16 px-2 py-1.5 rounded-lg border border-secondary/60 text-sm text-center focus:border-primary outline-none">
                        <button type="submit" class="btn btn-primary flex-1 text-xs px-3 py-1.5">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah
                        </button>
                    </form>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>
<?php include '../includes/footer.php'; ?>