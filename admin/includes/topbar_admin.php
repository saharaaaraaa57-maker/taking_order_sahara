<header class="bg-surface border-b border-secondary/40 h-16 flex items-center justify-between px-6 sticky top-0 z-30">
    <button onclick="toggleSidebar()"
            class="lg:hidden w-9 h-9 rounded-lg hover:bg-soft flex items-center justify-center"
            aria-label="Toggle sidebar">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    <div class="flex items-center gap-3 ml-auto">
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-soft border border-secondary/40 text-sm">
            <i data-lucide="user" class="w-4 h-4 text-primary"></i>
            <span class="font-medium text-accent"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin'); ?></span>
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    const sb = document.getElementById('adminSidebar');
    const ov = document.getElementById('sidebarOverlay');
    if (!sb) return;
    const isClosed = sb.classList.contains('-translate-x-full');
    if (isClosed) {
        sb.classList.remove('-translate-x-full');
        if (ov) ov.classList.remove('hidden');
    } else {
        sb.classList.add('-translate-x-full');
        if (ov) ov.classList.add('hidden');
    }
}
// Tutup sidebar otomatis saat resize ke desktop
window.addEventListener('resize', () => {
    const sb = document.getElementById('adminSidebar');
    const ov = document.getElementById('sidebarOverlay');
    if (!sb) return;
    if (window.innerWidth >= 1024) {
        sb.classList.remove('-translate-x-full');
        if (ov) ov.classList.add('hidden');
    } else {
        sb.classList.add('-translate-x-full');
        if (ov) ov.classList.add('hidden');
    }
});
</script>