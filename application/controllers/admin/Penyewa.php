<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Penyewa extends Admin_Controller {
    protected $per_page = 10;
    public function __construct() {
        parent::__construct();
        $this->load->model(['Tenant_model','Room_model']);
        $this->load->library('pagination');
    }
    public function index() {
        $search  = $this->input->get('search','');
        $page    = max(1, (int)$this->input->get('page', 1));
        $offset  = ($page - 1) * $this->per_page;
        $total   = $this->Tenant_model->count_filtered($search);
        $tenants = $this->Tenant_model->get_all_paginated($search, $this->per_page, $offset);

        // Pagination config
        $total_pages = ceil($total / $this->per_page);
        $base_url    = site_url('penyewa') . '?search=' . urlencode($search) . '&page=';

        $data = [
            'title'       => 'Data Penyewa',
            'tenants'     => $tenants,
            'search'      => $search,
            'total'       => $total,
            'per_page'    => $this->per_page,
            'current_page'=> $page,
            'total_pages' => $total_pages,
            'base_url'    => $base_url,
            'offset'      => $offset,
        ];
        $this->render('admin/penyewa/index', $data);
    }
    public function tambah() {
        $data = ['title'=>'Tambah Penyewa','tenant'=>null,'rooms'=>$this->Room_model->get_kosong()];
        $this->render('admin/penyewa/form', $data);
    }
    public function simpan() {
        $room_id = $this->input->post('room_id',TRUE);
        $data = ['name'=>$this->input->post('name',TRUE),'room_id'=>$room_id,
                 'phone'=>$this->input->post('phone',TRUE),'start_date'=>$this->input->post('start_date',TRUE)];
        $this->Tenant_model->insert($data);
        $this->Room_model->update($room_id, ['status'=>'Terisi']);
        $this->session->set_flashdata('success','Penyewa berhasil ditambahkan!');
        redirect('penyewa');
    }
    public function edit($id) {
        $tenant = $this->Tenant_model->get_by_id($id);
        $data = ['title'=>'Edit Penyewa','tenant'=>$tenant,'rooms'=>$this->Room_model->get_all()];
        $this->render('admin/penyewa/form', $data);
    }
    public function update($id) {
        $old     = $this->Tenant_model->get_by_id($id);
        $room_id = $this->input->post('room_id',TRUE);
        $data = ['name'=>$this->input->post('name',TRUE),'room_id'=>$room_id,
                 'phone'=>$this->input->post('phone',TRUE),'start_date'=>$this->input->post('start_date',TRUE)];
        if ($old->room_id != $room_id) {
            $this->Room_model->update($old->room_id, ['status'=>'Kosong']);
            $this->Room_model->update($room_id, ['status'=>'Terisi']);
        }
        $this->Tenant_model->update($id, $data);
        $this->session->set_flashdata('success','Penyewa berhasil diupdate!');
        redirect('penyewa');
    }
    public function hapus($id) {
        $tenant = $this->Tenant_model->get_by_id($id);
        $this->Room_model->update($tenant->room_id, ['status'=>'Kosong']);
        $this->Tenant_model->delete($id);
        $this->session->set_flashdata('success','Penyewa berhasil dihapus!');
        redirect('penyewa');
    }
}
