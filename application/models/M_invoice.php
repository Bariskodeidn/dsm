<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_invoice extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function apply_cabang_filter()
    {
        $kode_cabang = $this->session->userdata('kode_cabang');
        return $this->cb->where('id_cabang', $kode_cabang);
    }

    public function list_invoice($limit, $from, $keyword, $customer_id)
    {
        $kode_cabang = $this->session->userdata('kode_cabang');

        $this->cb->select('a.*, c.nama_customer, a.Id as nomor_id_invoice, a.slug as slug_invoice');
        $this->cb->from('invoice a');
        $this->cb->where('a.id_cabang', $kode_cabang);
        $this->cb->join('customer c', 'a.id_customer = c.id', 'left');

        if ($keyword) {
            $this->cb->like('no_invoice', $keyword);
            $this->cb->or_like('total_nonpph', $keyword);
        }

        if ($customer_id) {
            $this->cb->where('id_customer', $customer_id);
        }

        $invoices = $this->cb->order_by('no_invoice', 'DESC')->limit($limit, $from)->get()->result_array();

        // Ambil semua user dari database bdl_core
        $users = $this->db->select('id, nip, nama')->get('users')->result_array();
        $user_map = array_column($users, 'nama', 'nip');  // Menggunakan nama pengguna sebagai nama kolom

        // Gabungkan hasil query
        foreach ($invoices as &$invoice) {
            $invoice['created_by_name'] = isset($user_map[$invoice['created_by']]) ? $user_map[$invoice['created_by']] : null;
            $invoice['updated_by_name'] = isset($user_map[$invoice['updated_by']]) ? $user_map[$invoice['updated_by']] : null;
        }

        return $invoices;
    }

    public function invoice_count($keyword, $customer_id)
    {
        if ($keyword) {
            $this->cb->like('no_invoice', $keyword);
            $this->cb->or_like('total_nonpph', $keyword);
        }

        if ($customer_id) {
            $this->cb->where('id_customer', $customer_id);
        }

        return $this->cb->from('invoice')->count_all_results();
    }

    public function select_max($tahun)
    {
        // Query untuk mendapatkan nomor invoice maksimum berdasarkan tahun
        $query = $this->cb->select('MAX(no_invoice) AS max')
            ->where('YEAR(tanggal_invoice)', $tahun)
            ->get('invoice');

        // Cek apakah hasil query tidak kosong
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null; // Atau return array kosong jika diperlukan
        }
    }

    public function insert($invoice_data)
    {
        $this->cb->insert('invoice', $invoice_data);

        // Dapatkan ID invoice yang baru saja di-generate
        return $this->cb->insert_id();
    }

    public function insert_batch($detail_data)
    {
        return $this->cb->insert_batch('invoice_details', $detail_data);
    }

    public function show($no_inv)
    {
        return $this->cb->select('*, a.created_by as user_create, a.slug as slug_invoice, a.Id as nomor_id_invoice')->from('invoice a')->join('customer b', 'a.id_customer = b.id', 'left')->where('no_invoice', $no_inv)->get()->row_array();
    }

    public function showById($id)
    {
        return $this->cb->select('*, a.created_by as user_create, a.slug as slug_invoice, a.Id as nomor_id_invoice')->from('invoice a')->join('customer b', 'a.id_customer = b.id', 'left')->where('a.Id', $id)->get()->row_array();
    }

    public function item_list($id)
    {
        return $this->cb->where('id_invoice', $id)->get('invoice_details')->result();
    }

    public function report($from, $to)
    {
        return $this->cb->from('invoice a')->join('customer b', 'a.id_customer = b.id', 'left')->where('tanggal_invoice >=', $from)->where('tanggal_invoice <=', $to)->get()->result();
    }

    public function delete_detail($id)
    {
        return $this->cb->where('Id', $id)->delete('invoice_details');
    }

    public function update_item($id, $data)
    {
        return $this->cb->where('Id', $id)->update('invoice_details', $data);
    }

    public function update_invoice($id_invoice, $data)
    {
        return $this->cb->where('Id', $id_invoice)->update('invoice', $data);
    }

    public function get_discount($id)
    {
        return $this->cb->select('diskon')->where('Id', $id)->get('invoice')->row_array();
    }

    public function sum_total($id_invoice)
    {
        return $this->cb->select_sum('total')->where('id_invoice', $id_invoice)->get('invoice_details')->row_array();
    }

    public function get_item_names($searchTerm)
    {
        $this->cb->like('nama_item', $searchTerm);
        $query = $this->cb->get('item_invoice');
        return $query->result_array();
    }

    public function addLogPayment($data)
    {
        return $this->cb->insert('t_log_pembayaran', $data);
    }

    public function cek_user($id)
    {
        return $this->db->get_where('users', ['nip' => $id])->row_array();
    }

    public function outstanding_agent()
    {
        $this->apply_cabang_filter();
        // get data agent
        $customers = $this->cb->select('Id, nama_customer, jenis_outstanding, outstanding4, outstanding5')
            ->order_by('nama_customer', 'ASC')
            ->get('customer')
            ->result();

        $data = [];
        foreach ($customers as $c) {
            list($current, $out1, $out2, $out3, $out4) = $this->calculateOutstandingAmounts($c->Id, $c->jenis_outstanding);

            if ($c->Id >= 20 and $c->Id <= 25) {
                $out4 = ($c->outstanding4) ? $c->outstanding4 : 0;
                $out5 = ($c->outstanding5) ? $c->outstanding5 : 0;
            } else {
                $out4 = $out4;
                $out5 = 0;
            }

            $data[] = [
                'id_customer' => $c->Id,
                'nama_customer' => $c->nama_customer,
                'current' => $current,
                'out1' => $out1,
                'out2' => $out2,
                'out3' => $out3,
                'out4' => $out4,
                'out5' => $out5,
                'total' => $current + $out1 + $out2 + $out3 + $out4 + $out5,
            ];
            // echo $c->nama_customer . ' - ' . $current . ' - ' . $out1 . ' - ' . $out2 . ' - ' . $out3 . ' - ' . $out4 . '<br>';
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        return $data;
        // exit;
    }

    private function calculateOutstandingAmounts($customerId, $jenis_outstanding)
    {
        switch ($jenis_outstanding) {
            case '3':
                $current = $this->getOutstandingAmount($customerId, 'tanggal_invoice > DATE_SUB(NOW(), INTERVAL 30 DAY)');
                $out1 = $this->getOutstandingAmount($customerId, 'tanggal_invoice BETWEEN CURDATE() - INTERVAL 40 DAY AND CURDATE() - INTERVAL 31 DAY');
                $out2 = $this->getOutstandingAmount($customerId, 'tanggal_invoice BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() - INTERVAL 41 DAY');
                $out3 = $this->getOutstandingAmount($customerId, 'tanggal_invoice < CURDATE() - INTERVAL 61 DAY');
                $out4 = 0;
                break;
            case '1':
                $current = $this->getOutstandingAmount($customerId, 'tanggal_invoice > NOW()');
                $out1 = $this->getOutstandingAmount($customerId, 'tanggal_invoice < DATE_SUB(NOW(), INTERVAL 1 DAY)');
                $out2 = 0;
                $out3 = 0;
                $out4 = 0;
                break;
            default:
                $current = $this->getOutstandingAmount($customerId, 'tanggal_invoice > DATE_SUB(NOW(), INTERVAL 15 DAY)');
                $out1 = $this->getOutstandingAmount($customerId, 'tanggal_invoice BETWEEN CURDATE() - INTERVAL 25 DAY AND CURDATE() - INTERVAL 15 DAY');
                $out2 = $this->getOutstandingAmount($customerId, 'tanggal_invoice BETWEEN CURDATE() - INTERVAL 46 DAY AND CURDATE() - INTERVAL 26 DAY');
                $out3 = $this->getOutstandingAmount($customerId, 'tanggal_invoice BETWEEN CURDATE() - INTERVAL 106 DAY AND CURDATE() - INTERVAL 47 DAY');
                $out4 = $this->getOutstandingAmount($customerId, 'tanggal_invoice < CURDATE() - INTERVAL 106 DAY');
                break;
        }

        return [$current, $out1, $out2, $out3, $out4];
    }

    private function getOutstandingAmount($customerId, $dateCondition)
    {
        $query = $this->cb->select_sum('total_nonpph')
            ->select_sum('total_termin')
            ->where('id_customer', $customerId)
            ->where($dateCondition, null, false)
            ->where('status_bayar', '0')
            ->where('status_void', '0')
            ->get('invoice');
        $result = $query->row();

        $total_nonpph = $result->total_nonpph ?? 0;
        $total_termin = $result->total_termin ?? 0;

        return $total_nonpph - $total_termin;
    }

    public function list_nota($limit, $from, $keyword, $customer_id)
    {

        if ($keyword) {
            $this->cb->like('slug', $keyword);
        }

        $invoices = $this->cb->order_by('Id', 'DESC')->limit($limit, $from)->get('nota')->result_array();

        // Ambil semua user dari database bdl_core
        $users = $this->db->select('id, nip, nama')->get('users')->result_array();
        $user_map = array_column($users, 'nama', 'nip');  // Menggunakan nama pengguna sebagai nama kolom

        // Gabungkan hasil query
        foreach ($invoices as &$invoice) {
            $invoice['created_by_name'] = isset($user_map[$invoice['created_by']]) ? $user_map[$invoice['created_by']] : null;
            // $invoice['updated_by_name'] = isset($user_map[$invoice['updated_by']]) ? $user_map[$invoice['updated_by']] : null;
        }

        return $invoices;
    }

    public function insert_nota($nota_data)
    {
        $this->cb->insert('nota', $nota_data);

        // Dapatkan ID nota yang baru saja di-generate
        return $this->cb->insert_id();
    }

    public function insert_nota_batch($detail_data)
    {
        return $this->cb->insert_batch('nota_details', $detail_data);
    }
}
