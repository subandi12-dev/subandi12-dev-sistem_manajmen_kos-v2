<div class="form-card">
  <div class="form-card-header" style="display:flex;align-items:center;gap:10px">
    <i class="fas fa-door-open" style="color:var(--primary)"></i>
    <?= $title ?>
  </div>
  <div class="form-card-body">
    <form id="formKamar" action="<?= $room ? site_url('kamar/update/'.$room->id) : site_url('kamar/simpan') ?>" method="POST" novalidate>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label">Kode Kamar <span class="text-danger">*</span></label>
          <input type="text" name="room_code" id="room_code" class="form-input"
            value="<?= $room->room_code??'' ?>" placeholder="Contoh: A-01" required>
          <div class="invalid-feedback" id="err_room_code"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
          <select name="type" id="room_type" class="form-select-full" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="Standar" <?= ($room->type??'')=='Standar'?'selected':'' ?>>Standar</option>
            <option value="VIP" <?= ($room->type??'')=='VIP'?'selected':'' ?>>VIP</option>
          </select>
          <div class="invalid-feedback" id="err_type"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Harga / Bulan (Rp) <span class="text-danger">*</span></label>
          <input type="number" name="price" id="price" class="form-input"
            value="<?= $room->price??'' ?>" placeholder="700000" min="10000" required>
          <div class="invalid-feedback" id="err_price"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select-full" required>
            <option value="Kosong" <?= ($room->status??'')=='Kosong'?'selected':'' ?>>Kosong</option>
            <option value="Terisi" <?= ($room->status??'')=='Terisi'?'selected':'' ?>>Terisi</option>
          </select>
        </div>
      </div>

      <!-- Preview harga format Rupiah -->
      <div id="pricePreview" style="display:none;background:#f0f4ff;border:1px solid #c7d2fe;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#4338ca">
        <i class="fas fa-tag" style="margin-right:6px"></i>
        Harga: <strong id="priceFormatted"></strong>
      </div>

      <div class="form-divider"></div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> <?= $room ? 'Update Kamar' : 'Simpan Kamar' ?>
        </button>
        <a href="<?= site_url('kamar') ?>" class="btn btn-outline">
          <i class="fas fa-times"></i> Batal
        </a>
      </div>
    </form>
  </div>
</div>

<script>
// Format harga preview
document.getElementById('price').addEventListener('input', function() {
  var val = parseInt(this.value);
  var preview = document.getElementById('pricePreview');
  var formatted = document.getElementById('priceFormatted');
  if (val >= 10000) {
    formatted.textContent = 'Rp ' + val.toLocaleString('id-ID');
    preview.style.display = 'block';
  } else {
    preview.style.display = 'none';
  }
});
// Trigger on load (edit mode)
if (document.getElementById('price').value) {
  document.getElementById('price').dispatchEvent(new Event('input'));
}

// Validasi
document.getElementById('formKamar').addEventListener('submit', function(e) {
  e.preventDefault();
  var valid = true;

  document.querySelectorAll('.form-input, .form-select-full').forEach(function(el) { el.style.borderColor = ''; });
  document.querySelectorAll('.invalid-feedback').forEach(function(el) { el.textContent = ''; el.style.display = 'none'; });

  function showErr(fieldId, errId, msg) {
    var ef = document.getElementById(errId);
    var fi = document.getElementById(fieldId);
    if (ef) { ef.textContent = msg; ef.style.display = 'block'; }
    if (fi) fi.style.borderColor = '#ef4444';
    valid = false;
  }

  var code = document.getElementById('room_code').value.trim();
  if (!code) showErr('room_code','err_room_code','Kode kamar wajib diisi.');
  else if (!/^[A-Za-z0-9\-]+$/.test(code)) showErr('room_code','err_room_code','Hanya huruf, angka, dan tanda hubung.');

  var type = document.getElementById('room_type').value;
  if (!type) showErr('room_type','err_type','Pilih tipe kamar.');

  var price = document.getElementById('price').value;
  if (!price || price < 10000) showErr('price','err_price','Harga minimal Rp 10.000.');

  if (valid) this.submit();
});
</script>
