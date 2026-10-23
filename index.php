<?php
require_once 'config/koneksi.php';
if (isPelangganLoggedIn()) { header("Location: " . base_url('pelanggan/menu.php')); exit; }
$judul = "Selamat Datang — " . BRAND_NAME;
include 'includes/header.php';
?>
<main class="min-h-screen flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-5xl grid md:grid-cols-2 gap-12 items-center">

        <!-- Branding -->
        <section class="hidden md:block">
            <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo--lg mb-6">
            <h1 class="text-3xl lg:text-4xl font-extrabold leading-tight text-accent">
                Pesan langsung dari meja,<br>
                <span class="text-primary">tanpa antre.</span>
            </h1>
            <p class="mt-4 text-accent/70 leading-relaxed max-w-md">
                Isi data diri Anda, pilih menu favorit, dan nikmati hidangan yang kami siapkan dengan sepenuh hati.
            </p>

            <ul class="mt-8 space-y-3 text-sm text-accent/80">
                <li class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    Pemesanan cepat &amp; real-time
                </li>
                <li class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    Foto produk asli dari dapur kami
                </li>
                <li class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-primary"></i>
                    Struk digital setelah pesanan dikirim
                </li>
            </ul>
        </section>

        <!-- Form -->
        <section class="card card-shadow p-7 sm:p-9">
            <div class="text-center mb-6">
                <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>" class="brand-logo mx-auto mb-3">
                <h2 class="text-xl font-bold text-accent mt-4">Mulai Pesan</h2>
                <p class="text-sm text-accent/60 mt-1">Lengkapi data diri Anda</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 flex items-start gap-2 p-3 rounded-lg bg-[#FBEAEA] border border-[#E4A8A8] text-[#A32E2E] text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('proses/simpan_pelanggan.php'); ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i data-lucide="user" class="w-4 h-4 text-accent/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="nama" required placeholder="Masukkan nama Anda"
                               class="input input-icon">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1.5">Nomor Meja</label>
                    <div class="relative">
                        <i data-lucide="armchair" class="w-4 h-4 text-accent/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="no_meja" required placeholder="Contoh: A01"
                               class="input input-icon">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1.5">
                        No. HP <span class="text-accent/40 font-normal">(opsional)</span>
                    </label>
                    <div class="relative">
                        <i data-lucide="phone" class="w-4 h-4 text-accent/40 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="tel" name="no_hp" placeholder="08xxxxxxxxxx"
                               class="input input-icon">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    Mulai Pesan
                </button>
            </form>

            <p class="text-center text-xs text-accent/50 mt-5">
                Admin? <a href="<?= base_url('admin/login.php'); ?>" class="link-brand">Login di sini</a>
            </p>
        </section>
    </div>
</main>
<?php include 'includes/footer.php'; ?>