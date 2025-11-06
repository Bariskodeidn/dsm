<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_app extends CI_Model
{
  public function __construct()
  {
    parent::__construct(); // Call the parent constructor
  }

  public function search_user_memo($keyword = '', $limit = 10, $offset = 0)
  {
    $this->db->select('username, nama');
    $this->db->from('users');

    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    if (!empty($keyword)) {
      $this->db->like('nama', $keyword);
    }

    if ($this->session->userdata('level_jabatan') == 1) {

      $this->db->group_start();
      $this->db->where('level_jabatan', 1);
      $this->db->or_where('level_jabatan', 3);
      $this->db->where('bagian', $this->session->userdata('bagian'));
      $this->db->group_end();
    } else if ($this->session->userdata('level_jabatan') == 2) {
      // Selects records where the level_jabatan is 1 AND the 'bagian' is the same as the session
      $this->db->group_start();
      $this->db->where('level_jabatan', 1);
      $this->db->where('bagian', $this->session->userdata('bagian'));
      $this->db->group_end();

      // OR selects records where the level_jabatan is 3 OR 5
      $this->db->or_group_start();
      $this->db->where_in('level_jabatan', [3, 5]);
      $this->db->group_end();
    }
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('username !=', $this->session->userdata('username'));

    $this->db->order_by('level_jabatan', 'Desc');

    $this->db->limit($limit, $offset);
    $query = $this->db->get();

    return $query->result();
  }

  public function memo_count($nip, $keyword)
  {
    $this->db->select('Id')->from('memo')
      // ->where('id_perusahaan', $this->session->userdata('user_perusahaan_id'))
      ->group_start()
      // ->like('nip_kpd', $nip, 'both')
      // ->or_like('nip_cc', $nip, 'both')

      ->like('CONCAT(";", nip_kpd, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", nip_cc, ";")', ';' . $nip . ';', 'both')
      ->group_end();
    if ($keyword) {
      $this->db->like('judul', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }

  public function memo_get($limit, $start, $nip, $keyword)
  {
    $this->db->select('a.Id, a.nomor_memo, a.nip_kpd, a.judul, a.tanggal, a.read, a.nip_dari, b.nama')->from('memo a')->join('users b', 'a.nip_dari = b.username', 'left')
      // ->where('id_perusahaan', $this->session->userdata('user_perusahaan_id'))
      ->group_start()
      // ->like('nip_kpd', $nip, 'both')
      // ->or_like('nip_cc', $nip, 'both')

      ->like('CONCAT(";", nip_kpd, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", nip_cc, ";")', ';' . $nip . ';', 'both')
      ->group_end();
    if ($keyword) {
      $this->db->like('judul', $keyword, 'both');
    }
    $this->db->order_by('a.tanggal', 'DESC');
    return $this->db->limit($limit, $start)->get()->result();
  }

  public function memo_get_detail($id)
  {
    $nip = $this->session->userdata('username');
    $result = $this->db->select('read')->from('memo')->not_like('read', $nip)->where('Id', $id)->get()->row();

    if ($result) {
      $kalimat = $result->read;
      $kalimat1 = $kalimat . ' ' . $nip;
      $data_update1 = array(
        'read' => $kalimat1
      );
      $this->db->where('Id', $id);
      $this->db->update('memo', $data_update1);
    }

    $nip = $this->session->userdata('username');

    $query = $this->db->select('a.*,b.nama_jabatan,b.nama,b.supervisi,c.kode_nama,b.level_jabatan')->from('memo a')->join('users b', 'b.username = a.nip_dari', 'LEFT')->join('bagian c', 'b.bagian = c.kode_nama', 'left')->where('a.Id', $id)->group_start()
      // ->like('a.nip_dari', $nip, 'both')
      // ->or_like('a.nip_kpd', $nip, 'both')
      // ->or_like('a.nip_cc', $nip, 'both')
      ->like('CONCAT(";", a.nip_dari, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", a.nip_kpd, ";")', ';' . $nip . ';', 'both')
      ->or_like('CONCAT(";", a.nip_cc, ";")', ';' . $nip . ';', 'both')

      ->group_end()->get();
    return $query->row();
  }

  public function memo_count_outbox($nip, $keyword)
  {
    $this->db->select('Id')->from('memo')
      ->group_start()
      ->like('CONCAT(";", nip_dari, ";")', ';' . $nip . ';', 'both')
      ->group_end();
    if ($keyword) {
      $this->db->like('judul', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }

  public function memo_get_outbox($limit, $start, $nip, $keyword)
  {
    $this->db->select('a.Id, a.nomor_memo, a.nip_kpd, a.judul, a.tanggal, a.read, a.nip_dari, b.nama, a.id_perusahaan')->from('memo a')->join('users b', 'a.nip_dari = b.username', 'left')
      ->group_start()
      ->like('CONCAT(";", nip_dari, ";")', ';' . $nip . ';', 'both')
      ->group_end();
    if ($keyword) {
      $this->db->like('judul', $keyword, 'both');
    }
    $this->db->order_by('a.tanggal', 'DESC');
    return $this->db->limit($limit, $start)->get()->result();
  }
}
