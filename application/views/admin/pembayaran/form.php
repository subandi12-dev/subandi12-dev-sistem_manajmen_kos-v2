<div class="form-card">
  <div class="form-card-header">Tambah Pembayaran</div>
  <div class="form-card-body">
    <form action="<?= site_url('pembayaran/simpan') ?>" method="POST">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="form-group">
        <label class="form-label">Penyewa</label>
        <select name="tenant_id" class="form-select-full" required>
          <option value="">-- Pilih Penyewa --</option>
          <?php foreach ($tenants as $t): ?>
          <option value="<?= $t->id ?>"><?= $t->name ?> — <?= $t->room_code ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Bulan</label>
        <input type="text" name="month" class="form-input" placeholder="Juni 2026" required>
      </div>
      <div class="form-group">
        <label class="form-label">Jumlah (Rp)</label>
        <input type="number" name="amount" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">Metode</label>
        <select name="method" class="form-select-full">
          <option value="Transfer">Transfer</option>
          <option value="Cash">Cash</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Tanggal Bayar</label>
        <input type="date" name="pay_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="<?= site_url('pembayaran') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>
