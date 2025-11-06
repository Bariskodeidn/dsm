<?php if (!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem..!!');

class M_coa extends CI_Model
{
    // $this->cb untuk koneksi ke database corebank


    protected $cb; // Declare the property

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor
        $this->cb = $this->load->database('corebank', TRUE);
    }

    private function apply_cabang_filter()
    {
        $kode_cabang = $this->session->userdata('kode_cabang');
        return $this->cb->where('id_cabang', $kode_cabang);
    }

    public function list_coa()
    {
        return $this->apply_cabang_filter()->order_by('no_sbb', 'ASC')->get('v_coa_all')->result();
    }

    public function cek_coa($no_coa)
    {
        return $this->apply_cabang_filter()->select('posisi, nominal')->where('no_sbb', $no_coa)->get('v_coa_all')->row_array();
    }

    public function update_nominal_coa($no_coa, $data, $kolom, $tabel)
    {
        return $this->apply_cabang_filter()->where($kolom, $no_coa)->update($tabel, $data);
    }

    function update_nominal_coa_new($no_coa, $nominal, $kolom, $tabel, $operator)
    {
        $this->db->set('nominal', "nominal {$operator} {$nominal}", false);
        $this->db->where($kolom, $no_coa);
        $this->db->update($tabel);
    }

    public function add_transaksi($data)
    {
        return $this->cb->insert('t_log_transaksi', $data);
    }

    public function addJurnal($data)
    {
        return $this->cb->insert('jurnal_neraca', $data);
    }

    public function getNeraca($table, $posisi)
    {
        return $this->apply_cabang_filter()->where('nominal !=', '0')->where('posisi', $posisi)->get($table)->result();
    }

    public function getSumNeraca($table, $posisi)
    {
        return $this->cb->select_sum('nominal')->where('posisi', $posisi)->get($table)->row_array();
    }

    public function getPasivaWithLaba($table)
    {
        // $pasiva = $this->cb->where('posisi', 'PASIVA')->group_start()->where('nominal !=', '0')->or_where('no_sbb', '32020')->group_end()->get($table)->result();
        $pasiva = $this->cb->where('posisi', 'PASIVA')->where('nominal !=', '0')->or_where('no_sbb', '32020')->get($table)->result();
        // $total_activa = $this->getSumNeraca($table, 'AKTIVA')['nominal'];

        // foreach ($pasiva as &$row) {
        //     if ($row->no_sbb == '32020') { // Special handling for 'LABA'
        //         $row->nominal = $total_activa;
        //     }
        // }

        // echo '<pre>';
        // print_r($pasiva);
        // echo '</pre>';
        // exit;
        return $pasiva;
    }

    public function getCoaReport($no_coa, $from, $to, $keyword = null)
    {
        $this->cb->select('*');
        $this->cb->from('jurnal_neraca');

        if ($from) {
            $this->cb->where('tanggal >=', $from);
            $this->cb->where('tanggal <=', $to);
        }

        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));

        if ($no_coa != 'ALL') {
            $this->cb->group_start();
            $this->cb->where('akun_debit', $no_coa);
            $this->cb->or_where('akun_kredit', $no_coa);
            $this->cb->group_end();
        }

        if ($keyword) {
            $this->cb->group_start();
            $this->cb->like('akun_debit', $keyword);
            $this->cb->or_like('akun_kredit', $keyword);
            $this->cb->or_like('jumlah_debit', $keyword);
            $this->cb->or_like('keterangan', $keyword);
            $this->cb->group_end();
        }
        // $this->cb->order_by('tanggal', 'ASC');
        $this->cb->order_by('tanggal', 'DESC');
        $this->cb->order_by('Id', 'DESC');
        $query = $this->cb->get();

        $result = $query->result();

        return $result;
    }

    public function getCoa($no_coa)
    {
        return $this->cb->where('no_sbb', $no_coa)->where('id_cabang', $this->session->userdata('kode_cabang'))->get('v_coa_all')->row_array();
    }

    public function getCoaBB($no_coa)
    {
        if ($no_coa == "ALL") {
            $this->cb->select('nama_perkiraan, no_bb');
            return $this->cb->get('v_coabb_all')->result();
        } else {
            return $this->cb->where('no_bb', $no_coa)->get('v_coabb_all')->row_array();
        }
    }

    public function getCoaByCode($code = NULL)
    {
        $this->apply_cabang_filter();

        if ($code) {
            $this->cb->like('no_sbb', $code, 'after');
        }

        return $this->cb->get('v_coa_all')->result();
    }

    public function simpanLaporan($data)
    {
        return $this->cb->insert('t_log_neraca', $data);
    }

    public function count_laporan($jenis)
    {
        return $this->cb->from('t_log_neraca')->where('jenis', $jenis)->count_all_results();
    }

    public function list_laporan($jenis, $limit, $from)
    {
        $laporan = $this->cb->where('jenis', $jenis)->order_by('tanggal_simpan', 'DESC')->limit($limit, $from)->get('t_log_neraca')->result_array();

        // Ambil semua user dari database bdl_core
        $users = $this->db->select('id, nip, nama')->get('users')->result_array();
        $user_map = array_column($users, 'nama', 'nip');  // Menggunakan nama pengguna sebagai nama kolom

        // Gabungkan hasil query
        foreach ($laporan as &$lp) {
            $lp['created_by_name'] = isset($user_map[$lp['created_by']]) ? $user_map[$lp['created_by']] : null;
        }

        return $laporan;
    }

    public function showNeraca($slug)
    {
        return $this->cb->where('slug', $slug)->get('t_log_neraca')->row_array();
    }

    public function select_max($jenis)
    {
        return $this->cb->select('max(no_urut) as max')->where('jenis', $jenis)->get('t_log_neraca')->row_array();
    }

    public function count($keyword, $cabang, $tabel)
    {
        // $this->apply_cabang_filter();
        // if ($keyword) {
        //     $this->cb->like('no_sbb', $keyword);
        //     $this->cb->or_like('no_bb', $keyword);
        //     $this->cb->or_like('nama_perkiraan', $keyword);
        // }
        // return $this->cb->from($tabel)->count_all_results();
        if ($keyword) {
            // Start a new group for the OR conditions
            $this->cb->group_start();
            $this->cb->like('no_sbb', $keyword);
            $this->cb->or_like('no_bb', $keyword);
            $this->cb->or_like('nama_perkiraan', $keyword);
            // End the group
            $this->cb->group_end();
        }
        return $this->cb->from($tabel)->where('id_cabang', $cabang)->count_all_results();
    }

    public function list_coa_paginate($limit, $from, $keyword, $cabang)
    {
        // $this->cb;
        // $this->apply_cabang_filter();

        // $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        // if ($keyword) {
        //     $this->cb->like('no_sbb', $keyword);
        //     $this->cb->or_like('no_bb', $keyword);
        //     $this->cb->or_like('nama_perkiraan', $keyword);
        // }

        // return $this->cb->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_all')->result_array();

        // $laporan = $this->apply_cabang_filter()->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_alls')->result_array();
        // return $laporan;

        // return $this->cb->where('id_cabangs', $cabang)->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_all')->result_array();

        if ($keyword) {
            // Start a new group for the OR conditions
            $this->cb->group_start();
            $this->cb->like('no_sbb', $keyword);
            $this->cb->or_like('no_bb', $keyword);
            $this->cb->or_like('nama_perkiraan', $keyword);
            // End the group
            $this->cb->group_end();
        }

        $laporan = $this->cb->where('id_cabang', $cabang)->order_by(
            'no_sbb',
            'ASC'
        )->limit($limit, $from)->get('v_coa_all')->result_array();

        return $laporan;
    }

    public function count_bb($keyword, $perusahaan, $tabel)
    {
        // $this->apply_cabang_filter();
        // if ($keyword) {
        //     $this->cb->like('no_sbb', $keyword);
        //     $this->cb->or_like('no_bb', $keyword);
        //     $this->cb->or_like('nama_perkiraan', $keyword);
        // }
        // return $this->cb->from($tabel)->count_all_results();
        if ($keyword) {
            // Start a new group for the OR conditions
            $this->cb->group_start();
            $this->cb->or_like('no_bb', $keyword);
            $this->cb->or_like('nama_perkiraan', $keyword);
            // End the group
            $this->cb->group_end();
        }
        return $this->cb->from($tabel)->where('id_company', $perusahaan)->count_all_results();
    }

    public function list_coa_bb_paginate($limit, $from, $keyword, $perusahaan)
    {
        // $this->cb;
        // $this->apply_cabang_filter();

        // $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        // if ($keyword) {
        //     $this->cb->like('no_sbb', $keyword);
        //     $this->cb->or_like('no_bb', $keyword);
        //     $this->cb->or_like('nama_perkiraan', $keyword);
        // }

        // return $this->cb->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_all')->result_array();

        // $laporan = $this->apply_cabang_filter()->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_alls')->result_array();
        // return $laporan;

        // return $this->cb->where('id_cabangs', $cabang)->order_by(
        //     'no_sbb',
        //     'ASC'
        // )->limit($limit, $from)->get('v_coa_all')->result_array();

        if ($keyword) {
            // Start a new group for the OR conditions
            $this->cb->group_start();
            $this->cb->or_like('no_bb', $keyword);
            $this->cb->or_like('nama_perkiraan', $keyword);
            // End the group
            $this->cb->group_end();
        }

        $laporan = $this->cb->where('id_company', $perusahaan)->order_by(
            'no_bb',
            'ASC'
        )->limit($limit, $from)->get('v_coabb_all')->result_array();

        return $laporan;
    }

    public function isAvailableBB($kolom, $key)
    {
        // $this->apply_cabang_filter();
        return $this->cb->from('v_coabb_all')->where('id_company', $this->session->userdata('user_perusahaan_id'))->where($kolom, $key)->count_all_results();
    }

    public function isAvailable($kolom, $key)
    {
        $this->apply_cabang_filter();
        return $this->cb->from('v_coa_all')->where($kolom, $key)->count_all_results();
    }

    public function list_saldo()
    {
        $this->apply_cabang_filter();
        return $this->cb->order_by('periode', 'DESC')->get('saldo_awal')->result();
    }

    public function showSaldo($slug)
    {
        return $this->cb->where('slug', $slug)->get('saldo_awal')->row_array();
    }

    public function showDetailSaldo($id)
    {
        return $this->cb->from('saldo_awal_detail s')->join('v_coa_all v', 's.no_sbb = v.no_sbb')->where('id_saldo_awal', $id)->get()->result();
    }

    // Fungsi untuk menyimpan saldo awal ke tabel saldo_awal_neraca
    public function insert_saldo_awal($data)
    {
        return $this->cb->insert('saldo_awal', $data);
    }

    public function update_saldo_awal($periode, $data)
    {
        return $this->cb->where('periode', $periode)->update('saldo_awal', $data);
    }

    // Fungsi untuk mendapatkan saldo awal berdasarkan bulan tertentu
    public function get_saldo_awal($bulan)
    {
        $this->cb->select('*');
        $this->cb->from('saldo_awal');
        $this->cb->where('periode', $bulan);
        $query = $this->cb->get();
        return $query->row_array();
    }

    public function calculate_saldo_awal($bulan, $tahun)
    {
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;
        $kode_cabang = $this->session->userdata('kode_cabang');

        $query = $this->cb->query("
            SELECT 
                coa.no_sbb, coa.nama_perkiraan, coa.posisi, coa.table_source,
                SUM(
                    CASE 
                        WHEN jn.akun_debit = jn.akun_kredit THEN 0
                        WHEN coa.posisi = 'AKTIVA' AND jn.akun_debit = coa.no_sbb THEN jn.jumlah_debit
                        WHEN coa.posisi = 'AKTIVA' AND jn.akun_kredit = coa.no_sbb THEN -jn.jumlah_kredit
                        WHEN coa.posisi = 'PASIVA' AND jn.akun_kredit = coa.no_sbb THEN jn.jumlah_kredit
                        WHEN coa.posisi = 'PASIVA' AND jn.akun_debit = coa.no_sbb THEN -jn.jumlah_debit
                        ELSE 0
                    END
                ) AS saldo_awal
            FROM 
                v_coa_all coa
            LEFT JOIN 
                jurnal_neraca jn ON coa.no_sbb = jn.akun_debit OR coa.no_sbb = jn.akun_kredit
            WHERE 
                jn.id_cabang = '$kode_cabang' AND 
                coa.id_cabang = '$kode_cabang' AND 
                MONTH(jn.tanggal) = '$bulan' AND YEAR(jn.tanggal) = '$tahun'
            GROUP BY 
                coa.no_sbb
            ORDER BY 
                coa.no_sbb ASC
        ");
        // echo '<pre>';
        // print_r($query->result_array());
        // echo '</pre>';
        // exit;
        return $query->result();
    }

    public function cek_saldo_awal($bulan)
    {
        $this->apply_cabang_filter();
        return $this->cb->where('periode', $bulan)->where('id_cabang', $this->session->userdata('kode_cabang'))->get('saldo_awal')->row_array();
    }

    public function getNeracaByDate($table, $posisi, $tanggal_akhir)
    {
        $date = new DateTime($tanggal_akhir);

        $tanggal_awal = $date->format('Y-m') . '-01';
        $kode_cabang = $this->session->userdata('kode_cabang');

        if ($posisi == "AKTIVA") {

            $query = $this->cb->query("
            SELECT 
                coa.no_sbb, coa.nama_perkiraan, coa.posisi,
                SUM(
                    CASE 
                        WHEN jn.akun_debit = jn.akun_kredit THEN 0
                        WHEN coa.posisi = 'AKTIVA' AND jn.akun_debit = coa.no_sbb THEN jn.jumlah_debit
                        WHEN coa.posisi = 'AKTIVA' AND jn.akun_kredit = coa.no_sbb THEN -jn.jumlah_kredit
                        ELSE 0
                    END
                ) AS saldo_awal
            FROM 
                v_coa_all coa
            LEFT JOIN 
                jurnal_neraca jn ON coa.no_sbb = jn.akun_debit OR coa.no_sbb = jn.akun_kredit
            WHERE 
                jn.id_cabang = '$kode_cabang' AND 
                coa.id_cabang = '$kode_cabang' AND
                jn.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                AND coa.table_source = '$table' AND coa.posisi = '$posisi'
            GROUP BY 
                coa.no_sbb
            ORDER BY 
                coa.no_sbb ASC
        ");
        } else if ($posisi == "PASIVA") {

            $query = $this->cb->query("
            SELECT 
                coa.no_sbb, coa.nama_perkiraan, coa.posisi,
                SUM(
                    CASE 
                        WHEN jn.akun_debit = jn.akun_kredit THEN 0
                        WHEN coa.posisi = 'PASIVA' AND jn.akun_kredit = coa.no_sbb THEN jn.jumlah_kredit
                        WHEN coa.posisi = 'PASIVA' AND jn.akun_debit = coa.no_sbb THEN -jn.jumlah_debit
                        ELSE 0
                    END
                ) AS saldo_awal
            FROM 
                v_coa_all coa
            LEFT JOIN 
                jurnal_neraca jn ON coa.no_sbb = jn.akun_debit OR coa.no_sbb = jn.akun_kredit
            WHERE 
                jn.id_cabang = '$kode_cabang' AND 
                coa.id_cabang = '$kode_cabang' AND
                jn.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                AND coa.table_source = '$table' AND coa.posisi = '$posisi'
            GROUP BY 
                coa.no_sbb
            ORDER BY 
                coa.no_sbb ASC
        ");
        }

        return $query->result();
    }

    public function list_coa_paginate_financial_first($limit, $from, $keyword)
    {
        $this->apply_cabang_filter();
        if ($keyword) {
            $this->cb->like('no_sbb', $keyword);
            $this->cb->or_like('no_bb', $keyword);
            $this->cb->or_like('nama_perkiraan', $keyword);
        }
        $laporan = $this->cb->order_by(
            'no_sbb',
            'ASC'
        )->limit($limit, $from)->get('t_coa_sbb_gabungan')->result_array();

        return $laporan;
    }

    var $table1 = 't_coa_sbb_gabungan';
    var $column_order1 = array('no_bb', 'no_sbb', 'nama_perkiraan'); //set column field database for datatable orderable
    var $column_search1 = array('no_bb', 'no_sbb', 'nama_perkiraan'); //set column field database for datatable searchable 
    var $order1 = array('no_bb' => 'asc'); // default order 

    function _get_datatables_query1()
    {

        $this->cb->select('t_coa_sbb_gabungan.*'); // Select all from t_coa_sbb_gabungan, and specific columns from t_cabang
        $this->cb->from('t_coa_sbb_gabungan');
        $i = 0;

        foreach ($this->column_search1 as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->cb->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->cb->like($item, $_POST['search']['value']);
                } else {
                    $this->cb->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search1) - 1 == $i) //last loop
                    $this->cb->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->cb->order_by($this->column_order1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order1)) {
            $order = $this->order1;
            // $this->cb->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->cb->order_by($key, $value);
            }
        }
    }

    function get_datatables1()
    {
        $this->_get_datatables_query1();
        if ($_POST['length'] != -1)
            $this->cb->limit($_POST['length'], $_POST['start']);
        $query = $this->cb->get();
        return $query->result();
    }

    function count_filtered1()
    {
        $this->_get_datatables_query1();
        $query = $this->cb->get();
        return $query->num_rows();
    }

    function count_all1()
    {

        $this->_get_datatables_query1();
        $query = $this->cb->get();

        return $this->cb->count_all_results();
    }

    var $table1_bb = 't_coa_bb_gabungan';
    var $column_order1_bb = array('no_bb', 'nama_perkiraan'); //set column field database for datatable orderable
    var $column_search1_bb = array('no_bb', 'nama_perkiraan'); //set column field database for datatable searchable 
    var $order1_bb = array('no_bb' => 'asc'); // default order 

    function _get_datatables_query1_bb()
    {

        $this->cb->select('t_coa_bb_gabungan.*'); // Select all from t_coa_sbb_gabungan, and specific columns from t_cabang
        $this->cb->from('t_coa_bb_gabungan');
        $i = 0;

        foreach ($this->column_search1_bb as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->cb->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->cb->like($item, $_POST['search']['value']);
                } else {
                    $this->cb->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search1_bb) - 1 == $i) //last loop
                    $this->cb->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->cb->order_by($this->column_order1_bb[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order1_bb)) {
            $order = $this->order1_bb;
            // $this->cb->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->cb->order_by($key, $value);
            }
        }
    }

    function get_datatables1_bb()
    {
        $this->_get_datatables_query1_bb();
        if ($_POST['length'] != -1)
            $this->cb->limit($_POST['length'], $_POST['start']);
        $query = $this->cb->get();
        return $query->result();
    }

    function count_filtered1_bb()
    {
        $this->_get_datatables_query1_bb();
        $query = $this->cb->get();
        return $query->num_rows();
    }

    function count_all1_bb()
    {

        $this->_get_datatables_query1_bb();
        $query = $this->cb->get();

        return $this->cb->count_all_results();
    }

    function get_coa_activa_by_cabang()
    {
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'AKTIVA');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->result();
    }

    function get_coa_pasiva_by_cabang()
    {
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'PASIVA');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->result();
    }

    function get_sum_coa_activa_by_cabang()
    {
        $this->cb->select('SUM(nominal) as nominal');
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'AKTIVA');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->row();
    }

    function get_sum_coa_pasiva_by_cabang()
    {
        $this->cb->select('SUM(nominal) as nominal');
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'PASIVA');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->row();
    }


    function get_sum_coa_pasiva_coalr_by_cabang()
    {
        $this->cb->select('SUM(nominal) as nominal');
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'AKTIVA');
        $this->cb->where('table_source', 't_coalr_sbb');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->row();
    }

    function get_sum_coa_activa_coalr_by_cabang()
    {
        $this->cb->select('SUM(nominal) as nominal');
        $this->cb->from('v_coa_all');
        $this->cb->where('posisi', 'PASIVA');
        $this->cb->where('table_source', 't_coalr_sbb');
        $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
        return $this->cb->get()->row();
    }

    public function list_coa_with_nominal()
    {
        return $this->apply_cabang_filter()->where('nominal !=', 0)->order_by('no_sbb', 'ASC')->get('v_coa_all')->result();
    }

    public function getNeracaConsolByDate($table, $posisi, $tanggal_akhir, $id_company)
    {
        $date = new DateTime($tanggal_akhir);

        $tanggal_awal = $date->format('Y-m') . '-01';

        $id_cabang = $this->session->userdata('id_cabang');

        if ($posisi == "AKTIVA") {

            $query = $this->cb->query("
                SELECT 
                    coa.no_sbb, coa.nama_perkiraan, coa.posisi,
                    SUM(
                        CASE 
                            WHEN jn.akun_debit = jn.akun_kredit THEN 0
                            WHEN coa.posisi = 'AKTIVA' AND jn.akun_debit = coa.no_sbb THEN jn.jumlah_debit
                            WHEN coa.posisi = 'AKTIVA' AND jn.akun_kredit = coa.no_sbb THEN -jn.jumlah_kredit
                            ELSE 0
                        END
                    ) AS saldo_awal
                FROM 
                    (SELECT DISTINCT no_sbb, nama_perkiraan, posisi FROM v_coa_all WHERE id_company = '$id_company' AND table_source = '$table' AND posisi = '$posisi') coa
                LEFT JOIN 
                    jurnal_neraca jn ON coa.no_sbb = jn.akun_debit OR coa.no_sbb = jn.akun_kredit
                WHERE 
                    jn.id_company = '$id_company' AND 
                    jn.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                GROUP BY 
                    coa.no_sbb
                ORDER BY 
                    coa.no_sbb ASC
            ");
        } else if ($posisi == "PASIVA") {

            $query = $this->cb->query("
                SELECT 
                    coa.no_sbb, coa.nama_perkiraan, coa.posisi,
                    SUM(
                        CASE 
                            WHEN jn.akun_debit = jn.akun_kredit THEN 0
                            WHEN coa.posisi = 'PASIVA' AND jn.akun_kredit = coa.no_sbb THEN jn.jumlah_kredit
                            WHEN coa.posisi = 'PASIVA' AND jn.akun_debit = coa.no_sbb THEN -jn.jumlah_debit
                            ELSE 0
                        END
                    ) AS saldo_awal
                FROM 
                    (SELECT DISTINCT no_sbb, nama_perkiraan, posisi FROM v_coa_all WHERE id_company = '$id_company' AND table_source = '$table' AND posisi = '$posisi') coa
                LEFT JOIN 
                    jurnal_neraca jn ON coa.no_sbb = jn.akun_debit OR coa.no_sbb = jn.akun_kredit
                WHERE 
                    jn.id_company = '$id_company' AND 
                    jn.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                GROUP BY 
                    coa.no_sbb
                ORDER BY 
                    coa.no_sbb ASC
        ");

            // 

            // WHEN coa.posisi = 'PASIVA' AND jn.akun_kredit = coa.no_sbb THEN jn.jumlah_kredit
            // WHEN coa.posisi = 'PASIVA' AND jn.akun_debit = coa.no_sbb THEN -jn.jumlah_debit
        }

        return $query->result();
    }
}
