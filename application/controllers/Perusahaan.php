<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    if ($this->session->userdata('isLogin') == FALSE) {
      $this->session->set_flashdata('error', 'Your session has expired');
      redirect('auth');
    } else if (!$this->session->userdata('nama_perusahaan')) {
      redirect('auth');
    }

    $this->load->model(['M_user_access', 'M_perusahaans']);
  }

  public function perusahaan()
  {
    $nip = $this->session->userdata('nip');
    $data['title'] = 'Perusahaan';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['user'] = $this->db->get_where('users', ['nip' => $nip])->row_array();
    $data['pages'] = 'pages/perusahaan/v_perusahaan';
    // $data['pages_script'] = 'script/perusahaan/s_perusahaan';
    $data['menus'] = $this->M_menu->get_accessible_menus($nip);

    $this->load->view('index', $data);
  }

  public function cabang()
  {

    $this->db->from('t_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_cabang = $this->db->get()->num_rows(); // Get the number of rows

    $nip = $this->session->userdata('nip');

    $data['total_cabang'] = $total_cabang;
    $data['title'] = 'Cabang';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_cabang';
    $data['user'] = $this->db->get_where('users', ['nip' => $nip])->row_array();
    $data['pages'] = 'pages/perusahaan/v_cabang';
    $data['menus'] = $this->M_menu->get_accessible_menus($nip);


    $this->db->from('t_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_cabang = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_cabang = $perusahaan->kuota_cabang;

    $data['limit_cabang'] = $limit_cabang;
    $data['total_cabang'] = $total_cabang;

    $this->load->view('index', $data);
  }

  public function user()
  {
    $this->db->from('users');
    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('nama_jabatan !=', 'Super Admin');
    $total_user = $this->db->get()->num_rows(); // Get the number of rows



    $nip = $this->session->userdata('nip');
    $data['user_counts'] = $this->M_perusahaans->get_user_counts_by_role();
    $data['total_user'] = $total_user;
    $data['title'] = 'User';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_user';
    $data['user'] = $this->db->get_where('users', ['nip' => $nip])->row_array();
    $data['pages'] = 'pages/perusahaan/v_user';
    $data['menus'] = $this->M_menu->get_accessible_menus($nip);

    $this->db->from('users');
    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_user_perusahaan = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_user = $perusahaan->kuota_user;

    $data['total_user_perusahaan'] = $total_user_perusahaan;
    $data['limit_user'] = $limit_user;

    $this->load->view('index', $data);
  }

  public function ajax_user_list()
  {
    $list = $this->M_perusahaans->get_datatables1();
    $data = array();
    $crs = "";
    $no = $_POST['start'];

    foreach ($list as $cat) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $cat->nama;
      $row[] = $cat->username;
      $row[] = $cat->nama_cabang;
      $row[] = $cat->nama_jabatan;
      if ($cat->status == '1') {
        $row[] = "Aktif";
      } else {
        $row[] = "Tidak Aktif";
      }

      if ($cat->nama_jabatan == "Super Admin") {
        $row[] = '<a href="' . base_url('perusahaan/edit_user/' . $cat->id) . '" class="btn btn-warning btn-di-td">
        Update
      </a> ';
        // '<button onclick="onResetCuti(' . $cat->id . ')" class="btn btn-warning btn-di-td">
        //   Reset Cuti
        // </button>';
      } else {
        $row[] = '<a href="' . base_url('perusahaan/edit_user/' . $cat->id) . '" class="btn btn-warning btn-di-td">
        Update
      </a> <button onclick="onDelete(' . $cat->id . ')" class="btn btn-danger btn-di-td">
        Delete
      </button> ';
        //   '<button onclick="onResetCuti(' . $cat->nip . ')" class="btn btn-warning btn-di-td">
        //   Reset Cuti
        // </button>';
      }
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->M_perusahaans->count_all1(),
      "recordsFiltered" => $this->M_perusahaans->count_filtered1(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function add_user()
  {

    $this->db->from('users')->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('nama_jabatan !=', 'Super Admin');
    $supervisi = $this->db->where('level_jabatan >=', 2)->get()->result();
    $data['supervisi'] = $supervisi;

    $this->db->from('t_cabang');
    $cabang = $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'))->get()->result();
    $data['cabang'] = $cabang;

    $data['user_counts'] = $this->M_perusahaans->get_user_counts_by_role();


    // --- NEW: Fetch menu access data ---
    // $data['all_menus'] = $this->M_user_access->get_all_menus(); // Get all available menus
    $data['all_menus_hierarchical'] = $this->M_user_access->get_all_menus_hierarchical(); // Get all menus in hierarchy
    // $current_access = $this->M_user_access->get_user_access($user_id); // Get user's current access record

    // $data['user_menu_ids'] = [];
    // if (!empty($current_access) && !empty($current_access->menu_id)) {
    //   // Convert comma-separated string to an array of integers
    //   $data['user_menu_ids'] = array_map('intval', explode(',', $current_access->menu_id));
    // }
    // --- END NEW ---


    $data['title'] = 'Add User';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_user';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['pages'] = 'pages/perusahaan/v_user_add';
    $data['form_data'] = $this->session->flashdata('form_data');


    $this->db->from('users');
    $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_user_perusahaan = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_user = $perusahaan->kuota_user;
    if ($total_user_perusahaan >= $limit_user) {
      $this->session->set_flashdata('swal_message', [
        'icon' => 'info', // Tetap gunakan 'info' atau 'question' untuk kesan informatif
        'title' => 'Singgasana Menunggu Anda!', // Judul yang menarik dan bertema
        'text' => 'Batas jumlah pelayan (pengguna) dalam kerajaan Anda telah tercapai. Perluas kekuasaan Anda dan tambahkan lebih banyak rakyat dengan menaikan derajat kerajaan Anda!.',
        'confirmButtonText' => 'Klaim Takhta Sekarang!', // Kalimat persuasif untuk tombol
        'showCancelButton' => true,
        'cancelButtonText' => 'Tunda Penobatan', // Opsi yang lucu dan sesuai tema
        'redirectUrl' => base_url('subscription/upgrade')
      ]);
      redirect('perusahaan/user');
    }
    $this->load->view('index', $data);
    // $this->load->view('pages/absensi/lokasi_presensi_form', $data);
  }

  public function edit_user($id)
  {
    $this->db->from('users')->join('t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('nama_jabatan !=', 'Super Admin');
    $supervisi = $this->db->where('level_jabatan >=', 3)->get()->result();
    $data['supervisi'] = $supervisi;

    $this->db->from('t_cabang');
    $cabang = $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'))->get()->result();
    $data['cabang'] = $cabang;

    $data['user_counts'] = $this->M_perusahaans->get_user_counts_by_role();
    // --- NEW: Fetch menu access data ---
    // $data['all_menus'] = $this->M_user_access->get_all_menus(); // Get all available menus
    $data['all_menus_hierarchical'] = $this->M_user_access->get_all_menus_hierarchical(); // Get all menus in hierarchy
    $current_access = $this->M_user_access->get_user_access($id); // Get user's current access record

    $data['user_menu_ids'] = [];
    if (!empty($current_access) && !empty($current_access->menu_id)) {
      // Convert comma-separated string to an array of integers
      $data['user_menu_ids'] = array_map('intval', explode(',', $current_access->menu_id));
    }
    // --- END NEW ---

    $data['user'] = $this->M_perusahaans->get_detail_id_user($id);
    $data['title'] = 'Add Lokasi Presensi';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_user';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['pages'] = 'pages/perusahaan/v_user_add';



    $this->load->view('index', $data);
    // $this->load->view('pages/absensi/lokasi_presensi_form', $data);
  }

  public function proccess_add_user()
  {
    // Set validation rules
    $this->form_validation->set_rules('username', 'Username Wajib', 'required|trim|is_unique[users.nip]|min_length[5]|alpha_dash');
    $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|trim');
    // $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]|min_length[5]');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[password_confirmation]');
    $this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');
    $this->form_validation->set_rules('phone', 'Nomor Telepon', 'trim|numeric|required');
    // $this->form_validation->set_rules('nip', 'NIP', 'trim|is_unique[users.nip]');

    // Set custom error messages (optional)
    $this->form_validation->set_message('required', '{field} wajib diisi.');
    $this->form_validation->set_message('is_unique', '{field} sudah terdaftar, silakan gunakan yang lain.');
    $this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
    $this->form_validation->set_message('matches', '{field} tidak cocok dengan password.');
    $this->form_validation->set_message('valid_email', 'Format {field} tidak valid.');
    $this->form_validation->set_message('numeric', '{field} harus berupa angka.');
    $this->form_validation->set_message('alpha_numeric', '{field} hanya boleh berisi huruf dan angka.');
    $this->form_validation->set_message('alpha_dash', '{field} hanya boleh berisi huruf, angka, garis bawah (_), dan tanda hubung (-).');

    $uri1 = $this->input->post('uri1');
    $uri2 = $this->input->post('uri2');

    if ($this->form_validation->run() == FALSE) {
      // If validation fails, reload the registration form with errors

      // $response = [
      //   'success' => FALSE,
      //   // 'msg'     => 'Gagal Membuat Akun. Mohon periksa kembali input Anda.',
      //   // 'msg'     => 'Gagal Membuat Akun.' . validation_errors(),
      //   'errors'  => validation_errors() // Capture all validation errors
      // ];

      $this->session->set_flashdata('form_data', $this->input->post());

      $this->session->set_flashdata('error', 'Silakan lengkapi data perusahaan terlebih dahulu. <br><br>' . validation_errors());
      redirect('perusahaan/add_user/' . $uri1 . '/' . $uri2);
    }

    // $raw_slug = $this->input->post('nama_lokasi');
    // $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($raw_slug)));

    $radius = $this->input->post('radius_lokasi') / 1000;
    $add = [
      "nama" => $this->input->post('nama'),
      "username" => $this->input->post('username'),
      "password" => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
      // "level" => $ex_level,
      "status" => $this->input->post('status'),
      "email" => $this->input->post('email'),
      "phone" => $this->input->post('phone'),
      "kd_agent" => $this->input->post('kd_agent'),
      // "nip" => $this->input->post('nip'),
      "nip" => $this->input->post('username'),
      "level_jabatan" => $this->input->post('level_jabatan'),
      "role_name" => $this->input->post('role_name'),
      "bagian" => $this->input->post('bagian'),
      "nama_jabatan" => $this->input->post('nama_jabatan'),
      "supervisi" => $this->input->post('supervisi'),
      "tmt" => $this->input->post('tmt'),
      "cuti" => $this->input->post('cuti'),
      "id_cabang" => $this->input->post('cabang'),
      "ns_address" => 'ns1.bariskode.id',
    ];
    $this->db->insert('users', $add);

    $nip = $this->input->post('username');
    $selected_menu_ids = $this->input->post('menu_ids'); // This will be an array of selected menu IDs

    // var_dump($selected_menu_ids);

    if (empty($selected_menu_ids)) {
      $menu_id_string = ''; // No access
    } else {
      // Ensure unique IDs and convert to comma-separated string
      $menu_id_string = implode(',', array_unique($selected_menu_ids));
    }

    // var_dump($menu_id_string);
    // $query = $this->db->select('Id')->from('menus')->get();
    // $ids = [];
    // if ($query->num_rows() > 0) {
    //   foreach ($query->result_array() as $row) {
    //     $ids[] = $row['Id'];
    //   }
    // }

    // $menu_id_string = implode(',', $ids);

    // Save the access
    if ($this->M_user_access->save_user_access($nip, $menu_id_string)) {
      echo $nip;
      $this->session->set_flashdata('success', 'User menu access updated successfully!');
      echo 'Berhasil';
    } else {
      $this->session->set_flashdata('error', 'Failed to update user menu access. Please try again.');
      echo 'Tidak';
    }

    $this->session->set_flashdata('swal_message', [
      'icon' => 'success', // or 'success', 'warning', 'info', 'question'
      'title' => 'Berhasil!',
      'text' => 'Berhasil Menambah data!',
      'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
      'timerProgressBar' => true, // Shows a progress bar for the timer
    ]);

    if ($this->session->userdata('is_premium')) {
      redirect('perusahaan/user');
    } else {
      $this->db->from('users');
      $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
      $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
      $this->db->where('nama_jabatan !=', 'Super Admin');
      $total_user = $this->db->get()->num_rows(); // Get the number of rows

      $max_users_for_100_percent = 4; // Define your maximum limit
      // $max_users_for_100_percent = 5; // Define your maximum limit

      if ($total_user == $max_users_for_100_percent) {
        redirect('financial/list_coa');
      } else {
        redirect('perusahaan/user');
      }
    }
  }

  public function proccess_edit_user($id)
  {
    $edit_data = [
      "nama" => $this->input->post('nama'),
      "username" => $this->input->post('username'),
      "status" => $this->input->post('status'),
      "email" => $this->input->post('email'),
      "phone" => $this->input->post('phone'),
      "kd_agent" => $this->input->post('kd_agent'),
      // "nip" => $this->input->post('nip'),
      "nip" => $this->input->post('username'),
      "level_jabatan" => $this->input->post('level_jabatan'),
      "tmt" => $this->input->post('tmt'),
      "bagian" => $this->input->post('bagian'),
      "nama_jabatan" => $this->input->post('nama_jabatan'),
      "supervisi" => $this->input->post('supervisi'),
      "cuti" => $this->input->post('cuti'),
      "id_lokasi_presensi" => $this->input->post('lokasi_presensi'),
      "id_cabang" => $this->input->post('cabang'),
      "jam_masuk" => $this->input->post('jam_masuk'),
      "jam_keluar" => $this->input->post('jam_keluar')
    ];
    $this->db->where('id', $id);
    $this->db->update('users', $edit_data);

    $user_id = $this->input->post('user_id');
    $nip = $this->input->post('username');
    $selected_menu_ids = $this->input->post('menu_ids'); // This will be an array of selected menu IDs

    if (empty($selected_menu_ids)) {
      $menu_id_string = ''; // No access
    } else {
      // Ensure unique IDs and convert to comma-separated string
      $menu_id_string = implode(',', array_unique($selected_menu_ids));
    }

    // Save the access
    if ($this->M_user_access->save_user_access($nip, $menu_id_string)) {
      // $this->session->set_flashdata('success', 'User menu access updated successfully!');
      // echo 'Berhasil';
      $this->session->set_flashdata('swal_message', [
        'icon' => 'success', // or 'success', 'warning', 'info', 'question'
        'title' => 'Berhasil!',
        'text' => 'Berhasil Mengubah data!',
        'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
        'timerProgressBar' => true, // Shows a progress bar for the timer
      ]);
    } else {
      // $this->session->set_flashdata('error', 'Failed to update user menu access. Please try again.');
      // echo 'Tidak';
      $this->session->set_flashdata('swal_message', [
        'icon' => 'error', // or 'success', 'warning', 'info', 'question'
        'title' => 'Gagal!',
        'text' => 'Gagal Mengubah data, silahkan coba lagi',
        'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
        'timerProgressBar' => true, // Shows a progress bar for the timer
      ]);
    }

    redirect('perusahaan/user');
  }

  public function hapus_user()
  {
    $id = $this->input->post('id');
    $this->db->where('id', $id);
    $this->db->delete('users');

    echo json_encode(array("status" => 'success', "message" => "Berhasil Menghapus Data"));

    // redirect('perusahaan/cabang');
  }

  public function ajax_cabang_list()
  {
    $list = $this->M_perusahaans->get_datatables();
    $data = array();
    $crs = "";
    $no = $_POST['start'];

    foreach ($list as $cat) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $cat->nama_cabang;
      $row[] = $cat->alamat_cabang;

      $this->db->from('users');
      $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
      $this->db->where('t_cabang.uid', $cat->uid);
      $total_user = $this->db->get()->num_rows(); // Get the number of rows

      if (!$total_user) {
        $button_delete = '<button onclick="onDelete(' . $cat->uid . ')" class="btn btn-danger btn-di-td">
        Delete
      </button>';
      } else {
        $button_delete = '';
      }
      $row[] = '<a href="' . base_url('perusahaan/edit_cabang/' . $cat->uid) . '" class="btn btn-warning btn-di-td">
        Update
      </a> ' . $button_delete;

      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->M_perusahaans->count_all(),
      "recordsFiltered" => $this->M_perusahaans->count_filtered(),
      "data" => $data,
    );
    echo json_encode($output);
  }

  public function add_cabang()
  {
    $data['title'] = 'Add Cabang';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_cabang';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['pages'] = 'pages/perusahaan/v_cabang_add';

    $this->db->from('t_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_cabang = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_cabang = $perusahaan->kuota_cabang;

    $data['limit_cabang'] = $limit_cabang;
    $data['total_cabang'] = $total_cabang;

    if ($total_cabang >= $limit_cabang) {
      $this->session->set_flashdata('swal_message', [
        'icon' => 'info', // Tetap gunakan 'info' atau 'question' untuk kesan informatif
        'title' => 'Singgasana Menunggu Anda!', // Judul yang menarik dan bertema
        'text' => 'Batas jumlah wilayah (cabang) dalam kerajaan Anda telah tercapai. Perluas kekuasaan Anda dan tambahkan lebih banyak rakyat dengan menaikan derajat kerajaan Anda!.',
        'confirmButtonText' => 'Klaim Takhta Sekarang!', // Kalimat persuasif untuk tombol
        'showCancelButton' => true,
        'cancelButtonText' => 'Tunda Penobatan', // Opsi yang lucu dan sesuai tema
        'redirectUrl' => base_url('subscription/upgrade')
      ]);
      redirect('perusahaan/cabang');
    }
    $this->load->view('index', $data);

    // $this->load->view('pages/absensi/lokasi_presensi_form', $data);
  }

  public function proccess_add_cabang()
  {
    // $raw_slug = $this->input->post('nama_lokasi');
    // $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($raw_slug)));

    // $radius = $this->input->post('radius_lokasi') / 1000;
    $add = [
      "id_perusahaan" => $this->session->userdata('user_perusahaan_id'),
      "nama_cabang" => $this->input->post('nama_cabang'),
      "alamat_cabang" => $this->input->post('alamat_cabang'),
      "nomor_rekening" => $this->input->post('nomor_rekening'),
      "nama_bank" => $this->input->post('nama_bank'),
      'generate_sawal'       => '0',
    ];
    $this->db->insert('t_cabang', $add);
    $inserted_id = $this->db->insert_id();

    $this->session->set_flashdata('swal_message', [
      'icon' => 'success', // or 'success', 'warning', 'info', 'question'
      'title' => 'Berhasil!',
      'text' => 'Berhasil Menambahkan data!',
      'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
      'timerProgressBar' => true, // Shows a progress bar for the timer
    ]);
    redirect('perusahaan/cabang');
  }

  public function edit_cabang($id)
  {

    $data['cabang'] = $this->M_perusahaans->get_detail_id_cabang($id);
    $data['title'] = 'Edit Cabang';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_cabang';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['pages'] = 'pages/perusahaan/v_cabang_add';

    $this->load->view('index', $data);
    // $this->load->view('pages/absensi/lokasi_presensi_form', $data);
  }

  public function prosses_edit_cabang($id)
  {
    $edit_data = [
      "nama_cabang" => $this->input->post('nama_cabang'),
      "alamat_cabang" => $this->input->post('alamat_cabang'),
      "nomor_rekening" => $this->input->post('nomor_rekening'),
      "nama_bank" => $this->input->post('nama_bank'),
    ];
    $this->db->where('uid', $id);
    $this->db->update('t_cabang', $edit_data);

    $this->session->set_flashdata('swal_message', [
      'icon' => 'success', // or 'success', 'warning', 'info', 'question'
      'title' => 'Berhasil!',
      'text' => 'Berhasil Mengubah data!',
      'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
      'timerProgressBar' => true, // Shows a progress bar for the timer
    ]);

    redirect('perusahaan/cabang');
  }

  public function hapus_cabang()
  {
    $id = $this->input->post('id');
    $this->db->where('uid', $id);
    $this->db->delete('t_cabang');

    echo json_encode(array("status" => 'success', "message" => "Berhasil Menghapus Data"));

    // redirect('perusahaan/cabang');
  }

  public function detail()
  {

    $data['perusahaan'] = $this->M_perusahaans->get_detail_id_perusahaan($this->session->userdata('user_perusahaan_id'));
    $data['title'] = 'Add Lokasi Presensi';
    $data['utility'] = $this->db->get('utility')->row_array();
    // $data['pages_script'] = 'script/perusahaan/s_perusahaan';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $data['pages'] = 'pages/perusahaan/v_perusahaan_detail';
    $data['pages_script'] = 'script/perusahaan/s_perusahaan';

    // $this->cb->from('v_coa_all');
    // $this->cb->join('t_cabang', 't_cabang.uid = v_coa_all.id_cabang');
    // $this->cb->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    // // Tambahkan klausa where untuk memfilter kolom yang dimulai dengan '2'
    // $this->cb->where('v_coa_all.no_sbb LIKE', '2%');
    // $coa_all = $this->cb->get()->result();
    // $data['coa_all'] = $coa_all;
    $this->load->view('index', $data);
    // $this->load->view('pages/absensi/lokasi_presensi_form', $data);
  }

  public function save_new_bagian()
  {

    $this->output->set_content_type('application/json');

    // $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
    $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
    $this->form_validation->set_rules('kode_nama', 'Kode Nama', 'required|trim|is_unique[bagian.kode_nama]');
    $this->form_validation->set_rules('id_prsh', 'ID Perusahaan', 'required|integer');

    if ($this->form_validation->run() == FALSE) {
      echo json_encode([
        'status' => 'error',
        'message' => strip_tags(validation_errors())
      ]);
      return;
    }

    $data = array(
      // 'kode'      => $this->input->post('kode'),
      'nama'      => $this->input->post('nama'),
      'kode_nama' => $this->input->post('kode_nama'),
      'id_prsh'   => $this->input->post('id_prsh'),
    );

    $insert_id = $this->M_perusahaans->insert_bagian($data);

    if ($insert_id) {
      echo json_encode([
        'status'       => 'success',
        'new_id'       => $insert_id,
        'display_text' => $data['kode_nama'] . ' - ' . $data['nama']
      ]);
    } else {
      $db_error = $this->db->error();
      log_message('error', 'Failed to insert new bagian: ' . ($db_error['message'] ?? 'Unknown DB error'));

      echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to save new bagian to database.'
      ]);
    }
  }
  public function prosses_edit_perusahaan()
  {


    $besaran_ppn = $this->input->post('besaran_ppn') / 100;

    $edit_data = [
      "nama_perusahaan" => $this->input->post('nama_perusahaan'),
      "nama_singkat" => $this->input->post('nama_singkat'),
      "nama_ppn" => $this->input->post('nama_ppn'),
      "besaran_ppn" => $besaran_ppn,
      "nomor_rekening" => $this->input->post('nomor_rekening'),
      "nama_bank" => $this->input->post('nama_bank'),
      "alamat_perusahaan" => $this->input->post('alamat_perusahaan'),
      "nama_akronim" => $this->input->post('nama_akronim'),
      "nama_coa_ppn_keluaran" => $this->input->post('coa_ppn_keluaran_nama_perkiraan'),
      "nomor_coa_ppn_keluaran" => $this->input->post('coa_ppn_keluaran_no_sbb'),
      "nama_coa_utang_pph23" => $this->input->post('coa_utang_pph_nama_perkiraan'),
      "nomor_coa_utang_pph23" => $this->input->post('coa_utang_pph_no_sbb'),
    ];

    $max_file_size = 2 * 1024 * 1024;

    if (!empty($_FILES['logo_perusahaan']['name']) && $_FILES['logo_perusahaan']['error'] == UPLOAD_ERR_OK) {
      $file_tmp_name = $_FILES['logo_perusahaan']['tmp_name'];
      $file_type = $_FILES['logo_perusahaan']['type'];
      if ($_FILES['logo_perusahaan']['size'] > $max_file_size) {
        // Error: File size exceeds the limit
        $this->session->set_flashdata('error', 'Ukuran file logo terlalu besar. Maksimal adalah 2MB.');
        redirect('perusahaan/detail');
        exit; // Use exit to stop further execution cleanly
      }
      // Read the file content
      $file_content = file_get_contents($file_tmp_name);

      if ($file_content !== FALSE) {
        // Encode to Base64
        $logo_base64 = 'data:' . $file_type . ';base64,' . base64_encode($file_content);
        $edit_data['logo'] = $logo_base64;
      } else {
        // Handle error if file content could not be read
        // $response = [
        //   'success' => FALSE,
        //   'msg'     => 'Gagal membaca isi file logo. Silakan coba lagi.'
        // ];
        // echo json_encode($response);
        // return; // Stop execution
        $this->session->set_flashdata('swal_message', [
          'icon' => 'error', // or 'success', 'warning', 'info', 'question'
          'title' => 'Error!',
          'text' => 'Gagal membaca isi file logo. Silakan coba lagi',
          'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
          'timerProgressBar' => true, // Shows a progress bar for the timer
        ]);
        $this->session->set_flashdata('error', 'Gagal membaca isi file logo. Silakan coba lagi.');
        redirect('perusahaan/detail');
      }
    }

    $this->db->where('Id', $this->input->post('id'));
    $this->db->update('utility', $edit_data);

    $this->session->set_flashdata('swal_message', [
      'icon' => 'success', // or 'success', 'warning', 'info', 'question'
      'title' => 'Berhasil!',
      'text' => 'Berhasil Mengubah data!',
      'timer' => 3000, // SweetAlert2 will close after 3 seconds (3000 milliseconds)
      'timerProgressBar' => true, // Shows a progress bar for the timer
    ]);

    redirect('perusahaan/detail');
  }

  public function banner()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
    //pagination settings
    $config['base_url'] = site_url('perusahaan/banner');
    $config['total_rows'] = $this->M_perusahaans->banner_count($keyword);
    $config['per_page'] = "20";
    $config["uri_segment"] = 3;
    $config["num_links"] = 10;
    $config['enable_query_strings'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['use_page_numbers'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';

    // integrate bootstrap pagination
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

    // initialize pagination
    $this->pagination->initialize($config);
    $data['page'] = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
    $data['data_banner'] = $this->M_perusahaans->banner_get($config["per_page"], $data['page'], $keyword);
    $data['pagination'] = $this->pagination->create_links();

    $data['title'] = 'Banner';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/perusahaan/s_perusahaan';
    $data['pages'] = 'pages/perusahaan/v_banner';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $this->load->view('index', $data);
  }

  public function store_banner()
  {
    $file = $_FILES['attach']['name'];

    $config['upload_path']   = './assets/images/banner';
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size']      = 2048;
    $config['encrypt_name']  = TRUE;

    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, true);
    }

    $this->upload->initialize($config);

    if (!$this->upload->do_upload('attach')) {
      $response = [
        'success' => false,
        'msg' => $this->upload->display_errors()
      ];

      echo json_encode($response);

      return;
    }

    $upload = $this->upload->data();
    $insert =
      [
        'file' => $upload['file_name'],
        'file_name' => $file
      ];

    $this->db->insert('banner', $insert);

    $response = [
      'success' => true,
      'msg' => 'Banner berhasil ditambahkan!',
      'reload' => site_url('perusahaan/banner')
    ];


    echo json_encode($response);
  }

  public function update_banner($id)
  {
    $banner = $this->db->get_where('banner', ['Id' => $id])->row();

    $file = $_FILES['attach']['name'];
    $config['upload_path']   = './assets/images/banner';
    $config['allowed_types'] = 'jpeg|jpg|png';
    $config['max_size']      = 2048;
    $config['encrypt_name']  = TRUE;

    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, true);
    }

    $this->upload->initialize($config);

    if (!$this->upload->do_upload('attach')) {
      $response = [
        'success' => false,
        'msg' => $this->upload->display_errors()
      ];

      echo json_encode($response);

      return;
    }

    if ($banner) {
      if (file_exists('./assets/images/banner/' . $banner->file)) {
        unlink('./assets/images/banner/' . $banner->file);
      }
    }

    $upload = $this->upload->data();

    // Jika file lama ada, hapus

    $update =
      [
        'file' => $upload['file_name'],
        'file_name' => $file
      ];

    $this->db->where('Id', $id);
    $this->db->update('banner', $update);

    $response = [
      'success' => true,
      'msg' => 'Banner berhasil diubah!',
      'reload' => site_url('perusahaan/banner')
    ];


    echo json_encode($response);
  }
}
