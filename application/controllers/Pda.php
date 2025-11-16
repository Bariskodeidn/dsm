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
}
