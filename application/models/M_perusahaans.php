<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class M_perusahaans extends CI_Model
{

    public function __construct()
    {
        parent::__construct(); // Call the parent constructor
    }

    var $table = 't_cabang';
    var $column_order = array('uid', 'nama_cabang', 'alamat_cabang'); //set column field database for datatable orderable
    var $column_search = array('uid', 'nama_cabang', 'alamat_cabang'); //set column field database for datatable searchable 
    var $order = array('uid' => 'desc'); // default order 

    function _get_datatables_query()
    {

        $this->db->select('t_cabang.*');
        $this->db->from('t_cabang');
        $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            // $this->db->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {

        $this->_get_datatables_query();
        $query = $this->db->get();

        return $this->db->count_all_results();
    }
    public function get_detail_id($id)
    {
        $this->db->select('*'); // Fetch only these columns
        $this->db->from('t_cabang'); // Table name
        $this->db->where('uid', $id);
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }

    var $table1 = 'users';
    var $column_order1 = array('id', 'nama', 'username', 'nip', 'status'); //set column field database for datatable orderable
    var $column_search1 = array('id', 'nama', 'username', 'nip', 'status'); //set column field database for datatable searchable 
    var $order1 = array('id' => 'asc'); // default order 

    function _get_datatables_query1()
    {

        $this->db->select('users.*, t_cabang.nama_cabang'); // Select all from users, and specific columns from t_cabang
        $this->db->from('users');
        $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
        $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
        $i = 0;

        foreach ($this->column_search1 as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search1) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order1)) {
            $order = $this->order1;
            // $this->db->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    function get_datatables1()
    {
        $this->_get_datatables_query1();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered1()
    {
        $this->_get_datatables_query1();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all1()
    {

        $this->_get_datatables_query1();
        $query = $this->db->get();

        return $this->db->count_all_results();
    }

    public function get_detail_id_user($id)
    {
        $this->db->from('users'); // Table name
        $this->db->where('id', $id);
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }
    public function get_detail_id_cabang($id)
    {
        $this->db->from('t_cabang'); // Table name
        $this->db->where('uid', $id);
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }

    public function get_detail_id_perusahaan($id)
    {
        $this->db->from('utility'); // Table name
        $this->db->where('Id', $id);
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }

    public function get_user_counts_by_role()
    {
        $this->db->select('level_jabatan, COUNT(id) as user_count');
        $this->db->from('users');
        $this->db->join('t_cabang', 't_cabang.uid = users.id_cabang');
        $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
        // $this->db->where('id_prsh', $this->session->userdata('user_perusahaan_id'));
        $this->db->group_by('level_jabatan');
        $query = $this->db->get();

        $counts = [];
        foreach ($query->result() as $row) {
            $counts[$row->level_jabatan] = (int)$row->user_count;
        }
        return $counts;
    }

    public function insert_bagian($data)
    {
        $this->db->insert('bagian', $data); // Assuming 'bagian' is your table name

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id(); // Return the ID of the new row
        } else {
            return FALSE; // Return FALSE on failure
        }
    }

    public function banner_count($keyword)
    {
        $this->db->select('Id')->from('banner');
        if ($keyword) {
            $this->db->like('file_name', $keyword, 'both');
        }
        return $this->db->get()->num_rows();
    }

    public function banner_get($limit, $start, $keyword)
    {
        $this->db->select('a.Id, a.file_name, a.file')->from('banner a');
        if ($keyword) {
            $this->db->like('file_name', $keyword, 'both');
        }
        $this->db->order_by('a.Id', 'DESC');
        return $this->db->limit($limit, $start)->get()->result();
    }
}
