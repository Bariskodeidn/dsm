<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_file extends CI_Model
{
    public function search_memos_and_files($keyword)
    {
        $this->db->select('Id, judul, attach_name, attach,tanggal');
        $this->db->from('memo');

        // Mencari berdasarkan judul ATAU attach_name
        $this->db->group_start();
        $this->db->like('judul', $keyword);
        $this->db->or_like('attach_name', $keyword);
        $this->db->group_end();

        $this->db->order_by('Id', 'desc');

        return $this->db->get()->result_array();
    }

    public function get_memo_by_id($id)
    {
        return $this->db->get_where('memo', array('Id' => $id))->row_array();
    }
}
