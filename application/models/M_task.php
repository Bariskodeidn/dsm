<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_task extends CI_Model
{
  protected $cb; // Declare the property

  public function __construct()
  {
    parent::__construct(); // Call the parent constructor
    $this->cb = $this->load->database('corebank', TRUE);
  }
  public function search_user_task($keyword = '', $limit = 10, $offset = 0)
  {
    $this->db->select('nip, nama');
    $this->db->from('users');

    $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');

    if (!empty($keyword)) {
      $this->db->like('nama', $keyword);
    }
    // if ($this->session->userdata('level_jabatan') >= '3') {
    //   $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
    //   // $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    //   $this->db->where('id_cabang', $this->session->userdata('kode_cabang'));
    // } else {
    //   $this->db->where('id_cabang', $this->session->userdata('kode_cabang'));
    //   $this->db->where('users.bagian', $this->session->userdata('bagian'));
    // }
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('nip !=', $this->session->userdata('nip'));
    $this->db->limit($limit, $offset);
    $query = $this->db->get();

    return $query->result();
  }

  public function task_count($nip, $keyword)
  {
    $this->db->select('Id')->from('task')
      ->group_start()
      // ->like('member', $nip, 'both')
      // ->or_like('pic', $nip, 'both')
      ->like('CONCAT(";", member, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", pic, ";")', ';' . $nip . ';', 'both')

      ->group_end();
    if ($keyword) {
      $this->db->like('name', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }

  public function task_get($limit, $start, $nip, $keyword)
  {
    $this->db->select('a.Id, a.name, a.read, a.pic, a.activity, a.date_created, b.nama')->from('task a')->join('users b', 'b.nip = a.pic')
      ->group_start()
      // ->like('member', $nip, 'both')
      // ->or_like('pic', $nip, 'both')
      ->like('CONCAT(";", member, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", pic, ";")', ';' . $nip . ';', 'both')
      ->group_end();
    if ($keyword) {
      $this->db->like('name', $keyword, 'both');
    }
    $this->db->order_by('a.activity', 'ASC')->order_by('a.date_created', 'DESC');
    return $this->db->limit($limit, $start)->get()->result();
  }

  public function task_get_detail($id)
  {
    $nip = $this->session->userdata('nip');
    $sql = "SELECT task.read FROM task WHERE task.read NOT LIKE '%$nip%' OR task.id='$id'";
    $query = $this->db->query($sql);
    $result = $query->row();
    $kalimat = $result->read;

    if ($result) {
      $kalimat1 = $kalimat . ' ' . $nip;
      $data_update1    = array(
        'read'    => $kalimat1
      );
      $this->db->where('id', $id);
      $this->db->update('task', $data_update1);
    }

    $sql2 = "SELECT * FROM task where id='$id'";
    $query = $this->db->query($sql2);
    return $query->row();
  }

  public function sendto($level_jabatan, $bagian)
  {
    // if ($level_jabatan == 2) {
    //   $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 3) {
    //   $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) AND level like '%601%' ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 4) {
    //   $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 5 and $bagian <> 11) {
    //   $sql = "SELECT * FROM users WHERE level_jabatan >= 1 ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 5 and $bagian == 11) {
    //   $sql = "SELECT * FROM users WHERE (level_jabatan >= 1 OR bagian = 4) ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 6) {
    //   $sql = "SELECT * FROM users WHERE level_jabatan >= 1 ORDER BY level_jabatan DESC";
    // } elseif ($level_jabatan == 1) {
    //   $sql = "SELECT * FROM users WHERE bagian = '$bagian' ORDER BY level_jabatan DESC";
    // }

    $sql = "SELECT * FROM users WHERE bagian = '$bagian' ORDER BY level_jabatan DESC";

    $query = $this->db->query($sql);
    return $query->result();
  }
}
