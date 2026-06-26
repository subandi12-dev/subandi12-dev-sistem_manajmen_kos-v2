<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    protected $table = 'users';
    public function get_all()           { return $this->db->get($this->table)->result(); }
    public function get_by_id($id)      { return $this->db->get_where($this->table, ['id'=>$id])->row(); }
    public function get_by_email($email){ return $this->db->get_where($this->table, ['email'=>$email])->row(); }
    public function insert($data)       { return $this->db->insert($this->table, $data); }
    public function update($id, $data)  { return $this->db->where('id',$id)->update($this->table, $data); }
    public function delete($id)         { return $this->db->where('id',$id)->delete($this->table); }
}
