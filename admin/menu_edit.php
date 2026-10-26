<?php
require_once '../config/koneksi.php';
requireAdmin();
$judul = "Edit Menu — " . BRAND_NAME;

$id = (int)($_GET['id'] ?? 0);
$q  = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id");
$m  = mysqli_fetch_assoc($q);
if (!$m) { header("Location: menu.php"); exit; }

if (isPost()) {
    $nama   = clean($_POST['nama_menu'] ?? '');
    $kat    = clean($_POST['kategori'] ?? '');
    $harga  = (float)($_POST['harga'] ?? 0);
    $desc   = clean($_POST['deskripsi'] ?? '');
    $stok   = (int)($_POST['stok'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['tersedia','habis']) ? $_POST['status'] : 'tersedia';

    if (!empty($_POST['hapus_gambar'])) {
        hapusGambarMenu($m['gambar']);
        $m['gambar'] = null;
    }
    if (!empty($_FILES['gambar']['name'])) {
        $up = uploadGambarMenu($_FILES['gambar']);
        if (!$up['success']) {
            $_SESSION['error'] = $up['error'];
            header("Location: menu_edit.php?id=$id"); exit;
        }
        hapusGambarMenu($m['gambar']);
        $m['gambar'] = $up['filename'];
    }

    $g = $m['gambar'] ? "'" . clean($m['gambar']) . "'" : "NULL";
    $sql = "UPDATE menu SET nama_menu='$nama', kategori='$kat', harga=$harga,
            deskripsi='$desc', stok=$stok, gambar=$g, status='$status' WHERE id=$id";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Menu berhasil diperbarui.";
        header("Location: menu.php"); exit;
    }
    $_SESSION['error'] = "Gagal update: " . mysqli_error($koneksi);
    header("Location: menu_edit.php?id=$id"); exit;
}

include '../includes/header.php';
?>
<div class="flex min-h-screen">
    <?php include 'includes/sidebar_admin.php'; ?>
    <div class="flex-1 min-w-0">
        <?php include 'includes/topbar_admin.php'; ?>
        <main class="p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">
                <a href="menu.php" class="inline-flex items-center gap-1 text-sm font-medium text-accent/60 hover:text-primary mb-4">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
                <h1 class="text-2xl font-extrabold text-accent mb-6">Edit Menu</h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#FBEAEA] border border-[#E4A8A8] text-[#A32E2E] text-sm">
                        <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                        <span><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="card card-shadow p-6 lg:p-8 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold mb-2">Gambar Produk</label>
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            <div class="w-32 h-32 rounded-lg bg-soft border border-dashed border-secondary/60 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($m['gambar']) && is_file(UPLOAD_DIR . $m['gambar'])): ?>
                                    <img id="gambarPreview" src="<?= UPLOAD_URL . htmlspecialchars($m['gambar']); ?>"
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img id="gambarPreview" src="" class="w-full h-full object-cover hidden">
                                    <div id="gambarPlaceholder" class="flex flex-col items-center text-accent/40">
                                        <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        <span class="text-[11px] mt-1">Preview</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="file" id="gambarInput" name="gambar" accept="image/*"
                                       class="block w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white file:font-semibold hover:file:bg-[#cf5f7b] file:cursor-pointer cursor-pointer">
                                <p class="text-xs text-accent/50">Kosongkan jika tidak ingin mengganti gambar.</p>
                                <?php if (!empty($m['gambar'])): ?>
                                    <label class="inline-flex items-center gap-2 text-sm text-[#A32E2E] cursor-pointer">
                                        <input type="checkbox" name="hapus_gambar" value="1">
                                        Hapus gambar saat ini
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold mb-1.5">Nama Menu</label>
                            <input type="text" name="nama_menu" required value="<?= htmlspecialchars($m['nama_menu']); ?>" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Kategori</label>
                            <input type="text" name="kategori" required value="<?= htmlspecialchars($m['kategori']); ?>" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Harga (Rp)</label>
                            <input type="number" name="harga" required min="0" value="<?= (float)$m['harga']; ?>" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Stok</label>
                            <input type="number" name="stok" required min="0" value="<?= (int)$m['stok']; ?>" class="input">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Status</label>
                            <select name="status" class="input">
                                <option value="tersedia" <?= $m['status']==='tersedia'?'selected':''; ?>>Tersedia</option>
                                <option value="habis" <?= $m['status']==='habis'?'selected':''; ?>>Habis</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" class="input"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i> Update
                        </button>
                        <a href="menu.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include '../includes/footer.php'; ?>