<div class="data-card">
  <div class="data-card-header">
    <h5>Pembayaran</h5>
    <a href="<?= site_url('pembayaran/tambah') ?>" class="btn btn-primary" style="font-size:12.5px;padding:7px 14px">
      <i class="fas fa-plus"></i> Tambah Pembayaran
    </a>
  </div>
  <div class="data-card-body" style="padding-bottom:0">
    <form class="search-bar" method="GET">
      <div class="input-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari pembayaran..." value="<?= htmlspecialchars($search) ?>">
      </div>
    </form>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Penyewa</th><th>Bulan</th><th>Kamar</th><th>Tanggal Bayar</th><th>Jumlah</th><th>Metode</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($payments)): ?>
        <tr><td colspan="8" style="text-align:center;padding:28px;color:var(--text-light)">Belum ada data pembayaran</td></tr>
        <?php else: foreach ($payments as $i => $p): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td style="font-weight:500"><?= $p->tenant_name ?></td>
          <td><?= $p->month ?></td>
          <td><?= $p->room_code ?></td>
          <td><?= date('d M Y', strtotime($p->pay_date)) ?></td>
          <td>Rp <?= number_format($p->amount,0,',','.') ?></td>
          <td><span class="badge <?= $p->method=='Transfer'?'badge-transfer':'badge-cash' ?>"><?= $p->method ?></span></td>
          <td>
            <a href="<?= site_url('pembayaran/hapus/'.$p->id) ?>" class="btn-icon btn-icon-del" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">
    <span class="pagination-info">Menampilkan 1 – <?= count($payments) ?> dari <?= count($payments) ?> data</span>
    <div class="pagination-btns">
      <button class="page-btn-arrow"><i class="fas fa-chevron-left"></i></button>
      <button class="page-btn active">1</button>
      <button class="page-btn-arrow"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</div>
