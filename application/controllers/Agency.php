<?php

use PhpOffice\PhpWord\PhpWord;

defined('BASEPATH') or exit('No direct script access allowed');
class Agency extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();
    $this->load->model(['M_agency']);
    if ($this->session->userdata('isLogin') == FALSE) {
      redirect('home');
    }
    date_default_timezone_set('Asia/Jakarta');
  }

  public function port()
  {

    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('agency/port');
    $config['total_rows'] = $this->M_agency->count_port($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // Bootstrap style pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-end">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = "First";
    $config['last_link'] = "Last";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');

    // Initialize paginaton
    $this->pagination->initialize($config);
    $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['pagination'] = $this->pagination->create_links();

    $data['page'] = $page;
    $data['port'] = $this->M_agency->get_port($config['per_page'], $page, $keyword);
    $data['cabang'] = $this->db->get('agency_cabang')->result();
    $data['title'] = 'Daftar Port/Jetty';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/v_port';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function store_port()
  {
    $nama = $this->input->post('port');
    $kode = $this->input->post('kode');
    $cabang = $this->input->post('cabang');

    $this->form_validation->set_rules('port', 'nama port/jetty', 'required|trim', ['required' => '%s harus diisi!']);
    $this->form_validation->set_rules('kode', 'kode', 'required|alpha|trim', ['required' => '%s harus diisi!', 'alpha' => '%s hanya boleh berisi karakter huruf!']);
    $this->form_validation->set_rules('cabang', 'cabang', 'required', ['required' => '%s harus dipilih!']);
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $insert = [
        'nama' => $nama,
        'kode' => $kode,
        'id_cabang' => $cabang
      ];

      $this->db->insert('agency_port', $insert);

      $response = [
        'success' => true,
        'msg' => 'Port/jetty berhasil ditambahkan!',
        'reload' => site_url('agency/port'),
      ];
    }

    echo json_encode($response);
  }

  public function update_port($id)
  {
    $nama = $this->input->post('port');
    $kode = $this->input->post('kode');
    $cabang = $this->input->post('cabang');

    $this->form_validation->set_rules('port', 'nama port/jetty', 'required|trim', ['required' => '%s harus diisi!']);
    $this->form_validation->set_rules('kode', 'kode', 'required|alpha|trim', ['required' => '%s harus diisi!', 'alpha' => '%s hanya boleh berisi karakter huruf!']);
    $this->form_validation->set_rules('cabang', 'cabang', 'required', ['required' => '%s harus dipilih!']);
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $update = [
        'nama' => $nama,
        'kode' => $kode,
        'id_cabang' => $cabang
      ];

      $this->db->where('Id', $id);
      $this->db->update('agency_port', $update);

      $response = [
        'success' => true,
        'msg' => 'Port/jetty berhasil diubah!',
        'reload' => site_url('pda/port'),
      ];
    }

    echo json_encode($response);
  }

  public function customer()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('agency/customer');
    $config['total_rows'] = $this->M_agency->count_customer($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // Bootstrap style pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-end">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = "First";
    $config['last_link'] = "Last";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');

    // Initialize paginaton
    $this->pagination->initialize($config);
    $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['pagination'] = $this->pagination->create_links();

    $data['page'] = $page;
    $data['customer'] = $this->M_agency->get_customer($config['per_page'], $page, $keyword);
    $data['cabang'] = $this->db->get('agency_cabang')->result();
    $data['title'] = 'Daftar Customer';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/v_customer';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function store_customer()
  {
    $customer = $this->input->post('nama');
    $alamat = $this->input->post('alamat');
    $tlp = $this->input->post('telpon');
    $kode = $this->input->post('kode');
    $cabang = $this->input->post('cabang');

    $this->form_validation->set_rules('nama', 'Nama customer', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('cabang', 'Cabang', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('telpon', 'No. Telpon', 'required|numeric|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('kode', 'Kode', 'required|max_length[5]|trim', array('required' => '%s wajib diisi'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $insert = [
        'nama_customer' => $customer,
        'alamat' => $alamat,
        'telepon' => $tlp,
        'kode' => $kode,
        'id_cabang' => $cabang,
        'created_by' => $this->session->userdata('nip')
      ];

      $this->db->insert('agency_customer', $insert);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil ditambahkan!',
        'reload' => site_url('agency/customer')
      ];
    }

    echo json_encode($response);
  }

  public function update_customer($id)
  {
    $customer = $this->input->post('nama');
    $alamat = $this->input->post('alamat');
    $tlp = $this->input->post('telpon');
    $kode = $this->input->post('kode');
    $cabang = $this->input->post('cabang');

    $this->form_validation->set_rules('nama', 'Nama customer', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('cabang', 'Cabang', 'required|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('telpon', 'No. Telpon', 'required|numeric|trim', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('kode', 'Kode', 'required|max_length[5]|trim', array('required' => '%s wajib diisi'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $update = [
        'nama_customer' => $customer,
        'alamat' => $alamat,
        'telepon' => $tlp,
        'kode' => $kode,
        'id_cabang' => $cabang,
        'created_by' => $this->session->userdata('nip')
      ];

      $this->db->where('Id', $id);
      $this->db->update('agency_customer', $update);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil diubah!',
        'reload' => site_url('agency/customer')
      ];
    }

    echo json_encode($response);
  }

  public function item_pda()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('agency/item_pda');
    $config['total_rows'] = $this->M_agency->count_item_pda($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // Bootstrap style pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-end">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = "First";
    $config['last_link'] = "Last";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');

    // Initialize paginaton
    $this->pagination->initialize($config);
    $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['pagination'] = $this->pagination->create_links();

    $data['page'] = $page;
    $data['item_pda'] = $this->M_agency->get_item_pda($config['per_page'], $page, $keyword);
    $data['port'] = $this->db->get('agency_port')->result();
    $data['title'] = 'Daftar Item PDA';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_item_pda';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function store_item_pda()
  {
    $desc = $this->input->post('desc');
    $remarks = $this->input->post('remarks');
    $jenis = $this->input->post('jenis');
    $port = $this->input->post('port');
    $est = $this->input->post('est');
    $hpp_rill = $this->input->post('hpp_rill');
    $title = $this->input->post('title');

    $this->form_validation->set_rules('desc', 'Description', 'required|trim');
    $this->form_validation->set_rules('jenis', 'Jenis', 'required');
    $this->form_validation->set_rules('port', 'Port', 'required');
    $this->form_validation->set_rules('est', 'Harga Estimasi', 'required');
    $this->form_validation->set_rules('hpp_rill', 'HPP Rill', 'required');
    $this->form_validation->set_rules('title', 'Title', 'required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $insert = [
        'desc' => $desc,
        'remarks' => $remarks,
        'jenis' => $jenis,
        'port' => $port,
        'est' => preg_replace('/[^a-zA-Z0-9\']/', '', $est),
        'hpp_rill' => preg_replace('/[^a-zA-Z0-9\']/', '', $hpp_rill),
        'title' => $title
      ];

      $this->db->insert('t_item_pda', $insert);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil ditambahkan!',
        'reload' => base_url('agency/item_pda')
      ];
    }

    echo json_encode($response);
  }

  public function update_item_pda($id)
  {
    $desc = $this->input->post('desc');
    $remarks = $this->input->post('remarks');
    $jenis = $this->input->post('jenis');
    $port = $this->input->post('port');
    $est = $this->input->post('est');
    $hpp_rill = $this->input->post('hpp_rill');
    $title = $this->input->post('title');

    $this->form_validation->set_rules('desc', 'Description', 'required|trim');
    $this->form_validation->set_rules('jenis', 'Jenis', 'required');
    $this->form_validation->set_rules('port', 'Port', 'required');
    $this->form_validation->set_rules('est', 'Harga Estimasi', 'required');
    $this->form_validation->set_rules('hpp_rill', 'HPP Rill', 'required');
    $this->form_validation->set_rules('title', 'Title', 'required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $update = [
        'desc' => $desc,
        'remarks' => $remarks,
        'jenis' => $jenis,
        'port' => $port,
        'est' => preg_replace('/[^a-zA-Z0-9\']/', '', $est),
        'hpp_rill' => preg_replace('/[^a-zA-Z0-9\']/', '', $hpp_rill),
        'title' => $title
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_item_pda', $update);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil diubah!',
        'reload' => base_url('agency/item_pda')
      ];
    }

    echo json_encode($response);
  }

  public function penunjukan()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'List Penunjukan';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/v_penunjukan';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function filter_penunjukan_cabang()
  {
    $cabang = $this->input->post('filter_cabang');
    $this->session->set_userdata('filterCabangPenunjukan', $cabang);
    redirect('penunjukan');
  }

  public function reset_filter_penunjukan_cabang()
  {
    $this->session->unset_userdata('filterCabangPenunjukan');
    redirect('penunjukan');
  }

  public function penunjukan_ajax_list()
  {
    $list = $this->M_agency->get_penunjukan_datatables();
    $data = array();
    $no = $this->input->post('start');
    $i = 1;
    foreach ($list as $penunjukan) {
      $pdaSql = "SELECT a.*, b.status as status_pda, c.status as status_dokumen, d.status as status_hpp, e.status as status_hrgjual FROM t_pda a LEFT JOIN monitoring_prapda b ON a.Id = b.pda_id LEFT JOIN monitoring_dokumen c ON a.Id = c.pda_id LEFT JOIN monitoring_hpprill d ON d.pda_id = a.Id LEFT JOIN monitoring_hrgjual e ON a.Id = e.pda_id WHERE a.penunjukan = $penunjukan->Id";
      $pda = $this->db->query($pdaSql)->row_array();
      if ($pda) {
        if ($pda['status_pda'] == 2) {
          $active_pda = 'active';
        } else {
          $active_pda = '';
        }

        if ($pda['status_dokumen'] == 2) {
          $active_dok = 'active';
        } else if ($pda['status_dokumen'] == 1 or $pda['status_pda'] == 2) {
          $active_dok = 'onprogress';
        } else {
          $active_dok = '';
        }

        if ($pda['status_hpp'] == 2) {
          $active_hpp = 'active';
        } else if ($pda['status_hpp'] == 1 or $pda['status_dokumen'] == 2) {
          $active_hpp = 'onprogress';
        } else {
          $active_hpp = '';
        }

        if ($pda['status_hrgjual'] == 2) {
          $active_hrgjual = 'active';
        } else if ($pda['status_hrgjual'] == 1 or $pda['status_hpp'] == 2) {
          $active_hrgjual = 'onprogress';
        } else {
          $active_hrgjual = '';
        }

        if ($penunjukan->status == 1) {
          $active_invoice = 'active';
        } else if ($pda['status_hrgjual'] == 2) {
          $active_invoice = 'onprogress';
        } else {
          $active_invoice = '';
        }
      } else {
        $active_pda = '';
        $active_dok = '';
        $active_hpp = '';
        $active_hrgjual = '';
        $active_invoice = '';
      }

      $progress = '<div class="progressbar-wrapper">
                        <ul class="progressbar">
                            <li class="' . $active_pda . '">Pra</li>
                            <li class="' . $active_dok . '">Doc</li>
                            <li class="' . $active_hpp . '">Hpp</li>
                            <li class="' . $active_hrgjual . '">Price</li>
                            <li class="' . $active_invoice . '">Inv</li>
                        </ul>
                  </div>';

      $ubah = '<a href="' . base_url("agency/ubah_penunjukan/") . $penunjukan->Id . '" class="btn btn-success btn-sm"><i class="fe fe-edit-2"></i></a>';
      $file = '<a href="' . base_url("upload/penunjukan/") . $penunjukan->file_name . '" class="btn btn-primary btn-sm" target="_blank"><i class="fe fe-file"></i></a>';
      $monitor = '<button type="button" class="btn btn-warning btn-sm" onclick="modalMonitor(' . $penunjukan->Id . ')"><i class="fe fe-eye" ></i></button>';

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $penunjukan->nama_customer;
      $row[] = $penunjukan->no_surat;
      $row[] = $progress;
      $row[] = $ubah . $file . $monitor;
      $data[] = $row;
    }

    $minSearchLength = 3;
    $search = $this->input->post('search')['value'];
    $draw = intval($this->input->post('draw'));
    // Check if search length is less than the minimum
    if (strlen($search) > 0 && strlen($search) < $minSearchLength) {
      echo json_encode([
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
      ]);
      return;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->M_agency->count_all_penunjukan(),
      "recordsFiltered" => $this->M_agency->count_filtered_penunjukan(),
      "data" => $data,
    );
    //output to json format
    $this->output->set_output(json_encode($output));
  }

  public function monitorPenunjukan()
  {
    $id = $this->input->get('penunjukanId');
    $penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $id])->row_array();
    $pda = $this->db->get_where('t_pda', ['penunjukan' => $penunjukan['Id']])->row_array();

    if ($pda) {
      // Monitoring PRA PDA
      $monitoringPda = $this->db->get_where('monitoring_prapda', ['pda_id' => $pda['Id']])->row_array();
      if ($monitoringPda['status'] == 1) {
        $statusPrapda = 'On Progress';
        $btnLinkPda = '<a href=' . base_url('pda/pra/') . $pda['Id'] . ' target="blank_" class="btn btn-success btn-xs">Open</a>';
      } else if ($monitoringPda['status'] == 2) {
        $statusPrapda = 'Selesai';
        $btnLinkPda = '<a href=' . base_url('pda/pra/') . $pda['Id'] . ' target="blank_" class="btn btn-success btn-xs">Open</a>';
      } else {
        $statusPrapda = '-';
        $btnLinkPda = '-';
      }

      if ($monitoringPda['start']) {
        $start = tgl_indo(date('Y-m-d', strtotime($monitoringPda['start']))) . '|' . date('H:i:s', strtotime($monitoringPda['start']));
      } else {
        $start = '-';
      }

      if ($monitoringPda['end']) {
        $end = tgl_indo(date('Y-m-d', strtotime($monitoringPda['end']))) . '|' . date('H:i:s', strtotime($monitoringPda['end']));
      } else {
        $end = '-';
      }

      // Monitoring Dokumen
      $monitoringDok = $this->db->get_where('monitoring_dokumen', ['pda_id' => $pda['Id']])->row_array();
      if ($monitoringDok['status'] == 1) {
        $statusDok = 'On Progress';
      } else if ($monitoringDok['status'] == 2) {
        $statusDok = 'Selesai';
      } else {
        $statusDok = '-';
      }

      if ($monitoringDok['start']) {
        $startDok = tgl_indo(date('Y-m-d', strtotime($monitoringDok['start']))) . '|' . date('H:i:s', strtotime($monitoringDok['start']));
      } else {
        $startDok = '-';
      }

      if ($monitoringDok['end']) {
        $endDok = tgl_indo(date('Y-m-d', strtotime($monitoringDok['end']))) . '|' . date('H:i:s', strtotime($monitoringDok['end']));
      } else {
        $endDok = '-';
      }

      // Monitoring Hpp Ril
      $monitoringHpp = $this->db->get_where('monitoring_hpprill', ['pda_id' => $pda['Id']])->row_array();
      if ($monitoringHpp['status'] == 1) {
        $statusHpp = 'On Progress';
      } else if ($monitoringHpp['status'] == 2) {
        $statusHpp = 'Selesai';
      } else {
        $statusHpp = '-';
      }

      if ($monitoringHpp['start']) {
        $startHpp = tgl_indo(date('Y-m-d', strtotime($monitoringHpp['start']))) . '|' . date('H:i:s', strtotime($monitoringHpp['start']));
      } else {
        $startHpp = '-';
      }

      if ($monitoringHpp['end']) {
        $endHpp = tgl_indo(date('Y-m-d', strtotime($monitoringHpp['end']))) . '|' . date('H:i:s', strtotime($monitoringHpp['end']));
      } else {
        $endHpp = '-';
      }

      // Monitoring harga jual
      // $a = $this->session->userdata('level');
      $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

      $monitoringHrgJual = $this->db->get_where('monitoring_hrgjual', ['pda_id' => $pda['Id']])->row_array();
      if ($monitoringHpp['status'] == 2 and (in_array('pda/harga_jual', $access_menu_all))) {
        $openHrgJual = '<a href=' . base_url('pda/harga_jual/') . $pda['Id'] . ' target="blank_" class="btn btn-success btn-xs">Open</a>';
      } else {
        $openHrgJual = '';
      }

      if ($monitoringHrgJual['status'] == 1) {
        $statusHrgJual = 'On Progress';
      } else if ($monitoringHrgJual['status'] == 2) {
        $statusHrgJual = 'Selesai';
      } else {
        $statusHrgJual = '-';
      }

      if ($monitoringHrgJual['start']) {
        $startHrgJual = tgl_indo(date('Y-m-d', strtotime($monitoringHrgJual['start']))) . '|' . date('H:i:s', strtotime($monitoringHrgJual['start']));
      } else {
        $startHrgJual = '-';
      }

      if ($monitoringHrgJual['end']) {
        $endHrgJual = tgl_indo(date('Y-m-d', strtotime($monitoringHrgJual['end']))) . '|' . date('H:i:s', strtotime($monitoringHrgJual['end']));
      } else {
        $endHrgJual = '-';
      }

      if ($monitoringHrgJual['status'] == 2 and $penunjukan['status'] == 0) {
        $btnClose = '<a href="#" class="btn btn-danger mb-4" onclick="closePenunjukan(' . $id . ')">Close Penunjukan</a>';
      } else {
        $btnClose = '';
      }


      $table =
        '
        <div class="row">
          ' . $btnClose . '
        </div>
        <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>KEGIATAN</th>
                <th>STATUS</th>
                <th>MULAI</th>
                <th>SELESAI</th>
                <th>LINK</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>PRA PDA</td>
                <td>' . $statusPrapda . '</td>
                <td>' . $start . '</td>
                <td>' . $end . '</td>
                <td>' . $btnLinkPda . '</td>
              </tr>
              <tr>
                <td>DOKUMEN</td>
                <td>' . $statusDok . '</td>
                <td>' . $startDok . '</td>
                <td>' . $endDok . '</td>
                <td><a href=' . base_url('pda/dokumen/') . $pda['Id'] . ' target="blank_" class="btn btn-success btn-xs">Open</a></td>
              </tr>
              <tr>
                <td>HPP RILL</td>
                <td>' . $statusHpp . '</td>
                <td>' . $startHpp . '</td>
                <td>' . $endHpp . '</td>
                <td><a href=' . base_url('pda/hpp_rill/') . $pda['Id'] . ' target="blank_" class="btn btn-success btn-xs">Open</a></td>
              </tr>
              <tr>
                <td>HARGA JUAL</td>
                <td>' . $statusHrgJual . '</td>
                <td>' . $startHrgJual . '</td>
                <td>' . $endHrgJual . '</td>
                <td>' . $openHrgJual . '</td>
              </tr>
            </tbody>
          </table>';
    } else {
      $table = '<table class="table">
            <thead>
              <tr>
                <th>KEGIATAN</th>
                <th>STATUS</th>
                <th>MULAI</th>
                <th>SELESAI</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>PRA PDA</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>DOKUMEN</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>HPP RILL</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>HARGA JUAL</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
            </tbody>
          </table>';
    }

    $response = [
      'data' => $table,
      'no_penunjukan' => $penunjukan['no_surat']
    ];

    echo json_encode($response);
  }

  public function create_penunjukan()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/penunjukan', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'Buat Penunjukan';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/v_create_penunjukan';
    $data['pages_script'] = 'script/agency/s_penunjukan';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $data['cabang'] = $this->db->get('agency_cabang');
    $data['customer'] = $this->db->get('agency_customer');
    $data['agency'] = $this->db->get('agent');
    $data['penawaran'] = $this->db->get('t_penawaran');
    $data['port'] = $this->db->get('agency_port');
    $data['kategori_kapal'] = $this->db->get('agency_kapal_kategori')->result();

    $this->load->view('index', $data);
  }

  public function insert_penunjukan()
  {
    $agency = $this->input->post('agency');
    $penawaran = $this->input->post('surat-penawaran');
    $no_surat = $this->input->post('surat-penunjukan');
    $type = $this->input->post('type');
    $kapal = $this->input->post('kapal');
    $jenis = $this->input->post('jenis');
    $file = $_FILES['file-penunjukan']['name'];
    $customer = $this->input->post('customer');
    $cabang = $this->input->post('cabang');

    $port = $this->input->post('port');
    $eta = $this->input->post('eta');
    $grt = $this->input->post('grt');
    $grt_barge = $this->input->post('grt_barge');

    $this->form_validation->set_rules('cabang', 'Cabang', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('customer', 'Nama customer', 'required|callback_exists[agency_customer.Id]', array(
      'required' => "%s wajib dipilih!",
    ));
    $this->form_validation->set_rules('agency', 'Nama agency', 'required|callback_exists[agent.Id]', array(
      'required' => "%s wajib dipilih!",
    ));
    $this->form_validation->set_rules('type', 'Type kapal', 'required|callback_exists[agency_kapal_kategori.Id]', array(
      'required' => "%s wajib dipilih!",
    ));
    $this->form_validation->set_rules('surat-penunjukan', 'No surat penunjukan', 'required|trim', array('required' => "%s wajib diisi!"));
    $this->form_validation->set_rules('kapal', 'Nama Kapal', 'required', array('required|trim' => "%s wajib diisi!"));
    $this->form_validation->set_rules('jenis', 'Jenis', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('port', 'Port', 'required|callback_exists[agency_port.Id]', array(
      'required' => "%s wajib dipilih!",
    ));
    $this->form_validation->set_rules('eta', 'ETA', 'required', array('required' => "%s wajib diisi!"));
    $this->form_validation->set_rules('grt', 'GRT', 'required', array('required' => "%s wajib diisi!"));
    $this->form_validation->set_rules('grt_barge', 'GRT Barge', 'trim', array('required' => "%s wajib diisi!"));

    $config['upload_path'] = './upload/penunjukan';
    $config['allowed_types'] =  'jpg|jpeg|png|pdf';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    if (!is_dir('upload/penunjukan')) {
      mkdir('./upload/penunjukan', 0777, TRUE);
    }

    if ($this->form_validation->run() == FALSE) {
      $errors = [
        'cabang' => form_error('cabang'),
        'customer' => form_error('customer'),
        'agency' => form_error('agency'),
        'type' => form_error('type'),
        'kapal' => form_error('kapal'),
        'surat-penawaran' => form_error('surat-penawaran'),
        'surat-penunjukan' => form_error('surat-penunjukan'),
        'jenis' => form_error('jenis'),
        'port' => form_error('port'),
        'eta' => form_error('eta'),
        'grt' => form_error('grt'),
        'grt_barge' => form_error('grt_barge')
      ];

      $response = [
        'success' => false,
        'msg' => 'Gagal input periksa kembali inputan anda',
        'errors' => $errors
      ];
    } else {
      if (!$this->upload->do_upload('file-penunjukan')) {
        $errors = [
          'file-penunjukan' => $this->upload->display_errors()
        ];

        $response = [
          'success' => false,
          'msg' => 'Gagal input file',
          'errors' => $errors,
        ];
      } else {
        $upload = $this->upload->data();
        $insert_penunjukan = [
          'agency' => $agency,
          'no_surat' => $no_surat,
          'nama_kapal' => $kapal,
          'file' => $file,
          'file_name' => $upload['file_name'],
          'user' => $this->session->userdata('nip'),
          'jenis' => $jenis,
          'penawaran' => $penawaran,
          'customer' => $customer,
          'id_cabang' => $cabang,
          'type' => $type,
        ];

        $this->db->trans_start();
        // insert penunjukan
        $this->db->insert('t_penunjukan', $insert_penunjukan);
        $id_penunjukan = $this->db->insert_id();

        // Get Agency
        $agency = $this->db->get_where('agent', ['Id' => $agency])->row_array();

        // Get port 
        $data_port = $this->db->get_where('agency_port', ['Id' => $port])->row_array();

        $item_pda_desc = $this->db->get_where('t_item_pda', ['jenis' => $jenis, 'port' => $data_port['kode'], 'default' => 1, 'title' => 'DESC'])->result_array();
        $item_pda_ar = $this->db->get_where('t_item_pda', ['jenis' => $jenis, 'port' => $data_port['kode'], 'default' => 1, 'title' => 'AGENCY REMUNERATION'])->result_array();

        if (!$item_pda_desc or !$item_pda_ar) {
          $this->db->trans_rollback();
          $response = [
            'success' => false,
            'msg' => 'Item Pda untuk port ' . $data_port['kode'] . ' belum tersedia!'
          ];

          echo json_encode($response);
          return false;
        }

        foreach ($item_pda_desc as $val) {
          $id_desc[] = $val['Id'];
          $remarks[] = $val['remarks'];
          $data_grt[] = $val['grt'];
          $tarif[] = $val['tarif'];
          $activity[] = $val['activity'];
          $amount_desc[] = $val['amount'];
          $remark_desc[] = $val['tag'];
        }

        foreach ($item_pda_ar as $value) {
          $desc[] = $value['Id'];
          $amount[] = $value['est'];
          $qty[] = 1;
          $remark[] = $value['tag'];
        }

        $data = [
          'desc' => [
            'id_desc' => $id_desc,
            'remarks' => $remarks,
            'grt' => $data_grt,
            'tarif' => $tarif,
            'activity' => $activity,
            'amount_desc' => $amount_desc,
            'remark_desc' => $remark_desc
          ],
          'agency_remuneration' => [
            'desc' => $desc,
            'amount' => $amount,
            'qty' => $qty,
            'remark' => $remark
          ]
        ];

        $data_cabang = $this->db->get_where('agency_cabang', ['Id' => $cabang])->row_array();

        $insert_pda = [
          'penunjukan' => $id_penunjukan,
          'tanggal' => date('Y-m-d'),
          'to' => $agency['kode'] . ' Jakarta',
          'from' => $agency['kode'] . ' ' . $data_cabang['nama'],
          'port' => $port,
          'eta' => $eta,
          'grt' => str_replace(',', '', $grt),
          'grt_barge' => str_replace(',', '', $grt_barge),
          'vessel_name' => $kapal,
          'est' => json_encode($data),
          'user_request' => $cabang == 1 ? '202501116' : '',
          'id_cabang' => $cabang
        ];

        // insert pda
        $this->db->insert('t_pda', $insert_pda);
        $last_pda_id = $this->db->insert_id();

        // Insert monitoring pra pda
        $insert_monitoring = [
          'pda_id' => $last_pda_id,
          'start' => date('Y-m-d H:i:s'),
          'user_start' => $this->session->userdata('nip'),
          'user_end' => $this->session->userdata('nip'),
          'end' => date('Y-m-d H:i:s'),
          'detail' => json_encode($data),
          'status' => 2
        ];

        $this->db->insert('monitoring_prapda', $insert_monitoring);

        // Monitoring dokumen
        $insert_monitoring_dok = [
          'pda_id' => $last_pda_id,
          'status' => 0
        ];

        $this->db->insert('monitoring_dokumen', $insert_monitoring_dok);

        // Monitoring hpp
        $insert_monitoring_hpp = [
          'pda_id' => $last_pda_id,
          'status' => 0
        ];

        $this->db->insert('monitoring_hpprill', $insert_monitoring_hpp);

        // Monitoring harga jual
        $insert_monitoring_hrgjual = [
          'pda_id' => $last_pda_id,
          'status' => 0
        ];

        $this->db->insert('monitoring_hrgjual', $insert_monitoring_hrgjual);

        // Monitoring invoice
        $insert_monitoring_inv = [
          'pda_id' => $last_pda_id,
          'status' => 0
        ];

        $this->db->insert('monitoring_invoice', $insert_monitoring_inv);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
          $this->db->trans_rollback();
          $response = [
            'success' => false,
            'msg' => 'Gagal Input'
          ];
        } else {
          $this->db->trans_commit();
          $response = [
            'success' => true,
            'reload' => base_url('agency/penunjukan'),
            'msg' => 'Data berhasil disimpan!'
          ];
        }
      }
    }
    echo json_encode($response);
  }

  public function ubah_penunjukan($id)
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/penunjukan', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['detail'] = $this->M_agency->get_penunjukanById($id);

    $data['title'] = 'Ubah Penunjukan ' . $data['detail']['no_surat'];
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/v_ubah_penunjukan';
    $data['pages_script'] = 'script/agency/s_penunjukan';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $data['cabang'] = $this->db->get('agency_cabang');
    $data['customer'] = $this->db->get('agency_customer');
    $data['agency'] = $this->db->get('agent');
    $data['penawaran'] = $this->db->get('t_penawaran');
    $data['port'] = $this->db->get('agency_port');
    $data['data_pda'] = $this->db->get_where('t_pda', ['penunjukan' => $data['detail']['Id']])->row_array();
    $data['kategori_kapal'] = $this->db->get('agency_kapal_kategori')->result();
    $data['kapal'] = $this->db->get_where('agency_kapal', ['type' => $data['detail']['type']])->result();

    $this->load->view('index', $data);
  }

  public function update_penunjukan($id)
  {
    $cabang = $this->input->post('cabang');
    $agency = $this->input->post('agency');
    $penawaran = $this->input->post('surat-penawaran');
    $no_surat = $this->input->post('surat-penunjukan');
    $kapal = $this->input->post('kapal');
    $jenis = $this->input->post('jenis');
    $file = $_FILES['file-penunjukan-ubah']['name'];
    $customer = $this->input->post('customer');
    $port = $this->input->post('port');
    $eta = $this->input->post('eta');
    $grt = $this->input->post('grt');
    $grt_barge = $this->input->post('grt_barge');
    $type = $this->input->post('type');

    $config['upload_path'] = './upload/penunjukan';
    $config['allowed_types'] =  'jpg|jpeg|png|pdf';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;

    $this->upload->initialize($config);

    if (!is_dir('upload/penunjukan')) {
      mkdir('./upload/penunjukan', 0777, TRUE);
    }

    $this->db->trans_start();

    $pda = $this->db->select('Id, penunjukan, hpp_rill')->from('t_pda')->where('penunjukan', $id)->get()->row_array();
    $data_agency = $this->db->get_where('agent', ['Id' => $agency])->row_array();
    // Get port 
    $data_port = $this->db->get_where('agency_port', ['Id' => $port])->row_array();

    $item_pda_desc = $this->db->get_where('t_item_pda', ['jenis' => $jenis, 'port' => $data_port['kode'], 'default' => 1, 'title' => 'DESC'])->result_array();
    $item_pda_ar = $this->db->get_where('t_item_pda', ['jenis' => $jenis, 'port' => $data_port['kode'], 'default' => 1, 'title' => 'AGENCY REMUNERATION'])->result_array();

    if ($pda['hpp_rill']) {
      $this->db->trans_rollback();
      $response = [
        'success' => false,
        'msg' => 'Hpp rill sudah terisi, tidak bisa update!'
      ];

      echo json_encode($response);
      return false;
    }

    if (!$item_pda_desc or !$item_pda_ar) {
      $this->db->trans_rollback();
      $response = [
        'success' => false,
        'msg' => 'Item Pda untuk port ' . $data_port['kode'] . ' belum tersedia!'
      ];

      echo json_encode($response);
      return false;
    }

    foreach ($item_pda_desc as $val) {
      $id_desc[] = $val['Id'];
      $remarks[] = $val['remarks'];
      $data_grt[] = $val['grt'];
      $tarif[] = $val['tarif'];
      $activity[] = $val['activity'];
      $amount_desc[] = $val['amount'];
      $remark_desc[] = $val['tag'];
    }

    foreach ($item_pda_ar as $value) {
      $desc[] = $value['Id'];
      $amount[] = $value['est'];
      $qty[] = 1;
      $remark[] = $value['tag'];
    }

    $data = [
      'desc' => [
        'id_desc' => $id_desc,
        'remarks' => $remarks,
        'grt' => $data_grt,
        'tarif' => $tarif,
        'activity' => $activity,
        'amount_desc' => $amount_desc,
        'remark_desc' => $remark_desc
      ],
      'agency_remuneration' => [
        'desc' => $desc,
        'amount' => $amount,
        'qty' => $qty,
        'remark' => $remark
      ]
    ];

    $data_cabang = $this->db->get_where('agency_cabang', ['Id' => $cabang])->row_array();

    $update_pda = [
      'tanggal' => date('Y-m-d'),
      'to' => $data_agency['kode'] . ' Jakarta',
      'from' => $data_agency['kode'] . ' ' . $data_cabang['nama'],
      'port' => $port,
      'eta' => $eta,
      'grt' => str_replace(',', '', $grt),
      'grt_barge' => str_replace(',', '', $grt_barge),
      'vessel_name' => $kapal,
      'est' => json_encode($data),
      'user_request' => $cabang == 1 ? '202501116' : '',
      'id_cabang' => $cabang,
    ];

    // update pda
    $this->db->where('penunjukan', $id);
    $this->db->update('t_pda', $update_pda);

    // Insert monitoring pra pda
    $update_monitoring_pda = [
      'pda_id' => $pda['Id'],
      'start' => date('Y-m-d H:i:s'),
      'user_start' => $this->session->userdata('nip'),
      'user_end' => $this->session->userdata('nip'),
      'end' => date('Y-m-d H:i:s'),
      'detail' => json_encode($data),
      'status' => 2
    ];

    $this->db->where('pda_id', $pda['Id']);
    $this->db->update('monitoring_prapda', $update_monitoring_pda);

    if ($file) {
      if (!$this->upload->do_upload('file-penunjukan-ubah')) {
        $response = [
          'success' => false,
          'msg' => $this->upload->display_errors()
        ];

        echo json_encode($response);

        return false;
      } else {
        $file_old = $this->db->get_where('t_penunjukan', ['Id' => $id])->row_array();
        if ($file_old && file_exists('./upload/penunjukan/' . $file_old['file_name'])) {
          unlink('./upload/penunjukan/' . $file_old['file_name']);
        }
        $upload = $this->upload->data();
        $update = [
          'no_surat' => $no_surat,
          'agency' => $agency,
          'nama_kapal' => $kapal,
          'file' => $file,
          'file_name' => $upload['file_name'],
          'user_update' => $this->session->userdata('nip'),
          'jenis' => $jenis,
          'penawaran' => $penawaran,
          'customer' => $customer,
          'id_cabang' => $cabang,
          'type' => $type
        ];

        $this->db->where('Id', $id);
        $this->db->update('t_penunjukan', $update);
      }
    } else {
      $update = [
        'no_surat' => $no_surat,
        'nama_kapal' => $kapal,
        'agency' => $agency,
        'user_update' => $this->session->userdata('nip'),
        'jenis' => $jenis,
        'penawaran' => $penawaran,
        'customer' => $customer,
        'id_cabang' => $cabang,
        'type' => $type
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_penunjukan', $update);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = [
        'success' => false,
        'msg' => 'Gagal Input'
      ];
    } else {
      $this->db->trans_commit();
      $response = [
        'success' => true,
        'reload' => base_url('agency/penunjukan'),
        'msg' => 'Data berhasil diubah!',
        'pda' => $pda
      ];
    }

    echo json_encode($response);
  }

  public function item_penawaran()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('agency/item_penawaran');
    $config['total_rows'] = $this->M_agency->count_item_penawaran($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // Bootstrap style pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-end">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = "First";
    $config['last_link'] = "Last";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');

    // Initialize paginaton
    $this->pagination->initialize($config);
    $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['pagination'] = $this->pagination->create_links();

    $data['page'] = $page;
    $data['penawaran'] = $this->M_agency->get_item_penawaran($config['per_page'], $page, $keyword);

    $data['title'] = 'Item Penawaran';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/penawaran/v_item_penawaran';
    $data['pages_script'] = 'script/agency/s_penawaran';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function tambah_item_penawaran()
  {
    $nama = $this->input->post('nama_penawaran');
    $harga = $this->input->post('harga_penawaran');
    $jenis = $this->input->post('jenis_item');

    $this->form_validation->set_rules('nama_penawaran', 'Nama Penawaran', 'required', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('harga_penawaran', 'Harga Penawaran', 'required', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('jenis_item', 'Jenis', 'required', array('required' => '%s wajib diisi'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $insert = [
        'nama_penawaran' => $nama,
        'cost' => preg_replace('/[^a-zA-Z0-9\']/', '', $harga),
        'jenis' => $jenis
      ];

      $this->db->insert('t_item_penawaran', $insert);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil ditambahkan!',
      ];
    }

    echo json_encode($response);
  }

  public function ubah_item_penawaran($id)
  {
    $nama = $this->input->post('nama_penawaran_ubah');
    $harga = $this->input->post('harga_penawaran_ubah');
    $jenis = $this->input->post('jenis_item_ubah');

    $this->form_validation->set_rules('nama_penawaran_ubah', 'Nama Penawaran', 'required', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('harga_penawaran_ubah', 'Harga Penawaran', 'required', array('required' => '%s wajib diisi'));
    $this->form_validation->set_rules('jenis_item_ubah', 'Jenis', 'required', array('required' => '%s wajib diisi'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $update = [
        'nama_penawaran' => $nama,
        'cost' => preg_replace('/[^a-zA-Z0-9\']/', '', $harga),
        'jenis' => $jenis
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_item_penawaran', $update);

      $response = [
        'success' => true,
        'msg' => 'Data berhasil diubah!',
      ];
    }

    echo json_encode($response);
  }

  public function hapus_item_penawaran($id)
  {
    $this->db->where('Id', $id);
    $this->db->delete('t_item_penawaran');

    $response = [
      'success' => true,
      'msg' => 'Data berhasil dihapus!'
    ];

    echo json_encode($response);
  }

  public function penawaran()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('agency/penawaran');
    $config['total_rows'] = $this->M_agency->count_penawaran($keyword);
    $config['per_page'] = 10;
    $config['uri_segment'] = 3;
    $config['num_links'] = 3;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // Bootstrap style pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-end">';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = "First";
    $config['last_link'] = "Last";
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');

    // Initialize paginaton
    $this->pagination->initialize($config);
    $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['pagination'] = $this->pagination->create_links();

    $data['page'] = $page;
    $data['penawaran'] = $this->M_agency->get_penawaran($config['per_page'], $page, $keyword);

    $data['title'] = 'Daftar Penawaran';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/penawaran/v_penawaran';
    $data['pages_script'] = 'script/agency/s_penawaran';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function create_penawaran()
  {
    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/penawaran', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['customer'] = $this->db->get('agency_customer')->result_array();
    $data['penawaran_tetap'] = $this->db->get_where('t_item_penawaran', ['jenis' => 1])->result_array();
    $this->db->where('jenis', 1);
    $this->db->or_where('jenis', 2);
    $data['penawaran_tetap_all'] = $this->db->get('t_item_penawaran')->result_array();
    $data['penawaran_tetap_add'] = $this->db->get_where('t_item_penawaran', ['jenis' => 2])->result_array();
    $data['penawaran_tambahan'] = $this->db->get_where('t_item_penawaran', ['jenis' => 3])->result_array();

    $data['title'] = 'Buat Penawaran';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/penawaran/v_form_penawaran';
    $data['pages_script'] = 'script/agency/s_penawaran';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function getCostByIdPenawaran()
  {
    $id = $this->input->post('id');
    $penawaran = $this->db->get_where('t_item_penawaran', ['Id' => $id])->row_array();

    $response = [
      'cost' => $penawaran['cost'],
    ];

    echo json_encode($response);
  }

  public function insert_penawaran()
  {
    $tgl = $this->input->post('tgl');
    $cust = $this->input->post('cust');
    $isi = $this->input->post('isi');
    $attn = $this->input->post('attn');
    $notes = $this->input->post('notes');
    $perihal = $this->input->post('perihal');
    $agency = $this->input->post('agency');

    // item tetap
    $id_item_tetap = $this->input->post('item_tetap[]');
    $cost = $this->input->post('cost[]');
    $remarks = $this->input->post('remarks');

    // Item tambahan
    $desc = $this->input->post('desc');
    $cost_tambahan = $this->input->post('cost-tambahan[]');
    $remarks_tambahan = $this->input->post('remarks-tambahan[]');

    $this->form_validation->set_rules('tgl', 'Tanggal', 'required', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('cust', 'Customer', 'required', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('agency', 'Agency', 'required', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('isi', 'Isi', 'required', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('cost[]', 'Harga penawaran tetap', 'required|trim', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim', ['required' => '%s wajib diisi!']);

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $sql = "SELECT count(Id) as jumlah FROM t_penawaran WHERE YEAR(tanggal) = YEAR(curdate()) AND agency = '$agency';";
      $count = $this->db->query($sql)->row_array();

      $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
      $bln = $array_bln[date('n', strtotime($tgl))];
      $tahun = date('Y', strtotime($tgl));


      $agent = $this->db->get_where('agent', ['Id' => $agency])->row_array();
      $nomor_surat = $count['jumlah'] + 1 . "/" . $agent['kode'] . "-JKT/SP/" . $bln . "/" . $tahun;

      $item_tetap = [
        'id' => $id_item_tetap,
        'cost' => preg_replace('/[^a-zA-Z0-9\']/', '', $cost),
        'remarks' => $remarks
      ];

      $item_tambahan = [
        'id' => $desc,
        'cost' => preg_replace('/[^a-zA-Z0-9\']/', '', $cost_tambahan),
        'remarks' => $remarks_tambahan
      ];

      $insert = [
        'user' => $this->session->userdata('nip'),
        'isi' => $isi,
        'no_surat' => $nomor_surat,
        'perihal' => $perihal,
        'tujuan' => $cust,
        'attn' => $attn,
        'tanggal' => $tgl,
        'agency' => $agency,
        'item_tetap' => json_encode($item_tetap),
        'item_tambahan' => json_encode($item_tambahan),
        'catatan' => $notes
      ];

      $this->db->insert('t_penawaran', $insert);

      $response = [
        'success' => true,
        'msg' => 'Surat Penawaran Sukses Dibuat!'
      ];
    }

    echo json_encode($response);
  }

  public function view_penawaran($id)
  {
    // $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!in_array('agency/penawaran', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['penawaran'] = $this->M_agency->getByIdPenawaran($id)->row_array();
    $data['title'] = 'Detail Penawaran';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/penawaran/v_penawaran_detail';
    $data['pages_script'] = 'script/agency/s_penawaran';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function upload_penawaran($id)
  {
    $file_name = $_FILES['file-penawaran']['name'];

    $config['upload_path'] = './upload/penawaran';
    $config['allowed_types'] = 'docx|pdf';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    if (!is_dir('upload/penawaran')) {
      mkdir('./upload/penawaran', 0777, TRUE);
    }

    if (!$this->upload->do_upload('file-penawaran')) {
      $response = [
        'success' => false,
        'msg' => $this->upload->display_errors()
      ];
    } else {

      $upload = $this->upload->data();
      $data = [
        'file' => $file_name,
        'file_name' => $upload['file_name']
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_penawaran', $data);

      $response = [
        'success' => true,
        'msg' => 'File berhasil diupload!'
      ];
    }

    echo json_encode($response);
  }

  public function word_penawaran($id)
  {
    $sql = "SELECT a.Id, a.user, a.no_surat, a.perihal, a.tanggal, a.attn, a.isi, a.item_tetap, a.item_tambahan, a.catatan, b.nama_customer, c.nama, d.nama as nama_agen, d.desc, d.kode, d.logo FROM t_penawaran a LEFT JOIN agency_customer b ON a.tujuan = b.Id LEFT JOIN users c ON a.user = c.nip LEFT JOIN agent d ON d.Id = a.agency WHERE a.Id = '$id'";
    $result = $this->db->query($sql)->row_array();

    // Creating the new document...
    $phpWord = new PhpWord();

    /* Note: any element you append to a document must reside inside of a Section. */

    // Adding an empty Section to the document...
    $section = $phpWord->addSection();

    // set default
    $phpWord->setDefaultFontSize(12);
    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultParagraphStyle(
      array(
        'align'      => 'both',
        'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
        'spacing' => 120,
        'lineHeight' => 1
      )
    );

    // Add Header
    $cellRowContinue = array('vMerge' => 'continue');
    $header = $section->addHeader();
    $tableHeader = $header->addTable(array(
      'cellMargin' => 0,
      'spaceBefore' => 0,
      'spaceAfter' => 0,
      'spacing' => 0
    ));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue)->addImage(base_url('img/' . $result['logo']), array('width' => 80));
    // $tableHeader->addCell(2000, array('gridSpan' => 3))->addText($result['nama_agen'], array('bold' => true, 'size' => 14));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue);
    // $tableHeader->addCell(2000, array('gridSpan' => 3))->addText($result['desc'], array('bold' => true, 'size' => 12));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue);
    // $tableHeader->addCell(2000)->addText('Head Office', array('size' => 11));
    // $tableHeader->addCell(150)->addText(':', array('size' => 11));
    // $tableHeader->addCell()->addText('Wisma Baja 3rd Fl, Jl. Jend. Gatot Subroto Kav.54,Jakarta Selatan 12950 Phone : 021-5221244', array('size' => 11));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue);
    // $tableHeader->addCell(2000)->addText('Branch Office', array('size' => 11));
    // $tableHeader->addCell(150)->addText(':', array('size' => 11));
    // $tableHeader->addCell()->addText('Jl. Residence A Rozak Komplek Grand Pondok Indah No.4 Kalidoni Palembang – Sumatera Selatan', array('size' => 11));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue);
    // $tableHeader->addCell(2000)->addText('', array('size' => 11));
    // $tableHeader->addCell(150)->addText(':', array('size' => 11));
    // $tableHeader->addCell()->addText('Jl. Pakis No. 5, Rt. 019 Rw.000, Kel. Sidomulyo, Samarinda', array('size' => 11));
    // $tableHeader->addRow();
    // $tableHeader->addCell(3500, $cellRowContinue);
    // $tableHeader->addCell(2000)->addText('Email', array('size' => 11));
    // $tableHeader->addCell(150)->addText(':', array('size' => 11));
    // $tableHeader->addCell()->addText('marketing@djsshipping.co.id', array('size' => 11));

    $header->addLine(array('weight' => 2, 'width' => '450', 'height' => 0, 'color' => 00000));

    // add footer
    $footer = $section->addFooter();
    $footer->addLine(array('weight' => 1, 'width' => '450', 'height' => 0, 'color' => 00000));
    $phpWord->addParagraphStyle('footer', array('align' => 'center'));
    $phpWord->addFontStyle('fstyle_footer1', array('italic' => true, 'size' => 11));
    $phpWord->addFontStyle('fstyle_footer2', array('size' => 9, 'name' => 'Aptos Narrow'));
    $footer = $footer->addTextRun('footer');
    $footer->addText('Ship Agency, Ship Chartering, Ship Chandler Suplyer, Marine Engineering', 'fstyle_footer1');
    $footer->addTextBreak(1);
    $footer->addText('Gresik Office: One Place Building 2nd Fl, Jl.Jend Sudirman No.1, Gresik, East of Java, Indonesia, Phone: 031-3992-2545', 'fstyle_footer2');
    $footer->addTextBreak(1);
    $footer->addText('Merak Office: Gedong Cilegon Damai Blok C.39 No.8-A, Katimbang, Cibeber, Cilegon, Banten 42424, Phone: 0254-781.3126', 'fstyle_footer2');
    $footer->addTextBreak(1);
    $footer->addText('Pel.Ratu Office: Pantai Ratu Indah, Jl. Lumba-Lumba, Blok-B No.18, Palabuhanratu, Sukabumi 43364, Phone: 026-66448777', 'fstyle_footer2');
    $footer->addTextBreak(1);
    $footer->addText('Palembang Office : Jl. Residence A Rozak Komplek Grand Pondok Indah No.4 Kalidoni', 'fstyle_footer2');
    $footer->addTextBreak(1);
    $footer->addText('Samarinda Office : Jl. Pakis No. 5, Rt. 019 Rw.000, Kel. Sidomulyo,Samarinda.', 'fstyle_footer2');

    // Break
    $section->addTextBreak(1);

    // Tanggal Surat
    $phpWord->addParagraphStyle('tanggal', array('align' => 'right'));
    $tanggal = $section->addTextRun('tanggal');
    $tanggal->addText('Jakarta, ' . tgl_indo($result['tanggal']));

    // informasi no surat dan perihal
    $styleTable = array(
      'cellMargin' => 0,
      'spaceBefore' => 0,
      'spaceAfter' => 0,
      'spacing' => 0
    );

    $phpWord->addTableStyle('no-border', $styleTable);
    $table = $section->addTable('no-border');
    $table->addRow(0);
    $table->addCell(1200)->addText('No. Surat');
    $table->addCell(30)->addText(':');
    $table->addCell(2500)->addText($result['no_surat']);
    $table->addRow(0);
    $table->addCell(1200)->addText('Perihal');
    $table->addCell(30)->addText(':');
    $table->addCell(4000)->addText($result['perihal']);

    // Break
    $section->addTextBreak();

    // Tujuan
    $section->addText('Kepada Yth. :');
    $section->addText($result['nama_customer']);
    if ($result['attn']) {
      $section->addText('Attn : ' . $result['attn']);
    }

    // Break
    $section->addTextBreak();

    // Pengantar
    $section->addText($result['isi']);

    // Break
    $section->addTextBreak(1);

    // Table penawaran
    $borderStyle = array('borderSize' => 6, 'borderColor' => '000305', 'cellMarginTop' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 80, 'cellMarginRight' => 80);
    $fontStyle = array('bold' => true);
    $phpWord->addTableStyle('border', $borderStyle);
    $table = $section->addTable('border');
    $table->addRow();
    $table->addCell(600)->addText('No.', $fontStyle, array('align' => 'center'));
    $table->addCell(5000)->addText('Description', $fontStyle, array('align' => 'center'));
    $table->addCell(2500)->addText('Cost', $fontStyle, array('align' => 'center'));
    $table->addCell(3000)->addText('Remarks', $fontStyle, array('align' => 'center'));
    //Colspan
    $table->addRow();
    $table->addCell(600, array('gridSpan' => 4))->addText('BIAYA TETAP', $fontStyle, array('align' => 'center'));
    $biaya_tetap = json_decode($result['item_tetap']);
    foreach ($biaya_tetap->cost as $item) {
      $cost[] = $item;
    }

    foreach ($biaya_tetap->remarks as $item) {
      $remarks[] = $item;
    }

    $no = 1;
    foreach ($biaya_tetap->id as $key => $item) {
      $this->db->select('nama_penawaran');
      $item_penawaran[] = $this->db->get_where('t_item_penawaran', ['Id' => $item])->row_array();
      $table->addRow();
      $table->addCell(600)->addText($no++);
      $table->addCell(5000)->addText($item_penawaran[$key]['nama_penawaran']);
      $table->addCell(2500)->addText($cost[$key] != 0 ? 'Rp.' . number_format($cost[$key], 0, ',', '.') : '-');
      $table->addCell(3000)->addText($remarks[$key]);
    }

    $biaya_tambahan = json_decode($result['item_tambahan']);
    if ($biaya_tambahan->id[0] != "") {
      // Colspan
      $table->addRow();
      $table->addCell(600, array('gridSpan' => 4))->addText('BIAYA TAMBAHAN', $fontStyle, array('align' => 'center'));

      foreach ($biaya_tambahan->cost as $item) {
        $cost_tambahan[] = $item;
      }

      foreach ($biaya_tambahan->remarks as $item) {
        $remarks_tambahan[] = $item;
      }

      $n = 1;
      foreach ($biaya_tambahan->id as $k => $item) {
        $this->db->select('nama_penawaran');
        $item_penawaran_tambahan[] = $this->db->get_where('t_item_penawaran', ['Id' => $item])->row_array();
        $table->addRow();
        $table->addCell(600)->addText($n++);
        $table->addCell(5000)->addText($item_penawaran_tambahan[$k]['nama_penawaran']);
        $table->addCell(2500)->addText($cost_tambahan[$k] != 0 ? 'Rp.' . number_format($cost_tambahan[$k], 0, ',', '.') : '-');
        $table->addCell(3000)->addText($remarks_tambahan[$k]);
      }
    }
    // Break
    $section->addTextBreak(1);

    // Note
    $section->addText($result['catatan']);

    // Break
    $section->addTextBreak(1);

    // Informasi transfer
    $section->addText('Transfer Bank ABC Cabang ABC');
    $section->addText($result['nama_agen']);
    $section->addText('No. Rek : 000000');

    // Break
    $section->addTextBreak(2);

    // tanda tangan
    $table = $section->addTable(array('width' => 100));
    $table->addRow();
    $table->addCell(10000)->addText('Hormat Kami,');
    $table->addCell(5000)->addText('Disetujui,');

    $table->addRow();
    $table->addCell(10000)->addText($result['nama_agen']);
    $table->addCell(5000)->addText($result['nama_customer']);

    $table->addRow();
    // $table->addCell(10000)->addImage(base_url('img/ttd/erdanalis.png'), array(
    //   'width' => 150,
    //   'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    //   'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    //   'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
    //   'wrappingStyle' => 'square'
    // ));
    $table->addCell(5000)->addText("");

    $table->addRow();
    $table->addCell(10000)->addText('user');
    if ($result['attn']) {
      $table->addCell(5000)->addText($result['attn']);
    }

    $table->addRow();
    $table->addCell(10000)->addText('Kepala Cabang');
    $table->addCell(5000)->addText('');

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
    header('Content-Disposition: attachment;filename="' . $result['no_surat'] . '.docx"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    $objWriter->save('php://output');
  }

  public function kapal()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'List Kapal';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/kapal/v_list_kapal';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $this->load->view('index', $data);
  }

  public function kapal_ajax_list()
  {
    $list = $this->M_agency->get_kapalDatatables();
    $data = array();
    $no = $this->input->post('start');
    $i = 1;
    foreach ($list as $kapal) {

      $ubah = '<a href="' . base_url("agency/ubah_kapal/") . $kapal->Id . '" class="btn btn-success btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Update</a>';

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $kapal->name;
      $row[] = strtoupper($kapal->type);
      $row[] = $kapal->flag;
      $row[] = number_format($kapal->grt) . " T";
      $row[] = number_format($kapal->dwt) . " T";
      $row[] = $ubah;
      $data[] = $row;
    }

    $minSearchLength = 3;
    $search = $this->input->post('search')['value'];
    $draw = intval($this->input->post('draw'));
    // Check if search length is less than the minimum
    if (strlen($search) > 0 && strlen($search) < $minSearchLength) {
      echo json_encode([
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
      ]);
      return;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->M_agency->count_all_kapal(),
      "recordsFiltered" => $this->M_agency->count_filtered_kapal(),
      "data" => $data,
    );
    //output to json format
    $this->output->set_output(json_encode($output));
  }

  public function add_kapal()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/kapal', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'Form Kapal';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/kapal/v_form_kapal';
    $data['pages_script'] = 'script/agency/s_kapal';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['kategori'] = $this->db->get('agency_kapal_kategori')->result();

    $this->load->view('index', $data);
  }

  public function insert_kapal()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/kapal', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $vessel = $this->input->post('vessel-name');
    $type = $this->input->post('type');
    $flag = $this->input->post('flag');
    $gross = $this->input->post('gross');
    $gross_bg = $this->input->post('gross_barge');
    $dwt = $this->input->post('dwt');

    $this->form_validation->set_rules('vessel-name', 'vessel of name', 'required|trim');
    $this->form_validation->set_rules('type', 'type', 'required');
    $this->form_validation->set_rules('flag', 'flag', 'required|trim');
    $this->form_validation->set_rules('gross', 'gross tonnage', 'required|trim');
    $this->form_validation->set_rules('gross_barge', 'gross tonnage barge', 'required|trim');
    $this->form_validation->set_rules('dwt', 'dwt', 'required|trim');

    if ($this->form_validation->run() == FALSE) {

      $errors = [
        'vessel-name' => form_error('vessel-name'),
        'type' => form_error('type'),
        'flag' => form_error('flag'),
        'gross' => form_error('gross'),
        'dwt' => form_error('dwt'),
      ];

      $response = [
        'success' => false,
        'msg' => 'Gagal Input!',
        'errors' => $errors
      ];

      echo json_encode($response);
      return false;
    }

    $this->db->trans_start();
    $insert = [
      'name' => $vessel,
      'type' => $type,
      'flag' => $flag,
      'grt' => str_replace(',', '', $gross),
      'grt_barge' => str_replace(',', '', $gross_bg),
      'dwt' => str_replace(',', '', $dwt)
    ];

    $this->db->insert('agency_kapal', $insert);

    $this->db->trans_complete();

    if ($this->db->trans_status() == false) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      $response = [
        'success' => true,
        'reload' => base_url('agency/kapal'),
        'msg' => 'Data kapal berhasil disimpan!'
      ];
    }

    echo json_encode($response);
  }

  public function ubah_kapal($id)
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/kapal', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'Form Kapal';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/kapal/v_form_ubah_kapal';
    $data['pages_script'] = 'script/agency/s_kapal';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['kategori'] = $this->db->get('agency_kapal_kategori')->result();
    $data['kapal'] = $this->db->get_where('agency_kapal', ['Id' => $id])->row();

    if (!$data['kapal']) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $this->load->view('index', $data);
  }

  public function update_kapal($id)
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('agency/kapal', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $vessel = $this->input->post('vessel-name');
    $type = $this->input->post('type');
    $flag = $this->input->post('flag');
    $gross = $this->input->post('gross');
    $gross_bg = $this->input->post('gross_barge');
    $dwt = $this->input->post('dwt');

    $this->form_validation->set_rules('vessel-name', 'vessel of name', 'required|trim');
    $this->form_validation->set_rules('type', 'type', 'required');
    $this->form_validation->set_rules('flag', 'flag', 'required|trim');
    $this->form_validation->set_rules('gross', 'gross tonnage', 'required|trim');
    $this->form_validation->set_rules('gross_barge', 'gross tonnage barge', 'required|trim');
    $this->form_validation->set_rules('dwt', 'dwt', 'required|trim');

    if ($this->form_validation->run() == FALSE) {

      $errors = [
        'vessel-name' => form_error('vessel-name'),
        'type' => form_error('type'),
        'flag' => form_error('flag'),
        'gross' => form_error('gross'),
        'dwt' => form_error('dwt'),
      ];

      $response = [
        'success' => false,
        'msg' => 'Gagal Input!',
        'errors' => $errors
      ];

      echo json_encode($response);
      return false;
    }

    $this->db->trans_start();
    $update = [
      'name' => $vessel,
      'type' => $type,
      'flag' => $flag,
      'grt' => str_replace(',', '', $gross),
      'grt_barge' => str_replace(',', '', $gross_bg),
      'dwt' => str_replace(',', '', $dwt)
    ];

    $this->db->where('Id', $id);
    $this->db->update('agency_kapal', $update);

    $this->db->trans_complete();

    if ($this->db->trans_status() == false) {
      $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
      $response = [
        'success' => true,
        'reload' => base_url('agency/kapal'),
        'msg' => 'Data kapal berhasil diubah!'
      ];
    }

    echo json_encode($response);
  }

  public function getKapalByType()
  {
    $type = $this->input->post('id');
    $data = $this->db->where('type', $type)->get('agency_kapal')->result();
    echo json_encode($data);
  }

  public function getKapalById()
  {
    $id = $this->input->post('id');
    $kapal = $this->db->get_where('agency_kapal', ['Id' => $id])->row();

    echo json_encode($kapal);
  }

  public function exists($value, $params)
  {
    list($table, $field) = explode('.', $params);

    if ($value == '') {
      return TRUE;
    }

    $exists = $this->db
      ->where($field, $value)
      ->get($table)
      ->num_rows();

    if ($exists == 0) {
      $this->form_validation->set_message(
        'exists',
        '%s tidak valid'
      );
      return FALSE;
    }
    return TRUE;
  }
}
