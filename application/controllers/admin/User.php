<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        // Only admin can access
        if ($this->session->userdata('user_role') !== 'Administrator') {
            $this->session->set_flashdata('error','Anda tidak memiliki akses ke halaman ini!');
            redirect('dashboard');
        }
    }
    public function index() {
        $data = ['title'=>'Pengaturan User','users'=>$this->User_model->get_all()];
        $this->render('admin/user/index', $data);
    }
    public function tambah() { $this->render('admin/user/form', ['title'=>'Tambah User','usr'=>null]); }
    public function simpan() {
        $data = ['name'=>$this->input->post('name',TRUE),'email'=>$this->input->post('email',TRUE),
                 'password'=>password_hash($this->input->post('password',TRUE),PASSWORD_DEFAULT),
                 'role'=>$this->input->post('role',TRUE)];
        $this->User_model->insert($data);
        $this->session->set_flashdata('success','User berhasil ditambahkan!');
        redirect('user');
    }
    public function edit($id) {
        $data = ['title'=>'Edit User','usr'=>$this->User_model->get_by_id($id)];
        $this->render('admin/user/form', $data);
    }
    public function update($id) {
        $data = ['name'=>$this->input->post('name',TRUE),'email'=>$this->input->post('email',TRUE),'role'=>$this->input->post('role',TRUE)];
        if ($this->input->post('password')) $data['password'] = password_hash($this->input->post('password',TRUE),PASSWORD_DEFAULT);
        $this->User_model->update($id, $data);
        $this->session->set_flashdata('success','User berhasil diupdate!');
        redirect('user');
    }
    public function hapus($id) {
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error','Tidak bisa menghapus akun sendiri!');
        } else {
            $this->User_model->delete($id);
            $this->session->set_flashdata('success','User berhasil dihapus!');
        }
        redirect('user');
    }
}
