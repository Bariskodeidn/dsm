<?php

use PDFMerger\PDFMerger;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{

  public function __construct()
  {

    parent::__construct();
    $this->load->model(['M_invoice']);
    $this->load->library(['pdfgenerator']);
    if ($this->session->userdata('isLogin') == FALSE) {
      redirect('home');
    }
    date_default_timezone_set('Asia/Jakarta');
  }

  public function index()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }
    $data['title'] = 'List Invoice';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/invoice/v_invoice';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

    $this->load->view('index', $data);
  }

  public function create()
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('invoice', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $data['customer'] = $this->db->get('agency_customer');
    $data['penunjukan'] = $this->db->get('t_penunjukan');
    $data['title'] = 'Create Invoice';
    $data['utility'] = $this->db->get('utility')->row_array();
    $data['pages'] = 'pages/agency/invoice/v_invoice_create';
    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function insert()
  {
    $tanggal = $this->input->post('date');
    $penunjukan = $this->input->post('penunjukan');
    $customer = $this->input->post('customer');
    $kapal = $this->input->post('kapal');
    $jml_muatan_bs = $this->input->post('jml_muatan_bs');
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

    $sql = "SELECT agency_customer.kode, agency_customer.Id FROM agency_customer WHERE agency_customer.Id = '$customer'";
    $data_customer = $this->db->query($sql)->row_array();

    $this->form_validation->set_rules('date', 'Tanggal', 'required', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('penunjukan', 'No. penunjukan', 'required', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('customer', 'Customer', 'required', array('required' => '%s wajib dipilih!'));
    $this->form_validation->set_rules('kapal', 'Nama kapal', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('jml_muatan_bb', 'Jumlah muatan batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_muat_bb', 'Pelabuhan muat batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_bongkar_bb', 'Pelabuhan bongkar batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('cargo', 'Cargo', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('ta_nor', 'TA/NOR', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('td', 'TD', 'required|trim', array('required' => '%s wajib diisi!'));
    // $this->form_validation->set_rules('note', 'Note', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('uraian[]', 'Uraian pekerjaan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('satuan[]', 'Satuan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('harga[]', 'Harga', 'required|trim', array('required' => '%s wajib diisi!'));

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {
      $data_penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $penunjukan])->row_array();
      $agency = $this->db->get_where('agent', ['Id' => $data_penunjukan['agency']])->row_array();
      $cabang = $this->db->get_where('t_cabang', ['Id' => $data_penunjukan['id_cabang']])->row_array();

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

      $invoice = [
        'no_invoice' => sprintf("%03d", $count),
        'referensi' => $referensi,
        'penunjukan' => $penunjukan,
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
        'jenis' => 1,
        'nominal_pph' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $pph),
        'down_payment' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $dp),
        'id_cabang' => $data_penunjukan['id_cabang']
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
          'total' => $total,
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

  public function ubah($id)
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('invoice', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }


    $sql = "SELECT a.*, b.Id as id_cust, c.Id as id_penunjukan FROM t_invoice a LEFT JOIN t_penunjukan c ON c.Id = a.penunjukan LEFT JOIN agency_customer b ON b.Id = a.customer WHERE a.Id = $id";
    $data['invoice'] = $this->db->query($sql)->row_array();
    $data['customer'] = $this->db->get('agency_customer');
    $data['penunjukan'] = $this->db->get('t_penunjukan');
    if ($data['invoice']) {
      if ($data['invoice']['status'] == 1) {
        show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
      } else {
        $data['pages'] = 'pages/agency/invoice/v_invoice_ubah';
      }
    } else {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }


    $data['title'] = 'Ubah Invoice';
    $data['utility'] = $this->db->get('utility')->row_array();

    $data['pages_script'] = 'script/agency/s_agency';
    $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));
    $this->load->view('index', $data);
  }

  public function update($id)
  {
    $invoice = $this->db->get_where('t_invoice', ['Id' => $id])->row_array();
    $tanggal = $this->input->post('date');
    $penunjukan = $this->input->post('penunjukan');
    $customer = $this->input->post('customer');
    $kapal = $this->input->post('kapal');
    $jml_muatan_bs = $this->input->post('jml_muatan_bs');
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

    $sql = "SELECT agency_customer.kode, agency_customer.Id FROM agency_customer WHERE agency_customer.Id = '$customer'";
    $data_customer = $this->db->query($sql)->row_array();

    $data_penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $penunjukan])->row_array();
    $agency = $this->db->get_where('agent', ['Id' => $data_penunjukan['agency']])->row_array();

    $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
    $bln = $array_bln[date('n', strtotime($tanggal))];
    $year = date('Y', strtotime($tanggal));

    $count = $this->db->select('max(no_invoice) as maximal')->from('t_invoice')->where('customer', $data_customer['Id'])->get()->row_array();
    if ($count) {
      $count = $count['maximal'] + 1;
    } else {
      $count = 1;
    }

    $referensi = 'AGN/' . (sprintf("%03d", $count)) . '/INV/PLB-' . $agency['kode'] . '/' . $data_customer['kode'] . '/' . $bln . '/' . $year;


    $this->form_validation->set_rules('date', 'Tanggal', 'required', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('penunjukan', 'No. penunjukan', 'required', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('customer', 'Customer', 'required', array('required' => '%s wajib dipilih!'));
    $this->form_validation->set_rules('kapal', 'Nama kapal', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('jml_muatan_bb', 'Jumlah muatan batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_muat_bb', 'Pelabuhan muat batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('pel_bongkar_bb', 'Pelabuhan bongkar batu bara', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('cargo', 'Cargo', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('ta_nor', 'TA/NOR', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('td', 'TD', 'required|trim', array('required' => '%s wajib diisi!'));

    $this->form_validation->set_rules('uraian[]', 'Uraian pekerjaan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('satuan[]', 'Satuan', 'required|trim', array('required' => '%s wajib diisi!'));
    $this->form_validation->set_rules('harga[]', 'Harga', 'required|trim', array('required' => '%s wajib diisi!'));
    if ($invoice['jenis'] == 2) {
      $this->form_validation->set_rules('kategori[]', 'Kategori', 'required', array('required' => '%s wajib diisi!'));
    }

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'success' => false,
        'msg' => array_values($this->form_validation->error_array())[0]
      ];
    } else {

      if ($invoice['customer'] != $customer) {
        $update = [
          'no_invoice' => sprintf("%03d", $count),
          'referensi' => $referensi,
          'penunjukan' => $penunjukan,
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
          'nominal_pph' => preg_replace('/[^a-zA-Z0-9\']/', '', $pph),
          'down_payment' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $dp)
        ];
      } else {
        $update = [
          'penunjukan' => $penunjukan,
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
          'nominal_pph' => preg_replace('/[^a-zA-Z0-9\']/', '', $pph),
          'down_payment' =>  preg_replace('/[^a-zA-Z0-9\']/', '', $dp)
        ];
      }

      $this->db->where('Id', $id);
      $this->db->update('t_invoice', $update);

      // delete detail invoice
      $this->db->where('id_invoice', $id);
      $this->db->delete('t_detail_invoice');

      // insert detail invoice baru
      $sub_total = 0;
      for ($i = 0; $i < count($uraian); $i++) {
        $total = preg_replace('/[^a-zA-Z0-9\']/', '', $harga[$i]) * preg_replace('/[^a-zA-Z0-9\']/', '', $satuan[$i]);
        $sub_total += $total;
        if ($invoice['jenis'] == 1) {
          $detail = [
            'id_invoice' => $id,
            'uraian' => $uraian[$i],
            'jumlah' => preg_replace('/[^a-zA-Z0-9\']/', '', $harga[$i]),
            'satuan' => preg_replace('/[^a-zA-Z0-9\']/', '', $satuan[$i]),
            'total' => $total,
          ];
        } else {
          $detail = [
            'id_invoice' => $id,
            'uraian' => $uraian[$i],
            'jumlah' => preg_replace('/[^a-zA-Z0-9\']/', '', $harga[$i]),
            'satuan' => preg_replace('/[^a-zA-Z0-9\']/', '', $satuan[$i]),
            'total' => $total,
            'mulai' => $mulai[$i] ? $mulai[$i] : null,
            'selesai' => $selesai[$i] ? $selesai[$i] : null,
            'kategori' => $kategori[$i]
          ];
        }

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
        $dpp_lainnya = 0;
        $nom_ppn = 0;
      }

      $gt = $sub_total + $nom_ppn + $nom_materai - (preg_replace('/[^a-zA-Z0-9\']/', '', $pph)) - (preg_replace('/[^a-zA-Z0-9\']/', '', $dp));

      $update_invoice = [
        'sub_total' => $sub_total,
        'total' => $gt,
        'nominal_ppn' => $nom_ppn,
        'nominal_materai' => $nom_materai,
        'dpp' => $dpp_lainnya,
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_invoice', $update_invoice);

      $response = [
        'success' => true,
        'reload' => base_url('invoice'),
        'msg' => 'Invoice berhasil diubah!'
      ];
    }

    echo json_encode($response);
  }

  public function cetak($id)
  {
    $has_access = $this->M_menu->has_access();
    $access_menu_all = $this->M_menu->get_allowed_routes($this->session->userdata('nip'));

    if (!$has_access and !in_array('invoice', $access_menu_all)) {
      show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
    }

    $this->db->select('*')->from('t_invoice')->where('Id', $id);
    $invoice = $this->db->get()->row_array();
    $data['invoice'] = $invoice;

    // filename dari pdf ketika didownload
    $file_pdf = 'Invoice No. ' . $invoice['referensi'];

    // setting paper
    $paper = 'A4';

    //orientasi paper potrait / landscape
    $orientation = "portrait";

    $html = $this->load->view('pages/agency/invoice/v_cetak_invoice', $data, true);

    // run dompdf
    $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    // $this->load->view('djs/invoice/v_cetak_invoice', $data);

    $this->load->view('index', $data);
  }

  public function invoice_ajax_list()
  {
    $list = $this->M_invoice->get_datatables();
    $data = array();
    $no = $this->input->post('start');
    $i = 1;
    foreach ($list as $data_invoice) {

      if ($data_invoice->status == 1) {
        $color = '#88C273';
        $text = 'Paid';
      }
      if ($data_invoice->status == 0) {
        $color = '#FF9D3D';
        $text = 'Unpaid';
      }

      $status = '<span style="background-color:' . $color . ';color: white; padding:3px;">' . $text . '</span>';

      $btnUbah = '<a href="' . base_url("invoice/ubah/") . $data_invoice->Id . '" class="btn btn-success btn-sm mx-1 my-1"><i class="fe fe-edit" aria-hidden="true"></i></a>';
      $btnCetak = '<a href="' . base_url("invoice/cetak/") . $data_invoice->Id . '" class="btn btn-warning btn-sm mx-1 my-1" target="_blank"><i class="fe fe-file" aria-hidden="true"></i></a>';
      $btnFile = '<a href="' . base_url("upload/invoice-bayar/") . $data_invoice->file_name . '" class="btn btn-primary btn-sm mx-1 my-1" target="_blank"><i class="fe fe-file" aria-hidden="true"></i> Bukti Bayar</a>';
      $btnFileUpload = '<a href="' . base_url("upload/invoice-upload/") . $data_invoice->file_upload . '" class="btn btn-primary btn-sm mx-1 my-1" target="_blank"><i class="fe fe-file" aria-hidden="true"></i> File Invoice</a>';
      $btnBayar = '<button type="button" class="btn btn-primary btn-sm mx-1 my-1" onclick="modalBayar(' . $data_invoice->Id . ')"><i class="fe fe-credit-card" aria-hidden="true"></i> Bayar</button>';
      $btnKirim = '<button type="button" class="btn btn-success btn-sm mx-1 my-1" onclick="modalKirim(' . $data_invoice->Id . ')"><i class="fe fe-send" aria-hidden="true"></i> Kirim</button>';
      $btnUpload = '<button type="button" class="btn btn-primary btn-sm mx-1 my-1" onclick="modalUpload(' . $data_invoice->Id . ')"><i class="fe fe-upload" aria-hidden="true"></i> Upload</button>';

      if ($data_invoice->date_kirim == null) {
        $btnKirim = $btnKirim;
      } else {
        $btnKirim = "";
      }

      if ($data_invoice->file_upload == null) {
        $btnUpload = $btnUpload;
        $btnFileUpload = "";
      } else {
        $btnUpload = "";
        $btnFileUpload = $btnFileUpload;
      }

      if ($data_invoice->status == 0) {
        $aksi = $btnUbah . $btnCetak . $btnKirim . $btnBayar . $btnUpload . $btnFileUpload;
      }

      if ($data_invoice->status == 1) {
        $aksi = $btnCetak . $btnFile . $btnKirim . $btnUpload . $btnFileUpload;
      }

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $data_invoice->nama_customer;
      $row[] = $data_invoice->referensi;
      $row[] = $data_invoice->tanggal ? date('d/m/Y', strtotime($data_invoice->tanggal)) : "-";
      $row[] = $data_invoice->date_kirim ? date('d/m/Y', strtotime($data_invoice->date_kirim)) : "-";
      $row[] = $data_invoice->tgl_bayar ? date('d/m/Y', strtotime($data_invoice->tgl_bayar)) : "-";
      $row[] = $data_invoice->selisih != null ? $data_invoice->selisih . " Hari" : "-";
      $row[] = number_format($data_invoice->total);
      $row[] = $status;
      $row[] = $aksi;
      $data[] = $row;
    }

    $output = array(
      "draw" => $this->input->post('draw'),
      "recordsTotal" => $this->M_invoice->count_all(),
      "recordsFiltered" => $this->M_invoice->count_filtered(),
      "data" => $data,
    );
    //output to json format
    $this->output->set_output(json_encode($output));
  }

  public function getInvoiceById()
  {
    $invoiceId = $this->input->get('invoiceId');
    $invoice = $this->db->select('Id, referensi')->from('t_invoice')->where('Id', $invoiceId)->get()->row_array();

    $data = [
      'data' => $invoice
    ];

    echo json_encode($data);
  }

  public function bayar()
  {
    $id = $this->input->post('invoice-id');
    $tgl_bayar = $this->input->post('tgl');
    $catatan = $this->input->post('catatan');
    $file = $_FILES['file']['name'];

    $config['upload_path'] = './upload/invoice-bayar';
    $config['allowed_types'] = 'pdf|jpg|jpeg|png';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    if (!is_dir('upload/invoice-bayar')) {
      mkdir('./upload/invoice-bayar', 0777, TRUE);
    }

    if (!$this->upload->do_upload('file')) {
      $response = [
        'success' => false,
        'msg' => $this->upload->display_errors()
      ];
    } else {
      $upload = $this->upload->data();
      $update = [
        'file' => $file,
        'file_name' => $upload['file_name'],
        'keterangan' => $catatan,
        'status' => 1,
        'tgl_bayar' => $tgl_bayar
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_invoice', $update);

      $response = [
        'success' => true,
        'reload' => base_url('invoice'),
        'msg' => 'Invoice berhasil dibayar!'
      ];
    }

    echo json_encode($response);
  }

  public function kirim()
  {
    $id = $this->input->post('invoice-id');
    $tgl_kirim = $this->input->post('tgl');

    $update = [
      'date_kirim' => $tgl_kirim
    ];

    $this->db->where('Id', $id);
    $this->db->update('t_invoice', $update);

    $response = [
      'success' => true,
      'reload' => base_url('invoice'),
      'msg' => 'Invoice berhasil dikirim!'
    ];


    echo json_encode($response);
  }

  public function upload()
  {
    $id = $this->input->post('invoice-id');

    $config['upload_path'] = './upload/invoice-upload';
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = 5120;
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    if (!is_dir('upload/invoice-upload')) {
      mkdir('./upload/invoice-upload', 0777, TRUE);
    }

    if (!$this->upload->do_upload('file')) {
      $response = [
        'success' => false,
        'msg' => $this->upload->display_errors()
      ];
    } else {
      $upload = $this->upload->data();
      $update = [
        'file_upload' => $upload['file_name'],
      ];

      $this->db->where('Id', $id);
      $this->db->update('t_invoice', $update);

      $response = [
        'success' => true,
        'reload' => base_url('invoice'),
        'msg' => 'File invoice berhasil diupload!'
      ];
    }

    echo json_encode($response);
  }
}
