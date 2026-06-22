<div class="data-card">
  <div class="data-card-header">
    <h5>Data Kamar</h5>
    <a href="<?= site_url('kamar/tambah') ?>" class="btn btn-primary btn-sm" style="font-size:12.5px;padding:7px 14px">
      <i class="fas fa-plus"></i> Tambah Kamar
    </a>
  </div>
  <div class="data-card-body" style="padding-bottom:0">
    <form class="search-bar" method="GET" action="<?= site_url('kamar') ?>">
      <div class="input-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Cari kamar..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <select name="type" class="form-select-sm" onchange="this.form.submit()">
        <option value="">Semua Tipe</option>
        <option value="Standar" <?= $type=='Standar'?'selected':'' ?>>Standar</option>
        <option value="VIP" <?= $type=='VIP'?'selected':'' ?>>VIP</option>
      </select>
    </form>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Kode Kamar</th><th>Tipe</th><th>Harga / Bulan</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($rooms)): ?>
        <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--text-light)">Belum ada data kamar</td></tr>
        <?php else: foreach ($rooms as $i => $r): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td style="font-weight:600"><?= $r->room_code ?></td>
          <td><span class="badge <?= $r->type=='VIP'?'badge-vip':'badge-standar' ?>"><?= $r->type ?></span></td>
          <td>Rp <?= number_format($r->price,0,',','.') ?></td>
          <td><span class="badge <?= $r->status=='Terisi'?'badge-terisi':'badge-kosong' ?>"><?= $r->status ?></span></td>
          <td style="display:flex;gap:5px;align-items:center">
            <a href="<?= site_url('kamar/edit/'.$r->id) ?>" class="btn-icon btn-icon-edit"><i class="fas fa-edit"></i></a>
            <button class="btn-icon btn-icon-del"
              onclick="openModalHapus('<?= site_url('kamar/hapus/'.$r->id) ?>','Kamar <?= addslashes($r->room_code) ?>')"
              title="Hapus"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">
    <span class="pagination-info">Menampilkan 1 – <?= count($rooms) ?> dari <?= count($rooms) ?> data</span>
  </div>
</div>

<!-- Modal Hapus Kamar -->
<div id="modalHapus" class="modal-overlay" style="display:none" onclick="closeModalHapus(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-danger"><i class="fas fa-trash-alt"></i></div>
    <h5 class="modal-title">Konfirmasi Hapus</h5>
    <p class="modal-body" id="modalHapusText">Kamar ini akan dihapus permanen.</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalHapus()">Batal</button>
      <a id="modalHapusBtn" href="#" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</a>
    </div>
  </div>
</div>
<script>
function openModalHapus(url, label) {
  document.getElementById('modalHapusText').textContent = '"'+label+'" akan dihapus permanen. Lanjutkan?';
  document.getElementById('modalHapusBtn').href = url;
  document.getElementById('modalHapus').style.display = 'flex';
}
function closeModalHapus(e) {
  if (!e || e.target.id==='modalHapus') document.getElementById('modalHapus').style.display='none';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeModalHapus(); });
</script>
