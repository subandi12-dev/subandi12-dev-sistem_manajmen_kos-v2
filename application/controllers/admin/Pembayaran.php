<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pembayaran extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model(['Payment_model','Tenant_model','Room_model']);
    }
    public function index() {
        $search = $this->input->get('search','');
        $data = ['title'=>'Data Pembayaran','payments'=>$this->Payment_model->get_all($search),'search'=>$search];
        $this->render('admin/pembayaran/index', $data);
    }
    public function tambah() {
        $data = ['title'=>'Tambah Pembayaran','tenants'=>$this->Tenant_model->get_all()];
        $this->render('admin/pembayaran/form', $data);
    }
    public function simpan() {
        $tenant_id = $this->input->post('tenant_id',TRUE);
        $tenant = $this->Tenant_model->get_by_id($tenant_id);
        $data = ['tenant_id'=>$tenant_id,'room_id'=>$tenant->room_id,
                 'month'=>$this->input->post('month',TRUE),'amount'=>$this->input->post('amount',TRUE),
                 'method'=>$this->input->post('method',TRUE),'pay_date'=>$this->input->post('pay_date',TRUE)];
        $this->Payment_model->insert($data);
        $this->session->set_flashdata('success','Pembayaran berhasil dicatat!');
        redirect('pembayaran');
    }
    public function hapus($id) {
        $this->Payment_model->delete($id);
        $this->session->set_flashdata('success','Pembayaran berhasil dihapus!');
        redirect('pembayaran');
    }
}
