<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tagihan — <?= $bill->tenant_name ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',sans-serif;font-size:13px;background:#f8fafc;padding:30px;}
.invoice{background:white;max-width:680px;margin:0 auto;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);}
.inv-header{background:#4F46E5;color:white;padding:24px 28px;display:flex;justify-content:space-between;align-items:flex-start;}
.inv-header h2{font-size:18px;font-weight:700;margin-bottom:4px;}
.inv-header p{font-size:12px;opacity:.8;}
.inv-num{text-align:right;font-size:13px;font-weight:600;}
.inv-num small{display:block;opacity:.7;font-size:11px;margin-bottom:2px;}
.inv-body{padding:24px 28px;}
.inv-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px;}
.inv-section-label{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:#94A3B8;font-weight:700;margin-bottom:8px;}
.inv-info-text{font-size:13px;color:#475569;line-height:1.8;}
.inv-info-text strong{color:#1E293B;}
table{width:100%;border-collapse:collapse;margin-bottom:0;}
thead th{padding:9px 12px;background:#F8FAFC;font-size:11px;text-transform:uppercase;letter-spacing:.4px;color:#94A3B8;text-align:left;border-bottom:1px solid #E2E8F0;}
tbody td{padding:11px 12px;border-bottom:1px solid #F1F5F9;color:#1E293B;font-size:13px;}
.text-right{text-align:right;}
.total-section{background:#F8FAFC;border-radius:10px;padding:16px;margin-top:16px;display:flex;justify-content:space-between;align-items:center;}
.total-section .lbl{font-size:13px;color:#64748B;}
.total-section .val{font-size:20px;font-weight:700;color:#EF4444;}
.badge-lunas{background:#DCFCE7;color:#16A34A;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
.badge-belum{background:#FEE2E2;color:#DC2626;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
.inv-footer{padding:14px 28px;border-top:1px solid #E2E8F0;font-size:11.5px;color:#94A3B8;}
.no-print{text-align:center;margin-top:18px;}
.no-print button,.no-print a{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;border:1px solid #E2E8F0;}
.no-print .print-btn{background:#4F46E5;color:white;border:none;margin-right:8px;}
.no-print .back-btn{background:white;color:#475569;}
@media print{.no-print{display:none;} body{background:white;padding:0;} .invoice{box-shadow:none;border-radius:0;}}
</style>
</head>
<body>
<div class="invoice">
  <div class="inv-header">
    <div>
      <h2><i class="fas fa-home" style="margin-right:8px"></i>Sistem Manajemen Kos</h2>
      <p>Tagihan Sewa Bulanan</p>
    </div>
    <div class="inv-num">
      <small>Invoice</small>
      #<?= str_pad($bill->id,4,'0',STR_PAD_LEFT) ?>
    </div>
  </div>

  <div class="inv-body">
    <div class="inv-row">
      <div>
        <div class="inv-section-label">Kepada Yth.</div>
        <div class="inv-info-text">
          <strong><?= $bill->tenant_name ?></strong><br>
          Kamar <?= $bill->room_code ?> (<?= $bill->type ?>)<br>
          <?= $bill->phone ?>
        </div>
      </div>
      <div>
        <div class="inv-section-label">Detail Tagihan</div>
        <div class="inv-info-text">
          <strong>Bulan:</strong> <?= $bill->month ?><br>
          <strong>Tgl Tagihan:</strong> <?= date('d M Y', strtotime($bill->bill_date)) ?><br>
          <strong>Jatuh Tempo:</strong> <?= date('d M Y', strtotime($bill->due_date)) ?><br>
          <strong>Status:</strong> <span class="<?= $bill->status=='Lunas'?'badge-lunas':'badge-belum' ?>"><?= $bill->status ?></span>
        </div>
      </div>
    </div>

    <table>
      <thead><tr><th>No</th><th>Keterangan</th><th class="text-right">Jumlah</th></tr></thead>
      <tbody>
        <tr><td>1</td><td>Sewa Kamar</td><td class="text-right">Rp <?= number_format($bill->rent,0,',','.') ?></td></tr>
        <tr><td>2</td><td>Listrik</td><td class="text-right">Rp <?= number_format($bill->electric,0,',','.') ?></td></tr>
        <tr><td>3</td><td>Air</td><td class="text-right">Rp <?= number_format($bill->water,0,',','.') ?></td></tr>
        <tr><td colspan="2" class="text-right" style="color:#94A3B8">Denda</td><td class="text-right">Rp 0</td></tr>
      </tbody>
    </table>

    <div class="total-section">
      <span class="lbl">Total Bayar</span>
      <span class="val">Rp <?= number_format($bill->total,0,',','.') ?></span>
    </div>
  </div>

  <div class="inv-footer">Mohon melakukan pembayaran sebelum tanggal jatuh tempo. Terima kasih atas kepercayaan Anda.</div>
</div>

<div class="no-print">
  <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
  <a href="<?= site_url('tagihan') ?>" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
</body>
</html>
