<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_pda extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }


  public function get_pda()
  {
    $sql = "SELECT a.Id, a.tanggal, a.vessel_name, a.est, a.hpp_rill, b.nama as nama_port, d.no_surat FROM t_pda a LEFT JOIN t_port b ON b.Id = a.port LEFT JOIN t_penunjukan d ON d.Id = a.penunjukan";
    $result = $this->db->query($sql);
    return $result;
  }

  public function getById($id)
  {
    $sql = "SELECT a.Id, a.user, a.no_surat, a.perihal, a.tanggal, a.attn, a.isi, a.item_tetap, a.item_tambahan, a.catatan, b.nama_customer, c.nama FROM t_penawaran a LEFT JOIN t_customer b ON a.tujuan = b.Id LEFT JOIN users c ON a.user = c.nip WHERE a.Id = '$id'";
    $result = $this->db->query($sql);

    return $result;
  }

  public function get_dokumen($limit, $start, $keyword, $id)
  {
    $this->db->select('*')->from('t_dokumen')->where('id_pda', $id);
    if ($keyword) {
      $this->db->like('title', $keyword, 'both');
      $this->db->where('id_pda', $id);
      $this->db->or_like('file', $keyword, 'both');
      $this->db->where('id_pda', $id);
    }
    $result = $this->db->order_by('Id', 'DESC')->limit($limit, $start)->get();
    return $result;
  }

  public function count_dokumen($keyword, $id)
  {
    $this->db->select('*')->from('t_dokumen')->where('id_pda', $id);
    if ($keyword) {
      $this->db->like('title', $keyword, 'both');
      $this->db->where('id_pda', $id);
      $this->db->or_like('file', $keyword, 'both');
      $this->db->where('id_pda', $id);
    }
    return $this->db->get()->num_rows();
  }

  public function get_filtered_data($q, $limit, $offset, $penunjukan, $port, $title)
  {
    // Base query
    $this->db->select('Id, desc');  // Adjust to your table's columns
    $this->db->from('t_item_pda');  // Replace 'your_table' with the actual table name

    // Filter by search term if provided
    if (!empty($q)) {
      $this->db->like('desc', $q);  // Assuming 'name' is the field you're searching
    }

    $this->db->where(['jenis' => $penunjukan, 'port' => $port, 'title' => $title]);

    // Pagination
    $this->db->limit($limit, $offset);

    // Execute the query
    $query = $this->db->get();

    return $query->result();
  }

  public function get_all_requests()
  {
    $this->db->select('t_pda.*,t_penunjukan.no_surat')->from('t_pda');
    $this->db->join('t_penunjukan', 't_pda.penunjukan = t_penunjukan.Id', 'left');
    $this->db->where('er is NOT NULL');
    return $this->db->get()->result();
  }

  public function get_data($id)
  {
    return $this->db->get_where('t_pda', ['Id' => $id])->row();
  }

  public function update_report($id, $data_json, $total = null)
  {
    $update = ['er' => $data_json];
    if ($total !== null) {
      $update['total_expense'] = $total;
    }
    $this->db->where('Id', $id);
    return $this->db->update('t_pda', $update);
  }
}
