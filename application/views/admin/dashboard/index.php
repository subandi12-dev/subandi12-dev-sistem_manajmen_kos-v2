<!-- ===== NOTIFIKASI JATUH TEMPO & OVERDUE ===== -->
<?php if (!empty($overdue)): ?>
<div class="alert-notif alert-overdue">
  <i class="fas fa-exclamation-circle"></i>
  <div>
    <strong>Tagihan Jatuh Tempo Terlewat!</strong>
    <span><?= count($overdue) ?> tagihan sudah melewati batas pembayaran.</span>
    <a href="<?= site_url('tagihan?status=Belum+Lunas') ?>">Lihat tagihan →</a>
  </div>
  <button class="notif-close" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>
<?php if (!empty($jatuh_tempo)): ?>
<div class="alert-notif alert-warning-notif">
  <i class="fas fa-clock"></i>
  <div>
    <strong>Segera Jatuh Tempo!</strong>
    <span><?= count($jatuh_tempo) ?> tagihan akan jatuh tempo dalam 3 hari ke depan:</span>
    <ul style="margin:4px 0 0 16px;font-size:12px">
      <?php foreach ($jatuh_tempo as $jt): ?>
      <li><?= $jt->tenant_name ?> — <?= $jt->room_code ?> | Jatuh tempo: <?= date('d M Y', strtotime($jt->due_date)) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <button class="notif-close" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fas fa-door-open"></i></div>
    <div class="stat-info">
      <div class="label">Total Kamar</div>
      <div class="value"><?= $total_kamar ?></div>
      <div class="unit">Unit</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-bed"></i></div>
    <div class="stat-info">
      <div class="label">Kamar Terisi</div>
      <div class="value"><?= $kamar_terisi ?></div>
      <div class="unit">Unit</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-users"></i></div>
    <div class="stat-info">
      <div class="label">Total Penyewa</div>
      <div class="value"><?= $total_penyewa ?></div>
      <div class="unit">Orang</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon red"><i class="fas fa-file-invoice-dollar"></i></div>
    <div class="stat-info">
      <div class="label">Tagihan Belum Lunas</div>
      <div class="value"><?= $tagihan_belum ?></div>
      <div class="unit">Tagihan</div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="charts-row">
  <!-- Donut Chart -->
  <div class="chart-card">
    <div class="card-title">Persentase Kamar</div>
    <div class="donut-wrap">
      <div class="donut-center-wrap">
        <canvas id="donutChart"></canvas>
        <div class="donut-center-label">
          <div class="pct"><?= $pct_terisi ?>%</div>
          <div class="lbl">Terisi</div>
        </div>
      </div>
      <div class="donut-legend">
        <div class="legend-item">
          <span class="legend-dot" style="background:#4F46E5"></span>
          Terisi <?= $kamar_terisi ?> (<?= $pct_terisi ?>%)
        </div>
        <div class="legend-item">
          <span class="legend-dot" style="background:#E2E8F0"></span>
          Kosong <?= $kamar_kosong ?> (<?= 100-$pct_terisi ?>%)
        </div>
      </div>
    </div>
  </div>

  <!-- Line Chart — REAL DATA dari DB -->
  <div class="chart-card">
    <div class="income-header">
      <div>
        <div class="card-title" style="margin-bottom:4px">Pemasukan Bulan Ini</div>
        <div class="income-value">Rp <?= number_format($pemasukan,0,',','.') ?></div>
      </div>
      <span class="income-badge">Tahun <?= date('Y') ?></span>
    </div>
    <canvas id="lineChart"></canvas>
  </div>
</div>

<!-- Recent Bills -->
<div class="data-card">
  <div class="data-card-header">
    <h5>Tagihan Terbaru</h5>
    <a href="<?= site_url('tagihan') ?>" style="font-size:12.5px;color:var(--primary);text-decoration:none;font-weight:500">
      Lihat semua tagihan →
    </a>
  </div>
  <div class="tbl-wrap">
    <table class="kos-table">
      <thead>
        <tr><th>No</th><th>Penyewa</th><th>Kamar</th><th>Bulan</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php if (empty($tagihan_terbaru)): ?>
        <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-light)">Belum ada tagihan</td></tr>
        <?php else: foreach ($tagihan_terbaru as $i => $b): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td style="font-weight:500"><?= $b->tenant_name ?></td>
          <td><?= $b->room_code ?></td>
          <td><?= $b->month ?></td>
          <td>Rp <?= number_format($b->total,0,',','.') ?></td>
          <td><span class="badge <?= $b->status=='Lunas'?'badge-lunas':'badge-belum' ?>"><?= $b->status ?></span></td>
          <td>
            <?php if ($b->status=='Belum Lunas'): ?>
            <button class="btn-icon btn-icon-check"
              onclick="openModalLunas(<?= $b->id ?>, '<?= addslashes($b->tenant_name) ?>', '<?= $b->month ?>')"
              title="Tandai Lunas"><i class="fas fa-check"></i></button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ===== MODAL KONFIRMASI TANDAI LUNAS ===== -->
<div id="modalLunas" class="modal-overlay" style="display:none" onclick="closeModalLunas(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-success"><i class="fas fa-check-circle"></i></div>
    <h5 class="modal-title">Tandai Lunas?</h5>
    <p class="modal-body" id="modalLunasText">Tagihan ini akan ditandai sebagai LUNAS.</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalLunas()">Batal</button>
      <a id="modalLunasBtn" href="#" class="btn btn-primary"><i class="fas fa-check"></i> Ya, Tandai Lunas</a>
    </div>
  </div>
</div>

<!-- ===== MODAL KONFIRMASI HAPUS (global) ===== -->
<div id="modalHapus" class="modal-overlay" style="display:none" onclick="closeModalHapus(event)">
  <div class="modal-box">
    <div class="modal-icon modal-icon-danger"><i class="fas fa-trash-alt"></i></div>
    <h5 class="modal-title">Konfirmasi Hapus</h5>
    <p class="modal-body" id="modalHapusText">Data ini akan dihapus secara permanen.</p>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModalHapus()">Batal</button>
      <a id="modalHapusBtn" href="#" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</a>
    </div>
  </div>
</div>

<!-- Chart Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
  // Donut Chart
  var donutCtx = document.getElementById('donutChart');
  if (donutCtx) {
    new Chart(donutCtx, {
      type: 'doughnut',
      data: {
        labels: ['Terisi','Kosong'],
        datasets: [{
          data: [<?= $kamar_terisi ?>, <?= $kamar_kosong ?>],
          backgroundColor: ['#4F46E5','#E2E8F0'],
          borderWidth: 0,
          hoverOffset: 4
        }]
      },
      options: {
        cutout: '74%',
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(c) { return ' ' + c.label + ': ' + c.raw + ' kamar'; }
            }
          }
        }
      }
    });
  }

  // Line Chart — DATA REAL DARI DATABASE
  var monthlyData = <?= $monthly_income ?>;
  var lineCtx = document.getElementById('lineChart');
  if (lineCtx) {
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
          data: monthlyData,
          borderColor: '#4F46E5',
          backgroundColor: 'rgba(79,70,229,0.08)',
          fill: true,
          tension: 0.45,
          borderWidth: 2.5,
          pointRadius: 4,
          pointBackgroundColor: '#4F46E5',
          pointBorderColor: '#fff',
          pointBorderWidth: 1.5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(c) {
                return ' Rp ' + c.raw.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: '#F1F5F9' },
            ticks: {
              font: { size: 10 }, color: '#94A3B8',
              callback: function(v) {
                if (v >= 1000000) return (v/1000000).toFixed(1)+'jt';
                if (v >= 1000) return (v/1000).toFixed(0)+'rb';
                return v;
              }
            }
          },
          x: {
            grid: { display: false },
            ticks: { font: { size: 10 }, color: '#94A3B8' }
          }
        }
      }
    });
  }
})();

// ===== MODAL FUNCTIONS =====
function openModalLunas(id, name, month) {
  document.getElementById('modalLunasText').textContent =
    'Tandai tagihan "' + name + '" bulan ' + month + ' sebagai LUNAS?';
  document.getElementById('modalLunasBtn').href = '<?= site_url("tagihan/lunas/") ?>' + id;
  document.getElementById('modalLunas').style.display = 'flex';
}
function closeModalLunas(e) {
  if (!e || e.target.id === 'modalLunas') document.getElementById('modalLunas').style.display = 'none';
}
function openModalHapus(url, label) {
  document.getElementById('modalHapusText').textContent = 'Data "' + label + '" akan dihapus permanen. Lanjutkan?';
  document.getElementById('modalHapusBtn').href = url;
  document.getElementById('modalHapus').style.display = 'flex';
}
function closeModalHapus(e) {
  if (!e || e.target.id === 'modalHapus') document.getElementById('modalHapus').style.display = 'none';
}
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { closeModalLunas(); closeModalHapus(); }
});
</script>
