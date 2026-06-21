<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Room_model extends CI_Model {
    protected $table = 'rooms';
    public function get_all($search='', $type='') {
        if ($search) $this->db->like('room_code', $search);
        if ($type)   $this->db->where('type', $type);
        return $this->db->order_by('room_code','ASC')->get($this->table)->result();
    }
    public function get_by_id($id)     { return $this->db->get_where($this->table, ['id'=>$id])->row(); }
    public function get_kosong()       { return $this->db->get_where($this->table, ['status'=>'Kosong'])->result(); }
    public function count_all()        { return $this->db->count_all($this->table); }
    public function count_terisi()     { return $this->db->where('status','Terisi')->count_all_results($this->table); }
    public function count_kosong()     { return $this->db->where('status','Kosong')->count_all_results($this->table); }
    public function insert($data)      { return $this->db->insert($this->table, $data); }
    public function update($id, $data) { return $this->db->where('id',$id)->update($this->table, $data); }
    public function delete($id)        { return $this->db->where('id',$id)->delete($this->table); }
}
