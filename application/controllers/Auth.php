<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();
    $this->load->model(['M_login']);
    // $this->cb = $this->load->database('corebank', TRUE);
  }

  public function index()
  {
    if ($this->session->userdata('isLogin')) {
      if (!$this->session->userdata('nama_perusahaan')) {
        redirect('auth/register_perusahaan');
      } else {
        redirect('home');
      }
    }
    $data['title'] = 'Login';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/auth/v_login';
    $this->load->view('pages/auth/index', $data);
  }

  public function login()
  {

    $username = $this->input->post('username', TRUE);
    $password = $this->input->post('password', TRUE);

    $this->form_validation->set_rules('username', 'username', 'required|trim');
    $this->form_validation->set_rules('password', 'password', 'required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $cek = $this->M_login->ambilPengguna($username, 1);
      $data = $this->M_login->datapengguna($username);

      if (empty($cek)) {
        $response = [
          'success' => FALSE,
          'msg' => 'Akun tidak ditemukan!'
        ];
      } elseif (password_verify($password, $data->password)) {
        $this->session->set_userdata('isLogin', TRUE);
        $this->session->set_userdata('username', $username);
        $this->session->set_userdata('user_user_id', $data->id);
        $this->session->set_userdata('level', $data->level);
        $this->session->set_userdata('nama', $data->nama);
        $this->session->set_userdata('nip', $data->nip);
        $this->session->set_userdata('kd_agent', $data->kd_agent);
        $this->session->set_userdata('level_jabatan', $data->level_jabatan);
        $this->session->set_userdata('bagian', $data->bagian);
        $this->session->set_userdata('kode_cabang', $data->id_cabang);
        $this->session->set_userdata('is_token', $data->token);

        $this->db->select('utility.*');
        $this->db->from('utility');
        $this->db->join('t_cabang', 't_cabang.id_perusahaan = utility.Id');
        $setting = $this->db->where('t_cabang.uid', $data->id_cabang)->get()->row();
        // var_dump($setting);

        $kode_nama = $data->bagian;
        if (!empty($kode_nama)) {
          $sql = "select kode_nama FROM bagian WHERE Id = $kode_nama";
          $query = $this->db->query($sql);
          $res2 = $query->result_array();
          $result = $res2[0]['kode_nama'];
          $kod = $result;
        } else {
          $kod = '';
        }

        $this->session->set_userdata('kode_nama', $kod);
        $this->session->set_userdata('user_perusahaan_id', $setting->Id);
        $this->session->set_userdata('icon', $setting->logo);
        $this->session->set_userdata('nama_singkat', $setting->nama_singkat);
        $this->session->set_userdata('nama_perusahaan', $setting->nama_perusahaan);
        $this->session->set_userdata('alamat_perusahaan', $setting->alamat_perusahaan);
        $this->session->set_userdata('nomor_rekening', $setting->nomor_rekening);
        $this->session->set_userdata('nama_ppn', $setting->nama_ppn);
        $this->session->set_userdata('ppn', $setting->besaran_ppn);
        $this->session->set_userdata('nama_akronim', $setting->nama_akronim);
        $response = [
          'success' => TRUE,
          'msg' => 'Berhasil Masuk!',
          'reload' => base_url('home')
        ];
      } else {
        $response = [
          'success' => FALSE,
          'msg' => 'Gagal Masuk : Cek username dan password anda'
        ];
      }
    }
    echo json_encode($response);
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect('auth');
  }

  public function register_cabang()
  {
    if (!$this->session->userdata('isLogin')) {
      redirect('auth');
    } else if ($this->session->userdata('nama_perusahaan')) {
      redirect('home');
    }

    $company_data_from_session = $this->session->userdata('data_perusahaan');
    // if (empty($company_data_from_session)) {
    //   $this->session->set_flashdata('error', 'Silakan lengkapi data perusahaan terlebih dahulu.');
    //   redirect('auth/register_perusahaan'); // Redirect back to company registration
    // }

    $data['title'] = 'Register Cabang';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/auth/v_register_progress_cabang';
    $this->load->view('pages/auth/index', $data);
  }

  public function process_registrasi_cabang()
  {
    $company_data_from_session = $this->session->userdata('data_perusahaan');
    if (empty($company_data_from_session)) {
      $this->session->set_flashdata('error', 'Silakan lengkapi data perusahaan terlebih dahulu.');
      redirect('auth/register_perusahaan'); // Redirect back to company registration
    }

    // --- Validation Rules for Branch Data ---
    $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required|trim');
    $this->form_validation->set_rules('alamat_cabang', 'Alamat Cabang', 'required|trim');

    $this->form_validation->set_message('required', '{field} wajib diisi.');

    if ($this->form_validation->run() == FALSE) {
      // If validation fails, reload the registration form with errors
      // $response = [
      //   'success' => FALSE,
      //   'msg'     => 'Gagal Registrasi Cabang. Mohon periksa kembali input Anda. ' . validation_errors(),
      //   'errors'  => validation_errors() // Capture all validation errors
      // ];

      $this->session->set_flashdata('error', 'Gagal Registrasi Cabang. Mohon periksa kembali input Anda. ' . validation_errors());
      redirect('auth/register_cabang');
    } else {

      $company_inserted_id = $this->M_login->register_perusahaan($company_data_from_session);
      if (is_array($company_inserted_id) && isset($company_result['code'])) {
        // Log the detailed error message for yourself (the developer)
        log_message('error', 'Database Error (register_perusahaan): Code ' . $company_result['code'] . ' - ' . $company_result['message']);

        $this->db->trans_rollback(); // Rollback all operations if this fails
        $this->session->set_flashdata('error', 'Gagal mendaftarkan data perusahaan. Silakan coba lagi.');
        redirect('auth/register_cabang');
      } else {
        $branch_data = array(
          'id_perusahaan' => $company_inserted_id, // Get from hidden field
          'nama_cabang'   => $this->input->post('nama_cabang'),
          'alamat_cabang' => $this->input->post('alamat_cabang'),
          'nomor_rekening'    => $this->input->post('nomor_rekening'),
          'nama_bank'         => $this->input->post('nama_bank'),
          'generate_sawal'       => '0',

        );

        $branch_inserted_id = $this->M_login->register_cabang($branch_data);

        // ADD BAGIAN UNTUK USER NON PREMIUM
        $data_bagian = array(
          'id_prsh' => $company_inserted_id, // Get from hidden field
          // 'kode'   => '1',
          'nama' => 'Finance',
          'kode_nama' => 'FIN',
        );
        $this->db->insert('bagian', $data_bagian);
        $bagian_now = $this->db->insert_id();

        if (is_array($branch_inserted_id) && isset($branch_inserted_id['code'])) {
          $this->db->trans_rollback(); // Rollback all operations if this fails
          log_message('error', 'Database Error (register_cabang): Code ' . $branch_inserted_id['code'] . ' - ' . $branch_inserted_id['message']);
          $this->session->set_flashdata('error', 'Gagal mendaftarkan data cabang. Silakan coba lagi.');
          redirect('auth/register_cabang');
        } else {

          $user_data = array(
            'id_cabang' => $branch_inserted_id,
            'bagian' => $bagian_now,
          );
          // Assuming 'users' table is in the default database
          $this->db->where('nip', $this->session->userdata('nip')); // Assuming 'id' is the primary key for users table
          $this->db->update('users', $user_data);

          $user_updated = $this->db->affected_rows() > 0;


          // if ($substr_coa == "1" || $substr_coa == "5" || $substr_coa == "6" || $substr_coa == "7" || $substr_coa == "5" || $substr_coa == "6") {
          //   $posisi = 'AKTIVA';
          // } else {
          //   $posisi = 'PASIVA';
          // }

          // // cek tabel
          // if ($substr_coa == "1" || $substr_coa == "2" || $substr_coa == "3") {
          //   $tabel = "t_coa_sbb";
          // } else if ($substr_coa == "4" || $substr_coa == "5" || $substr_coa == "6" || $substr_coa == "7" || $substr_coa == "8" || $substr_coa == "9") {
          //   $tabel = "t_coalr_sbb";
          // }

          $data_bagian1 = array(
            'no_bb' => '23011',
            'no_sbb' => '23011',
            'nama_perkiraan' => 'PPN KELUARAN',
            'posisi' => 'PASIVA',
            'nominal' => '0',
            'id_cabang' => $branch_inserted_id,
          );

          $this->cb->insert('t_coa_sbb', $data_bagian1);


          $data_bagian2 = array(
            'no_bb' => '23014',
            'no_sbb' => '23014',
            'nama_perkiraan' => 'UTANG PPH 23',
            'posisi' => 'PASIVA',
            'nominal' => '0',
            'id_cabang' => $branch_inserted_id,
          );

          $this->cb->insert('t_coa_sbb', $data_bagian2);

          // if ($this->M_login->register_perusahaan($company_data)) {

          if ($company_inserted_id && $branch_inserted_id && $user_updated) {
            // Set success flashdata message
            $this->db->select('utility.*');
            $this->db->from('utility');
            $this->db->join($this->cb->database . '.t_cabang', 't_cabang.id_perusahaan = utility.Id');
            $setting = $this->db->where('t_cabang.uid', $branch_inserted_id)->get()->row();
            // var_dump($setting);

            $this->session->set_userdata('kode_cabang', $branch_inserted_id);
            $this->session->set_userdata('user_perusahaan_id', $setting->Id);
            $this->session->set_userdata('icon', $setting->logo);
            $this->session->set_userdata('nama_singkat', $setting->nama_singkat);
            $this->session->set_userdata('nama_perusahaan', $setting->nama_perusahaan);
            $this->session->set_userdata('alamat_perusahaan', $setting->alamat_perusahaan);
            $this->session->set_userdata('nomor_rekening', $setting->nomor_rekening);
            $this->session->set_userdata('nama_ppn', $setting->nama_ppn);
            $this->session->set_userdata('ppn', $setting->besaran_ppn);
            $this->session->set_userdata('nama_akronim', $setting->nama_akronim);
            $is_premium_boolean = (bool)$setting->is_premium;
            $this->session->set_userdata('is_premium', $is_premium_boolean);



            // $response = [
            //   'success' => TRUE,
            //   'msg'     => 'Berhasil Membuat Akun! Anda akan diarahkan ke halaman utama.',
            //   'reload' => base_url('home')
            // ];

            $this->session->set_flashdata('success', 'Berhasil Membuat Akun! Anda akan diarahkan ke halaman utama.' . validation_errors());
            redirect('home');
          } else {
            // Set error flashdata message
            // $response = [
            //   'success' => FALSE,
            //   'msg'     => 'Gagal Membuat Akun. Terjadi kesalahan pada server. Silakan coba lagi.'
            // ];

            $this->session->set_flashdata('error', 'Gagal Membuat Akun. Terjadi kesalahan pada server. Silakan coba lagi.' . validation_errors());
            redirect('auth/register_cabang');
          }
        }
      }
    }
    // echo json_encode($response);
  }

  public function verifikasi_akun()
  {
    // if (!$this->session->userdata('isLogin')) {
    //   redirect('auth');
    // }

    if (!$this->session->userdata('is_token')) {
      redirect('auth/register_perusahaan');
    }
    $data['title'] = 'Verifikasi Akun';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/auth/v_verifikasi_akun';
    $this->load->view('pages/auth/index', $data);
  }

  public function kirim_ulang_token()
  {

    $user = $this->db->from('users')->where('id', $this->session->userdata('user_user_id'))->get()->row();
    $msg = "Kode verifikasi Akun *Bariskode* Anda adalah *$user->token*, Gunakan Token Saat Login untuk pertama kali. Jangan bagikan kode ini kepada siapa pun.";

    if ($this->api_whatsapp->wa_notif($msg, $user->phone)) {
      echo json_encode(['success' => true, 'message' => 'Berhasil Mengirim Pesan ke nomor ' . $this->censor_phone_number($user->phone) . '.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Gagal Mengirip Pesan, silahkan coba lagi.']);
    }
    // echo json_encode(['success' => false, 'message' => 'Gagal Mengirip Pesan, silahkan coba lagi.']);
  }

  private function censor_phone_number($phone)
  {
    // Get the total length of the phone number string.
    $length = strlen($phone);

    // If the number is 4 digits or less, don't censor anything.
    if ($length <= 4) {
      return $phone;
    }

    // Extract the last 4 digits.
    $last_four = substr($phone, -4);

    // Create a string of asterisks to censor the front part.
    $censored_part = str_repeat('*', $length - 4);

    // Combine the censored part with the last four digits.
    return $censored_part . $last_four;
  }

  public function cek_token()
  {
    $this->db->from('users');
    $this->db->where('id', $this->session->userdata('user_user_id'));
    $data_user = $this->db->get()->row();

    if ($data_user->token == $this->input->post('token')) {
      $edit_data = [
        "token" => null,
      ];
      $this->db->where('id', $this->session->userdata('user_user_id'));
      // $this->db->update('users', $edit_data);

      // Save the access
      if ($this->db->update('users', $edit_data)) {
        $this->session->unset_userdata('is_token');
        // $response = [
        //   'success' => TRUE,
        //   'msg' => 'Login berhasil!',
        //   'reload' => base_url('home')
        // ];
        $this->session->set_flashdata('success', 'Verifikasi successfully!');
        // echo 'Berhasil';
        redirect('auth/register_perusahaan');
      } else {
        // $response = [
        //   'success' => FALSE,
        //   'msg' => 'Failed to update user menu access. Please try again.',
        // ];
        $this->session->set_flashdata('error', 'Failed to update user menu access. Please try again.');
        // echo 'Tidak';
        redirect('auth/verifikasi_akun');
      }
      // redirect('auth');
    } else {
      // $response = [
      //   'success' => FALSE,
      //   'msg' => 'Token Salah. Please try again.',
      // ];
      $this->session->set_flashdata('error', 'Token Salah. Please try again.');
      redirect('auth/verifikasi_akun');
    }
  }

  public function forgot_password()
  {
    if ($this->session->userdata('isLogin')) {
      if (!$this->session->userdata('nama_perusahaan')) {
        redirect('auth/register_perusahaan');
      } else {
        redirect('home');
      }
    }
    $data['title'] = 'Forget Password';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/auth/v_forget_password';
    $this->load->view('pages/auth/index', $data);
  }

  public function proses_lupa_password()
  {
    $username = $this->input->post('username');

    $user = $this->M_login->cekPenggunaForget($username);

    if ($user) {

      $new_random_password = $this->generate_random_password(10); // Misalnya 10 karakter
      $hashed_password = password_hash($new_random_password, PASSWORD_DEFAULT);
      $this->db->where('username', $username)->update('users', ['password' => $hashed_password]);

      $msg = "Kata Sandi Akun *Bariskode* Anda yang baru adalah *$new_random_password*. Gunakan kata sandi ini untuk masuk. Kami sarankan Anda segera menggantinya setelah login.";

      $this->api_whatsapp->wa_notif($msg, $user->phone);

      $response = [
        'status' => 'success',
        'message' => 'Akun ditemukan! Kata sandi baru Anda telah dikirimkan ke nomor WhatsApp yang terdaftar.',
      ];
    } else {
      $response = [
        'status' => 'error',
        'message' => 'Akun tidak ditemukan!'
      ];
    }
    echo json_encode($response);
  }

  function generate_random_password(int $length = 12): string
  {
    // Character sets to use:
    // 1. Lowercase letters
    $lower = 'abcdefghijkmnpqrstuvwxyz';
    // 2. Uppercase letters (excluding similar letters like I, L, O)
    $upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    // 3. Digits (excluding 0 and 1)
    $digits = '23456789';
    // 4. Symbols (common, non-problematic symbols)
    $symbols = '!@#$%^&*()-_+={}[]:;<,>.?/~';

    $all_chars = $lower . $upper . $digits . $symbols;
    $password = '';

    // Generate the random password string
    for ($i = 0; $i < $length; $i++) {
      // Select a random character from the combined set
      $password .= $all_chars[random_int(0, strlen($all_chars) - 1)];
    }

    // Shuffle the result to ensure unpredictability, especially if using 
    // guarantee logic (though the loop is usually sufficient for security)
    return str_shuffle($password);
  }
}
