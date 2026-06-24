<div class="data-card">
  <div class="data-card-header">
    <h5>
      Verifikasi Transfer
      <?php if ($total_menunggu > 0): ?>
      <span class="badge-count"><?= $total_menunggu ?> menunggu</span>
      <?php endif; ?>
    </h5>
  </div>
  <div class="data-card-body" style="padding-bottom:0">
    <div class="filter-tabs">
      <a href="<?= site_url('transfer/verifikasi') ?>" class="filter-tab <?= $filter===''?'active':'' ?>">Semua</a>
      <a href="<?= site_url('transfer/verifikasi?status=Menunggu') ?>" class="filter-tab <?= $filter==='Menunggu'?'active':'' ?>">
        <i class="fas fa-clock"></i> Menunggu
      </a>
      <a href="<?= site_url('transfer/verifikasi?status=Dikonfirmasi') ?>" class="filter-tab <?= $filter==='Dikonfirmasi'?'active':'' ?>">
        <i class="fas fa-check-circle"></i> Dikonfirmasi
      </a>
      <a href="<?= site_url('transfer/verifikasi?status=Ditolak') ?>" class="filter-tab <?= $filter==='Ditolak'?'active':'' ?>">
        <i class="fas fa-times-circle"></i> Ditolak
      </a>
    </div>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr>
          <th>No</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th>
          <th>Bank</th><th>Nominal</th><th>Tgl Transfer</th>
          <th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($list)): ?>
        <tr><td colspan="9" style="text-align:center;padding:32px;color:var(--text-light)">Belum ada data transfer</td></tr>
        <?php else: foreach ($list as $i => $t): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td style="font-weight:500"><?= $t->tenant_name ?></td>
          <td><?= $t->room_code ?></td>
          <td><?= $t->month ?></td>
          <td>
            <span class="bank-badge bank-badge-<?= strtolower($t->bank_name) ?>"><?= $t->bank_name ?></span>
          </td>
          <td>Rp <?= number_format($t->amount,0,',','.') ?></td>
          <td><?= date('d M Y', strtotime($t->transfer_date)) ?></td>
          <td>
            <?php
              $stclass = ['Menunggu'=>'badge-menunggu','Dikonfirmasi'=>'badge-lunas','Ditolak'=>'badge-ditolak'];
              $sticon  = ['Menunggu'=>'fa-clock','Dikonfirmasi'=>'fa-check-circle','Ditolak'=>'fa-times-circle'];
            ?>
            <span class="badge <?= $stclass[$t->status] ?? '' ?>">
              <i class="fas <?= $sticon[$t->status] ?? '' ?>"></i> <?= $t->status ?>
            </span>
          </td>
          <td>
            <a href="<?= site_url('transfer/detail/'.$t->id) ?>" class="btn-icon btn-icon-view" title="Detail"><i class="fas fa-eye"></i></a>
            <?php if ($t->status === 'Menunggu'): ?>
            <button class="btn-icon btn-icon-check"
              onclick="openModalKonfirmasi(<?= $t->id ?>,'<?= addslashes($t->tenant_name) ?>','<?= $t->month ?>')"
              title="Konfirmasi Lunas"><i class="fas fa-check"></i></button>
            <button class="btn-icon btn-icon-del"
              onclick="openModalTolak(<?= $t->id ?>,'<?= addslashes($t->tenant_name) ?>')"
              title="Tolak"><i class="fas fa-times"></i></button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Konfirmasi Lunas -->
<div id="modalKonfirmasi" class="modal-overlay" style="display:none" onclick="if(event.target.id==='modalKonfirmasi')closeModalKonfirmasi()">
  <div class="modal-box">
    <div class="modal-icon modal-icon-success"><i class="fas fa-check-circle"></i></div>
    <h5 class="modal-title">Konfirmasi Transfer Lunas?</h5>
    <p class="modal-body" id="modalKonfirmasiText"></p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalKonfirmasi()">Batal</button>
      <a id="modalKonfirmasiBtn" href="#" class="btn btn-primary"><i class="fas fa-check"></i> Ya, Konfirmasi Lunas</a>
    </div>
  </div>
</div>

<!-- Modal Tolak Transfer -->
<div id="modalTolak" class="modal-overlay" style="display:none" onclick="if(event.target.id==='modalTolak')closeModalTolak()">
  <div class="modal-box" style="max-width:440px">
    <div class="modal-icon modal-icon-danger"><i class="fas fa-times-circle"></i></div>
    <h5 class="modal-title">Tolak Transfer?</h5>
    <p class="modal-body" id="modalTolakText"></p>
    <form id="formTolak" method="POST" action="">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="form-group" style="text-align:left">
        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
        <textarea name="catatan_admin" id="catatanAdmin" class="form-input" rows="3"
          placeholder="Contoh: Nominal tidak sesuai, bukti tidak jelas, dll." required></textarea>
        <div class="invalid-feedback" id="err_catatan"></div>
      </div>
      <div class="modal-actions" style="margin-top:0">
        <button type="button" class="btn btn-outline" onclick="closeModalTolak()">Batal</button>
        <button type="submit" class="btn btn-danger" onclick="return validateTolak()">
          <i class="fas fa-times"></i> Ya, Tolak
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openModalKonfirmasi(id, name, month) {
  document.getElementById('modalKonfirmasiText').textContent =
    'Transfer dari "'+name+'" untuk bulan '+month+' akan dikonfirmasi sebagai LUNAS.';
  document.getElementById('modalKonfirmasiBtn').href = '<?= site_url("transfer/konfirmasi/") ?>'+id;
  document.getElementById('modalKonfirmasi').style.display = 'flex';
}
function closeModalKonfirmasi() { document.getElementById('modalKonfirmasi').style.display='none'; }

function openModalTolak(id, name) {
  document.getElementById('modalTolakText').textContent = 'Transfer dari "'+name+'" akan ditolak. Tagihan dikembalikan ke Belum Lunas.';
  document.getElementById('formTolak').action = '<?= site_url("transfer/tolak/") ?>'+id;
  document.getElementById('catatanAdmin').value = '';
  document.getElementById('modalTolak').style.display = 'flex';
}
function closeModalTolak() { document.getElementById('modalTolak').style.display='none'; }

function validateTolak() {
  var v = document.getElementById('catatanAdmin').value.trim();
  if (!v) {
    document.getElementById('err_catatan').textContent = 'Alasan penolakan wajib diisi.';
    document.getElementById('err_catatan').style.display = 'block';
    return false;
  }
  return true;
}

document.addEventListener('keydown', function(e){
  if (e.key==='Escape') { closeModalKonfirmasi(); closeModalTolak(); }
});
</script>
