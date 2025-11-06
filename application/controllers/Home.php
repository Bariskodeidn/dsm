<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    // $this->load->model(['M_coa']);

    if ($this->session->userdata('isLogin') == FALSE) {
      $this->session->set_flashdata('error', 'Your session has expired');
      redirect('auth');
    } else if (!$this->session->userdata('nama_perusahaan')) {
      redirect('auth');
    } else if (!$this->session->userdata('is_premium')) {
      $this->db->from('users');
      $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
      $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
      $total_user = $this->db->get()->num_rows(); // Get the number of rows
      if ($total_user < 5) {
        redirect('perusahaan/user');
      }
    }
  }

  public function index()
  {
    $this->db->from('users');
    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('nama_jabatan !=', 'Super Admin');
    $total_user = $this->db->get()->num_rows(); // Get the number of rows

    $max_users_for_100_percent = 4; // Define your maximum limit
    // $max_users_for_100_percent = 5; // Define your maximum limit
    if ($total_user < $max_users_for_100_percent) {
      redirect('perusahaan/user');
    }
    $nip = $this->session->userdata('nip');
    $data['title'] = 'Home';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['user'] = $this->db->get_where('users', ['nip' => $nip])->row_array();
    $data['pages'] = 'pages/home/v_home';
    $data['pages_script'] = 'script/home/s_home';
    $data['menus'] = $this->M_menu->get_accessible_menus($nip);



    $this->db->from('t_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_cabang = $this->db->get()->num_rows(); // Get the number of rows


    $this->db->from('users');
    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_user = $this->db->get()->num_rows(); // Get the number of rows

    $data['total_cabang'] = $total_cabang;
    $data['total_user'] = $total_user;


    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $data['perusahaan'] = $perusahaan;

    $hasFinancialMenu = false;

    $menus = $this->M_menu->get_accessible_menus($nip);

    foreach ($menus as $item) {
      if (isset($item->menu_name) && $item->menu_name === "Financial") {
        $hasFinancialMenu = true;
        break; // Exit the loop once a match is found
      }
    }

    $data['hasFinancialMenu'] = $hasFinancialMenu;

    $this->load->view('index', $data);
  }
}
