<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();
    $this->load->model(['M_app']);
    if ($this->session->userdata('isLogin') == FALSE) {
      redirect('home');
    }
    date_default_timezone_set('Asia/Jakarta');
  }

  public function create_memo()
  {
    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['title'] = 'Create Memo';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/memo/v_create';
    $data['pages_script'] = 'script/memo/s_memo';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $this->db->from('memo');
    $this->db->where('memo.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('MONTH(memo.created_at)', date('m'));
    $this->db->where('YEAR(memo.created_at)', date('Y'));
    $total_memo = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_memo = $perusahaan->kuota_memo;
    if ($total_memo >= $limit_memo) {
      $this->session->set_flashdata('swal_message', [
        'icon' => 'info',
        'title' => 'Singgasana Menunggu Anda!',
        'text' => 'Batas jumlah Surat (Memo) dalam kerajaan Anda telah tercapai. Tambah kapasitas pesan dan kirim lebih banyak pesan penting dengan menaikkan derajat kekuasaan Anda.',
        'confirmButtonText' => 'Klaim Takhta Sekarang!',
        'showCancelButton' => true,
        'cancelButtonText' => 'Tunda Penobatan',
        'redirectUrl' => base_url('subscription/upgrade')
      ]);
      redirect('home');
    }

    $data['limit_memo'] = $limit_memo;
    $data['total_memo'] = $total_memo;

    $this->load->view('index', $data);
  }

  public function send_memo()
  {
    $tujuan = $this->input->post('tujuan[]');
    $cc = $this->input->post('cc[]');
    $judul = $this->input->post('judul');
    $isi = $this->input->post('ckeditor');

    $this->form_validation->set_rules('tujuan[]', 'Tujuan', 'required', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('judul', 'Judul', 'required|trim', ['required' => '%s wajib diisi!']);
    $this->form_validation->set_rules('ckeditor', 'Isi memo', 'required|trim', ['required' => '%s wajib diisi!']);

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {

      $filesCount = count($_FILES['attach']['name']);
      $uploadedFiles = [];
      $errors = [];
      $uploadedFileName = [];


      $hasFile = false;
      for ($i = 0; $i < $filesCount; $i++) {
        if ($_FILES['attach']['name'][$i] != '') {
          $hasFile = true;
          break;
        }
      }

      $nip_kpd = '';
      $nip_cc = '';
      $j = 0;

      foreach ($this->input->post('tujuan[]') as $value) {
        $nip_kpd .= $value . ';';
        $get_user[] = $this->db->get_where('users', ['username' => $value])->row_array();
        $phone[] = $get_user[$j]['phone'];
        $j++;
      }

      if (!empty($this->input->post('cc[]'))) {
        $ii = 0;
        foreach ($this->input->post('cc[]') as $value1) {
          $nip_cc .= $value1 . ';';
          $get_user_cc[] = $this->db->get_where('users', ['username' => $value1])->row_array();
          $phone_cc[] = $get_user_cc[$ii]['phone'];
          $ii++;
        }
      }

      // cek no memo
      if ($this->session->userdata('level_jabatan') >= 1) {
        $bagian = $this->session->userdata('kode_nama');
        $sql = "SELECT MAX(nomor_memo) FROM memo WHERE bagian = '$bagian' AND id_perusahaan = '" . $this->session->userdata('user_perusahaan_id') . "' AND YEAR(tanggal) = year(curdate());";
        $res1 = $this->db->query($sql);

        if ($res1->num_rows() > 0) {
          $res2 = $res1->result_array();
          $no_memo = $res2[0]['MAX(nomor_memo)'] + 1;
        } else {
          $no_memo = 1;
        }
      } else {
        $no_memo = '';
      }

      // Cek apakah ada attachment atau tidak
      if ($hasFile) {
        // === BATASI TOTAL SIZE SEMUA FILE (15MB)
        $totalSize = 0;
        for ($i = 0; $i < $filesCount; $i++) {
          $totalSize += $_FILES['attach']['size'][$i];
        }

        if ($totalSize > 15 * 1024 * 1024) { // 15MB
          $response = [
            'success' => FALSE,
            'msg' => 'total ukuran file tidak boleh lebih dari 15MB.'
          ];
          return;
        }

        for ($i = 0; $i < $filesCount; $i++) {
          $_FILES['file']['name']     = $_FILES['attach']['name'][$i];
          $_FILES['file']['type']     = $_FILES['attach']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['attach']['tmp_name'][$i];
          $_FILES['file']['error']    = $_FILES['attach']['error'][$i];
          $_FILES['file']['size']     = $_FILES['attach']['size'][$i];

          $config['upload_path']   = './upload/att_memo';
          $config['allowed_types'] = '*';
          $config['max_size']      = 2048;
          $config['encrypt_name']  = TRUE;
          // $config['file_name'] = $custom_file_name;
          // $config['encrypt_name']  = FALSE; // Set to FALSE to use your custom file_name

          $this->upload->initialize($config);

          if ($this->upload->do_upload('file')) {
            $uploadData = $this->upload->data();
            $uploadedFiles[] = $uploadData['full_path'];
            $uploadedFileName[] = $uploadData['file_name'];
          } else {
            $errors[] = $this->upload->display_errors();
            break; // Stop dan batal jika ada gagal
          }
        }

        // Hapus file jika gagal
        if (!empty($errors)) {
          foreach ($uploadedFiles as $filePath) {
            if (file_exists($filePath)) {
              unlink($filePath);
            }
          }
          $response = [
            'success' => FALSE,
            'msg' => 'Error : ' . $errors[0]
          ];
          echo json_encode($response);
          return;
        } else {
          $attach = implode(';', $uploadedFileName);
          $attach_name = implode(';', $_FILES['attach']['name']);

          if (!empty($this->input->post('attach_exist'))) {
            $attach_name = $this->input->post('attach_exist') . ';' . $attach_name;
            $attach = $this->input->post('attach_exist_encrypt') . ';' . $attach;
          }

          $insert = [
            'nomor_memo'  => $no_memo,
            'nip_kpd'    => $nip_kpd,
            'nip_cc'    => $nip_cc,
            'judul'      => $judul,
            'isi_memo'    => $isi,
            'nip_dari'    => $this->session->userdata('username'),
            'tanggal'    => date('Y-m-d H:i:s'),
            'read'      => 0,
            'persetujuan'  => 0,
            'bagian'    => $this->session->userdata('kode_nama'),
            'attach'    => $attach,
            'attach_name'  => $attach_name,
            'id_perusahaan'  => $this->session->userdata('user_perusahaan_id')
          ];

          $this->db->insert('memo', $insert);

          //Send notif wa
          $nama_session = $this->session->userdata('nama');
          $msg = "There's a new Memo\nBOC From : *$nama_session*\nSubject :  *$judul*";

          if (!empty($this->input->post('cc_memo[]'))) {
            $phone_user = array_merge($phone, $phone_cc);
          } else {
            $phone_user = $phone;
          }

          foreach ($phone_user as $p) {
            if ($this->session->userdata('is_premium')) {
              $this->api_whatsapp->wa_notif($msg, $p);
            }
          }

          $response = [
            'success' => true,
            'msg' => 'Sukses kirim memo',
            'reload' => base_url('app/create_memo')
          ];
        }
      } else {
        if (!empty($this->input->post('attach_exist'))) {
          $attach_name = $this->input->post('attach_exist');
          $attach = $this->input->post('attach_exist_encrypt');
        } else {
          $attach_name = null;
          $attach = null;
        }

        $insert = [
          'nomor_memo'  => $no_memo,
          'nip_kpd'    => $nip_kpd,
          'nip_cc'    => $nip_cc,
          'judul'      => $judul,
          'isi_memo'    => $isi,
          'nip_dari'    => $this->session->userdata('username'),
          'tanggal'    => date('Y-m-d H:i:s'),
          'read'      => 0,
          'persetujuan'  => 0,
          'bagian'    => $this->session->userdata('kode_nama'),
          'attach'    => $attach,
          'attach_name'  => $attach_name,
          'id_perusahaan'  => $this->session->userdata('user_perusahaan_id')

        ];

        $this->db->insert('memo', $insert);

        //Send notif wa
        $nama_session = $this->session->userdata('nama');
        $msg = "There's a new Memo\nBOC From : *$nama_session*\nSubject :  *$judul*";

        if (!empty($this->input->post('cc_memo[]'))) {
          $phone_user = array_merge($phone, $phone_cc);
        } else {
          $phone_user = $phone;
        }

        $send_wa = implode(',', $phone_user);
        // $this->api_whatsapp->wa_notif($msg, $send_wa);


        $response = [
          'success' => true,
          'msg' => 'Sukses kirim memo',
          'reload' => base_url('app/create_memo')
        ];
      }
    }
    echo json_encode($response);
  }

  public function search_user_memo()
  {
    $search = $this->input->get('q');
    $page = (int) $this->input->get('page');
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $users = $this->M_app->search_user_memo($search, $limit + 1, $offset);

    $results = [];
    $more = false;

    if (count($users) > $limit) {
      $more = true;
      array_pop($users); // Remove the extra item
    }

    foreach ($users as $user) {
      $results[] = [
        'id' => $user->username,
        'text' => $user->nama
      ];
    }

    echo json_encode([
      'items' => $results,
      'more' => $more
    ]);
  }

  public function inbox()
  {

    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
    //pagination settings
    $config['base_url'] = site_url('app/inbox');
    $config['total_rows'] = $this->M_app->memo_count($this->session->userdata('username'), $keyword);
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
    $data['data_memo'] = $this->M_app->memo_get($config["per_page"], $data['page'], $this->session->userdata('username'), $keyword);
    $data['pagination'] = $this->pagination->create_links();

    $data['title'] = 'Inbox';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/memo/s_memo';
    $data['pages'] = 'pages/memo/v_inbox';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));



    $this->db->from('memo');
    $this->db->where('memo.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('MONTH(memo.created_at)', date('m'));
    $this->db->where('YEAR(memo.created_at)', date('Y'));
    $total_memo = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_memo = $perusahaan->kuota_memo;

    $data['limit_memo'] = $limit_memo ? $limit_memo : 0;
    $data['total_memo'] = $total_memo ? $total_memo : 0;

    $this->load->view('index', $data);
  }

  public function memo_view($id)
  {
    $data['title'] = 'View Memo';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/memo/v_memo_detail';
    $data['memo'] = $this->M_app->memo_get_detail($id);
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));


    if (!$data['memo']) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $this->load->view('index', $data);
  }

  public function reply_memo($id)
  {
    $data['title'] = 'Reply Memo';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/memo/v_memo_reply';
    $data['memo'] = $this->M_app->memo_get_detail($id);
    $data['pages_script'] = 'script/memo/s_memo';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    if (!$data['memo']) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $user_dari = $this->db->select('username, nama')->from('users')->where('username', $data['memo']->nip_dari)->get()->row_array();
    $data['selected_item'] = [
      [
        'id' => $user_dari['username'],
        'text' => $user_dari['nama']
      ]
    ];

    $this->load->view('index', $data);
  }

  public function reply_all_memo($id)
  {
    $data['title'] = 'Reply Memo';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/memo/v_memo_reply_all';
    $data['memo'] = $this->M_app->memo_get_detail($id);
    $data['pages_script'] = 'script/memo/s_memo';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    if (!$data['memo']) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $user_dari = $this->db->select('username, nama')->from('users')->where('username', $data['memo']->nip_dari)->get()->row_array();

    $data['selected_item'] = [
      [
        'id' => $user_dari['username'],
        'text' => $user_dari['nama']
      ]
    ];

    if ($data['memo']->nip_cc) {
      $string = substr($data['memo']->nip_cc, 0, -1);
      $nip_cc = explode(";", $string);
      $i = 0;

      $data['selected_item_cc'] = [];
      foreach ($nip_cc as $uc) {
        $user_cc[] = $this->db->select('username,nama')->from('users')->where('username', $uc)->get()->row_array();
        $array_user[] = [
          'id' => $user_cc[$i]['username'],
          'text' => $user_cc[$i]['nama']
        ];

        array_push($data['selected_item_cc'], $array_user[$i]);

        $i++;
      }
    }

    $this->load->view('index', $data);
  }

  public function memo_pdf($id)
  {
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['memo'] = $this->M_app->memo_get_detail($id);

    if (!$data['memo']) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $this->load->view('pages/memo/v_memo_pdf', $data);
  }

  public function outbox()
  {

    $has_access = $this->M_menu->has_access();
    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $keyword = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
    //pagination settings
    $config['base_url'] = site_url('app/outbox');
    $config['total_rows'] = $this->M_app->memo_count_outbox($this->session->userdata('username'), $keyword);
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
    $data['data_memo'] = $this->M_app->memo_get_outbox($config["per_page"], $data['page'], $this->session->userdata('username'), $keyword);
    $data['pagination'] = $this->pagination->create_links();

    $data['title'] = 'Outbox';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages_script'] = 'script/memo/s_memo';
    $data['pages'] = 'pages/memo/v_outbox';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));


    $this->db->from('memo');
    $this->db->where('memo.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $this->db->where('MONTH(memo.created_at)', date('m'));
    $this->db->where('YEAR(memo.created_at)', date('Y'));
    $total_memo = $this->db->get()->num_rows(); // Get the number of rows

    $this->db->from('utility');
    $this->db->where('Id', $this->session->userdata('user_perusahaan_id'));
    $perusahaan = $this->db->get()->row(); // Get the number of rows

    $limit_memo = $perusahaan->kuota_memo;

    $data['limit_memo'] = $limit_memo ? $limit_memo : 0;
    $data['total_memo'] = $total_memo ? $total_memo : 0;


    $this->load->view('index', $data);
  }
}
