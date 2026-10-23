<?php
require_once '../config/koneksi.php';
if (isAdminLoggedIn()) { header("Location: dashboard.php"); exit; }
$judul = "Login Admin — " . BRAND_NAME;
include '../includes/header.php';
?>
<main class="min-h-screen flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-md">
        <div class="card card-shadow p-8 sm:p-10">
            <div class="text-center mb-7">
                <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo mx-auto">
                <h1 class="text-xl font-bold text-accent mt-4">Login Admin</h1>
                <p class="text-sm text-accent/60 mt-1">Panel Manajemen <?= BRAND_NAME; ?></p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#FBEAEA] border border-[#E4A8A8] text-[#A32E2E] text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="w-4 h-4 text-accent/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="username" required class="input input-icon">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-accent/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="password" name="password" required class="input input-icon">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk
                </button>
            </form>

            <p class="text-center text-xs text-accent/50 mt-5">
                <a href="<?= base_url('index.php'); ?>" class="link-brand">Kembali ke Halaman Pelanggan</a>
            </p>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>