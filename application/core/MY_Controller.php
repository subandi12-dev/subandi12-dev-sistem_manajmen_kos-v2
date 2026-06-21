<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
}

class Admin_Controller extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    protected function render($view, $data = []) {
        $data['user'] = $this->session->userdata();
        $this->load->view('layouts/header', $data);
        $this->load->view('layouts/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('layouts/footer', $data);
    }
}
