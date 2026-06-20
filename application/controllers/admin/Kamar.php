<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kamar extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Room_model');
    }
    public function index() {
        $search = $this->input->get('search','');
        $type   = $this->input->get('type','');
        $data = ['title'=>'Data Kamar','rooms'=>$this->Room_model->get_all($search,$type),'search'=>$search,'type'=>$type];
        $this->render('admin/kamar/index', $data);
    }
    public function tambah() { $this->render('admin/kamar/form', ['title'=>'Tambah Kamar','room'=>null]); }
    public function simpan() {
        $data = ['room_code'=>$this->input->post('room_code',TRUE),'type'=>$this->input->post('type',TRUE),
                 'price'=>$this->input->post('price',TRUE),'status'=>$this->input->post('status',TRUE)];
        $this->Room_model->insert($data);
        $this->session->set_flashdata('success','Kamar berhasil ditambahkan!');
        redirect('kamar');
    }
    public function edit($id) {
        $data = ['title'=>'Edit Kamar','room'=>$this->Room_model->get_by_id($id)];
        $this->render('admin/kamar/form', $data);
    }
    public function update($id) {
        $data = ['room_code'=>$this->input->post('room_code',TRUE),'type'=>$this->input->post('type',TRUE),
                 'price'=>$this->input->post('price',TRUE),'status'=>$this->input->post('status',TRUE)];
        $this->Room_model->update($id, $data);
        $this->session->set_flashdata('success','Kamar berhasil diupdate!');
        redirect('kamar');
    }
    public function hapus($id) {
        $this->Room_model->delete($id);
        $this->session->set_flashdata('success','Kamar berhasil dihapus!');
        redirect('kamar');
    }
}
