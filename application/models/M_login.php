<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function ambilPengguna($username, $status)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('username', $username);
    $this->db->where('status', $status);
    $query = $this->db->get();

    return $query->num_rows();
  }

  public function password() {}

  public function dataPengguna($username)
  {
    $this->db->select('*');
    $this->db->where('username', $username);
    $query = $this->db->get('users');

    return $query->row();
  }

  public function utility()
  {
    $this->db->select('*');
    $this->db->where('Id', 1);
    $query = $this->db->get('utility');

    return $query->row();
  }

  public function register_user($data)
  {
    return $this->db->insert('users', $data);
  }
  public function register_user_access($data)
  {
    return $this->db->insert('user_menu_access', $data);
  }
  public function register_perusahaan($data)
  {
    $this->db->insert('utility', $data);

    if ($this->db->affected_rows() > 0) {
      // Return the ID of the newly inserted record
      return $this->db->insert_id();
    } else {
      // If the insert failed, return false or null to indicate failure
      // return FALSE; // Or return null;
      return $this->db->error();
    }
  }
  public function register_cabang($data)
  {
    $this->cb->insert('t_cabang', $data);

    if ($this->cb->affected_rows() > 0) {
      // Return the ID of the newly inserted record
      return $this->cb->insert_id();
    } else {
      // If the insert failed, return false or null to indicate failure
      // return FALSE; // Or return null;
      return $this->cb->error();
    }
  }
  public function cekPengguna($username, $status)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('username', $username);
    $this->db->where('status', $status);
    $query = $this->db->get();

    return $query->row();
  }
  public function cekPenggunaForget($username)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('username', $username);
    $query = $this->db->get();

    return $query->row();
  }
  public function register_coa($data)
  {
    return $this->db->insert('t_coa_sbb', $data);
  }
}
