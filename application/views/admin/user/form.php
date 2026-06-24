<div class="form-card">
  <div class="form-card-header"><?= $title ?></div>
  <div class="form-card-body">
    <form action="<?= $usr ? site_url('user/update/'.$usr->id) : site_url('user/simpan') ?>" method="POST">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="form-group">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-input" value="<?= $usr->name??'' ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" value="<?= $usr->email??'' ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password <?= $usr?'<span style="font-weight:400;color:var(--text-light)">(kosongkan jika tidak diubah)</span>':'' ?></label>
        <input type="password" name="password" class="form-input" <?= $usr?'':'required' ?>>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" class="form-select-full">
          <option value="Administrator" <?= ($usr->role??'')=='Administrator'?'selected':'' ?>>Administrator</option>
          <option value="Petugas" <?= ($usr->role??'')=='Petugas'?'selected':'' ?>>Petugas</option>
        </select>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <a href="<?= site_url('user') ?>" class="btn btn-outline">Batal</a>
      </div>
    </form>
  </div>
</div>
