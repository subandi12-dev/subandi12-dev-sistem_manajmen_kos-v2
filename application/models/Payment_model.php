<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_model extends CI_Model {
    protected $table = 'payments';
    public function get_all($search='') {
        $this->db->select('payments.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = payments.tenant_id');
        $this->db->join('rooms','rooms.id = payments.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        $this->db->order_by('payments.pay_date','DESC');
        return $this->db->get($this->table)->result();
    }
    public function get_by_date($from, $to) {
        $this->db->select('payments.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = payments.tenant_id');
        $this->db->join('rooms','rooms.id = payments.room_id');
        $this->db->where('payments.pay_date >=', $from);
        $this->db->where('payments.pay_date <=', $to);
        $this->db->order_by('payments.pay_date','ASC');
        return $this->db->get($this->table)->result();
    }
    public function total_bulan_ini() {
        $this->db->where('MONTH(pay_date)', date('m'));
        $this->db->where('YEAR(pay_date)', date('Y'));
        $this->db->select_sum('amount');
        $row = $this->db->get($this->table)->row();
        return $row->amount ?? 0;
    }
    public function get_by_id($id)     { return $this->db->get_where($this->table, ['id'=>$id])->row(); }
    public function insert($data)      { return $this->db->insert($this->table, $data); }
    public function delete($id)        { return $this->db->where('id',$id)->delete($this->table); }
}
