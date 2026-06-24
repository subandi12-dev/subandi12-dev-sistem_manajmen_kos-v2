<div class="form-card">
  <div class="form-card-header" style="display:flex;align-items:center;gap:10px">
    <i class="fas fa-file-invoice-dollar" style="color:var(--primary)"></i>
    Buat Tagihan
  </div>
  <div class="form-card-body">
    <form id="formTagihan" action="<?= site_url('tagihan/simpan') ?>" method="POST" novalidate>
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

      <div class="form-grid-2">
        <div class="form-group" style="grid-column:1/-1">
          <label class="form-label">Penyewa <span class="text-danger">*</span></label>
          <select name="tenant_id" id="tenant_id" class="form-select-full" required onchange="updateTotal()">
            <option value="">-- Pilih Penyewa --</option>
            <?php foreach ($tenants as $t): ?>
            <option value="<?= $t->id ?>" data-price="<?= $t->price ?>">
              <?= $t->name ?> — <?= $t->room_code ?> (Rp <?= number_format($t->price,0,',','.') ?>/bln)
            </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback" id="err_tenant"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Bulan Tagihan <span class="text-danger">*</span></label>
          <input type="text" name="month" id="month" class="form-input"
            placeholder="Contoh: Juni 2026" value="<?= date('F Y') ?>" required>
          <div class="invalid-feedback" id="err_month"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Sewa Kamar (Rp)</label>
          <input type="text" id="rent_display" class="form-input" disabled
            placeholder="Otomatis dari penyewa" style="background:#f8fafc;color:var(--primary);font-weight:600">
          <input type="hidden" name="rent" id="rent_hidden" value="0">
        </div>

        <div class="form-group">
          <label class="form-label">Biaya Listrik (Rp)</label>
          <input type="number" name="electric" id="electric" class="form-input"
            value="30000" min="0" oninput="updateTotal()">
          <div class="invalid-feedback" id="err_electric"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Biaya Air (Rp)</label>
          <input type="number" name="water" id="water" class="form-input"
            value="20000" min="0" oninput="updateTotal()">
          <div class="invalid-feedback" id="err_water"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
          <input type="date" name="bill_date" id="bill_date" class="form-input"
            value="<?= date('Y-m-d') ?>" required onchange="updateDueDate()">
          <div class="invalid-feedback" id="err_bill_date"></div>
        </div>
        <div class="form-group">
          <label class="form-label">Jatuh Tempo <span class="text-danger">*</span></label>
          <input type="date" name="due_date" id="due_date" class="form-input"
            value="<?= date('Y-m-d', strtotime('+10 days')) ?>" required>
          <div class="invalid-feedback" id="err_due_date"></div>
        </div>
      </div>

      <!-- Total Preview -->
      <div class="total-preview-box" id="totalBox">
        <div class="tp-row"><span>Sewa Kamar</span><span id="tp_rent">Rp 0</span></div>
        <div class="tp-row"><span>Listrik</span><span id="tp_electric">Rp 30.000</span></div>
        <div class="tp-row"><span>Air</span><span id="tp_water">Rp 20.000</span></div>
        <div class="tp-sep"></div>
        <div class="tp-row tp-total"><span>Total Tagihan</span><strong id="tp_total">Rp 0</strong></div>
      </div>

      <div class="form-divider"></div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane"></i> Buat Tagihan
        </button>
        <a href="<?= site_url('tagihan') ?>" class="btn btn-outline">
          <i class="fas fa-times"></i> Batal
        </a>
      </div>
    </form>
  </div>
</div>

<script>
function fmt(n) { return 'Rp ' + parseInt(n||0).toLocaleString('id-ID'); }

document.getElementById('tenant_id').addEventListener('change', function() {
  var opt = this.options[this.selectedIndex];
  var price = opt.dataset.price || 0;
  document.getElementById('rent_display').value = price ? fmt(price) : '';
  document.getElementById('rent_hidden').value  = price;
  updateTotal();
});

function updateTotal() {
  var rent     = parseInt(document.getElementById('rent_hidden').value) || 0;
  var electric = parseInt(document.getElementById('electric').value) || 0;
  var water    = parseInt(document.getElementById('water').value) || 0;
  var total    = rent + electric + water;
  document.getElementById('tp_rent').textContent     = fmt(rent);
  document.getElementById('tp_electric').textContent = fmt(electric);
  document.getElementById('tp_water').textContent    = fmt(water);
  document.getElementById('tp_total').textContent    = fmt(total);
}

function updateDueDate() {
  var bill = document.getElementById('bill_date').value;
  if (bill) {
    var d = new Date(bill);
    d.setDate(d.getDate() + 10);
    document.getElementById('due_date').value = d.toISOString().split('T')[0];
  }
}

updateTotal();

document.getElementById('formTagihan').addEventListener('submit', function(e) {
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

  if (!document.getElementById('tenant_id').value) showErr('tenant_id','err_tenant','Pilih penyewa terlebih dahulu.');
  if (!document.getElementById('month').value.trim()) showErr('month','err_month','Bulan tagihan wajib diisi.');

  var electric = parseFloat(document.getElementById('electric').value);
  if (isNaN(electric) || electric < 0) showErr('electric','err_electric','Biaya listrik tidak valid.');

  var water = parseFloat(document.getElementById('water').value);
  if (isNaN(water) || water < 0) showErr('water','err_water','Biaya air tidak valid.');

  var billDate = document.getElementById('bill_date').value;
  if (!billDate) showErr('bill_date','err_bill_date','Tanggal tagihan wajib diisi.');

  var dueDate = document.getElementById('due_date').value;
  if (!dueDate) showErr('due_date','err_due_date','Jatuh tempo wajib diisi.');
  else if (billDate && dueDate < billDate) showErr('due_date','err_due_date','Jatuh tempo harus setelah tanggal tagihan.');

  if (valid) this.submit();
});
</script>
