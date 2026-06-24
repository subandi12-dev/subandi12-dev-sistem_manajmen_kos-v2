<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer extends Admin_Controller {

    // Info rekening tujuan (bisa dipindah ke config/db settings)
    protected $rekening = [
        'BCA'     => ['no' => '1234567890', 'nama' => 'Kos Bahagia'],
        'BRI'     => ['no' => '0987654321', 'nama' => 'Kos Bahagia'],
        'Mandiri' => ['no' => '1122334455', 'nama' => 'Kos Bahagia'],
        'BNI'     => ['no' => '5566778899', 'nama' => 'Kos Bahagia'],
    ];

    public function __construct() {
        parent::__construct();
        $this->load->model(['Transfer_model','Bill_model','Tenant_model']);
        $this->load->library('upload');
    }

    // ===== HALAMAN FORM KONFIRMASI TRANSFER (diakses dari tagihan) =====
    public function form($bill_id) {
        $bill = $this->Bill_model->get_by_id($bill_id);
        if (!$bill) { show_404(); return; }

        // Cek sudah ada pending transfer belum
        $existing = $this->Transfer_model->get_by_bill($bill_id);

        $data = [
            'title'    => 'Konfirmasi Transfer',
            'bill'     => $bill,
            'rekening' => $this->rekening,
            'existing' => $existing,
        ];
        $this->render('admin/transfer/form', $data);
    }

    // ===== SIMPAN UPLOAD BUKTI TRANSFER =====
    public function simpan() {
        $bill_id = $this->input->post('bill_id', TRUE);
        $bill    = $this->Bill_model->get_by_id($bill_id);
        if (!$bill) { redirect('tagihan'); return; }

        // Konfigurasi upload
        $upload_path = FCPATH . 'assets/uploads/bukti_transfer/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, TRUE);

        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'jpg|jpeg|png|pdf',
            'max_size'      => 5120, // 5MB
            'encrypt_name'  => TRUE,
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('bukti_file')) {
            $this->session->set_flashdata('error', 'Gagal upload: ' . $this->upload->display_errors('',''));
            redirect('transfer/form/' . $bill_id);
            return;
        }

        $file = $this->upload->data();

        $transfer_data = [
            'bill_id'         => $bill_id,
            'tenant_id'       => $bill->tenant_id,
            'bank_name'       => $this->input->post('bank_name', TRUE),
            'account_number'  => $this->input->post('account_number', TRUE),
            'transfer_date'   => $this->input->post('transfer_date', TRUE),
            'amount'          => $bill->total,
            'bukti_file'      => $file['file_name'],
            'catatan_penyewa' => $this->input->post('catatan_penyewa', TRUE),
            'status'          => 'Menunggu',
        ];

        $this->Transfer_model->insert($transfer_data);
        // Update status tagihan jadi Menunggu Verifikasi
        $this->Bill_model->update($bill_id, ['status' => 'Menunggu Verifikasi']);

        $this->session->set_flashdata('success', 'Bukti transfer berhasil dikirim! Menunggu verifikasi admin.');
        redirect('tagihan');
    }

    // ===== HALAMAN VERIFIKASI ADMIN =====
    public function verifikasi() {
        $filter = $this->input->get('status', '');
        $data = [
            'title'    => 'Verifikasi Transfer',
            'list'     => $this->Transfer_model->get_all($filter),
            'filter'   => $filter,
            'total_menunggu' => $this->Transfer_model->count_menunggu(),
        ];
        $this->render('admin/transfer/verifikasi', $data);
    }

    // ===== DETAIL VERIFIKASI =====
    public function detail($id) {
        $transfer = $this->Transfer_model->get_by_id($id);
        if (!$transfer) { show_404(); return; }
        $data = [
            'title'    => 'Detail Verifikasi Transfer',
            'transfer' => $transfer,
            'rekening' => $this->rekening,
        ];
        $this->render('admin/transfer/detail', $data);
    }

    // ===== KONFIRMASI LUNAS =====
    public function konfirmasi($id) {
        $transfer = $this->Transfer_model->get_by_id($id);
        if (!$transfer) { redirect('transfer/verifikasi'); return; }

        $user_id = $this->session->userdata('user_id');
        $this->Transfer_model->update($id, [
            'status'      => 'Dikonfirmasi',
            'verified_by' => $user_id,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
        // Update tagihan jadi Lunas
        $this->Bill_model->update($transfer->bill_id, [
            'status'  => 'Lunas',
            'paid_at' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('success', 'Transfer dikonfirmasi! Tagihan '.$transfer->tenant_name.' bulan '.$transfer->month.' telah ditandai Lunas.');
        redirect('transfer/verifikasi');
    }

    // ===== TOLAK TRANSFER =====
    public function tolak($id) {
        $transfer = $this->Transfer_model->get_by_id($id);
        if (!$transfer) { redirect('transfer/verifikasi'); return; }

        $catatan = $this->input->post('catatan_admin', TRUE);
        $user_id = $this->session->userdata('user_id');

        $this->Transfer_model->update($id, [
            'status'        => 'Ditolak',
            'catatan_admin' => $catatan,
            'verified_by'   => $user_id,
            'verified_at'   => date('Y-m-d H:i:s'),
        ]);
        // Kembalikan status tagihan ke Belum Lunas
        $this->Bill_model->update($transfer->bill_id, ['status' => 'Belum Lunas']);

        $this->session->set_flashdata('error', 'Transfer ditolak. Tagihan dikembalikan ke status Belum Lunas.');
        redirect('transfer/verifikasi');
    }
}
