<?php if (!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem..!!');

class M_cuti extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getJenisCuti()
    {
        $nipSession = $this->session->userdata('nip');
        $user = $this->db->get_where('users', ['nip' => $nipSession])->row();

        // Menghitung jumlah masa kerja karyawan
        $masuk = new DateTime($user->tmt);
        $sekarang = new DateTime('today');
        $tahun = $sekarang->diff($masuk)->y;

        // if ($tahun < 1) {
        //     $this->db->where('Id', 7);
        // }

        // if ($tahun < 5) {
        //     $this->db->where('Id !=', 2);
        // } else {
        if ($user->cuti_panjang == 1) {
            if ($tahun % 5 != 0) {
                $this->db->where('Id !=', 2);
            } else {
                $this->db->order_by('tgl_cuti', 'DESC');
                $this->db->limit(1);
                $cutiPanjang = $this->db->get_where('cuti', ['nip' => $user->nip])->row();

                if (date('Y', strtotime($cutiPanjang->tgl_cuti)) == date('Y')) {
                    $this->db->where('Id !=', 2);
                }
            }
        }
        // }

        // Mengecek jika karyawan pernah mengambil cuti haji
        if ($user->cuti_haji == 1) {
            $this->db->where('Id !=', 5);
        }
        // Mengecek jika karyawan pernah mengambil cuti perjalanan spiritual
        if ($user->cuti_spiritual == 1) {
            $this->db->where('Id !=', 4);
        }
        // $this->db->where('id_perusahaan', $this->session->userdata('user_perusahaan_id'));

        return $this->db->get('jenis_cuti')->result_array();
    }

    public function get_all_jenis_cuti()
    {
        // $this->db->where('id_perusahaan',    $this->session->userdata('user_perusahaan_id'));
        return $this->db->get('jenis_cuti')->result_array();
    }

    public function getDetailByJenis($idJenis)
    {
        $detail =  $this->db->get_where('sub_jenis_cuti', ['parents' => $idJenis]);
        $data = "<option value=''>-- Detail Cuti --</option>";
        if ($detail->num_rows() < 1) {
            $data = 0;
        } else {
            foreach ($detail->result_array() as $row) {
                $data .= "<option value='$row[Id]'>$row[nama_sub_jenis]</option>";
            }
        }
        return $data;
    }

    public function get_detail_by_jenis($idJenis)
    {
        $this->db->where('Id !=', 11); // cuti tanpa keterangan
        $detail =  $this->db->get_where('sub_jenis_cuti', ['parents' => $idJenis]);
        $data = "<option value=''>-- Detail Cuti --</option>";
        if ($detail->num_rows() < 1) {
            $data = 0;
        } else {
            foreach ($detail->result_array() as $row) {
                $data .= "<option value='$row[Id]'>$row[nama_sub_jenis]</option>";
            }
        }
        return $data;
    }

    public function getDataJenis($id)
    {
        return $this->db->get_where('jenis_cuti', ['Id' => $id])->row_array();
    }

    public function getDataDetail($idDetail)
    {
        return $this->db->get_where('sub_jenis_cuti', ['Id' => $idDetail])->row();
    }

    public function insertCuti($table, $params)
    {
        $this->db->insert($table, $params);
    }

    public function insert_cuti_manual($table, $params)
    {
        $this->db->insert($table, $params);
    }

    public function getDataCuti($id)
    {
        return $this->db->get_where('cuti', ['id_cuti' => $id])->row_array();
    }

    public function updateAtasan($params, $where)
    {
        $this->db->set($params);
        $this->db->where($where);
        $this->db->update('cuti');
    }

    public function statusHrd($params, $where)
    {
        $this->db->set($params);
        $this->db->where($where);
        $this->db->update('cuti');
    }

    public function approveDireksi($params, $where)
    {
        $this->db->set($params);
        $this->db->where($where);
        $this->db->update('cuti');
    }

    public function getAllCutiByNip($nip)
    {
        return $this->db->get_where('cuti', ['nip' => $nip])->result_array();
    }

    public function detailCutiById($table, $where)
    {
        return $this->db->get_where($table, $where)->row_array();
    }

    public function historyCutiById($id)
    {
        $cuti = $this->db->get_where('cuti', ['id_cuti' => $id])->row();
        $sql = "SELECT * FROM cuti WHERE nip = '$cuti->nip' AND status_atasan = 'Disetujui' ORDER BY id_cuti DESC";
        $data = $this->db->query($sql);
        return $data->result_array();
    }

    public function getKaryawan()
    {
        $this->db->where([
            'bagian !=' => null,
            'level_jabatan !=' => null
        ]);
        return $this->db->get('users')->result();
    }

    public function getBagian()
    {
        // $this->db->where();
        $this->db->where('id_prsh', $this->session->userdata('user_perusahaan_id'));
        return $this->db->get('bagian')->result();
    }
}
