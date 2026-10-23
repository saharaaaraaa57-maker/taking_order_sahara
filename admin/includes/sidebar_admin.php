<?php
$current = basename($_SERVER['PHP_SELF']);
$items = [
    ['file' => 'dashboard.php', 'label' => 'Dashboard',  'icon' => 'layout-dashboard'],
    ['file' => 'menu.php',      'label' => 'Kelola Menu', 'icon' => 'utensils'],
    ['file' => 'order.php',     'label' => 'Data Order',  'icon' => 'receipt'],
    ['file' => 'laporan.php',   'label' => 'Laporan',     'icon' => 'bar-chart-3'],
];
?>
<!-- Overlay untuk mobile -->
<div id="sidebarOverlay"
     onclick="toggleSidebar()"
     class="hidden fixed inset-0 bg-accent/40 z-40 lg:hidden"></div>

<!-- Sidebar: static di desktop, drawer di mobile -->
<aside id="adminSidebar"
       class="fixed lg:static inset-y-0 left-0 z-50 lg:z-0
              w-64 bg-surface border-r border-secondary/40
              flex flex-col shrink-0
              -translate-x-full lg:translate-x-0
              transition-transform duration-200">

    <div class="h-16 flex items-center gap-3 px-5 border-b border-secondary/40 shrink-0">
        <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo--sm">
        <p class="text-[10px] uppercase tracking-widest text-primary font-semibold">Admin Panel</p>
    </div>

    <nav class="flex-1 overflow-y-auto p-3 space-y-1">
        <?php foreach ($items as $it): $active = ($current === $it['file']); ?>
            <a href="<?= $it['file']; ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                      <?= $active ? 'bg-primary text-white' : 'text-accent/70 hover:bg-soft hover:text-accent'; ?>">
                <i data-lucide="<?= $it['icon']; ?>" class="w-4 h-4 shrink-0"></i>
                <?= $it['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="p-3 border-t border-secondary/40 shrink-0">
        <a href="logout.php"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-[#A32E2E] hover:bg-[#FBEAEA] transition">
            <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i> Logout
        </a>
    </div>
</aside>