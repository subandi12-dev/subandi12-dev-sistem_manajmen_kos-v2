<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tenant_model extends CI_Model {
    protected $table = 'tenants';
    public function get_all($search='') {
        $this->db->select('tenants.*, rooms.room_code, rooms.type, rooms.price');
        $this->db->join('rooms','rooms.id = tenants.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        $this->db->where('tenants.status','Aktif');
        return $this->db->get($this->table)->result();
    }
    public function get_all_paginated($search='', $limit=10, $offset=0) {
        $this->db->select('tenants.*, rooms.room_code, rooms.type, rooms.price');
        $this->db->join('rooms','rooms.id = tenants.room_id');
        if ($search) $this->db->like('tenants.name', $search);
        $this->db->where('tenants.status','Aktif');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->table)->result();
    }
    public function count_filtered($search='') {
        if ($search) $this->db->like('name', $search);
        $this->db->where('status','Aktif');
        return $this->db->count_all_results($this->table);
    }
    public function get_by_id($id) {
        $this->db->select('tenants.*, rooms.room_code, rooms.type, rooms.price');
        $this->db->join('rooms','rooms.id = tenants.room_id');
        return $this->db->get_where($this->table, ['tenants.id'=>$id])->row();
    }
    public function count_aktif()      { return $this->db->where('status','Aktif')->count_all_results($this->table); }
    public function insert($data)      { return $this->db->insert($this->table, $data); }
    public function update($id, $data) { return $this->db->where('id',$id)->update($this->table, $data); }
    public function delete($id)        { return $this->db->where('id',$id)->delete($this->table); }
}
