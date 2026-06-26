<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start;">

  <!-- LEFT: Info + Rincian -->
  <div class="data-card">
    <div class="data-card-header">
      <h5>Detail Tagihan</h5>
    </div>
    <div style="padding:22px">
      <!-- Info Row -->
      <div class="detail-grid">
        <div>
          <div class="detail-section-title">Informasi Penyewa</div>
          <div class="penyewa-info">
            <div class="penyewa-avatar"><?= strtoupper(substr($bill->tenant_name,0,1)) ?></div>
            <div>
              <div class="penyewa-name"><?= $bill->tenant_name ?></div>
              <div class="penyewa-phone"><?= $bill->phone ?></div>
            </div>
          </div>
          <div class="penyewa-room" style="margin-top:8px">Kamar <?= $bill->room_code ?></div>
        </div>
        <div>
          <div class="detail-section-title">Informasi Tagihan</div>
          <table class="info-table">
            <tr><td>Bulan Tagihan</td><td>: <?= $bill->month ?></td></tr>
            <tr><td>Tanggal Tagihan</td><td>: <?= date('d M Y', strtotime($bill->bill_date)) ?></td></tr>
            <tr><td>Jatuh Tempo</td><td>: <?= date('d M Y', strtotime($bill->due_date)) ?></td></tr>
            <tr><td>Status</td><td>: <span class="badge <?= $bill->status=='Lunas'?'badge-lunas':'badge-belum' ?>"><?= $bill->status ?></span></td></tr>
          </table>
        </div>
      </div>

      <!-- Rincian -->
      <div class="detail-section-title">Rincian Tagihan</div>
      <table class="kos-table" style="margin-bottom:0">
        <thead><tr><th>No</th><th>Deskripsi</th><th style="text-align:right">Jumlah</th></tr></thead>
        <tbody>
          <tr><td>1</td><td>Sewa Kamar</td><td style="text-align:right">Rp <?= number_format($bill->rent,0,',','.') ?></td></tr>
          <tr><td>2</td><td>Listrik</td><td style="text-align:right">Rp <?= number_format($bill->electric,0,',','.') ?></td></tr>
          <tr><td>3</td><td>Air</td><td style="text-align:right">Rp <?= number_format($bill->water,0,',','.') ?></td></tr>
          <tr><td colspan="2" style="font-weight:700">Total</td><td style="text-align:right;font-weight:700">Rp <?= number_format($bill->total,0,',','.') ?></td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- RIGHT: Total + Actions -->
  <div>
    <div class="data-card">
      <div style="padding:20px">
        <div class="detail-section-title">Total Tagihan</div>
        <div style="font-size:22px;font-weight:700;color:var(--text-dark);margin-bottom:16px">
          Rp <?= number_format($bill->total,0,',','.') ?>
        </div>
        <div style="font-size:12px;color:var(--text-light);margin-bottom:4px">Denda</div>
        <div style="font-size:14px;font-weight:600;margin-bottom:16px">Rp 0</div>
        <div style="border-top:1px solid var(--border);padding-top:14px">
          <div style="font-size:12px;color:var(--text-light);margin-bottom:4px">Total Bayar</div>
          <div style="font-size:22px;font-weight:700;color:#EF4444">Rp <?= number_format($bill->total,0,',','.') ?></div>
        </div>
      </div>
    </div>

    <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px;" class="no-print">
      <?php if ($bill->status=='Belum Lunas'): ?>
      <a href="<?= site_url('tagihan/lunas/'.$bill->id) ?>" class="btn btn-success" style="justify-content:center" onclick="return confirm('Tandai Lunas?')">
        <i class="fas fa-check-circle"></i> Tandai Lunas
      </a>
      <?php endif; ?>
      <a href="<?= site_url('tagihan/cetak/'.$bill->id) ?>" class="btn btn-outline" style="justify-content:center" target="_blank">
        <i class="fas fa-print"></i> Cetak Tagihan
      </a>
    </div>
  </div>

</div>
