<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSendMail extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Lấy danh sách tất cả các email
    public function get_list() {
        $this->db->select("*");
        $this->db->from("mail");
        $query = $this->db->get();
        return $query->result();
    }

    // Lấy thông tin email theo ID
    public function get_mail($id) {
        if (is_numeric($id) && $id > 0) {
            $this->db->select("*");
            $this->db->from("mail");
            $this->db->where("id", $id);
            $query = $this->db->get();
            return $query->result();
        } else {
            return false;
        }
    }

    // Chèn email mới vào cơ sở dữ liệu
    public function insert_mail($data) {
        $this->db->insert("mail", $data);
        return $this->db->insert_id();
    }

    // Lấy danh sách email có trạng thái 'chưa' để gửi
    public function get_pending_emails() {
        $this->db->select("*");
        $this->db->from("mail");
        $this->db->where("trang_thai", "chưa");
        $query = $this->db->get();
        return $query->result();
    }

    // Cập nhật trạng thái email sau khi gửi
    public function update_mail($id, $status) {
        $this->db->where("id", $id);
        $this->db->update("mail", [
            "trang_thai" => $status,
            "thoi_gian_gui" => date("Y-m-d H:i:s") // Lưu thời gian gửi thành công
        ]);
    }

}
