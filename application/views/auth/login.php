<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Sistem Manajemen Kos</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="login-bg">
  <div class="login-card">
    <div class="login-icon"><i class="fas fa-home"></i></div>
    <div class="login-title">Sistem Manajemen Kos</div>
    <div class="login-sub">Silakan login untuk melanjutkan</div>

    <?php if ($this->session->flashdata('error')): ?>
    <div class="flash-msg flash-danger"><i class="fas fa-exclamation-circle"></i><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('proses_login') ?>" method="POST">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

      <div class="login-group">
        <label class="login-label">Email</label>
        <div class="login-input-wrap">
          <i class="fas fa-envelope icon-left"></i>
          <input type="email" name="email" placeholder="admin@kost.com" value="admin@kost.com" required>
        </div>
      </div>

      <div class="login-group">
        <label class="login-label">Password</label>
        <div class="login-input-wrap">
          <i class="fas fa-lock icon-left"></i>
          <input type="password" name="password" id="passInput" placeholder="••••••••" value="password" required>
          <button type="button" class="eye-btn" onclick="togglePass()"><i class="fas fa-eye" id="eyeIcon"></i></button>
        </div>
      </div>

      <div class="login-footer-row">
        <label class="login-check"><input type="checkbox"> Ingat saya</label>
        <span class="login-forgot-text">Lupa password? Hubungi admin</span>
      </div>

      <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt" style="margin-right:8px"></i>Login</button>
    </form>

    <div class="demo-box">
      <div class="demo-title">Demo Account</div>
      <p>Email: admin@kost.com<br>Password: password</p>
    </div>
  </div>
</div>
<script>
function togglePass(){
  var i=document.getElementById('passInput'),e=document.getElementById('eyeIcon');
  i.type=i.type==='password'?'text':'password';
  e.className=i.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
</script>
</body>
</html>
