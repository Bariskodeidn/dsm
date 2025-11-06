<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lokasi_Presensi_m extends CI_Model
{

    var $table = 'lokasi_presensi';
    var $column_order = array('id', 'nama_lokasi', 'alamat_lokasi', 'radius'); //set column field database for datatable orderable
    var $column_search = array('id', 'nama_lokasi', 'alamat_lokasi', 'radius'); //set column field database for datatable searchable 
    var $order = array('created_at' => 'desc'); // default order 

    function _get_datatables_query()
    {

        $this->db->select('lokasi_presensi.*');
        $this->db->from('lokasi_presensi');
        $this->db->where('id_perusahaan', $this->session->userdata('user_perusahaan_id'));
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
        $this->db->from('lokasi_presensi'); // Table name
        $this->db->where('id', $id);
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }
}
