<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Bill_model extends CI_Model {
    protected $table = 'bills';
    public function get_all($search='', $status='') {
        $this->db->select('bills.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        if ($status) $this->db->where('bills.status', $status);
        $this->db->order_by('bills.bill_date','DESC');
        return $this->db->get($this->table)->result();
    }
    public function get_all_paginated($search='', $status='', $limit=10, $offset=0) {
        $this->db->select('bills.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        if ($status) $this->db->where('bills.status', $status);
        $this->db->order_by('bills.bill_date','DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }
    public function count_filtered($search='', $status='') {
        $this->db->select('bills.id');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        if ($status) $this->db->where('bills.status', $status);
        return $this->db->get($this->table)->num_rows();
    }
    public function get_by_id($id) {
        $this->db->select('bills.*, tenants.name as tenant_name, tenants.phone, rooms.room_code, rooms.type');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        return $this->db->get_where($this->table, ['bills.id'=>$id])->row();
    }
    public function count_belum_lunas() { return $this->db->where('status','Belum Lunas')->count_all_results($this->table); }
    public function get_terbaru($limit=5) {
        $this->db->select('bills.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        $this->db->order_by('bills.bill_date','DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }
    // Get monthly income for current year (for line chart)
    public function get_monthly_income_chart() {
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $this->db->select_sum('total');
            $this->db->where('status', 'Lunas');
            $this->db->where('MONTH(bill_date)', $m);
            $this->db->where('YEAR(bill_date)', date('Y'));
            $row = $this->db->get($this->table)->row();
            $result[] = (int)($row->total ?? 0);
        }
        return $result;
    }
    // Get bills due within next N days (for notification)
    public function get_jatuh_tempo($days=3) {
        $today    = date('Y-m-d');
        $deadline = date('Y-m-d', strtotime("+{$days} days"));
        $this->db->select('bills.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        $this->db->where('bills.status', 'Belum Lunas');
        $this->db->where('bills.due_date >=', $today);
        $this->db->where('bills.due_date <=', $deadline);
        $this->db->order_by('bills.due_date','ASC');
        return $this->db->get($this->table)->result();
    }
    // Get overdue bills
    public function get_overdue() {
        $today = date('Y-m-d');
        $this->db->select('bills.*, tenants.name as tenant_name, rooms.room_code');
        $this->db->join('tenants','tenants.id = bills.tenant_id');
        $this->db->join('rooms','rooms.id = bills.room_id');
        $this->db->where('bills.status', 'Belum Lunas');
        $this->db->where('bills.due_date <', $today);
        $this->db->order_by('bills.due_date','ASC');
        return $this->db->get($this->table)->result();
    }
    public function insert($data)      { return $this->db->insert($this->table, $data); }
    public function update($id, $data) { return $this->db->where('id',$id)->update($this->table, $data); }
    public function delete($id)        { return $this->db->where('id',$id)->delete($this->table); }
}
