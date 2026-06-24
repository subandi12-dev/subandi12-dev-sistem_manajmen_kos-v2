<table>
<tr><th>No</th><th>Tanggal Bayar</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th><th>Jumlah</th><th>Metode</th></tr>
<?php foreach ($payments as $i => $p): ?>
<tr><td><?= $i+1 ?></td><td><?= date('d/m/Y', strtotime($p->pay_date)) ?></td><td><?= $p->tenant_name ?></td><td><?= $p->room_code ?></td><td><?= $p->month ?></td><td><?= $p->amount ?></td><td><?= $p->method ?></td></tr>
<?php endforeach; ?>
<tr><td colspan="5"><strong>Total Pemasukan</strong></td><td><strong><?= $total ?></strong></td><td></td></tr>
</table>
