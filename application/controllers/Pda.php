<?php

use PDFMerger\PDFMerger;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Pda extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();
    $this->load->model(['M_pda']);
    $this->load->library(['pdfgenerator']);
    if ($this->session->userdata('isLogin') == FALSE) {
      redirect('home');
    }
    date_default_timezone_set('Asia/Jakarta');
  }

  public function pra($id)
  {

    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('pda/pra', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $cabang = $this->session->userdata('kode_cabang');
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    if ($cabang != $data['pda']['id_cabang'] and $cabang != 0) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['title'] = 'Pra Pda';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_prapda';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function dokumen($id)
  {
    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('pda/dokumen', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $cabang = $this->session->userdata('kode_cabang');
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    if ($cabang != $data['pda']['id_cabang'] and $cabang != 0) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data_dok = $this->db->get_where('monitoring_dokumen', ['pda_id' => $id])->row_array();
    if ($data_dok) {
      if ($data_dok['status'] == 0) {
        $update_monitoring = [
          'start' => date('Y-m-d H:i:s'),
          'user_start' => $this->session->userdata('nip'),
          'status' => 1,
        ];

        $this->db->where('pda_id', $id);
        $this->db->update('monitoring_dokumen', $update_monitoring);
      }
    }

    $keyword = htmlspecialchars($this->input->get('keyword') ?? '', ENT_QUOTES, 'UTF-8');
    $config['base_url'] = base_url('pda/dokumen/' . $id);
    $config['total_rows'] = $this->M_pda->count_dokumen($keyword, $id);
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
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $data['dokumen'] = $this->M_pda->get_dokumen($config['per_page'], $page, $keyword, $id);
    $data['monitoringDokumen'] = $this->db->get_where('monitoring_dokumen', ['pda_id' => $id])->row_array();

    $data['title'] = 'Dokumen';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_dokumen';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function upload()
  {
    $id_pda = $this->input->post('id_pda');
    $title = $this->input->post('title');
    $file = $_FILES['file']['name'];

    $this->form_validation->set_rules('title', 'Nama Kegiatan', 'required', array('required' => '%s wajib diisi!'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      $config['upload_path'] = './upload/dokumen-pda/' . $id_pda;
      $config['allowed_types'] = 'pdf';
      $config['max_size'] = 5120;
      $config['encrypt_name'] = TRUE;
      $this->upload->initialize($config);

      if (!is_dir('upload/dokumen-pda/' . $id_pda)) {
        mkdir('./upload/dokumen-pda/' . $id_pda, 0777, TRUE);
      }

      if (!$this->upload->do_upload('file')) {
        $response = [
          'success' => false,
          'msg' => $this->upload->display_errors()
        ];
      } else {
        $upload = $this->upload->data();
        $insert = [
          'id_pda' => $id_pda,
          'title' => $title,
          'file' => $file,
          'file_name' => $upload['file_name'],
          'user' => $this->session->userdata('nip')
        ];

        $this->db->insert('t_dokumen', $insert);

        $response = [
          'success' => true,
          'reload' => base_url('pda/dokumen/') . $id_pda,
          'msg' => 'Dokumen berhasil ditambahkan!'
        ];
      }
    }
    echo json_encode($response);
  }

  public function update_dokumen()
  {
    $id_dok = $this->input->post('id_dokumen');
    $title = $this->input->post('title');
    $file = $_FILES['file-ubah']['name'];

    $dokumen = $this->db->get_where('t_dokumen', ['Id' => $id_dok])->row_array();

    $this->form_validation->set_rules('title', 'Nama Kegiatan', 'required', array('required' => '%s wajib diisi!'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0],
      ];
    } else {
      if ($file) {
        $config['upload_path'] = './upload/dokumen-pda/' . $dokumen['id_pda'];
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 5120;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if (!is_dir('upload/dokumen-pda/' . $dokumen['id_pda'])) {
          mkdir('./upload/dokumen-pda/' . $dokumen['id_pda'], 0777, TRUE);
        }

        if (!$this->upload->do_upload('file-ubah')) {
          $response = [
            'success' => false,
            'msg' => $this->upload->display_errors()
          ];
        } else {
          if ($dokumen && file_exists('./upload/dokumen-pda/' . $dokumen['id_pda'] . '/' . $dokumen['file_name'])) {
            unlink('./upload/dokumen-pda/' . $dokumen['id_pda'] . '/' . $dokumen['file_name']);
          }
          $upload = $this->upload->data();
          $update = [
            'title' => $title,
            'file' => $file,
            'file_name' => $upload['file_name'],
            'user_update' => $this->session->userdata('nip')
          ];

          $this->db->where('Id', $id_dok);
          $this->db->update('t_dokumen', $update);

          $response = [
            'success' => true,
            'reload' => base_url('pda/dokumen/') . $dokumen['id_pda'],
            'msg' => 'Dokumen berhasil diubah!'
          ];
        }
      } else {
        $update = [
          'title' => $title,
          'user_update' => $this->session->userdata('nip')
        ];

        $this->db->where('Id', $id_dok);
        $this->db->update('t_dokumen', $update);

        $response = [
          'success' => true,
          'reload' => base_url('pda/dokumen/') . $dokumen['id_pda'],
          'msg' => 'Dokumen berhasil diubah!'
        ];
      }
    }
    echo json_encode($response);
  }

  public function delete_dokumen($id)
  {
    $dokumen = $this->db->get_where('t_dokumen', ['Id' => $id])->row_array();
    if ($dokumen && file_exists('./upload/dokumen-pda/' . $dokumen['id_pda'] . '/' . $dokumen['file_name'])) {
      unlink('./upload/dokumen-pda/' . $dokumen['id_pda'] . '/' . $dokumen['file_name']);
    }

    $this->db->where('Id', $id);
    $this->db->delete('t_dokumen');

    $response = [
      'success' => true,
      'reload' => base_url('pda/dokumen/') . $dokumen['id_pda'],
      'msg' => 'Dokumen berhasil dihapus!'
    ];

    echo json_encode($response);
  }

  public function akhiri_dokumen($id)
  {
    $update_monitoringDok = [
      'end' => date('Y-m-d H:i:s'),
      'user_end' => $this->session->userdata('nip'),
      'status' => 2
    ];

    $this->db->trans_begin();
    $this->db->where('pda_id', $id);
    $this->db->update('monitoring_dokumen', $update_monitoringDok);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = [
        'success' => false,
        'msg' => 'Gagal proses akhiri upload dokumen'
      ];
    } else {
      $this->db->trans_commit();
      $response = [
        'success' => true,
        'msg' => 'Proses upload dokumen berhasil diakhiri!',
        'reload' => base_url('pda/dokumen/') . $id,
      ];
    }

    echo json_encode($response);
  }

  public function merge_file($id)
  {
    include APPPATH . 'libraries/PDFMerger/PDFMerger.php';
    $pdf = new PDFMerger;

    $pda = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $pda['penunjukan']])->row_array();
    $invoices = $this->db->get_where('t_invoice', ['penunjukan' => $penunjukan['Id']]);

    $pdf->addPDF('upload/penunjukan/' . $penunjukan['file_name'], 'all');
    if ($penunjukan['penawaran'] != 0) {
      $penawaran = $this->db->get_where('t_penawaran', ['Id' => $penunjukan['penawaran']])->row_array();
      if ($penawaran['file_name'] != null) {
        $pdf->addPDF('upload/penawaran/' . $penawaran['file_name'], 'all');
      }
    }

    if ($invoices->num_rows() > 0) {
      foreach ($invoices->result_array() as $inv) {
        if ($inv['file_upload']) {
          $pdf->addPDF('upload/invoice-upload/' . $inv['file_upload'], 'all');
        }
      }
    }

    $dokumen = $this->db->get_where('t_dokumen', ['id_pda' => $id])->result_array();
    foreach ($dokumen as $dok) {
      $pdf->addPDF('upload/dokumen-pda/' . $id . '/' . $dok['file_name'], 'all');
    }

    $pdf->merge('browser', 'test.pdf');
  }

  public function hpp_rill($id)
  {
    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('pda/hpp_rill', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $cabang = $this->session->userdata('kode_cabang');
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    if ($cabang != $data['pda']['id_cabang'] and $cabang != 0) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data_hppril = $this->db->get_where('monitoring_hpprill', ['pda_id' => $id])->row_array();
    if ($data_hppril) {
      if ($data_hppril['status'] == 0) {
        $update_monitoring = [
          'start' => date('Y-m-d H:i:s'),
          'user_start' => $this->session->userdata('nip'),
          'status' => 1,
        ];

        $this->db->where('pda_id', $id);
        $this->db->update('monitoring_hpprill', $update_monitoring);
      }
    }

    $data['title'] = 'Hpp Rill';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_hpp_rill';
    $data['pages_script'] = 'script/agency/s_hpp_rill';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function get_item()
  {
    // Get query parameters from AJAX request
    $q = $this->input->get('q');  // Search term
    $page = (int)$this->input->get('page');  // Current page
    $penunjukan = $this->input->get('jenis');
    $port = $this->input->get('port');
    $title = $this->input->get('desc');
    $limit = 20;
    $offset = ($page - 1) * $limit;

    // Call the model function to get the filtered and paginated data
    $datas = $this->M_pda->get_filtered_data($q, $limit + 1, $offset, $penunjukan, $port, $title);

    $results = [];
    $more = false;

    if (count($datas) > $limit) {
      $more = true;
      array_pop($datas); // Remove the extra item
    }

    foreach ($datas as $data) {
      $results[] = [
        'id' => $data->Id,
        'text' => $data->desc
      ];
    }

    // Prepare the response in Select2's expected format
    $response = array(
      'items' => $results,  // Data to display in the dropdown
      'more' => $more  // Total count for pagination
    );

    // Send the response as JSON
    echo json_encode($response);
  }

  public function get_item_pda_by_port()
  {
    $id = $this->input->get('id_port');
    $port = $this->db->get_where('agency_port', ['Id' => $id])->row_array();

    $sql = "SELECT a.desc,a.est,a.hpp_rill, b.desc as desc_detail FROM t_item_pda a LEFT JOIN t_detail_item_pda b ON a.Id = b.pda WHERE a.port = '$port[kode]'";
    $query = $this->db->query($sql)->result_array();

    $response = [
      'data' => $query
    ];

    echo json_encode($response);
  }

  public function getItemById()
  {
    $id = $this->input->get('id');
    $this->db->select('Id, est');
    $item_pda = $this->db->get_where('t_item_pda', ['Id' => $id])->row_array();


    $response = [
      'data' => $item_pda
    ];

    echo json_encode($response);
  }

  public function insert_hpprill($id)
  {
    $data_hppril = $this->db->get_where('monitoring_hpprill', ['pda_id' => $id])->row_array();
    if ($data_hppril) {
      if ($data_hppril['status'] == 1) {
        $update_monitoring = [
          'user_end' => $this->session->userdata('nip'),
          'end' => date('Y-m-d H:i:s'),
          'status' => 2,
        ];

        $this->db->where('pda_id', $id);
        $this->db->update('monitoring_hpprill', $update_monitoring);
      }
    }
    $id_desc = $this->input->post('id_desc[]');
    $remarks = $this->input->post('remarks[]');
    $grt = $this->input->post('grt[]');
    $tarif = $this->input->post('tarif[]');
    $activity = $this->input->post('activity[]');
    $amount_desc = $this->input->post('amount-desc[]');
    $remark_desc = $this->input->post('remark-desc[]');

    $desc = $this->input->post('desc[]');
    $amount = $this->input->post('amount[]');
    $remark = $this->input->post('remark[]');
    $qty = $this->input->post('qty[]');
    $mulai = $this->input->post('mulai[]');
    $selesai = $this->input->post('selesai[]');

    $desc_other = $this->input->post('desc-other[]');
    $amount_other = $this->input->post('amount-other[]');
    $remark_other = $this->input->post('remark-other[]');
    $qty_other = $this->input->post('qty-other[]');
    $mulai_other = $this->input->post('mulai-other[]');
    $selesai_other = $this->input->post('selesai-other[]');

    $data = [
      'desc' => [
        'id_desc' => $id_desc,
        'remarks' => $remarks,
        'grt' => $grt,
        'tarif' => $tarif,
        'activity' => $activity,
        'amount_desc' => $amount_desc,
        'remark_desc' => $remark_desc
      ],
      'agency_remuneration' => [
        'desc' => $desc,
        'amount' => $amount,
        'qty' => $qty,
        'tanggal_mulai' => $mulai,
        'tanggal_selesai' => $selesai,
        'remark' => $remark
      ],
      'other' => [
        'desc' => $desc_other,
        'amount' => $amount_other,
        'qty' => $qty_other,
        'tanggal_mulai' => $mulai_other,
        'tanggal_selesai' => $selesai_other,
        'remark' => $remark_other
      ]
    ];

    $existing_data = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    if ($existing_data['harga_jual']) {
      $hpp_rill = json_decode($existing_data['hpp_rill'], true);
      $harga_jual = json_decode($existing_data['harga_jual'], true);

      // Update HPP Rill dari $data (hasil post)
      $hpp_rill['agency_remuneration'] = $data['agency_remuneration'];
      $hpp_rill['other'] = $data['other'];

      // Index amount dari harga_jual berdasarkan desc
      $amount_map = [];
      if (isset($harga_jual['agency_remuneration']['desc'])) {
        foreach ($harga_jual['agency_remuneration']['desc'] as $i => $desc_val) {
          $amount_map[$desc_val] = $harga_jual['agency_remuneration']['amount'][$i] ?? '0';
        }
      }

      // Siapkan struktur baru
      $updated_remuneration = [
        'desc' => [],
        'amount' => [],
        'qty' => [],
        'tanggal_mulai' => [],
        'tanggal_selesai' => [],
        'remark' => [],
      ];

      $updated_other = [
        'desc' => [],
        'amount' => [],
        'qty' => [],
        'tanggal_mulai' => [],
        'tanggal_selesai' => [],
        'remark' => []
      ];

      // Loop data terbaru dari hpp_rill
      foreach ($hpp_rill['agency_remuneration']['desc'] as $i => $desc_val) {
        $updated_remuneration['desc'][] = $desc_val;

        // Jika amount lama ada, gunakan
        $updated_remuneration['amount'][] = $amount_map[$desc_val] ?? $hpp_rill['agency_remuneration']['amount'][$i];

        // Kolom lain tetap pakai dari hpp_rill (data terbaru)
        $updated_remuneration['qty'][] = $hpp_rill['agency_remuneration']['qty'][$i];
        $updated_remuneration['tanggal_mulai'][] = $hpp_rill['agency_remuneration']['tanggal_mulai'][$i];
        $updated_remuneration['tanggal_selesai'][] = $hpp_rill['agency_remuneration']['tanggal_selesai'][$i];
        $updated_remuneration['remark'][] = $hpp_rill['agency_remuneration']['remark'][$i];
      }

      // Loop data terbaru dari hpp_rill
      foreach ($hpp_rill['other']['desc'] as $i => $desc_val) {
        $updated_other['desc'][] = $desc_val;

        // Jika amount lama ada, gunakan
        $updated_other['amount'][] = $amount_map[$desc_val] ?? $hpp_rill['other']['amount'][$i];

        // Kolom lain tetap pakai dari hpp_rill (data terbaru)
        $updated_other['qty'][] = $hpp_rill['other']['qty'][$i];
        $updated_other['tanggal_mulai'][] = $hpp_rill['other']['tanggal_mulai'][$i];
        $updated_other['tanggal_selesai'][] = $hpp_rill['other']['tanggal_selesai'][$i];
        $updated_other['remark'][] = $hpp_rill['other']['remark'][$i];
      }


      $harga_jual['other'] = $updated_other;
      $harga_jual['agency_remuneration'] = $updated_remuneration;
      $updated_json_encoded = json_encode($harga_jual);
    } else {
      $updated_json_encoded = null;
    }

    $this->db->where('Id', $id);
    $this->db->update('t_pda', ['hpp_rill' => json_encode($data), 'harga_jual' => $updated_json_encoded]);

    $response = [
      'success' => true,
      'reload' => base_url('agency/penunjukan'),
      'msg' => 'Data berhasil disimpan!'
    ];

    echo json_encode($response);
  }

  public function view_hpprill_excel($id)
  {

    $pda = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $hpp = json_decode($pda['hpp_rill']);
    $agency_remuneration = $hpp->agency_remuneration;
    $desc = $hpp->desc;
    $other = $hpp->other;

    if ($other->desc != "") {
      $other_desc = $other->desc;
    } else {
      $other_desc = [""];
    }

    $this->db->select('nama');
    $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $style_col = [
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
    ];

    $sheet->mergeCells('A1:B1');
    $sheet->setCellValue('A1', "To");
    $sheet->setCellValue('C1', ":");
    $sheet->setCellValue('D1', $pda['to']);
    $sheet->mergeCells('D1:M1');

    $sheet->getStyle('A1:M1')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A2:M2')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A3:M3')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A4:M4')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A5:M5')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A6:M6')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A7:M7')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A8:M8')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A9:M9')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A10:M10')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->mergeCells('A2:B2');
    $sheet->setCellValue('A2', "From");
    $sheet->setCellValue('C2', ":");
    $sheet->setCellValue('D2', $pda['from']);
    $sheet->mergeCells('D2:M2');

    $sheet->mergeCells('A3:B3');
    $sheet->setCellValue('A3', "Date");
    $sheet->setCellValue('C3', ":");
    $sheet->setCellValue('D3', date('d/m/Y', strtotime($pda['tanggal'])));
    $sheet->mergeCells('D3:M3');

    $sheet->mergeCells('A4:M4');
    $sheet->setCellValue('A4', 'FINAL PORT DISBURSEMENT ACCOUNT');
    $sheet->getStyle('A4:M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
    // style header 1
    $sheet->getStyle('A4')->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true,
          'color' => array('rgb' => 'FFFFFF'),
          'size' => 12,
        ]
      ]
    );

    $sheet->mergeCells('A5:B5');
    $sheet->setCellValue('A5', "PORT");
    $sheet->setCellValue('C5', ":");
    $sheet->setCellValue('D5', $port['nama']);
    $sheet->mergeCells('D5:H5');

    $sheet->mergeCells('A6:B6');
    $sheet->setCellValue('A6', "VESSEL NAME");
    $sheet->setCellValue('C6', ":");
    $sheet->setCellValue('D6', $pda['vessel_name']);
    $sheet->mergeCells('D6:H6');

    $sheet->mergeCells('A7:B7');
    $sheet->setCellValue('A7', "GRT");
    $sheet->setCellValue('C7', ":");
    $sheet->setCellValue('D7',   $pda['grt']);
    $sheet->mergeCells('D7:H7');

    $sheet->setCellValue('A8', 'NO.');
    $sheet->setCellValue('B8', 'DESCRIPTION');
    $sheet->setCellValue('G8', 'REMARKS');
    $sheet->setCellValue('H8', 'GRT');
    $sheet->setCellValue('I8', 'TARIF');
    $sheet->setCellValue('K8', 'ACTIVITY');
    $sheet->setCellValue('L8', 'AMOUNT (IDR)');
    $sheet->setCellValue('M8', 'REMARK');
    $sheet->mergeCells('B8:F8');
    $sheet->mergeCells('I8:J8');

    $sheet->getStyle('A8:M8')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $sheet->getStyle('A8')->applyFromArray($style_col);
    $sheet->getStyle('B8')->applyFromArray($style_col);
    $sheet->getStyle('G8')->applyFromArray($style_col);
    $sheet->getStyle('H8')->applyFromArray($style_col);
    $sheet->getStyle('I8')->applyFromArray($style_col);
    $sheet->getStyle('K8')->applyFromArray($style_col);
    $sheet->getStyle('L8')->applyFromArray($style_col);
    $sheet->getStyle('M8')->applyFromArray($style_col);


    $no = 1;
    $num_row = 9;
    $gf = 0;
    foreach ($desc->id_desc as $key => $val) {
      $this->db->select('desc');
      $item_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
      $gf += intval(preg_replace('/[^a-zA-Z0-9\']/', '', $desc->amount_desc[$key]));

      $sheet->setCellValue('A' . $num_row, $no++);
      $sheet->setCellValue('B' . $num_row, $item_desc['desc']);
      $sheet->setCellValue('G' . $num_row, $desc->remarks[$key]);
      $sheet->setCellValue('H' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc->grt[$key]));
      $sheet->setCellValue('I' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc->tarif[$key]));
      $sheet->setCellValue('K' . $num_row, $desc->activity[$key]);
      $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc->amount_desc[$key]));
      $sheet->setCellValue('M' . $num_row, $desc->remark_desc[$key]);

      // Merge cell
      $sheet->mergeCells('B' . $num_row . ':' . 'F' . $num_row);
      $sheet->mergeCells('I' . $num_row . ':' . 'J' . $num_row);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('G' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('H' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('I' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('K' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('M' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    $sheet->setCellValue('A' . $num_row, 'GF');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // AGENCY REMUNERATION
    $num_row = $num_row + 1;

    $sheet->setCellValue('A' . $num_row, 'B.');
    $sheet->setCellValue('B' . $num_row, 'AGENCY REMUNERATION');
    $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
    // style header 1
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('B' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $i = 1;
    $grand_total = 0;
    $num_row = $num_row + 1;
    foreach ($agency_remuneration->desc as $k => $value) {
      $this->db->select('desc');
      $item_pda = $this->db->get_where('t_item_pda', ['Id' => $value])->row_array();
      if ($agency_remuneration->qty[$k] != "") {
        $amount[] = floatval($agency_remuneration->qty[$k]) * floatval(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]));
      } else {
        $amount[] = preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]);
      }
      $grand_total += preg_replace('/[^a-zA-Z0-9\']/', '', $amount[$k]);

      $sheet->setCellValue('A' . $num_row, $i++);
      $sheet->setCellValue('B' . $num_row, $item_pda['desc']);
      $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
      $sheet->setCellValue('L' . $num_row, $amount[$k]);
      $sheet->setCellValue('M' . $num_row, $agency_remuneration->remark[$k]);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    // Jika ada item tambahan
    $total_other = 0;
    if ($other_desc[0] != "") {
      // AGENCY REMUNERATION
      // $num_row = $num_row + 1;

      $sheet->setCellValue('A' . $num_row, 'C.');
      $sheet->setCellValue('B' . $num_row, 'OTHER / OWNER EXPENSE');
      $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);
      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
      // style header 1
      $sheet->getStyle('A' . $num_row)->applyFromArray(
        [
          'font' => [
            'bold' => true,
            'size' => 12,
          ]
        ]
      );

      $sheet->getStyle('B' . $num_row)->applyFromArray(
        [
          'font' => [
            'bold' => true,
            'size' => 12,
          ]
        ]
      );

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $j = 1;
      $num_row =  $num_row + 1;
      foreach ($other_desc as $index => $o) {
        $total_other += intval(preg_replace('/[^a-zA-Z0-9\']/', '', $other->amount[$index]));

        $sheet->setCellValue('A' . $num_row, $j++);
        $sheet->setCellValue('B' . $num_row, $o);
        $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
        $sheet->setCellValue('L' . $num_row, str_replace('.', '', $other->amount[$index]));
        $sheet->setCellValue('M' . $num_row, str_replace('.', '', $other->remark[$index]));

        $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);
        $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
          [
            'borders' => [
              'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
              'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
              'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ]
          ]
        );

        $num_row++;
      }
      // $num_row = $num_row + 1;
    }
    $sheet->setCellValue('A' . $num_row, "GRAND TOTAL DISBURSEMENT");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf + $grand_total + $total_other));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'Request');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Sincerely,');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Approved by,');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, '');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, '');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, '');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $this->db->select('nama, nama_jabatan');
    $user = $this->db->get_where('users', ['nip' => $pda['user_request']])->row_array();
    $sheet->setCellValue('A' . $num_row, $user['nama']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Jumari');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Rahma Irianto');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, $user['nama_jabatan']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Manager Ops');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'General Manager');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);
    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );


    foreach ($sheet->getColumnIterator() as $column) {
      $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="HPP_RILL.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }

  public function create_er($id)
  {
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    $data['title'] = 'Hpp Rill';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_create_er';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function update_er($id)
  {
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    $data['title'] = 'Hpp Rill';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_update_er';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function insert_er($id)
  {
    $desc = $this->input->post('desc[]');
    $amount = $this->input->post('amount[]');
    $qty = $this->input->post('qty[]');
    $mulai = $this->input->post('mulai[]');
    $selesai = $this->input->post('selesai[]');
    $er = $this->input->post('er[]');
    $remark = $this->input->post('remark[]');

    $lkk_in = $this->input->post('lkk_in');
    $lkk_out = $this->input->post('lkk_out');

    $this->form_validation->set_rules('lkk_in', 'Request LKK IN', 'required');
    $this->form_validation->set_rules('lkk_out', 'Request LKK OUT', 'required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      for ($i = 0; $i < count($desc); $i++) {
        if ($er[$i] == 1) {
          $option_er = '1';
        } else {
          $option_er = '2';
        }

        $dataDesc[] = $desc[$i];
        $dataAmount[] = $amount[$i];
        $dataQty[] = $qty[$i];
        $dataMulai[] = $mulai[$i];
        $dataSelesai[] = $selesai[$i];
        $dataRemark[] = $remark[$i];
        $dataEr[] = $option_er;
      }

      $data = [
        'desc' => [
          'id_desc' => $this->input->post('id_desc[]'),
          'remarks' => $this->input->post('remarks_desc[]'),
          'grt' => $this->input->post('grt_desc[]'),
          'tarif' => $this->input->post('tarif_desc[]'),
          'activity' => $this->input->post('activity_desc[]'),
          'amount_desc' => $this->input->post('amount_desc[]'),
          'remark_desc' => $this->input->post('remark_desc[]'),
        ],
        'agency_remuneration' => [
          'desc' => $dataDesc,
          'amount' => $dataAmount,
          'qty' => $dataQty,
          'tanggal_mulai' => $dataMulai,
          'tanggal_selesai' => $dataSelesai,
          'remark' => $dataRemark,
          'er' => $dataEr
        ]
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_pda', [
        'er' => json_encode($data),
        'lkk_in' => preg_replace('/[^a-zA-Z0-9\']/', '', $lkk_in),
        'lkk_out' => preg_replace('/[^a-zA-Z0-9\']/', '', $lkk_out),
      ]);

      $response = [
        'success' => true,
        'reload' => base_url('pda/hpp_rill/') . $id,
        'msg' => 'Data ER berhasil dibuat!'
      ];
    }
    echo json_encode($response);
  }

  public function er_excel($id)
  {
    $pda = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $hpp = json_decode($pda['hpp_rill']);
    $agency_remuneration_hpp = $hpp->agency_remuneration;
    $desc_hpp = $hpp->desc;

    $er = json_decode($pda['er']);
    $agency_remuneration = $er->agency_remuneration;
    $desc_er = $er->desc;
    $dataEr = $agency_remuneration->er;

    foreach ($dataEr as $key => $val) {
      if ($val == '1') {
        $erDesc[] = $agency_remuneration->desc[$key];
        $erAmount[] = $agency_remuneration->amount[$key];
        $erQty[] = $agency_remuneration->qty[$key];
        $erMulai[] = $agency_remuneration->tanggal_mulai[$key];
        $erSelesai[] = $agency_remuneration->tanggal_selesai[$key];
        $erRemark[] = $agency_remuneration->remark[$key];
        $erEr[] = $agency_remuneration->er[$key];
      } else {
        $noErDesc[] = $agency_remuneration->desc[$key];
        $noErAmount[] = $agency_remuneration->amount[$key];
        $noErQty[] = $agency_remuneration->qty[$key];
        $noErMulai[] = $agency_remuneration->tanggal_mulai[$key];
        $noErSelesai[] = $agency_remuneration->tanggal_selesai[$key];
        $noErRemark[] = $agency_remuneration->remark[$key];
        $noErER[] = $agency_remuneration->er[$key];
      }
    }
    $data['er'] = [
      'desc' => $erDesc,
      'amount' => $erAmount,
      'qty' => $erQty,
      'tanggal_mulai' => $erMulai,
      'tanggal_selesai' => $erSelesai,
      'remark' => $erRemark,
      'er' => $erEr
    ];

    $data['no_er'] = [
      'desc' => $noErDesc,
      'amount' => $noErAmount,
      'qty' => $noErQty,
      'tanggal_mulai' => $noErMulai,
      'tanggal_selesai' => $noErSelesai,
      'remark' => $noErRemark,
      'er' => $noErER
    ];

    $this->db->select('nama');
    $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $style_col = [
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
    ];

    $sheet->mergeCells('A1:B1');
    $sheet->setCellValue('A1', "To");
    $sheet->setCellValue('C1', ":");
    $sheet->setCellValue('D1', $pda['to']);
    $sheet->mergeCells('D1:M1');

    $sheet->getStyle('A1:M1')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A2:M2')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A3:M3')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A4:M4')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A5:M5')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A6:M6')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A7:M7')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A8:M8')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A9:M9')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A10:M10')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->mergeCells('A2:B2');
    $sheet->setCellValue('A2', "From");
    $sheet->setCellValue('C2', ":");
    $sheet->setCellValue('D2', $pda['from']);
    $sheet->mergeCells('D2:M2');

    $sheet->mergeCells('A3:B3');
    $sheet->setCellValue('A3', "Date");
    $sheet->setCellValue('C3', ":");
    $sheet->setCellValue('D3', date('d/m/Y', strtotime($pda['tanggal'])));
    $sheet->mergeCells('D3:M3');

    $sheet->mergeCells('A4:M4');
    $sheet->setCellValue('A4', 'FINAL PORT DISBURSEMENT ACCOUNT');
    $sheet->getStyle('A4:M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
    // style header 1
    $sheet->getStyle('A4')->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true,
          'color' => array('rgb' => 'FFFFFF'),
          'size' => 12,
        ]
      ]
    );

    $sheet->mergeCells('A5:B5');
    $sheet->setCellValue('A5', "PORT");
    $sheet->setCellValue('C5', ":");
    $sheet->setCellValue('D5', $port['nama']);
    $sheet->mergeCells('D5:H5');

    $sheet->mergeCells('A6:B6');
    $sheet->setCellValue('A6', "VESSEL NAME");
    $sheet->setCellValue('C6', ":");
    $sheet->setCellValue('D6', $pda['vessel_name']);
    $sheet->mergeCells('D6:H6');

    $sheet->mergeCells('A7:B7');
    $sheet->setCellValue('A7', "GRT");
    $sheet->setCellValue('C7', ":");
    $sheet->setCellValue('D7',  preg_replace('/[^a-zA-Z0-9\']/', '', $pda['grt']));
    $sheet->mergeCells('D7:H7');

    $sheet->setCellValue('A8', 'NO.');
    $sheet->setCellValue('B8', 'DESCRIPTION');
    $sheet->setCellValue('G8', 'REMARKS');
    $sheet->setCellValue('H8', 'GRT');
    $sheet->setCellValue('I8', 'TARIF');
    $sheet->setCellValue('K8', 'ACTIVITY');
    $sheet->setCellValue('L8', 'AMOUNT (IDR)');
    $sheet->setCellValue('M8', 'REMARK');
    $sheet->mergeCells('B8:F8');
    $sheet->mergeCells('I8:J8');

    $sheet->getStyle('A8:M8')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $sheet->getStyle('A8')->applyFromArray($style_col);
    $sheet->getStyle('B8')->applyFromArray($style_col);
    $sheet->getStyle('G8')->applyFromArray($style_col);
    $sheet->getStyle('H8')->applyFromArray($style_col);
    $sheet->getStyle('I8')->applyFromArray($style_col);
    $sheet->getStyle('K8')->applyFromArray($style_col);
    $sheet->getStyle('L8')->applyFromArray($style_col);
    $sheet->getStyle('M8')->applyFromArray($style_col);


    $no = 1;
    $num_row = 9;
    $gf_desc = 0;
    foreach ($desc_er->id_desc as $key => $val) {
      $this->db->select('desc');
      $item_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
      $gf_desc += intval(preg_replace('/[^a-zA-Z0-9\']/', '', $desc_er->amount_desc[$key]));

      $sheet->setCellValue('A' . $num_row, $no++);
      $sheet->setCellValue('B' . $num_row, $item_desc['desc']);
      $sheet->setCellValue('G' . $num_row, $desc_er->remarks[$key]);
      $sheet->setCellValue('H' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc_er->grt[$key]));
      $sheet->setCellValue('I' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc_er->tarif[$key]));
      $sheet->setCellValue('K' . $num_row, $desc_er->activity[$key]);
      $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $desc_er->amount_desc[$key]));
      $sheet->setCellValue('M' . $num_row, $desc_er->remark_desc[$key]);

      // Merge cell
      $sheet->mergeCells('B' . $num_row . ':' . 'F' . $num_row);
      $sheet->mergeCells('I' . $num_row . ':' . 'J' . $num_row);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('G' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('H' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('I' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('K' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('M' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    $sheet->setCellValue('A' . $num_row, 'GF');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf_desc));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // AGENCY REMUNERATION (EXPENSE REPORT)
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'B.');
    $sheet->setCellValue('B' . $num_row, 'AGENCY REMUNERATION (EXPENSE REPORT)');
    $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
    // style header 1
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('B' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $i = 1;
    $grand_total_er = 0;
    $num_row = $num_row + 1;
    foreach ($data['er']['desc'] as $k => $value) {
      $this->db->select('desc');
      $item_pda = $this->db->get_where('t_item_pda', ['Id' => $value])->row_array();
      if ($agency_remuneration->qty[$k] != "") {
        // $amount[] = $agency_remuneration->qty[$k] * preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]);
        $amount[] = $data['er']['qty'][$k] * preg_replace('/[^a-zA-Z0-9\']/', '', $data['er']['amount'][$k]);
      } else {
        $amount[] = preg_replace('/[^a-zA-Z0-9\']/', '', $data['er']['amount'][$k]);
      }
      $grand_total_er += preg_replace('/[^a-zA-Z0-9\']/', '', $amount[$k]);

      $sheet->setCellValue('A' . $num_row, $i++);
      $sheet->setCellValue('B' . $num_row, $item_pda['desc']);
      $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
      $sheet->setCellValue('L' . $num_row, $amount[$k]);
      $sheet->setCellValue('M' . $num_row, $agency_remuneration->remark[$k]);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }
    $sheet->setCellValue('A' . $num_row, "GF");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $grand_total_er));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // LKK IN
    $nomLkkIn = preg_replace('/[^a-zA-Z0-9\']/', '', $pda['lkk_in']);
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'REQUEST LKK IN');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, $nomLkkIn);
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // LKK OUT
    $nomLkkOut = preg_replace('/[^a-zA-Z0-9\']/', '', $pda['lkk_out']);
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'REQUEST LKK OUT');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, $nomLkkOut);
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // REFUND TO JAKARTA
    $refund = $nomLkkIn + $nomLkkOut - ($gf_desc + $grand_total_er);
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'REFUND TO JAKARTA');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, $refund);
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC000');

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // AGENCY REMUNERATION
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'C.');
    $sheet->setCellValue('B' . $num_row, 'AGENCY REMUNERATION');
    $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
    // style header 1
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('B' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    //  Agency Remuneration (NO ER)
    $num_row = $num_row + 1;
    $j = 1;
    $grand_total_noEr = 0;
    foreach ($data['no_er']['desc'] as $k => $value) {
      $this->db->select('desc');
      $item_pda = $this->db->get_where('t_item_pda', ['Id' => $value])->row_array();
      if ($agency_remuneration->qty[$k] != "") {
        // $amount[] = $agency_remuneration->qty[$k] * preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]);
        $amount[] = floatval($data['no_er']['qty'][$k]) * floatval(preg_replace('/[^a-zA-Z0-9\']/', '', $data['no_er']['amount'][$k]));
      } else {
        $amount[] = preg_replace('/[^a-zA-Z0-9\']/', '', $data['no_er']['amount'][$k]);
      }
      $grand_total_noEr += preg_replace('/[^a-zA-Z0-9\']/', '', $amount[$k]);

      $sheet->setCellValue('A' . $num_row, $j++);
      $sheet->setCellValue('B' . $num_row, $item_pda['desc']);
      $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
      $sheet->setCellValue('L' . $num_row, $amount[$k]);
      $sheet->setCellValue('M' . $num_row, $agency_remuneration->remark[$k]);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    $sheet->setCellValue('A' . $num_row, "GF");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $grand_total_noEr));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // Grand Total Disbursement
    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, "GRAND TOTAL DISBURSEMENT");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf_desc + $grand_total_er + $grand_total_noEr));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // Jika ada item tambahan
    $total_other = 0;
    // if ($other_desc[0] != "") {
    //   // AGENCY REMUNERATION
    //   $num_row = $num_row + 1;

    //   $sheet->setCellValue('A' . $num_row, 'C.');
    //   $sheet->setCellValue('B' . $num_row, 'OTHER / OWNER EXPENSE');
    //   $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);
    //   $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
    //   // style header 1
    //   $sheet->getStyle('A' . $num_row)->applyFromArray(
    //     [
    //       'font' => [
    //         'bold' => true,
    //         'size' => 12,
    //       ]
    //     ]
    //   );

    //   $sheet->getStyle('B' . $num_row)->applyFromArray(
    //     [
    //       'font' => [
    //         'bold' => true,
    //         'size' => 12,
    //       ]
    //     ]
    //   );

    //   $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
    //     [
    //       'borders' => [
    //         'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //         'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //         'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    //         'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    //       ]
    //     ]
    //   );

    //   $j = 1;
    //   foreach ($other_desc as $index => $o) {
    //     $total_other += intval(preg_replace('/[^a-zA-Z0-9\']/', '', $other->amount[$index]));

    //     $sheet->setCellValue('A' . $num_row, $j++);
    //     $sheet->setCellValue('B' . $num_row, $o);
    //     $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
    //     $sheet->setCellValue('L' . $num_row, $other->amount[$index]);
    //     $sheet->setCellValue('M' . $num_row, $other->remark[$index]);

    //     $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);
    //     $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
    //       [
    //         'borders' => [
    //           'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //           'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //           'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    //         ]
    //       ]
    //     );

    //     $num_row++;
    //   }
    //   $num_row = $num_row + 1;
    // }
    // $sheet->setCellValue('A' . $num_row, "GRAND TOTAL DISBURSEMENT");
    // $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    // $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf + $grand_total + $total_other));
    // $sheet->getStyle('A' . $num_row)->applyFromArray(
    //   [
    //     'alignment' => [
    //       'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    //       'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    //     ],
    //   ]
    // );

    // $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
    //   [
    //     'borders' => [
    //       'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //       'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //       'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //       'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    //       'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
    //     ]
    //   ]
    // );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'Request');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Sincerely,');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Approved by,');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, '');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, '');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, '');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $this->db->select('nama, nama_jabatan');
    $user = $this->db->get_where('users', ['nip' => $pda['user_request']])->row_array();
    $sheet->setCellValue('A' . $num_row, $user['nama']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Jumari');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Rahma Irianto');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, $user['nama_jabatan']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Manager Ops');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'General Manager');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);
    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );


    foreach ($sheet->getColumnIterator() as $column) {
      $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="EXPENSE_REPORT.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }

  public function view_prapda_excel($id)
  {

    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('pda/pra', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $pda = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $estimasi = json_decode($pda['est']);
    $agency_remuneration = $estimasi->agency_remuneration;
    $desc = $estimasi->desc;

    $this->db->select('nama');
    $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $style_col = [
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ],
    ];

    $sheet->mergeCells('A1:M1');
    $sheet->setCellValue('A1', 'PRA PDA');
    $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    // style header 1
    $sheet->getStyle('A1')->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->mergeCells('A2:B2');
    $sheet->setCellValue('A2', "To");
    $sheet->setCellValue('C2', ":");
    $sheet->setCellValue('D2', $pda['to']);
    $sheet->mergeCells('D2:M2');

    $sheet->getStyle('A2:M2')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A3:M3')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A4:M4')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A5:M5')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->getStyle('A6:M6')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A7:M7')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A8:M8')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A9:M9')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A10:M10')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );
    $sheet->getStyle('A11:M11')->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK], // Set border top dengan garis tipis
        ]
      ]
    );

    $sheet->mergeCells('A3:B3');
    $sheet->setCellValue('A3', "From");
    $sheet->setCellValue('C3', ":");
    $sheet->setCellValue('D3', $pda['from']);
    $sheet->mergeCells('D3:M3');

    $sheet->mergeCells('A4:B4');
    $sheet->setCellValue('A4', "Date");
    $sheet->setCellValue('C4', ":");
    $sheet->setCellValue('D4', date('d/m/Y', strtotime($pda['tanggal'])));
    $sheet->mergeCells('D4:M4');

    $sheet->mergeCells('A5:M5');
    $sheet->setCellValue('A5', 'FINAL PORT DISBURSEMENT ACCOUNT');
    $sheet->getStyle('A5:M5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('0070C0');
    // style header 1
    $sheet->getStyle('A5')->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true,
          'color' => array('rgb' => 'FFFFFF'),
          'size' => 12,
        ]
      ]
    );

    $sheet->mergeCells('A6:B6');
    $sheet->setCellValue('A6', "PORT");
    $sheet->setCellValue('C6', ":");
    $sheet->setCellValue('D6', $port['nama']);
    $sheet->mergeCells('D6:H6');

    $sheet->mergeCells('A7:B7');
    $sheet->setCellValue('A7', "VESSEL NAME");
    $sheet->setCellValue('C7', ":");
    $sheet->setCellValue('D7', $pda['vessel_name']);
    $sheet->mergeCells('D7:H7');

    $sheet->mergeCells('A8:B8');
    $sheet->setCellValue('A8', "GRT");
    $sheet->setCellValue('C8', ":");
    $sheet->setCellValue('D8',  $pda['grt']);
    $sheet->mergeCells('D8:H8');

    $sheet->setCellValue('A9', 'NO.');
    $sheet->setCellValue('B9', 'DESCRIPTION');
    $sheet->setCellValue('G9', 'REMARKS');
    $sheet->setCellValue('H9', 'GRT');
    $sheet->setCellValue('I9', 'TARIF');
    $sheet->setCellValue('K9', 'ACTIVITY');
    $sheet->setCellValue('L9', 'AMOUNT (IDR)');
    $sheet->setCellValue('M9', 'REMARK');
    $sheet->mergeCells('B9:F9');
    $sheet->mergeCells('I9:J9');

    $sheet->getStyle('A9:M9')->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $sheet->getStyle('A9')->applyFromArray($style_col);
    $sheet->getStyle('B9')->applyFromArray($style_col);
    $sheet->getStyle('G9')->applyFromArray($style_col);
    $sheet->getStyle('H9')->applyFromArray($style_col);
    $sheet->getStyle('I9')->applyFromArray($style_col);
    $sheet->getStyle('K9')->applyFromArray($style_col);
    $sheet->getStyle('L9')->applyFromArray($style_col);
    $sheet->getStyle('M9')->applyFromArray($style_col);


    $no = 1;
    $num_row = 10;
    $gf = 0;
    foreach ($desc->id_desc as $key => $val) {
      $this->db->select('desc');
      $item_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
      $gf += intval(preg_replace('/[^a-zA-Z0-9\']/', '', $desc->amount_desc[$key]));

      $sheet->setCellValue('A' . $num_row, $no++);
      $sheet->setCellValue('B' . $num_row, $item_desc['desc']);
      $sheet->setCellValue('G' . $num_row, $desc->remarks[$key]);
      $sheet->setCellValue('H' . $num_row, $desc->grt[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc->grt[$key]) : "");
      $sheet->setCellValue('I' . $num_row, $desc->tarif[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc->tarif[$key]) : "");
      $sheet->setCellValue('K' . $num_row, $desc->activity[$key]);
      $sheet->setCellValue('L' . $num_row, $desc->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc->amount_desc[$key]) : "");
      $sheet->setCellValue('M' . $num_row, $desc->remark_desc[$key]);

      // Merge cell
      $sheet->mergeCells('B' . $num_row . ':' . 'F' . $num_row);
      $sheet->mergeCells('I' . $num_row . ':' . 'J' . $num_row);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('G' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('H' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('I' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('K' . $num_row)->applyFromArray($style_col);
      $sheet->getStyle('M' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    $sheet->setCellValue('A' . $num_row, 'GF');
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    // AGENCY REMUNERATION
    $num_row = $num_row + 1;

    $sheet->setCellValue('A' . $num_row, 'B.');
    $sheet->setCellValue('B' . $num_row, 'AGENCY REMUNERATION');
    $sheet->mergeCells('B' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0');
    // style header 1
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('B' . $num_row)->applyFromArray(
      [
        'font' => [
          'bold' => true,
          'size' => 12,
        ]
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $i = 1;
    $grand_total = 0;
    $num_row = $num_row + 1;
    foreach ($agency_remuneration->desc as $k => $value) {
      $this->db->select('desc');
      $item_pda = $this->db->get_where('t_item_pda', ['Id' => $value])->row_array();
      if ($agency_remuneration->qty[$k] != "") {
        $amount[] = $agency_remuneration->qty[$k] * preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]);
      } else {
        $amount[] = preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration->amount[$k]);
      }
      $grand_total += preg_replace('/[^a-zA-Z0-9\']/', '', $amount[$k]);

      $sheet->setCellValue('A' . $num_row, $i++);
      $sheet->setCellValue('B' . $num_row, $item_pda['desc']);
      $sheet->mergeCells('B' . $num_row . ':' . 'K' . $num_row);
      $sheet->setCellValue('L' . $num_row, $amount[$k]);
      $sheet->setCellValue('M' . $num_row, $agency_remuneration->remark[$k]);

      $sheet->getStyle('A' . $num_row)->applyFromArray($style_col);

      $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
        [
          'borders' => [
            'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
            'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          ]
        ]
      );

      $num_row++;
    }

    $sheet->setCellValue('A' . $num_row, "GF");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $grand_total));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT, // Set text jadi ditengah secara horizontal (right)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, "GRAND TOTAL DISBURSEMENT");
    $sheet->mergeCells('A' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, preg_replace('/[^a-zA-Z0-9\']/', '', $gf + $grand_total));
    $sheet->getStyle('A' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, // Set text jadi ditengah secara horizontal (left)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
      ]
    );

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, 'Request');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Sincerely,');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Approved by,');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, '');
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, '');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, '');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $this->db->select('nama, nama_jabatan');
    $user = $this->db->get_where('users', ['nip' => $pda['user_request']])->row_array();
    $sheet->setCellValue('A' . $num_row, $user['nama']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Jumari');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'Rahma Irianto');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);

    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'font' => [
          'bold' => true
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ]
      ]
    );

    $num_row = $num_row + 1;
    $sheet->setCellValue('A' . $num_row, $user['nama_jabatan']);
    $sheet->mergeCells('A' . $num_row . ':' . 'E' . $num_row);
    $sheet->setCellValue('F' . $num_row, 'Manager Ops');
    $sheet->mergeCells('F' . $num_row . ':' . 'K' . $num_row);
    $sheet->setCellValue('L' . $num_row, 'General Manager');
    $sheet->mergeCells('L' . $num_row . ':' . 'M' . $num_row);
    $sheet->getStyle('A' . $num_row . ':' . 'M' . $num_row)->applyFromArray(
      [
        'alignment' => [
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
        ],
        'borders' => [
          'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
          'vertical' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
          'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK],
        ]
      ]
    );


    foreach ($sheet->getColumnIterator() as $column) {
      $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="PRA_PDA.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }

  public function harga_jual($id)
  {
    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('pda/harga_jual', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $cabang = $this->session->userdata('kode_cabang');
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();

    if ($cabang != $data['pda']['id_cabang'] and $cabang != 0) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $dataHrgJual = $this->db->get_where('monitoring_hrgjual', ['pda_id' => $id])->row_array();
    if ($dataHrgJual) {
      if ($dataHrgJual['status'] == 0) {
        $update_monitoring = [
          'start' => date('Y-m-d H:i:s'),
          'user_start' => $this->session->userdata('nip'),
          'status' => 1,
        ];

        $this->db->where('pda_id', $id);
        $this->db->update('monitoring_hrgjual', $update_monitoring);
      }
    }

    $data['title'] = 'Harga Jual';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_harga_jual';
    $data['pages_script'] = 'script/agency/s_hpp_rill';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function insert_hargajual($id)
  {
    $data_hrgjual = $this->db->get_where('monitoring_hrgjual', ['pda_id' => $id])->row_array();
    if ($data_hrgjual) {
      if ($data_hrgjual['status'] == 1) {
        $update_monitoring = [
          'user_end' => $this->session->userdata('nip'),
          'end' => date('Y-m-d H:i:s'),
          'status' => 2,
        ];

        $this->db->where('pda_id', $id);
        $this->db->update('monitoring_hrgjual', $update_monitoring);
      }
    }

    $pda = $this->db->select('a.*, b.customer')->from('t_pda a')->join('t_penunjukan b', 'b.Id = a.penunjukan', 'left')->where('a.Id', $id)->get()->row_array();

    $id_desc = $this->input->post('id_desc[]');
    $remarks = $this->input->post('remarks[]');
    $grt = $this->input->post('grt[]');
    $tarif = $this->input->post('tarif[]');
    $activity = $this->input->post('activity[]');
    $amount_desc = $this->input->post('amount-desc[]');
    $remark_desc = $this->input->post('remark-desc[]');

    $desc = $this->input->post('desc[]');
    $amount = $this->input->post('amount[]');
    $remark = $this->input->post('remark[]');
    $qty = $this->input->post('qty[]');
    $mulai = $this->input->post('mulai[]');
    $selesai = $this->input->post('selesai[]');

    $desc_other = $this->input->post('desc-other[]');
    $amount_other = $this->input->post('amount-other[]');
    $remark_other = $this->input->post('remark-other[]');
    $qty_other = $this->input->post('qty-other[]');
    $mulai_other = $this->input->post('mulai-other[]');
    $selesai_other = $this->input->post('selesai-other[]');

    foreach ($desc as $key => $value) {
      $hrgjual = $this->db->select('*')->from('t_harga_jual')->where('customer', $pda['customer'])->where('item_pda', $value)->get()->num_rows();

      if ($hrgjual == 0 and $amount[$key] != 0) {
        $this->db->insert('t_harga_jual', [
          'customer' => $pda['customer'],
          'item_pda' => $value,
          'harga' => preg_replace('/[^a-zA-Z0-9\']/', '', $amount[$key])
        ]);
      }
    }

    $data = [
      'desc' => [
        'id_desc' => $id_desc,
        'remarks' => $remarks,
        'grt' => $grt,
        'tarif' => $tarif,
        'activity' => $activity,
        'amount_desc' => $amount_desc,
        'remark_desc' => $remark_desc
      ],
      'agency_remuneration' => [
        'desc' => $desc,
        'amount' => $amount,
        'remark' => $remark,
        'qty' => $qty,
        'tanggal_mulai' => $mulai,
        'tanggal_selesai' => $selesai
      ],
      'other' => [
        'desc' => $desc_other,
        'amount' => $amount_other,
        'qty' => $qty_other,
        'tanggal_mulai' => $mulai_other,
        'tanggal_selesai' => $selesai_other,
        'remark' => $remark_other
      ]
    ];

    $this->db->where('Id', $id);
    $this->db->update('t_pda', ['harga_jual' => json_encode($data)]);

    $response = [
      'success' => true,
      'reload' => base_url('agency/penunjukan'),
      'msg' => 'Data berhasil diubah!'
    ];

    echo json_encode($response);
  }

  public function kwitansi($id)
  {
    $data['pda'] = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    // $this->load->view('djs/pda/v_kwitansi', $data);

    // filename dari pdf ketika didownload
    $file_pdf = 'Kwitansi';

    // setting paper
    $paper = 'A4';

    //orientasi paper potrait / landscape
    $orientation = "potrait";

    $html = $this->load->view('pages/agency/pda/v_kwitansi', $data, true);

    // run dompdf
    $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
  }

  public function invoice($id)
  {
    $has_access = $this->M_menu->has_access();

    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('invoice', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $cabang = $this->session->userdata('kode_cabang');
    $sqlPda = "SELECT a.*, b.Id as id_cust FROM t_pda a LEFT JOIN t_penunjukan c ON c.Id = a.penunjukan LEFT JOIN t_customer b ON b.Id = c.customer WHERE a.Id = $id";
    $data['pda'] = $this->db->query($sqlPda)->row_array();

    if ($cabang != $data['pda']['id_cabang'] and $cabang != 0) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['customer'] = $this->db->get('t_customer')->result_array();
    $data['title'] = 'Create Invoice';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/pda/v_invoice_create';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function insert_invoice($id)
  {
    $pda = $this->db->get_where('t_pda', ['Id' => $id])->row_array();
    $penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $pda['penunjukan']])->row_array();
    $agency = $this->db->get_where('agent', ['Id' => $penunjukan['agency']])->row_array();
    $cabang = $this->db->get_where('agency_cabang', ['Id' => $pda['id_cabang']])->row_array();

    $tanggal = $this->input->post('date');
    $kapal = $this->input->post('kapal');
    $jml_muatan_bs = $this->input->post('jml_muatan_bs');
    $customer = $this->input->post('customer');
    $pel_muat_bs = $this->input->post('pel_muat_bs');
    $pel_bongkar_bs = $this->input->post('pel_bongkar_bs');
    $jml_muatan_bb = $this->input->post('jml_muatan_bb');
    $pel_muat_bb = $this->input->post('pel_muat_bb');
    $pel_bongkar_bb = $this->input->post('pel_bongkar_bb');
    $cargo = $this->input->post('cargo');
    $ta_nor = $this->input->post('ta_nor');
    $td = $this->input->post('td');
    $materai = $this->input->post('materai');
    $ppn = $this->input->post('ppn');
    $note = $this->input->post('note');
    $pph = $this->input->post('pph');
    $dp = $this->input->post('dp');

    $uraian = $this->input->post('uraian[]');
    $satuan = $this->input->post('satuan[]');
    $harga = $this->input->post('harga[]');
    $mulai = $this->input->post('mulai[]');
    $selesai = $this->input->post('selesai[]');
    $kategori = $this->input->post('kategori[]');

    $sql = "SELECT t_customer.kode, t_customer.Id FROM t_customer WHERE t_customer.Id = '$customer'";
    $data_customer = $this->db->query($sql)->row_array();

    $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
    $bln = $array_bln[date('n', strtotime($tanggal))];
    $year = date('Y', strtotime($tanggal));

    $count = $this->db->select('max(no_invoice) as maximal')->from('t_invoice')->where('customer', $data_customer['Id'])->get()->row_array();
    if ($count) {
      $count = $count['maximal'] + 1;
    } else {
      $count = 1;
    }
    $referensi = 'AGN/' . (sprintf("%03d", $count)) . '/INV/' . $cabang['kode'] . '-' . $agency['kode'] . '/' . $data_customer['kode'] . '/' . $bln . '/' . $year;
    // $count = sprintf("%03d", $count + 1);

    $this->form_validation->set_rules('date', 'Tanggal', 'required', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('customer', 'Customer', 'required', array('required' => '%s wajib dipilih!'));
    $this->form_validation->set_rules('kapal', 'Nama kapal', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('jml_muatan_bb', 'Jumlah muatan batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_muat_bb', 'Pelabuhan muat batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_bongkar_bb', 'Pelabuhan bongkar bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('cargo', 'Cargo', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('ta_nor', 'TA/NOR', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('td', 'TD', 'required|trim', array('required' => '%s wajib diisi!'));
    // $this->form_validation->set_rules('note', 'Note', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('uraian[]', 'Uraian pekerjaan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('satuan[]', 'Satuan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('harga[]', 'Harga', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('kategori[]', 'Kategori', 'required', array('required' => '%s wajib dipilih!'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $invoice = [
        'no_invoice' => sprintf("%03d", $count),
        'referensi' => $referensi,
        'penunjukan' => $pda['penunjukan'],
        'customer' => $customer,
        'tanggal' => $tanggal,
        'nama_kapal' => $kapal,
        'jml_muatan' => $jml_muatan_bb,
        'pel_muat' => $pel_muat_bb,
        'pel_bongkar' => $pel_bongkar_bb,
        'jml_muatan_bs' => $jml_muatan_bs,
        'pel_muat_bs' => $pel_muat_bs,
        'pel_bongkar_bs' => $pel_bongkar_bs,
        'cargo' => $cargo,
        'ta_nor' => $ta_nor,
        'td' => $td,
        'notes' => $note,
        'created_by' => $this->session->userdata('nip'),
        'status' => 0,
        'materai' => $materai,
        'ppn' => $ppn,
        'jenis' => 2,
        'nominal_pph' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $pph),
        'down_payment' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $dp),
        'id_cabang' => $pda['id_cabang']
      ];

      $this->db->insert('t_invoice', $invoice);

      $id_invoice = $this->db->insert_id();

      $sub_total = 0;
      for ($i = 0; $i < count($uraian); $i++) {
        $total = preg_replace('/[^a-zA-Z0-9\']/', '', $harga[$i]) * preg_replace('/[^a-zA-Z0-9\']/', '', $satuan[$i]);
        $sub_total += $total;
        $detail = [
          'id_invoice' => $id_invoice,
          'uraian' => $uraian[$i],
          'jumlah' => preg_replace('/[^a-zA-Z0-9\']/', '', $harga[$i]),
          'satuan' => preg_replace('/[^a-zA-Z0-9\']/', '', $satuan[$i]),
          'mulai' => $mulai[$i] ? $mulai[$i] : null,
          'selesai' => $selesai[$i] ? $selesai[$i] : null,
          'total' => $total,
          'kategori' => $kategori[$i]
        ];

        $this->db->insert('t_detail_invoice', $detail);
      }

      if ($materai == 1) {
        $nom_materai = 10000;
      } else {
        $nom_materai = 0;
      }

      if ($ppn == 1) {
        $dpp_lainnya = $sub_total * 11 / 12;
        $nom_ppn = 0.12 * $dpp_lainnya;
      } else {
        $nom_ppn = 0;
        $dpp_lainnya = 0;
      }

      $gt = $sub_total + $nom_ppn + $nom_materai - preg_replace('/[^a-zA-Z0-9\']/', '', $pph) - (preg_replace('/[^a-zA-Z0-9\']/', '', $dp));

      $update_invoice = [
        'sub_total' => $sub_total,
        'total' => $gt,
        'nominal_ppn' => $nom_ppn,
        'nominal_materai' => $nom_materai,
        'dpp' => $dpp_lainnya,
      ];

      $this->db->where('Id', $id_invoice);
      $this->db->update('t_invoice', $update_invoice);

      $response = [
        'success' => true,
        'reload' => base_url('invoice'),
        'msg' => 'Invoice berhasil dibuat!'
      ];
    }

    echo json_encode($response);
  }
}
