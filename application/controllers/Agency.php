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
}
