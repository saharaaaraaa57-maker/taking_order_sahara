<footer class="bg-surface border-t border-secondary/40 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo--sm">
            <div class="leading-tight">
                <p class="text-sm font-bold text-accent"><?= BRAND_NAME; ?></p>
                <p class="text-[11px] text-accent/50"><?= BRAND_TAGLINE; ?></p>
            </div>
        </div>
        <p class="text-xs text-accent/60 text-center md:text-right">
            &copy; <?= date('Y'); ?> <?= BRAND_NAME; ?>. Sistem Pemesanan Berbasis Web.
        </p>
    </div>
</footer>

<script>if (window.lucide) lucide.createIcons();</script>
<script src="<?= asset('js/app.js'); ?>"></script>
</body>
</html>