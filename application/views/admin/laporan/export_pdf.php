<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pembayaran</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>body{font-size:12px;} @media print{.no-print{display:none;}}</style>
</head>
<body class="p-4">
<div class="text-center mb-4">
  <h5 class="fw-bold">🏠 SISTEM MANAJEMEN KOS</h5>
  <h6>LAPORAN PEMBAYARAN</h6>
  <p class="text-muted small">Periode: <?= date('d M Y', strtotime($from)) ?> — <?= date('d M Y', strtotime($to)) ?></p>
</div>
<table class="table table-bordered table-sm">
  <thead style="background:#4f46e5;color:white;"><tr><th>No</th><th>Tgl Bayar</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th><th>Jumlah</th><th>Metode</th></tr></thead>
  <tbody>
    <?php foreach ($payments as $i => $p): ?>
    <tr><td><?= $i+1 ?></td><td><?= date('d/m/Y', strtotime($p->pay_date)) ?></td><td><?= $p->tenant_name ?></td><td><?= $p->room_code ?></td><td><?= $p->month ?></td><td>Rp <?= number_format($p->amount,0,',','.') ?></td><td><?= $p->method ?></td></tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot><tr><td colspan="5" class="text-end fw-bold">Total Pemasukan</td><td colspan="2" class="fw-bold">Rp <?= number_format($total,0,',','.') ?></td></tr></tfoot>
</table>
<div class="no-print mt-3"><button onclick="window.print()" class="btn btn-primary btn-sm">Cetak</button></div>
</body>
</html>
