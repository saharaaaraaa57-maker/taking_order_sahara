<?php
$keranjangCount = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<header class="bg-surface border-b border-secondary/40 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

        <a href="<?= base_url('pelanggan/menu.php'); ?>" class="flex items-center gap-3">
            <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo">
        </a>

        <?php if (isset($_SESSION['pelanggan_id'])): ?>
        <div class="flex items-center gap-2">
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-soft border border-secondary/40 text-sm">
                <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                <span class="font-medium text-accent"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
                <span class="text-accent/30">•</span>
                <span class="text-accent/70 text-xs">Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></span>
            </div>

            <a href="<?= base_url('pelanggan/keranjang.php'); ?>"
               class="relative inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-[#cf5f7b] transition">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Keranjang</span>
                <?php if ($keranjangCount > 0): ?>
                    <span class="absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] px-1 rounded-full bg-accent text-white text-[10px] font-bold flex items-center justify-center">
                        <?= $keranjangCount; ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="<?= base_url('pelanggan/logout_pelanggan.php'); ?>"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-accent/15 text-sm font-medium text-accent hover:bg-soft transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Selesai</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</header>