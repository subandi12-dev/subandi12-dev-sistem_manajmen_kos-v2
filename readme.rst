# 🏠 Sistem Manajemen Kos

Aplikasi web berbasis **Bebasis Frontend : HTL, CSS, JavaScrip, Boostrap 5, Backhend : PHP, Cidelnigter 3, dan Database : Mysql.** untuk mengelola operasional kos secara digital dan terpusat — mulai dari data kamar, penyewa, tagihan, pembayaran, hingga verifikasi bukti transfer.

---

## 👥 Tim Pengembang

| Nama | NIM | Role |
|------|-----|------|
| Ahmad Subandi | 24010110043 | Ketua / Backend (Login, Dashboard, Database, Laporan) |
| Yusril Rifki Muzakki | 24010110001 | Frontend (UI/UX, Views: Dashboard, Kamar, Penyewa) |
| Nur Fitri Rahmayani | 24010110019 | Full Stack (Pembayaran, Tagihan, Cetak PDF) |
| Windi Ariyanti | 24010110018 | Dokumentasi / Backend (User Management, Laporan Ekspor, SRS) |

---

## ✨ Fitur Utama

- 🔐 **Login Multi-user** — Admin (akses penuh) & Petugas (akses operasional)
- 📊 **Dashboard** — Statistik real-time: total kamar, penyewa, tagihan belum lunas, grafik pemasukan bulanan
- 🚪 **Manajemen Kamar** — CRUD data kamar (kode, tipe, harga, status)
- 👤 **Manajemen Penyewa** — CRUD data penyewa + auto update status kamar
- 🧾 **Pengelolaan Tagihan** — Buat tagihan bulanan, tandai lunas, cetak PDF
- 💳 **Pengelolaan Pembayaran** — Catat transaksi (Transfer/Cash)
- 🏦 **Konfirmasi & Verifikasi Transfer** — Upload bukti transfer, verifikasi oleh admin
- 📈 **Laporan Pembayaran** — Filter rentang tanggal, ekspor PDF & Excel
- 👥 **Manajemen User** — Kelola akun admin & petugas (khusus Administrator)
- 🔔 **Notifikasi Jatuh Tempo** — Alert tagihan yang hampir/sudah jatuh tempo

---

## 🛠️ Teknologi

| Layer | Teknologi |
|-------|-----------|
| Frontend | HTML, CSS, JavaScript, Font Awesome |
| Backend | PHP 7.4+, CodeIgniter 3 |
| Database | MySQL |
| Server | Apache (XAMPP) |

---

## ⚙️ Cara Menjalankan

### Prasyarat
- [XAMPP](https://www.apachefriends.org) (PHP 7.4+ & MySQL)
- Git

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/[username]/sistem_manajemen_kos.git
```

**2. Pindahkan ke folder htdocs**
```
C:\xampp\htdocs\kos\
```
Pastikan seluruh file (termasuk `application/`, `assets/`, `index.php`) langsung berada di dalam folder `kos/`.

**3. Jalankan XAMPP**

Buka XAMPP Control Panel → Start **Apache** dan **MySQL**

**4. Import database**

- Buka `http://localhost/phpmyadmin`
- Buat database baru bernama `kos_db`
- Pilih database `kos_db` → tab **SQL** → paste isi file `database/kos_db.sql` → klik **Go**

**5. Konfigurasi database**

Buka `application/config/database.php`, sesuaikan:
```php
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = '';        // kosong untuk XAMPP default
$db['default']['database'] = 'kos_db';
```

**6. Konfigurasi base URL**

Buka `application/config/config.php`, sesuaikan:
```php
$config['base_url'] = 'http://localhost/kos/';
```

**7. Buat folder uploads**

Pastikan folder berikut ada dan memiliki izin tulis:
```
assets/uploads/bukti_transfer/
```

**8. Akses aplikasi**

Buka browser dan akses:
```
http://localhost/kos/
```

---

## 🔑 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Administrator | admin@kost.com | password |
| Petugas | petugas1@kost.com | password |

---

## 📁 Struktur Direktori

```
/
├── README.md
├── index.php
├── database/
│   └── kos_db.sql
├── application/
│   ├── config/
│   ├── controllers/
│   │   └── admin/
│   ├── models/
│   └── views/
│       ├── admin/
│       │   ├── dashboard/
│       │   ├── kamar/
│       │   ├── penyewa/
│       │   ├── tagihan/
│       │   ├── pembayaran/
│       │   ├── transfer/
│       │   ├── laporan/
│       │   └── user/
│       ├── auth/
│       └── layouts/
├── assets/
│   ├── css/
│   ├── js/
│   └── uploads/
└── system/
```

---

## 📌 Catatan Tambahan

- Fitur **Verifikasi Transfer** memerlukan folder `assets/uploads/bukti_transfer/` dengan izin tulis (`chmod 755` di Linux/Mac)
- Untuk reset password user, Admin dapat menggunakan menu **Pengaturan > User > Edit**
- Laporan dapat diekspor dalam format **PDF** dan **Excel** melalui menu Laporan

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan **Tugas UAS Pemrograman Web**  
Program Studi S1 Ilmu Komputer — Fakultas Teknik — Universitas Bumigora 2026
