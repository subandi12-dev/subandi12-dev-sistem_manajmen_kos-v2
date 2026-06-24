<div class="data-card">
  <div class="data-card-header">
    <h5>Laporan Pembayaran</h5>
  </div>
  <div class="data-card-body">
    <form class="laporan-filter" method="GET" action="<?= site_url('laporan') ?>">
      <div class="filter-group">
        <label class="filter-label">Dari Tanggal</label>
        <div class="filter-input-wrap">
          <input type="date" name="from" value="<?= $from ?>">
          <i class="fas fa-calendar-alt cal-icon"></i>
        </div>
      </div>
      <div class="filter-group">
        <label class="filter-label">Sampai Tanggal</label>
        <div class="filter-input-wrap">
          <input type="date" name="to" value="<?= $to ?>">
          <i class="fas fa-calendar-alt cal-icon"></i>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="align-self:flex-end"><i class="fas fa-search"></i> Tampilkan</button>
      <?php if (!empty($payments)): ?>
      <a href="<?= site_url('laporan/export_pdf?from='.$from.'&to='.$to) ?>" target="_blank" class="btn btn-pdf" style="align-self:flex-end"><i class="fas fa-file-pdf"></i> Export PDF</a>
      <a href="<?= site_url('laporan/export_excel?from='.$from.'&to='.$to) ?>" class="btn btn-excel" style="align-self:flex-end"><i class="fas fa-file-excel"></i> Export Excel</a>
      <?php endif; ?>
    </form>
  </div>

  <?php if (!empty($payments)): ?>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Tanggal Bayar</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th><th>Jumlah</th><th>Metode</th></tr>
      </thead>
      <tbody>
        <?php foreach ($payments as $i => $p): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= date('d M Y', strtotime($p->pay_date)) ?></td>
          <td style="font-weight:500"><?= $p->tenant_name ?></td>
          <td><?= $p->room_code ?></td>
          <td><?= $p->month ?></td>
          <td>Rp <?= number_format($p->amount,0,',','.') ?></td>
          <td><span class="badge <?= $p->method=='Transfer'?'badge-transfer':'badge-cash' ?>"><?= $p->method ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr style="background:#F8FAFC;">
          <td colspan="5" style="text-align:right;font-weight:700;padding:12px 14px">Total Pemasukan</td>
          <td colspan="2" style="font-weight:700;padding:12px 14px;color:var(--text-dark)">Rp <?= number_format($total,0,',','.') ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php elseif ($this->input->get('from')): ?>
  <div style="text-align:center;padding:40px;color:var(--text-light)">
    <i class="fas fa-inbox" style="font-size:28px;margin-bottom:10px;display:block"></i>
    Tidak ada data pada rentang tanggal tersebut
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:40px;color:var(--text-light)">
    <i class="fas fa-calendar-alt" style="font-size:28px;margin-bottom:10px;display:block"></i>
    Pilih rentang tanggal dan klik Tampilkan
  </div>
  <?php endif; ?>
</div>
