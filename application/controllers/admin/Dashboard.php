<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Room_model','Tenant_model','Payment_model','Bill_model']);
    }
    public function index() {
        $total  = $this->Room_model->count_all();
        $terisi = $this->Room_model->count_terisi();

        // Real monthly income data for chart (Jan–Dec current year)
        $monthly_income = $this->Bill_model->get_monthly_income_chart();

        // Notif jatuh tempo (dalam 3 hari) & overdue
        $jatuh_tempo = $this->Bill_model->get_jatuh_tempo(3);
        $overdue     = $this->Bill_model->get_overdue();

        $data = [
            'title'          => 'Dashboard',
            'total_kamar'    => $total,
            'kamar_terisi'   => $terisi,
            'kamar_kosong'   => $this->Room_model->count_kosong(),
            'total_penyewa'  => $this->Tenant_model->count_aktif(),
            'tagihan_belum'  => $this->Bill_model->count_belum_lunas(),
            'pemasukan'      => $this->Payment_model->total_bulan_ini(),
            'pct_terisi'     => $total > 0 ? round($terisi/$total*100) : 0,
            'tagihan_terbaru'=> $this->Bill_model->get_terbaru(5),
            'monthly_income' => json_encode($monthly_income),
            'jatuh_tempo'    => $jatuh_tempo,
            'overdue'        => $overdue,
        ];
        $this->render('admin/dashboard/index', $data);
    }
}
