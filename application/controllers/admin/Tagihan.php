<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tagihan extends Admin_Controller {
    protected $per_page = 10;
    public function __construct() {
        parent::__construct();
        $this->load->model(['Bill_model','Tenant_model','Room_model']);
        $this->load->library('pagination');
    }
    public function index() {
        $search  = $this->input->get('search','');
        $status  = $this->input->get('status','');
        $page    = max(1, (int)$this->input->get('page', 1));
        $offset  = ($page - 1) * $this->per_page;
        $total   = $this->Bill_model->count_filtered($search, $status);
        $bills   = $this->Bill_model->get_all_paginated($search, $status, $this->per_page, $offset);

        $total_pages = ceil($total / $this->per_page);
        $base_url    = site_url('tagihan') . '?search=' . urlencode($search) . '&status=' . urlencode($status) . '&page=';

        $data = [
            'title'        => 'Data Tagihan',
            'bills'        => $bills,
            'search'       => $search,
            'status'       => $status,
            'total'        => $total,
            'per_page'     => $this->per_page,
            'current_page' => $page,
            'total_pages'  => $total_pages,
            'base_url'     => $base_url,
            'offset'       => $offset,
        ];
        $this->render('admin/tagihan/index', $data);
    }
    public function buat() {
        $data = ['title'=>'Buat Tagihan','tenants'=>$this->Tenant_model->get_all()];
        $this->render('admin/tagihan/form', $data);
    }
    public function simpan() {
        $tenant_id = $this->input->post('tenant_id',TRUE);
        $tenant    = $this->Tenant_model->get_by_id($tenant_id);
        $rent      = $tenant->price;
        $electric  = $this->input->post('electric',TRUE) ?: 0;
        $water     = $this->input->post('water',TRUE) ?: 0;
        $data = ['tenant_id'=>$tenant_id,'room_id'=>$tenant->room_id,
                 'month'=>$this->input->post('month',TRUE),'rent'=>$rent,
                 'electric'=>$electric,'water'=>$water,'total'=>$rent+$electric+$water,
                 'bill_date'=>$this->input->post('bill_date',TRUE),'due_date'=>$this->input->post('due_date',TRUE)];
        $this->Bill_model->insert($data);
        $this->session->set_flashdata('success','Tagihan berhasil dibuat!');
        redirect('tagihan');
    }
    public function detail($id) {
        $data = ['title'=>'Detail Tagihan','bill'=>$this->Bill_model->get_by_id($id)];
        $this->render('admin/tagihan/detail', $data);
    }
    public function lunas($id) {
        $this->Bill_model->update($id, ['status'=>'Lunas', 'paid_at'=>date('Y-m-d H:i:s')]);
        $this->session->set_flashdata('success','Tagihan ditandai Lunas!');
        redirect('tagihan');
    }
    public function hapus($id) {
        $this->Bill_model->delete($id);
        $this->session->set_flashdata('success','Tagihan berhasil dihapus!');
        redirect('tagihan');
    }
    public function cetak($id) {
        $data = ['title'=>'Cetak Tagihan','bill'=>$this->Bill_model->get_by_id($id)];
        $this->load->view('admin/tagihan/cetak', $data);
    }
}
