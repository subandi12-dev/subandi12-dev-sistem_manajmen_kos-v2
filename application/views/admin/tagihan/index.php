<div class="data-card">
  <div class="data-card-header">
    <h5>Tagihan</h5>
    <a href="<?= site_url('tagihan/buat') ?>" class="btn btn-primary" style="font-size:12.5px;padding:7px 14px">
      <i class="fas fa-plus"></i> Buat Tagihan
    </a>
  </div>
  <div class="data-card-body" style="padding-bottom:0">
    <form class="search-bar" method="GET" action="<?= site_url('tagihan') ?>">
      <div class="input-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari tagihan..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <select name="status" class="form-select-sm" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="Lunas" <?= $status=='Lunas'?'selected':'' ?>>Lunas</option>
        <option value="Belum Lunas" <?= $status=='Belum Lunas'?'selected':'' ?>>Belum Lunas</option>
      </select>
      <button type="submit" class="btn btn-outline" style="font-size:12px;padding:6px 12px">Cari</button>
    </form>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th><th>Tgl Tagihan</th><th>Jatuh Tempo</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($bills)): ?>
        <tr><td colspan="9" style="text-align:center;padding:28px;color:var(--text-light)">Belum ada tagihan</td></tr>
        <?php else:
          $today = date('Y-m-d');
          foreach ($bills as $i => $b):
            $isOverdue = ($b->status=='Belum Lunas' && $b->due_date < $today);
        ?>
        <tr class="<?= $isOverdue ? 'row-overdue' : '' ?>">
          <td><?= $offset + $i + 1 ?></td>
          <td style="font-weight:500"><?= $b->tenant_name ?></td>
          <td><?= $b->room_code ?></td>
          <td><?= $b->month ?></td>
          <td><?= date('d M Y', strtotime($b->bill_date)) ?></td>
          <td>
            <?= date('d M Y', strtotime($b->due_date)) ?>
            <?php if ($isOverdue): ?>
            <span class="badge badge-overdue" style="margin-left:4px;font-size:10px">Terlambat</span>
            <?php endif; ?>
          </td>
          <td>Rp <?= number_format($b->total,0,',','.') ?></td>
          <td><span class="badge <?= $b->status=='Lunas'?'badge-lunas':'badge-belum' ?>"><?= $b->status ?></span></td>
          <td style="display:flex;gap:5px">
            <a href="<?= site_url('tagihan/detail/'.$b->id) ?>" class="btn-icon btn-icon-view"><i class="fas fa-eye"></i></a>
            <?php if ($b->status=='Belum Lunas'): ?>
            <a href="<?= site_url('transfer/form/'.$b->id) ?>" class="btn-icon btn-icon-transfer" title="Upload Bukti Transfer"><i class="fas fa-university"></i></a>
            <?php endif; ?>
            <?php if ($b->status=='Belum Lunas'): ?>
            <button class="btn-icon btn-icon-check"
              onclick="openModalLunas(<?= $b->id ?>,'<?= addslashes($b->tenant_name) ?>','<?= $b->month ?>')"
              title="Tandai Lunas"><i class="fas fa-check"></i></button>
            <?php endif; ?>
            <button class="btn-icon btn-icon-del"
              onclick="openModalHapus('<?= site_url('tagihan/hapus/'.$b->id) ?>','tagihan <?= addslashes($b->tenant_name) ?>')"
              title="Hapus"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <!-- PAGINATION REAL -->
  <div class="pagination-wrap">
    <?php
      $from = $total > 0 ? $offset + 1 : 0;
      $to   = min($offset + $per_page, $total);
    ?>
    <span class="pagination-info">Menampilkan <?= $from ?> – <?= $to ?> dari <?= $total ?> data</span>
    <?php if ($total_pages > 1): ?>
    <div class="pagination-btns">
      <?php if ($current_page > 1): ?>
      <a href="<?= $base_url.($current_page-1) ?>" class="page-btn-arrow"><i class="fas fa-chevron-left"></i></a>
      <?php else: ?>
      <button class="page-btn-arrow" disabled><i class="fas fa-chevron-left"></i></button>
      <?php endif; ?>

      <?php
        $start = max(1, $current_page - 2);
        $end   = min($total_pages, $current_page + 2);
        if ($start > 1) echo '<span class="page-ellipsis">…</span>';
        for ($p = $start; $p <= $end; $p++):
      ?>
      <a href="<?= $base_url.$p ?>" class="page-btn <?= $p==$current_page?'active':'' ?>"><?= $p ?></a>
      <?php endfor;
        if ($end < $total_pages) echo '<span class="page-ellipsis">…</span>';
      ?>

      <?php if ($current_page < $total_pages): ?>
      <a href="<?= $base_url.($current_page+1) ?>" class="page-btn-arrow"><i class="fas fa-chevron-right"></i></a>
      <?php else: ?>
      <button class="page-btn-arrow" disabled><i class="fas fa-chevron-right"></i></button>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modal Tandai Lunas -->
<div id="modalLunas" class="modal-overlay" style="display:none" onclick="closeModalLunas(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-success"><i class="fas fa-check-circle"></i></div>
    <h5 class="modal-title">Tandai Lunas?</h5>
    <p class="modal-body" id="modalLunasText"></p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalLunas()">Batal</button>
      <a id="modalLunasBtn" href="#" class="btn btn-primary"><i class="fas fa-check"></i> Ya, Tandai Lunas</a>
    </div>
  </div>
</div>

<!-- Modal Hapus -->
<div id="modalHapus" class="modal-overlay" style="display:none" onclick="closeModalHapus(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-danger"><i class="fas fa-trash-alt"></i></div>
    <h5 class="modal-title">Konfirmasi Hapus</h5>
    <p class="modal-body" id="modalHapusText"></p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalHapus()">Batal</button>
      <a id="modalHapusBtn" href="#" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</a>
    </div>
  </div>
</div>

<script>
function openModalLunas(id, name, month) {
  document.getElementById('modalLunasText').textContent = 'Tandai tagihan "'+name+'" bulan '+month+' sebagai LUNAS?';
  document.getElementById('modalLunasBtn').href = '<?= site_url("tagihan/lunas/") ?>'+id;
  document.getElementById('modalLunas').style.display = 'flex';
}
function closeModalLunas(e) {
  if (!e || e.target.id==='modalLunas') document.getElementById('modalLunas').style.display='none';
}
function openModalHapus(url, label) {
  document.getElementById('modalHapusText').textContent = 'Data "'+label+'" akan dihapus permanen. Lanjutkan?';
  document.getElementById('modalHapusBtn').href = url;
  document.getElementById('modalHapus').style.display = 'flex';
}
function closeModalHapus(e) {
  if (!e || e.target.id==='modalHapus') document.getElementById('modalHapus').style.display='none';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeModalLunas(); closeModalHapus(); } });
</script>
