<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_agency extends CI_Model
{

  var $column_order_penunjukan = array('d.nama_customer', 'a.no_surat', null, null);
  var $column_search_penunjukan = array('d.nama_customer', 'a.no_surat');
  var $order_penunjukan = array('Id' => 'desc');


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

  public function get_item_pda($limit, $start, $keyword)
  {
    $this->db->select('a.Id, a.desc, a.remarks, a.jenis, a.port, b.nama, a.est, a.hpp_rill, a.title')->from('t_item_pda a')->join('agency_port b', 'a.port = b.kode', 'left');
    if ($keyword) {
      $this->db->like('a.desc', $keyword, 'both');
    }
    $this->db->order_by('a.Id', 'desc');
    $result = $this->db->limit($limit, $start)->get();
    return $result;
  }

  public function count_item_pda($keyword)
  {
    $this->db->select('a.Id, a.desc, a.remarks, a.jenis, b.nama, a.port, a.est, a.hpp_rill, a.title')->from('t_item_pda a')->join('agency_port b', 'a.port = b.kode', 'left');
    if ($keyword) {
      $this->db->like('a.desc', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }

  private function _get_penunjukan_datatables_query()
  {
    $cabang = $this->session->userdata('kode_cabang');
    $jenis = $this->session->userdata('filterCabangPenunjukan') ?? '';
    $this->db->select('a.Id, a.no_surat, a.penawaran, a.customer, a.nama_kapal, a.file_name, a.file, a.status, b.nama, a.jenis, c.no_surat as no_penawaran, d.nama_customer')->from('t_penunjukan a')->join('users b', 'a.user = b.nip', 'left')->join('t_penawaran c', 'a.penawaran = c.Id', 'left')->join('agency_customer d', 'd.Id = a.customer', 'left');
    if ($jenis) {
      $this->db->where('a.id_cabang', $jenis);
    }
    $i = 0;
    foreach ($this->column_search_penunjukan as $item) {
      if ($this->input->post('search')['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $this->input->post('search')['value']);
        } else {
          $this->db->or_like($item, $this->input->post('search')['value']);
        }
        if (count($this->column_search_penunjukan) - 1 == $i) //looping terakhir
          $this->db->group_end();
      }
      $i++;
    }
    // jika datatable mengirim POST untuk order
    if ($this->input->post('order')) {
      $this->db->order_by($this->column_order_penunjukan[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
    } else if (isset($this->order_penunjukan)) {
      $order = $this->order_penunjukan;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_penunjukan_datatables()
  {
    $this->_get_penunjukan_datatables_query();
    if ($this->input->post('length') != -1)
      $this->db->limit($this->input->post('length'), $this->input->post('start'));
    $query = $this->db->get();
    return $query->result();
  }

  public function count_filtered_penunjukan()
  {
    $this->_get_penunjukan_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all_penunjukan()
  {
    $this->_get_penunjukan_datatables_query();
    return $this->db->count_all_results();
  }

  public function get_penunjukanById($id)
  {
    $this->db->where('Id', $id);
    $result = $this->db->get('t_penunjukan')->row_array();

    return $result;
  }

  public function get_item_penawaran($limit, $start, $keyword)
  {
    $this->db->select('*')->from('t_item_penawaran');
    if ($keyword) {
      $this->db->like('nama_penawaran', $keyword, 'both');
    }
    $result = $this->db->order_by('Id', 'DESC')->limit($limit, $start)->get();
    return $result;
  }

  public function count_item_penawaran($keyword)
  {
    $this->db->select('*')->from('t_item_penawaran');
    if ($keyword) {
      $this->db->like('nama_penawaran', $keyword, 'both');
    }
    return $this->db->get()->num_rows();
  }

  public function get_penawaran($limit, $start, $keyword)
  {
    $this->db->select('a.Id, a.user, a.no_surat, a.file, a.file_name, a.perihal, a.tanggal, b.nama_customer, c.nama')->from('t_penawaran a')->join('agency_customer b', 'a.tujuan = b.Id', 'left')->join('users c', 'a.user = c.nip', 'left');
    if ($keyword) {
      $this->db->like('a.no_surat', $keyword, 'both');
      $this->db->or_like('b.nama_customer', $keyword, 'both');
    }
    $result = $this->db->order_by('Id', 'DESC')->limit($limit, $start)->get();
    return $result;
  }

  public function count_penawaran($keyword)
  {
    $this->db->select('a.Id, a.user, a.no_surat, a.file, a.file_name, a.perihal, a.tanggal, b.nama_customer, c.nama')->from('t_penawaran a')->join('agency_customer b', 'a.tujuan = b.Id', 'left')->join('users c', 'a.user = c.nip', 'left');
    if ($keyword) {
      $this->db->like('a.no_surat', $keyword, 'both');
      $this->db->or_like('b.nama_customer', $keyword, 'both');
    }
    $result = $this->db->get()->num_rows();
    return $result;
  }

  public function getByIdPenawaran($id)
  {
    $sql = "SELECT a.Id, a.user, a.no_surat, a.perihal, a.tanggal, a.attn, a.isi, a.item_tetap, a.item_tambahan, a.catatan, b.nama_customer, c.nama FROM t_penawaran a LEFT JOIN agency_customer b ON a.tujuan = b.Id LEFT JOIN users c ON a.user = c.nip WHERE a.Id = '$id'";
    $result = $this->db->query($sql);

    return $result;
  }
}
