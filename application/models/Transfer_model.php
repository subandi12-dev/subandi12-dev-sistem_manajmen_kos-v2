<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_model extends CI_Model {
    protected $table = 'transfer_bukti';

    /** Semua transfer pending (untuk halaman verifikasi admin) */
    public function get_all($status = '') {
        $this->db->select('transfer_bukti.*, bills.month, bills.total as bill_total,
                           tenants.name as tenant_name, tenants.phone,
                           rooms.room_code, users.name as verified_by_name');
        $this->db->join('bills',   'bills.id   = transfer_bukti.bill_id');
        $this->db->join('tenants', 'tenants.id = transfer_bukti.tenant_id');
        $this->db->join('rooms',   'rooms.id   = bills.room_id');
        $this->db->join('users',   'users.id   = transfer_bukti.verified_by', 'left');
        if ($status) $this->db->where('transfer_bukti.status', $status);
        $this->db->order_by('transfer_bukti.created_at','DESC');
        return $this->db->get($this->table)->result();
    }

    /** Count per status untuk badge sidebar */
    public function count_menunggu() {
        return $this->db->where('status','Menunggu')->count_all_results($this->table);
    }

    public function get_by_id($id) {
        $this->db->select('transfer_bukti.*, bills.month, bills.total as bill_total,
                           bills.rent, bills.electric, bills.water,
                           tenants.name as tenant_name, tenants.phone,
                           rooms.room_code, rooms.type as room_type,
                           users.name as verified_by_name');
        $this->db->join('bills',   'bills.id   = transfer_bukti.bill_id');
        $this->db->join('tenants', 'tenants.id = transfer_bukti.tenant_id');
        $this->db->join('rooms',   'rooms.id   = bills.room_id');
        $this->db->join('users',   'users.id   = transfer_bukti.verified_by', 'left');
        return $this->db->get_where($this->table, ['transfer_bukti.id' => $id])->row();
    }

    /** Cek apakah tagihan sudah ada bukti transfer pending */
    public function get_by_bill($bill_id) {
        return $this->db->get_where($this->table, ['bill_id' => $bill_id, 'status' => 'Menunggu'])->row();
    }

    public function insert($data) { return $this->db->insert($this->table, $data); }
    public function update($id, $data) { return $this->db->where('id',$id)->update($this->table, $data); }
}
