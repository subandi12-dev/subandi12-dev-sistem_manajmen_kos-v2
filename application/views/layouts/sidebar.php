<?php
$seg1 = $this->uri->segment(1);
$role = $this->session->userdata('user_role');
$uname = $this->session->userdata('user_name');
?>
<div class="app-layout">

  <!-- SIDEBAR -->
  <aside id="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><i class="fas fa-home"></i></div>
      <div class="brand-text">Sistem Manajemen Kos</div>
    </div>
    <nav class="sidebar-nav">
      <a href="<?= site_url('dashboard') ?>" class="<?= $seg1=='dashboard'?'active':'' ?>">
        <i class="fas fa-th-large"></i><span>Dashboard</span>
      </a>
      <a href="<?= site_url('kamar') ?>" class="<?= $seg1=='kamar'?'active':'' ?>">
        <i class="fas fa-door-open"></i><span>Kamar</span>
      </a>
      <a href="<?= site_url('penyewa') ?>" class="<?= $seg1=='penyewa'?'active':'' ?>">
        <i class="fas fa-users"></i><span>Penyewa</span>
      </a>
      <a href="<?= site_url('pembayaran') ?>" class="<?= $seg1=='pembayaran'?'active':'' ?>">
        <i class="fas fa-money-bill-wave"></i><span>Pembayaran</span>
      </a>
      <a href="<?= site_url('tagihan') ?>" class="<?= $seg1=='tagihan'?'active':'' ?>">
        <i class="fas fa-file-invoice-dollar"></i><span>Tagihan</span>
      </a>
      <a href="<?= site_url('laporan') ?>" class="<?= $seg1=='laporan'?'active':'' ?>">
        <i class="fas fa-chart-bar"></i><span>Laporan</span>
      </a>
      <a href="<?= site_url('transfer/verifikasi') ?>" class="<?= $seg1=='transfer'?'active':'' ?>">
        <i class="fas fa-university"></i><span>Verifikasi Transfer</span>
      </a>
      <?php if ($role === 'Administrator'): ?>
      <a href="<?= site_url('user') ?>" class="<?= $seg1=='user'?'active':'' ?>">
        <i class="fas fa-user-cog"></i><span>User</span>
      </a>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- MAIN -->
  <div class="main-content">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-left">
        <h4><?= $title ?? 'Dashboard' ?></h4>
      </div>
      <div class="topbar-right">
        <span class="bell"><i class="fas fa-bell"></i></span>
        <div class="user-pill">
          <div class="user-avatar"><?= strtoupper(substr($uname,0,1)) ?></div>
          <div class="user-info-text">
            <div class="name"><?= $uname ?></div>
            <div class="role"><?= $role ?></div>
          </div>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="page-wrap">

<?php if ($this->session->flashdata('success')): ?>
<div class="flash-msg flash-success"><i class="fas fa-check-circle"></i><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="flash-msg flash-danger"><i class="fas fa-exclamation-circle"></i><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>
