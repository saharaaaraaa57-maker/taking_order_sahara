<?php
require_once '../config/koneksi.php';
requireAdmin();

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = clean($tgl_awal);
$tk = clean($tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order ASC");
$total_pendapatan = 0;
$data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total_pendapatan += $row['total_harga'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Order — <?= BRAND_NAME; ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; color: #623933; font-size: 12px; padding: 15mm; }
        .header { text-align: center; border-bottom: 3px double #623933; padding-bottom: 12px; margin-bottom: 18px; }
        .header img { height: 60px; width: auto; margin: 0 auto 8px; display: block; }
        .header h1 { font-size: 16px; letter-spacing: 2px; }
        .header p { font-size: 11px; margin-top: 2px; color: #623933; }
        h2 { text-align: center; margin: 10px 0 16px; font-size: 14px; letter-spacing: 2px; }
        .info { font-size: 12px; margin-bottom: 14px; }
        .info table { border: none; }
        .info td { padding: 2px 6px; border: none; }
        table.data { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.data th, table.data td { border: 1px solid #623933; padding: 6px 8px; }
        table.data th { background: #FFF5F7; text-align: center; font-weight: bold; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .total-box { margin-top: 14px; text-align: right; font-size: 13px; font-weight: bold; }
        .ttd { margin-top: 40px; text-align: right; font-size: 12px; }
        .ttd p { margin: 0; }
        .ttd .nama { margin-top: 60px; border-top: 1px solid #623933; display: inline-block; padding-top: 3px; min-width: 180px; text-align: center; }
        @media print {
            body { padding: 0; margin: 10mm; }
            .no-print { display: none !important; }
            @page { size: A4; margin: 15mm; }
        }
        .btn-print { position: fixed; top: 20px; right: 20px; padding: 8px 16px; background: #E0708C; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 13px; }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">Cetak</button>

    <div class="header">
        <img src="<?= asset('logo-brand.png'); ?>" alt="<?= BRAND_NAME; ?>">
        <h1><?= strtoupper(BRAND_NAME); ?></h1>
        <p><?= BRAND_TAGLINE; ?> &middot; <?= BRAND_ADDRESS; ?></p>
        <p>Telp: <?= BRAND_PHONE; ?></p>
    </div>

    <h2>LAPORAN ORDER</h2>

    <div class="info">
        <table>
            <tr><td>Periode</td><td>:</td><td><?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></td></tr>
            <tr><td>Tanggal Cetak</td><td>:</td><td><?= date('d/m/Y H:i'); ?></td></tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th style="width:130px;">Kode Order</th>
                <th>Pelanggan</th>
                <th style="width:50px;">Meja</th>
                <th style="width:100px;">Total</th>
                <th style="width:80px;">Status</th>
                <th style="width:110px;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="7" class="text-center">Tidak ada data pada periode ini.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($data as $o): ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($o['kode_order']); ?></td>
                    <td><?= htmlspecialchars($o['nama']); ?></td>
                    <td class="text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                    <td class="text-end"><?= rupiah($o['total_harga']); ?></td>
                    <td class="text-center"><?= ucfirst($o['status']); ?></td>
                    <td class="text-center"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="total-box">Total Pendapatan (Order Selesai): <?= rupiah($total_pendapatan); ?></div>

    <div class="ttd">
        <p>Kota Anda, <?= date('d/m/Y'); ?></p>
        <p>Administrator,</p>
        <p class="nama"><?= htmlspecialchars($_SESSION['admin_nama']); ?></p>
    </div>

    <script>window.onload = () => window.print();</script>
</body>
</html>