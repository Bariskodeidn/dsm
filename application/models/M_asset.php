<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_asset extends CI_Model
{

  var $column_order_pengecualian = array(null, 'nama_asset', 'kode', null);
  var $column_search_pengecualian = array('nama_asset', 'spesifikasi', 'kode');
  var $order_pengecualian = array('Id' => 'desc');

  public function __construct()
  {
    parent::__construct();
  }

  public function asset_get($limit, $start, $cabang, $keyword)
  {
    $this->db->select('a.*, c.keterangan as nama_ruangan, d.nama_jenis')->from('asset_list a')->join('asset_history b', 'a.kode = b.kode', 'left')->join('asset_ruang c', 'c.Id = a.ruangan', 'left')->join('asset_jenis d', 'd.Id = a.jenis_asset', 'left')
      ->group_start()
      ->where('a.cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('a.kode', $keyword, 'both');
      $this->db->or_like('a.spesifikasi', $keyword, 'both');
      $this->db->or_like('a.nama_asset', $keyword, 'both');
      $this->db->or_like('b.remark', $keyword, 'both');
      $this->db->or_like('c.keterangan', $keyword, 'both');
      $this->db->or_like('d.nama_jenis', $keyword, 'both');
      $this->db->group_end();
    }
    $this->db->order_by('a.Id', 'DESC');
    $this->db->group_by('b.kode');
    return $this->db->limit($limit, $start)->get()->result();
  }

  public function asset_count($cabang, $keyword)
  {
    $this->db->select('a.*, c.keterangan as nama_ruangan, d.nama_jenis')->from('asset_list a')->join('asset_history b', 'a.kode = b.kode', 'left')->join('asset_ruang c', 'c.Id = a.ruangan', 'left')->join('asset_jenis d', 'd.Id = a.jenis_asset', 'left')
      ->group_start()
      ->where('a.cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('a.kode', $keyword, 'both');
      $this->db->like('a.kode', $keyword, 'both');
      $this->db->or_like('a.spesifikasi', $keyword, 'both');
      $this->db->or_like('a.nama_asset', $keyword, 'both');
      $this->db->or_like('b.remark', $keyword, 'both');
      $this->db->or_like('c.keterangan', $keyword, 'both');
      $this->db->or_like('d.nama_jenis', $keyword, 'both');
      $this->db->group_end();
    }
    $this->db->group_by('b.kode');
    return $this->db->get()->num_rows();
  }

  public function asset_jenis_get($limit, $start, $cabang, $keyword)
  {
    $this->db->select('*')->from('asset_jenis')
      ->group_start()
      ->where('cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('nama_jenis', $keyword, 'both');
      $this->db->group_end();
    }

    $this->db->order_by('Id', 'DESC');
    return $this->db->limit($limit, $start)->get()->result();
  }

  public function jenis_count($cabang, $keyword)
  {
    $this->db->select('*')->from('asset_jenis')
      ->group_start()
      ->where('cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('nama_jenis', $keyword, 'both');
      $this->db->group_end();
    }
    return $this->db->get()->num_rows();
  }

  public function ruangan_get($limit, $start, $cabang, $keyword)
  {
    $this->db->select('*')->from('asset_ruang')
      ->group_start()
      ->where('cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('keterangan', $keyword, 'both');
      $this->db->group_end();
    }

    $this->db->order_by('Id', 'DESC');
    return $this->db->limit($limit, $start)->get()->result();
  }

  public function ruangan_count($cabang, $keyword)
  {
    $this->db->select('*')->from('asset_ruang')
      ->group_start()
      ->where('cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->db->group_start();
      $this->db->like('keterangan', $keyword, 'both');
      $this->db->group_end();
    }
    return $this->db->get()->num_rows();
  }

  public function ambil_data_asset($id, $cabang)
  {
    $this->db->select('a.*, c.keterangan as nama_ruangan, d.nama_jenis')->from('asset_list a')->join('asset_history b', 'a.kode = b.kode', 'left')->join('asset_ruang c', 'c.Id = a.ruangan', 'left')->join('asset_jenis d', 'd.Id = a.jenis_asset', 'left')
      ->where('a.Id', $id)
      ->where('a.cabang', $cabang);
    $query = $this->db->get();
    return $query->row();
  }

  public function ambil_data_history($kode, $cabang)
  {
    $this->db->select('a.*, b.keterangan as nama_ruangan')
      ->from('asset_history a')->join('asset_ruang b', 'b.Id = a.ruangan', 'left')
      ->where('kode', $kode)
      ->where('lokasi', $cabang);
    $query = $this->db->get();
    return $query->result();
  }

  public function penyusutan_count($cabang, $keyword)
  {
    $this->cb->select('a.*')->from('t_penyusutan a')
      ->group_start()
      ->where('a.cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->cb->group_start();
      $this->cb->where('a.periode', $keyword);
      $this->cb->group_end();
    }
    return $this->cb->get()->num_rows();
  }

  public function penyusutan_get($limit, $start, $cabang, $keyword)
  {
    $this->cb->select('a.*')->from('t_penyusutan a')
      ->group_start()
      ->where('a.cabang', $cabang)
      ->group_end();
    if ($keyword) {
      $this->cb->group_start();
      $this->cb->where('a.periode', $keyword);
      $this->cb->group_end();
    }
    $this->cb->order_by('Id', 'DESC');
    return $this->cb->limit($limit, $start)->get()->result();
  }

  private function _get_datatables_query_penyusutan_pengecualian()
  {
    $this->db->select('a.*')->from('asset_list a');
    $i = 0;
    foreach ($this->column_search_pengecualian as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value']);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }
        if (count($this->column_search_pengecualian) - 1 == $i) //looping terakhir
          $this->db->group_end();
      }
      $i++;
    }
    $this->db->where('penyusutan', 0);
    // jika datatable mengirim POST untuk order
    if ($this->input->post('order')) {
      $this->db->order_by($this->column_order_pengecualian[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables_penyusutan_pengecualian()
  {
    $this->_get_datatables_query_penyusutan_pengecualian();
    if ($this->input->post('length') != -1)
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    $query = $this->db->get();
    return $query->result();
  }

  public function count_filtered_pengecualian()
  {
    $this->_get_datatables_query_penyusutan_pengecualian();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all_pengecualian()
  {
    $this->_get_datatables_query_penyusutan_pengecualian();
    return $this->db->count_all_results();
  }
}
