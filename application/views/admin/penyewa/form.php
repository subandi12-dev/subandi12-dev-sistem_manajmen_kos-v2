<div class="form-card">
  <div class="form-card-header" style="display:flex;align-items:center;gap:10px">
    <i class="fas fa-user-plus" style="color:var(--primary)"></i>
    <?= $title ?>
  </div>
  <div class="form-card-body">
    <form id="formPenyewa" action="<?= $tenant ? site_url('penyewa/update/'.$tenant->id) : site_url('penyewa/simpan') ?>" method="POST" novalidate>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label">Nama Penyewa <span class="text-danger">*</span></label>
          <input type="text" name="name" id="penyewa_name" class="form-input"
            value="<?= $tenant->name??'' ?>" placeholder="Nama lengkap penyewa" required>
          <div class="invalid-feedback" id="err_name"></div>
        </div>
        <div class="form-group">
          <label class="form-label">No. HP / WhatsApp</label>
          <input type="text" name="phone" id="phone" class="form-input"
            value="<?= $tenant->phone??'' ?>" placeholder="08xxxxxxxxxx">
          <div class="invalid-feedback" id="err_phone"></div>
        </div>
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Pilih Kamar <span class="text-danger">*</span></label>
          <select name="room_id" id="room_id" class="form-select-full" required>
            <option value="">-- Pilih Kamar Tersedia --</option>
            <?php foreach ($rooms as $r): ?>
            <option value="<?= $r->id ?>" <?= ($tenant->room_id??'')==$r->id?'selected':'' ?>>
              <?= $r->room_code ?> — <?= $r->type ?> &nbsp;|&nbsp; Rp <?= number_format($r->price,0,',','.') ?>/bulan
              <?= isset($r->status)?' ['.$r->status.']':'' ?>
            </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback" id="err_room"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
          <input type="date" name="start_date" id="start_date" class="form-input"
            value="<?= $tenant->start_date??'' ?>" required>
          <div class="invalid-feedback" id="err_date"></div>
        </div>
      </div>

      <div class="form-divider"></div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> <?= $tenant ? 'Update Penyewa' : 'Simpan Penyewa' ?>
        </button>
        <a href="<?= site_url('penyewa') ?>" class="btn btn-outline">
          <i class="fas fa-times"></i> Batal
        </a>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('formPenyewa').addEventListener('submit', function(e) {
  e.preventDefault();
  var valid = true;
  document.querySelectorAll('.form-input, .form-select-full').forEach(function(el) { el.style.borderColor=''; });
  document.querySelectorAll('.invalid-feedback').forEach(function(el) { el.textContent=''; el.style.display='none'; });

  function showErr(fieldId, errId, msg) {
    var ef = document.getElementById(errId);
    var fi = document.getElementById(fieldId);
    if (ef) { ef.textContent = msg; ef.style.display = 'block'; }
    if (fi) fi.style.borderColor = '#ef4444';
    valid = false;
  }

  var name = document.getElementById('penyewa_name').value.trim();
  if (!name) showErr('penyewa_name','err_name','Nama penyewa wajib diisi.');
  else if (name.length < 3) showErr('penyewa_name','err_name','Nama minimal 3 karakter.');

  if (!document.getElementById('room_id').value) showErr('room_id','err_room','Pilih kamar terlebih dahulu.');

  var phone = document.getElementById('phone').value.trim();
  if (phone && !/^0[0-9]{9,12}$/.test(phone)) showErr('phone','err_phone','Format HP tidak valid (contoh: 08123456789).');

  if (!document.getElementById('start_date').value) showErr('start_date','err_date','Tanggal masuk wajib diisi.');

  if (valid) this.submit();
});
</script>
