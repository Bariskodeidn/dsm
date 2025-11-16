<?php
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
    $config['first_link'] = true;
    $config['last_link'] = true;
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
    $config['first_link'] = true;
    $config['last_link'] = true;
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
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $data['cabang'] = $this->db->get('agency_cabang');
    $data['customer'] = $this->db->get('agency_customer');
    $data['agency'] = $this->db->get('agent');
    $data['penawaran'] = $this->db->get('t_penawaran');
    $data['port'] = $this->db->get('agency_port');

    $this->load->view('index', $data);
  }

  public function insert_penunjukan()
  {
    $agency = $this->input->post('agency');
    $penawaran = $this->input->post('surat-penawaran');
    $no_surat = $this->input->post('surat-penunjukan');
    $kapal = $this->input->post('kapal');
    $jenis = $this->input->post('jenis');
    $file = $_FILES['file-penunjukan']['name'];
    $customer = $this->input->post('customer');
    $cabang = $this->input->post('cabang');

    $port = $this->input->post('port');
    $eta = $this->input->post('eta');
    $grt = $this->input->post('grt');

    $this->form_validation->set_rules('cabang', 'Cabang', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('customer', 'Nama customer', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('agency', 'Nama agency', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('surat-penunjukan', 'No surat penunjukan', 'required|trim', array('required' => "%s wajib diisi!"));
    $this->form_validation->set_rules('kapal', 'Nama Kapal', 'required', array('required|trim' => "%s wajib diisi!"));
    $this->form_validation->set_rules('jenis', 'Jenis', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('port', 'Port', 'required', array('required' => "%s wajib dipilih!"));
    $this->form_validation->set_rules('eta', 'ETA', 'required', array('required' => "%s wajib diisi!"));
    $this->form_validation->set_rules('grt', 'GRT', 'required', array('required|trim' => "%s wajib diisi!"));

    $config['upload_path'] = './upload/penunjukan';
    $config['allowed_types'] =  'jpg|jpeg|png|pdf';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    if (!is_dir('upload/penunjukan')) {
      mkdir('./upload/penunjukan', 0777, TRUE);
    }

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      if (!$this->upload->do_upload('file-penunjukan')) {
        $response = [
          'success' => false,
          'msg' => $this->upload->display_errors()
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
          'id_cabang' => $cabang
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
          'grt' => $grt,
          'vessel_name' => $kapal,
          'est' => json_encode($data),
          'user_request' => $cabang == 1 ? '50090220010' : '50097240035',
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
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $data['cabang'] = $this->db->get('agency_cabang');
    $data['customer'] = $this->db->get('agency_customer');
    $data['agency'] = $this->db->get('agent');
    $data['penawaran'] = $this->db->get('t_penawaran');
    $data['port'] = $this->db->get('agency_port');
    $data['data_pda'] = $this->db->get_where('t_pda', ['penunjukan' => $data['detail']['Id']])->row_array();

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
      'grt' => $grt,
      'vessel_name' => $kapal,
      'est' => json_encode($data),
      'user_request' => $cabang == 1 ? '50090220010' : '50097240035',
      'id_cabang' => $cabang
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
          'id_cabang' => $cabang
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
        'id_cabang' => $cabang
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
}
