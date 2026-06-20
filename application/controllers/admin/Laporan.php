<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');
    }
    public function index() {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to')   ?: date('Y-m-d');
        $payments = $this->input->get('from') ? $this->Payment_model->get_by_date($from,$to) : [];
        $total = array_sum(array_column((array)$payments,'amount'));
        $data = ['title'=>'Laporan Pembayaran','payments'=>$payments,'from'=>$from,'to'=>$to,'total'=>$total];
        $this->render('admin/laporan/index', $data);
    }
    public function export_pdf() {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to')   ?: date('Y-m-d');
        $payments = $this->Payment_model->get_by_date($from,$to);
        $total = array_sum(array_column((array)$payments,'amount'));
        $data = ['payments'=>$payments,'from'=>$from,'to'=>$to,'total'=>$total];
        $this->load->view('admin/laporan/export_pdf', $data);
    }
    public function export_excel() {
        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to')   ?: date('Y-m-d');
        $payments = $this->Payment_model->get_by_date($from,$to);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Laporan_Pembayaran_'.$from.'_sd_'.$to.'.xls"');
        $data = ['payments'=>$payments,'from'=>$from,'to'=>$to,'total'=>array_sum(array_column((array)$payments,'amount'))];
        $this->load->view('admin/laporan/export_excel', $data);
    }
}
