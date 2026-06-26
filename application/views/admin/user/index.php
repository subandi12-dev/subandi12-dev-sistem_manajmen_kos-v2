<div class="data-card">
  <div class="data-card-header">
    <h5>Pengaturan User</h5>
    <a href="<?= site_url('user/tambah') ?>" class="btn btn-primary" style="font-size:12.5px;padding:7px 14px">
      <i class="fas fa-plus"></i> Tambah User
    </a>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach ($users as $i => $u): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td style="font-weight:500"><?= $u->name ?></td>
          <td style="color:var(--text-mid)"><?= $u->email ?></td>
          <td><span class="badge <?= $u->role=='Administrator'?'badge-admin':'badge-petugas' ?>"><?= $u->role ?></span></td>
          <td style="display:flex;gap:5px">
            <a href="<?= site_url('user/edit/'.$u->id) ?>" class="btn-icon btn-icon-edit"><i class="fas fa-edit"></i></a>
            <?php if ($u->id != $this->session->userdata('user_id')): ?>
            <a href="<?= site_url('user/hapus/'.$u->id) ?>" class="btn-icon btn-icon-del" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">
    <span class="pagination-info">Menampilkan 1 – <?= count($users) ?> dari <?= count($users) ?> data</span>
  </div>
</div>
