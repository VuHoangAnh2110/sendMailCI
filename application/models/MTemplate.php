<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MTemplate extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Lấy danh sách tất cả các template
    public function get_list() {
        $this->db->select("*");
        $this->db->from("template_mail");
        $query = $this->db->get();
        return $query->result();
    }

    // Lấy thông tin template theo ID
    public function get_template($id) {
            $this->db->select("*");
            $this->db->from("template_mail");
            $this->db->where("id_template", $id);
            $query = $this->db->get();
            return $query->row();
    }

    // Chèn template mới vào cơ sở dữ liệu
    public function insert_template($data) {
        $this->db->insert("template_mail", $data);
        return $this->db->insert_id();
    }

}
