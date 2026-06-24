<?php
$banks = array_keys($rekening);
$defaultBank = $banks[0];
?>

<!-- Info: sudah ada pending transfer -->
<?php if ($existing): ?>
<div class="alert-notif alert-warning-notif" style="margin-bottom:20px">
  <i class="fas fa-clock"></i>
  <div>
    <strong>Bukti transfer sudah dikirim</strong>
    <span>Tagihan ini sedang menunggu verifikasi admin sejak <?= date('d M Y H:i', strtotime($existing->created_at)) ?>.
    Tidak perlu upload ulang.</span>
  </div>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">

  <!-- KIRI: Form Upload -->
  <div class="form-card">
    <div class="form-card-header" style="display:flex;align-items:center;gap:10px">
      <i class="fas fa-university" style="color:var(--primary)"></i>
      Konfirmasi Pembayaran Transfer
    </div>
    <div class="form-card-body">
      <!-- Info Tagihan -->
      <div class="transfer-bill-summary">
        <div class="tbs-row"><span>Penyewa</span><strong><?= $bill->tenant_name ?></strong></div>
        <div class="tbs-row"><span>Kamar</span><strong><?= $bill->room_code ?> — <?= $bill->type ?></strong></div>
        <div class="tbs-row"><span>Bulan</span><strong><?= $bill->month ?></strong></div>
        <div class="tbs-sep"></div>
        <div class="tbs-row"><span>Sewa</span><span>Rp <?= number_format($bill->rent,0,',','.') ?></span></div>
        <div class="tbs-row"><span>Listrik</span><span>Rp <?= number_format($bill->electric,0,',','.') ?></span></div>
        <div class="tbs-row"><span>Air</span><span>Rp <?= number_format($bill->water,0,',','.') ?></span></div>
        <div class="tbs-sep"></div>
        <div class="tbs-row tbs-total"><span>Total</span><strong>Rp <?= number_format($bill->total,0,',','.') ?></strong></div>
      </div>

      <?php if (!$existing): ?>
      <form id="formTransfer" action="<?= site_url('transfer/simpan') ?>" method="POST" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
        <input type="hidden" name="bill_id" value="<?= $bill->id ?>">

        <!-- Pilih Bank -->
        <div class="form-group">
          <label class="form-label">Pilih Bank Transfer <span class="text-danger">*</span></label>
          <div class="bank-selector">
            <?php foreach ($rekening as $bank => $info): ?>
            <label class="bank-option">
              <input type="radio" name="bank_name" value="<?= $bank ?>" <?= $bank===$defaultBank?'checked':'' ?> onchange="updateRekening()">
              <div class="bank-card">
                <div class="bank-logo bank-<?= strtolower($bank) ?>"><?= $bank ?></div>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
          <div class="invalid-feedback" id="err_bank"></div>
        </div>

        <!-- Rekening Tujuan (dinamis) -->
        <div class="rekening-tujuan-box" id="rekeningBox">
          <div class="rek-label">Transfer ke Rekening:</div>
          <div class="rek-bank" id="rekBank"><?= $defaultBank ?></div>
          <div class="rek-number" id="rekNumber"><?= $rekening[$defaultBank]['no'] ?></div>
          <div class="rek-name" id="rekName">a.n. <?= $rekening[$defaultBank]['nama'] ?></div>
          <button type="button" class="rek-copy" onclick="copyRekening()" id="copyBtn">
            <i class="fas fa-copy"></i> Salin Nomor
          </button>
        </div>

        <!-- No Rekening Pengirim -->
        <div class="form-group">
          <label class="form-label">No. Rekening Pengirim <span class="text-danger">*</span></label>
          <input type="text" name="account_number" id="account_number" class="form-input"
            placeholder="Nomor rekening Anda (pengirim)" required>
          <div class="invalid-feedback" id="err_accnum"></div>
        </div>

        <!-- Tanggal Transfer -->
        <div class="form-group">
          <label class="form-label">Tanggal Transfer <span class="text-danger">*</span></label>
          <input type="date" name="transfer_date" id="transfer_date" class="form-input"
            value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
          <div class="invalid-feedback" id="err_tgltr"></div>
        </div>

        <!-- Upload Bukti -->
        <div class="form-group">
          <label class="form-label">Foto / File Bukti Transfer <span class="text-danger">*</span></label>
          <div class="upload-zone" id="uploadZone" onclick="document.getElementById('bukti_file').click()">
            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="upload-text">Klik atau drag & drop file di sini</div>
            <div class="upload-hint">JPG, PNG, PDF — maks. 5 MB</div>
            <div class="upload-preview" id="uploadPreview" style="display:none">
              <img id="previewImg" src="" alt="" style="max-height:120px;border-radius:8px">
              <span id="previewName" style="font-size:12px;color:var(--text-mid)"></span>
            </div>
          </div>
          <input type="file" name="bukti_file" id="bukti_file" accept=".jpg,.jpeg,.png,.pdf" style="display:none"
            onchange="handleFileChange(this)">
          <div class="invalid-feedback" id="err_bukti"></div>
        </div>

        <!-- Catatan Opsional -->
        <div class="form-group">
          <label class="form-label">Catatan (opsional)</label>
          <textarea name="catatan_penyewa" class="form-input" rows="2"
            placeholder="Contoh: Transfer dari BCA atas nama Budi Santoso"></textarea>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-paper-plane"></i> Kirim Bukti Transfer
          </button>
          <a href="<?= site_url('tagihan') ?>" class="btn btn-outline">Batal</a>
        </div>
      </form>
      <?php else: ?>
      <div style="text-align:center;padding:24px">
        <div style="font-size:48px;margin-bottom:12px">⏳</div>
        <div style="font-weight:600;color:var(--text)">Menunggu Konfirmasi Admin</div>
        <div style="font-size:13px;color:var(--text-light);margin-top:6px">Dikirim: <?= date('d M Y H:i', strtotime($existing->created_at)) ?></div>
        <a href="<?= site_url('tagihan') ?>" class="btn btn-outline" style="margin-top:16px">← Kembali</a>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- KANAN: Panduan Transfer -->
  <div class="form-card">
    <div class="form-card-header" style="display:flex;align-items:center;gap:10px">
      <i class="fas fa-info-circle" style="color:#3b82f6"></i>
      Panduan Pembayaran
    </div>
    <div class="form-card-body">
      <div class="panduan-steps">
        <div class="step-item">
          <div class="step-num">1</div>
          <div class="step-text">
            <strong>Pilih bank</strong> yang akan Anda gunakan untuk transfer.
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">2</div>
          <div class="step-text">
            <strong>Transfer persis</strong> sebesar <span class="amount-highlight">Rp <?= number_format($bill->total,0,',','.') ?></span>
            ke rekening yang ditampilkan.
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">3</div>
          <div class="step-text">
            <strong>Ambil screenshot</strong> atau foto struk bukti transfer dari ATM/mobile banking.
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">4</div>
          <div class="step-text">
            <strong>Upload bukti</strong> melalui form ini. Admin akan memverifikasi dalam 1×24 jam.
          </div>
        </div>
        <div class="step-item">
          <div class="step-num">5</div>
          <div class="step-text">
            Status tagihan akan berubah menjadi <strong>Lunas</strong> setelah dikonfirmasi admin.
          </div>
        </div>
      </div>

      <div class="info-box-warn" style="margin-top:20px">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
          <strong>Perhatian:</strong> Pastikan nominal transfer <u>tepat</u> sesuai tagihan.
          Transfer dengan nominal berbeda dapat memperlambat proses verifikasi.
        </div>
      </div>
    </div>
  </div>

</div>

<!-- JS: Bank selector & file upload -->
<script>
var rekeningData = <?= json_encode($rekening) ?>;

function updateRekening() {
  var selected = document.querySelector('input[name="bank_name"]:checked');
  if (!selected) return;
  var bank = selected.value;
  var info = rekeningData[bank];
  document.getElementById('rekBank').textContent   = bank;
  document.getElementById('rekNumber').textContent = info.no;
  document.getElementById('rekName').textContent   = 'a.n. ' + info.nama;
  document.getElementById('copyBtn').innerHTML     = '<i class="fas fa-copy"></i> Salin Nomor';
}

function copyRekening() {
  var no = document.getElementById('rekNumber').textContent;
  navigator.clipboard.writeText(no).then(function() {
    document.getElementById('copyBtn').innerHTML = '<i class="fas fa-check"></i> Tersalin!';
    setTimeout(function(){ document.getElementById('copyBtn').innerHTML = '<i class="fas fa-copy"></i> Salin Nomor'; }, 2000);
  });
}

function handleFileChange(input) {
  if (!input.files[0]) return;
  var file = input.files[0];
  var preview = document.getElementById('uploadPreview');
  var previewImg = document.getElementById('previewImg');
  var previewName = document.getElementById('previewName');
  var zone = document.getElementById('uploadZone');

  previewName.textContent = file.name + ' (' + (file.size/1024).toFixed(0) + ' KB)';
  preview.style.display = 'block';

  if (file.type.startsWith('image/')) {
    var reader = new FileReader();
    reader.onload = function(e) { previewImg.src = e.target.result; previewImg.style.display='block'; };
    reader.readAsDataURL(file);
  } else {
    previewImg.style.display = 'none';
  }
  zone.style.borderColor = 'var(--primary)';
  zone.style.background  = 'rgba(79,70,229,0.04)';
}

// Drag & drop
var zone = document.getElementById('uploadZone');
if (zone) {
  zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.style.borderColor='var(--primary)'; });
  zone.addEventListener('dragleave', function(){ zone.style.borderColor=''; });
  zone.addEventListener('drop', function(e){
    e.preventDefault();
    var dt = e.dataTransfer;
    if (dt.files[0]) {
      document.getElementById('bukti_file').files = dt.files;
      handleFileChange(document.getElementById('bukti_file'));
    }
  });
}

// Validasi form
var formTransfer = document.getElementById('formTransfer');
if (formTransfer) {
  formTransfer.addEventListener('submit', function(e) {
    e.preventDefault();
    var valid = true;
    document.querySelectorAll('.invalid-feedback').forEach(function(el){ el.textContent=''; el.style.display='none'; });

    function showErr(errId, msg) {
      var ef = document.getElementById(errId);
      if (ef) { ef.textContent = msg; ef.style.display = 'block'; valid = false; }
    }

    if (!document.querySelector('input[name="bank_name"]:checked')) showErr('err_bank','Pilih bank transfer.');

    var acc = document.getElementById('account_number').value.trim();
    if (!acc) showErr('err_accnum','Nomor rekening pengirim wajib diisi.');
    else if (!/^[0-9\-]{8,20}$/.test(acc)) showErr('err_accnum','Nomor rekening tidak valid.');

    var tgl = document.getElementById('transfer_date').value;
    if (!tgl) showErr('err_tgltr','Tanggal transfer wajib diisi.');

    var fileInput = document.getElementById('bukti_file');
    if (!fileInput.files[0]) showErr('err_bukti','Upload bukti transfer terlebih dahulu.');
    else {
      var sz = fileInput.files[0].size / 1024 / 1024;
      if (sz > 5) showErr('err_bukti','Ukuran file maksimal 5 MB.');
    }

    if (valid) {
      document.getElementById('submitBtn').disabled = true;
      document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      this.submit();
    }
  });
}
</script>
