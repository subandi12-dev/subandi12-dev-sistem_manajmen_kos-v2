<?php defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth
$route['login']  = 'Auth/index';
$route['logout'] = 'Auth/logout';
$route['proses_login'] = 'Auth/proses_login';

// Dashboard
$route['dashboard'] = 'admin/Dashboard/index';

// Kamar
$route['kamar']             = 'admin/Kamar/index';
$route['kamar/tambah']      = 'admin/Kamar/tambah';
$route['kamar/simpan']      = 'admin/Kamar/simpan';
$route['kamar/edit/(:num)'] = 'admin/Kamar/edit/$1';
$route['kamar/update/(:num)'] = 'admin/Kamar/update/$1';
$route['kamar/hapus/(:num)'] = 'admin/Kamar/hapus/$1';

// Penyewa
$route['penyewa']             = 'admin/Penyewa/index';
$route['penyewa/tambah']      = 'admin/Penyewa/tambah';
$route['penyewa/simpan']      = 'admin/Penyewa/simpan';
$route['penyewa/edit/(:num)'] = 'admin/Penyewa/edit/$1';
$route['penyewa/update/(:num)'] = 'admin/Penyewa/update/$1';
$route['penyewa/hapus/(:num)'] = 'admin/Penyewa/hapus/$1';

// Pembayaran
$route['pembayaran']             = 'admin/Pembayaran/index';
$route['pembayaran/tambah']      = 'admin/Pembayaran/tambah';
$route['pembayaran/simpan']      = 'admin/Pembayaran/simpan';
$route['pembayaran/hapus/(:num)'] = 'admin/Pembayaran/hapus/$1';

// Tagihan
$route['tagihan']             = 'admin/Tagihan/index';
$route['tagihan/buat']        = 'admin/Tagihan/buat';
$route['tagihan/simpan']      = 'admin/Tagihan/simpan';
$route['tagihan/detail/(:num)'] = 'admin/Tagihan/detail/$1';
$route['tagihan/lunas/(:num)'] = 'admin/Tagihan/lunas/$1';
$route['tagihan/hapus/(:num)'] = 'admin/Tagihan/hapus/$1';
$route['tagihan/cetak/(:num)'] = 'admin/Tagihan/cetak/$1';

// Laporan
$route['laporan']        = 'admin/Laporan/index';
$route['laporan/export_pdf']   = 'admin/Laporan/export_pdf';
$route['laporan/export_excel'] = 'admin/Laporan/export_excel';

// User
$route['user']             = 'admin/User/index';
$route['user/tambah']      = 'admin/User/tambah';
$route['user/simpan']      = 'admin/User/simpan';
$route['user/edit/(:num)'] = 'admin/User/edit/$1';
$route['user/update/(:num)'] = 'admin/User/update/$1';
$route['user/hapus/(:num)'] = 'admin/User/hapus/$1';

// Transfer & Verifikasi
$route['transfer/form/(:num)']      = 'admin/Transfer/form/$1';
$route['transfer/simpan']           = 'admin/Transfer/simpan';
$route['transfer/verifikasi']       = 'admin/Transfer/verifikasi';
$route['transfer/detail/(:num)']    = 'admin/Transfer/detail/$1';
$route['transfer/konfirmasi/(:num)']= 'admin/Transfer/konfirmasi/$1';
$route['transfer/tolak/(:num)']     = 'admin/Transfer/tolak/$1';
