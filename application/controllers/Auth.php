<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) redirect('dashboard');
        $this->load->view('auth/login');
    }

    public function proses_login() {
        $email    = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->User_model->get_by_email($email);
        if ($user && password_verify($password, $user->password)) {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'user_email'=> $user->email,
                'user_role' => $user->role,
            ]);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Email atau password salah!');
            redirect('login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
