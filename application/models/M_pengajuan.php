<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pengajuan extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function pengajuan_count($nip, $keyword)
  {
    $this->cb->select('Id')->from('t_pengajuan')
      ->group_start()
      ->where('user', $nip)
      ->group_end();
    if ($keyword) {
      $this->cb->like('kode', $keyword, 'both');
    }
    return $this->cb->get()->num_rows();
  }

  public function pengajuan_get($limit, $start, $nip, $keyword)
  {
    $this->cb->select('*')->from('t_pengajuan a')
      ->group_start()
      ->where('user', $nip)
      ->group_end();
    if ($keyword) {
      $this->cb->like('kode', $keyword, 'both');
    }
    // $this->cb->order_by('a.status', 'ASC');
    $this->cb->order_by(
      "a.status ASC, a.id DESC"
    );
    return $this->cb->limit($limit, $start)->get()->result();
  }

  public function pengajuan_count_spv($nip, $keyword)
  {
    $this->cb->select('Id')->from('t_pengajuan')
      ->group_start()
      ->where('spv', $nip, 'both')
      // ->where('cabang', $this->session->userdata('kode_cabang'))
      ->group_end();
    if ($keyword) {
      $this->cb->like('kode', $keyword, 'both');
    }
    return $this->cb->get()->num_rows();
  }

  public function pengajuan_get_spv($limit, $start, $nip, $keyword)
  {
    $this->cb->select('a.*, b.nama')->from('t_pengajuan a')->join($this->db->database . '.users b', 'b.nip = a.user', 'left')
      ->group_start()
      ->where('a.spv', $nip)
      // ->where('a.cabang', $this->session->userdata('kode_cabang'))
      ->group_end();
    if ($keyword) {
      $this->cb->like('a.kode', $keyword, 'both');
    }
    // $this->cb->order_by('a.status', 'ASC');
    $this->cb->order_by(
      "a.status ASC, a.id DESC"
    );
    return $this->cb->limit($limit, $start)->get()->result();
  }

  public function pengajuan_count_direksi($nip, $keyword)
  {
    $id_perusahaan = $this->session->userdata('user_perusahaan_id');
    $cabang = $this->cb->get_where('t_cabang', ['id_perusahaan' => $id_perusahaan])->result_array();

    $cabang_ids = array_column($cabang, 'uid');

    // var_dump($cabang_ids);

    $this->cb->select('Id')->from('t_pengajuan')
      ->group_start()
      ->where('direksi', $nip, 'both')
      ->where_in('cabang', $cabang_ids)
      ->group_end();
    if ($keyword) {
      $this->cb->like('kode', $keyword, 'both');
    }
    return $this->cb->get()->num_rows();
  }

  public function pengajuan_get_direksi($limit, $start, $nip, $keyword)
  {
    $id_perusahaan = $this->session->userdata('user_perusahaan_id');
    $cabang = $this->cb->get_where('t_cabang', ['id_perusahaan' => $id_perusahaan])->result_array();

    $cabang_ids = array_column($cabang, 'uid');
    $this->cb->select('a.*, b.nama')->from('t_pengajuan a')->join($this->db->database . '.users b', 'b.nip = a.user', 'left')
      ->group_start()
      ->where('a.direksi', $nip)
      ->where_in('a.cabang', $cabang_ids)
      ->group_end();
    if ($keyword) {
      $this->cb->like('a.kode', $keyword, 'both');
    }
    // $this->cb->order_by('a.status', 'ASC');
    $this->cb->order_by(
      "a.status ASC, a.id DESC"
    );
    return $this->cb->limit($limit, $start)->get()->result();
  }

  public function pengajuan_count_keuangan($keyword)
  {
    $this->cb->select('Id')->from('t_pengajuan')
      ->group_start()
      ->where('status !=', 0)
      ->where('cabang', $this->session->userdata('kode_cabang'))
      ->group_end();
    if ($keyword) {
      $this->cb->like('kode', $keyword, 'both');
    }
    return $this->cb->get()->num_rows();
  }

  public function pengajuan_get_keuangan($limit, $start, $nip, $keyword)
  {
    $this->cb->select('a.*, b.nama')->from('t_pengajuan a')->join($this->db->database . '.users b', 'b.nip = a.user', 'left')
      ->group_start()
      ->where('a.status !=', 0)
      ->where('a.cabang', $this->session->userdata('kode_cabang'))
      ->group_end();
    if ($keyword) {
      $this->cb->like('a.kode', $keyword, 'both');
    }
    // $this->cb->order_by('CASE WHEN a.status = 2 THEN 1 ELSE 0 END, a.status ASC, a.id DESC', false);
    $this->cb->order_by(
      "a.status ASC, a.id DESC"
    );
    return $this->cb->limit($limit, $start)->get()->result();
  }

  public function simpan_pengajuan($insert)
  {
    $this->cb->insert('t_pengajuan', $insert);
    return $this->cb->insert_id();
  }

  public function simpan_detail_batch($items)
  {
    return $this->cb->insert_batch('t_pengajuan_detail', $items);
  }

  public function pengajuan_get_detail($kode)
  {
    $this->cb->select('a.Id, a.item, a.qty, a.price, a.total, a.realisasi, b.status')->from('t_pengajuan_detail a')->join('t_pengajuan b', 'b.Id = a.no_pengajuan', 'left')->where('b.kode', $kode);
    return $this->cb->get()->result();
  }

  public function pengajuan_by_kode($kode)
  {
    $this->cb->select('*')->from('t_pengajuan')->where('kode', $kode);
    return $this->cb->get()->row();
  }

  public function update_pengajuan($update, $kode)
  {
    $this->cb->where('kode', $kode);
    return $this->cb->update('t_pengajuan', $update);
  }

  public function delete_detail($id)
  {
    $this->cb->where('no_pengajuan', $id);
    return $this->cb->delete('t_pengajuan_detail');
  }


  // NEW
  public function get_coa_nominal($no_sbb, $id_cabang)
  {
    $this->cb->select('nominal');
    $this->cb->from('v_coa_all');
    $this->cb->where('no_sbb', $no_sbb);
    $this->cb->where('id_cabang', $id_cabang);
    $query = $this->cb->get();

    if ($query->num_rows() > 0) {
      return (float) $query->row()->nominal;
    }
    return 0.0;
  }

  public function get_item_name_by_id($item_id)
  {
    $this->cb->select('item');
    $this->cb->from('t_pengajuan_detail');
    $this->cb->where('Id', $item_id);
    $query = $this->cb->get();

    if ($query->num_rows() > 0) {
      return $query->row()->item;
    }
    return 'Uraian Tidak Dikenal';
  }
}
