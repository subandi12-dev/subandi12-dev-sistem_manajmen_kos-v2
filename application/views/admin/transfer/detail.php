<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">

  <!-- KIRI: Info Transfer & Aksi -->
  <div class="form-card">
    <div class="form-card-header" style="display:flex;align-items:center;justify-content:space-between">
      <span><i class="fas fa-university" style="color:var(--primary);margin-right:8px"></i>Detail Transfer</span>
      <?php
        $stclass = ['Menunggu'=>'badge-menunggu','Dikonfirmasi'=>'badge-lunas','Ditolak'=>'badge-ditolak'];
        $sticon  = ['Menunggu'=>'fa-clock','Dikonfirmasi'=>'fa-check-circle','Ditolak'=>'fa-times-circle'];
      ?>
      <span class="badge <?= $stclass[$transfer->status] ?? '' ?>" style="font-size:13px;padding:5px 12px">
        <i class="fas <?= $sticon[$transfer->status] ?? '' ?>"></i> <?= $transfer->status ?>
      </span>
    </div>
    <div class="form-card-body">
      <div class="transfer-bill-summary">
        <div class="tbs-row"><span>Penyewa</span><strong><?= $transfer->tenant_name ?></strong></div>
        <div class="tbs-row"><span>No. HP</span><span><?= $transfer->phone ?: '-' ?></span></div>
        <div class="tbs-row"><span>Kamar</span><strong><?= $transfer->room_code ?> — <?= $transfer->room_type ?></strong></div>
        <div class="tbs-row"><span>Bulan Tagihan</span><strong><?= $transfer->month ?></strong></div>
        <div class="tbs-sep"></div>
        <div class="tbs-row"><span>Sewa</span><span>Rp <?= number_format($transfer->rent,0,',','.') ?></span></div>
        <div class="tbs-row"><span>Listrik</span><span>Rp <?= number_format($transfer->electric,0,',','.') ?></span></div>
        <div class="tbs-row"><span>Air</span><span>Rp <?= number_format($transfer->water,0,',','.') ?></span></div>
        <div class="tbs-sep"></div>
        <div class="tbs-row tbs-total"><span>Total Tagihan</span><strong>Rp <?= number_format($transfer->bill_total,0,',','.') ?></strong></div>
      </div>

      <div class="detail-transfer-info">
        <div class="dti-row">
          <span><i class="fas fa-university"></i> Bank Transfer</span>
          <strong class="bank-badge bank-badge-<?= strtolower($transfer->bank_name) ?>"><?= $transfer->bank_name ?></strong>
        </div>
        <div class="dti-row">
          <span><i class="fas fa-credit-card"></i> No. Rek. Pengirim</span>
          <strong><?= $transfer->account_number ?></strong>
        </div>
        <div class="dti-row">
          <span><i class="fas fa-calendar-day"></i> Tanggal Transfer</span>
          <strong><?= date('d M Y', strtotime($transfer->transfer_date)) ?></strong>
        </div>
        <div class="dti-row">
          <span><i class="fas fa-money-bill-wave"></i> Nominal Transfer</span>
          <strong style="color:var(--primary)">Rp <?= number_format($transfer->amount,0,',','.') ?></strong>
        </div>
        <div class="dti-row">
          <span><i class="fas fa-clock"></i> Dikirim</span>
          <span><?= date('d M Y H:i', strtotime($transfer->created_at)) ?></span>
        </div>
        <?php if ($transfer->catatan_penyewa): ?>
        <div class="dti-row dti-catatan">
          <span><i class="fas fa-comment"></i> Catatan Penyewa</span>
          <span><?= nl2br(htmlspecialchars($transfer->catatan_penyewa)) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($transfer->catatan_admin): ?>
        <div class="dti-row dti-catatan dti-catatan-admin">
          <span><i class="fas fa-user-shield"></i> Catatan Admin</span>
          <span><?= nl2br(htmlspecialchars($transfer->catatan_admin)) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($transfer->verified_at): ?>
        <div class="dti-row">
          <span><i class="fas fa-user-check"></i> Diverifikasi</span>
          <span><?= $transfer->verified_by_name ?? '-' ?> · <?= date('d M Y H:i', strtotime($transfer->verified_at)) ?></span>
        </div>
        <?php endif; ?>
      </div>

      <?php if ($transfer->status === 'Menunggu'): ?>
      <div class="form-actions" style="margin-top:20px">
        <button class="btn btn-primary"
          onclick="openModalKonfirmasi(<?= $transfer->id ?>,'<?= addslashes($transfer->tenant_name) ?>','<?= $transfer->month ?>')">
          <i class="fas fa-check-circle"></i> Konfirmasi Lunas
        </button>
        <button class="btn btn-danger"
          onclick="openModalTolak(<?= $transfer->id ?>,'<?= addslashes($transfer->tenant_name) ?>')">
          <i class="fas fa-times-circle"></i> Tolak
        </button>
        <a href="<?= site_url('transfer/verifikasi') ?>" class="btn btn-outline">← Kembali</a>
      </div>
      <?php else: ?>
      <div style="margin-top:16px">
        <a href="<?= site_url('transfer/verifikasi') ?>" class="btn btn-outline">← Kembali ke Verifikasi</a>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- KANAN: Preview Bukti Transfer -->
  <div class="form-card">
    <div class="form-card-header">
      <i class="fas fa-image" style="color:#3b82f6;margin-right:8px"></i>Bukti Transfer
    </div>
    <div class="form-card-body" style="text-align:center">
      <?php
        $ext = strtolower(pathinfo($transfer->bukti_file, PATHINFO_EXTENSION));
        $file_url = base_url('assets/uploads/bukti_transfer/' . $transfer->bukti_file);
      ?>
      <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
        <img src="<?= $file_url ?>" alt="Bukti Transfer"
          style="max-width:100%;border-radius:12px;border:1px solid var(--border);cursor:zoom-in"
          onclick="window.open(this.src,'_blank')">
        <div style="font-size:12px;color:var(--text-light);margin-top:8px">Klik gambar untuk membuka ukuran penuh</div>
      <?php elseif ($ext === 'pdf'): ?>
        <div class="pdf-preview-box">
          <i class="fas fa-file-pdf" style="font-size:48px;color:#ef4444;margin-bottom:12px"></i>
          <div style="font-weight:600;margin-bottom:8px">File PDF</div>
          <div style="font-size:12.5px;color:var(--text-light);margin-bottom:16px"><?= $transfer->bukti_file ?></div>
          <a href="<?= $file_url ?>" target="_blank" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Buka File PDF
          </a>
        </div>
      <?php else: ?>
        <div class="pdf-preview-box">
          <i class="fas fa-file" style="font-size:48px;color:var(--text-light);margin-bottom:12px"></i>
          <div style="font-weight:600">File tidak dapat dipreview</div>
          <a href="<?= $file_url ?>" download class="btn btn-outline" style="margin-top:12px">
            <i class="fas fa-download"></i> Download
          </a>
        </div>
      <?php endif; ?>

      <!-- Perbandingan nominal -->
      <div class="nominal-check" style="margin-top:20px">
        <?php $match = ($transfer->amount == $transfer->bill_total); ?>
        <div class="nc-row <?= $match?'nc-match':'nc-mismatch' ?>">
          <div class="nc-label">Tagihan</div>
          <div class="nc-val">Rp <?= number_format($transfer->bill_total,0,',','.') ?></div>
        </div>
        <div class="nc-row <?= $match?'nc-match':'nc-mismatch' ?>">
          <div class="nc-label">Nominal Transfer</div>
          <div class="nc-val">Rp <?= number_format($transfer->amount,0,',','.') ?></div>
        </div>
        <div class="nc-status">
          <?php if ($match): ?>
          <i class="fas fa-check-circle" style="color:#10b981"></i> Nominal sesuai tagihan
          <?php else: ?>
          <i class="fas fa-exclamation-triangle" style="color:#f59e0b"></i> Nominal tidak sesuai — harap cek!
          <?php endif; ?>
        </div>
      </div>
    </div>
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

<!-- Modal Tolak -->
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
          placeholder="Contoh: Nominal tidak sesuai, bukti tidak jelas..."></textarea>
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
    'Transfer dari "'+name+'" bulan '+month+' akan dikonfirmasi sebagai LUNAS.';
  document.getElementById('modalKonfirmasiBtn').href = '<?= site_url("transfer/konfirmasi/") ?>'+id;
  document.getElementById('modalKonfirmasi').style.display = 'flex';
}
function closeModalKonfirmasi(){ document.getElementById('modalKonfirmasi').style.display='none'; }

function openModalTolak(id, name) {
  document.getElementById('modalTolakText').textContent = 'Transfer dari "'+name+'" akan ditolak. Tagihan dikembalikan ke Belum Lunas.';
  document.getElementById('formTolak').action = '<?= site_url("transfer/tolak/") ?>'+id;
  document.getElementById('catatanAdmin').value = '';
  document.getElementById('modalTolak').style.display = 'flex';
}
function closeModalTolak(){ document.getElementById('modalTolak').style.display='none'; }

function validateTolak() {
  var v = document.getElementById('catatanAdmin').value.trim();
  if (!v) {
    document.getElementById('err_catatan').textContent='Alasan wajib diisi.';
    document.getElementById('err_catatan').style.display='block';
    return false;
  }
  return true;
}
document.addEventListener('keydown', function(e){
  if (e.key==='Escape'){ closeModalKonfirmasi(); closeModalTolak(); }
});
</script>
