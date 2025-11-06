<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_agency extends CI_Model
{
  public function __construct()
  {
    parent::__construct(); // Call the parent constructor
  }

  public function get_port($limit, $start, $keyword)
  {
    $this->db->select('a.*, b.nama as nama_cabang')->from('agency_port a')->join('agency_cabang b', 'a.id_cabang = b.Id', 'left');
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('a.nama', $keyword, 'both');
      $this->db->or_like('a.kode', $keyword, 'both');
      $this->db->or_like('b.nama', $keyword, 'both');
      $this->db->group_end();
    }
    $result = $this->db->order_by('Id', 'DESC')->limit($limit, $start)->get();
    return $result;
  }

  public function count_port($keyword)
  {
    $this->db->select('a.*, b.nama as nama_cabang')->from('agency_port a')->join('agency_cabang b', 'a.id_cabang = b.Id', 'left');
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('a.nama', $keyword, 'both');
      $this->db->or_like('a.kode', $keyword, 'both');
      $this->db->or_like('b.nama', $keyword, 'both');
      $this->db->group_end();
    }
    return $this->db->get()->num_rows();
  }


  public function get_customer($limit, $start, $keyword)
  {
    $this->db->select('a.*, b.nama as nama_cabang')->from('agency_customer a')->join('agency_cabang b', 'a.id_cabang = b.Id', 'left');
    if ($keyword) {
      $this->db->like('a.nama_customer', $keyword, 'both');
      $this->db->or_like('a.kode', $keyword, 'both');
      $this->db->or_like('b.nama', $keyword, 'both');
    }
    $result = $this->db->order_by('Id', 'DESC')->limit($limit, $start)->get();
    return $result;
  }

  public function count_customer($keyword)
  {
    $this->db->select('a.*, b.nama as nama_cabang')->from('agency_customer a')->join('agency_cabang b', 'a.id_cabang = b.Id', 'left');
    if ($keyword) {
      $this->db->like('a.nama_customer', $keyword, 'both');
      $this->db->or_like('a.kode', $keyword, 'both');
      $this->db->or_like('b.nama', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }
}
