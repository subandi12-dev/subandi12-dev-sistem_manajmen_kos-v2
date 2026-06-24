<div class="data-card">
  <div class="data-card-header">
    <h5>Data Penyewa</h5>
    <a href="<?= site_url('penyewa/tambah') ?>" class="btn btn-primary" style="font-size:12.5px;padding:7px 14px">
      <i class="fas fa-plus"></i> Tambah Penyewa
    </a>
  </div>
  <div class="data-card-body" style="padding-bottom:0">
    <form class="search-bar" method="GET" action="<?= site_url('penyewa') ?>">
      <div class="input-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari penyewa..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-outline" style="font-size:12px;padding:6px 12px">Cari</button>
    </form>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Nama</th><th>Kamar</th><th>No. HP</th><th>Tgl Masuk</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($tenants)): ?>
        <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--text-light)">Belum ada data penyewa</td></tr>
        <?php else: foreach ($tenants as $i => $t): ?>
        <tr>
          <td><?= $offset + $i + 1 ?></td>
          <td style="font-weight:500"><?= $t->name ?></td>
          <td><?= $t->room_code ?></td>
          <td><?= $t->phone ?></td>
          <td><?= date('d M Y', strtotime($t->start_date)) ?></td>
          <td style="display:flex;gap:5px">
            <a href="<?= site_url('penyewa/edit/'.$t->id) ?>" class="btn-icon btn-icon-edit"><i class="fas fa-edit"></i></a>
            <button class="btn-icon btn-icon-del"
              onclick="openModalHapus('<?= site_url('penyewa/hapus/'.$t->id) ?>','<?= addslashes($t->name) ?>')"
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

<!-- Modal Hapus Penyewa -->
<div id="modalHapus" class="modal-overlay" style="display:none" onclick="closeModalHapus(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-danger"><i class="fas fa-trash-alt"></i></div>
    <h5 class="modal-title">Konfirmasi Hapus</h5>
    <p class="modal-body" id="modalHapusText">Data penyewa ini akan dihapus permanen.</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalHapus()">Batal</button>
      <a id="modalHapusBtn" href="#" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</a>
    </div>
  </div>
</div>
<script>
function openModalHapus(url, label) {
  document.getElementById('modalHapusText').textContent = 'Penyewa "' + label + '" akan dihapus permanen. Lanjutkan?';
  document.getElementById('modalHapusBtn').href = url;
  document.getElementById('modalHapus').style.display = 'flex';
}
function closeModalHapus(e) {
  if (!e || e.target.id === 'modalHapus') document.getElementById('modalHapus').style.display = 'none';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeModalHapus(); });
</script>
