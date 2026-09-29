<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_file');
        $this->load->helper(array('download', 'url'));
    }

    // Halaman pencarian dan daftar hasil
    public function search()
    {
        $has_access = $this->M_menu->has_access();
        if (!$has_access) {
            show_error('Forbidden Access: You do not have permission to view this page.', 403, '403 Forbidden');
        }

        $keyword = $this->input->get('keyword', TRUE);
        $data['files'] = array();
        $data['keyword'] = $keyword;

        $data['title'] = 'Search File Memo';
        $data['utility'] = $this->db->get('utility')->row_array();
        $data['pages'] = 'pages/memo/v_file';
        $data['pages_script'] = 'script/memo/s_memo';
        $data['menus'] = $this->M_menu->get_accessible_menus($this->session->userdata('nip'));

        if (!empty($keyword)) {
            $memos = $this->M_file->search_memos_and_files($keyword);

            foreach ($memos as $memo) {
                // Cek apakah kata kunci ada di dalam judul memo
                $title_matched = (stripos($memo['judul'], $keyword) !== FALSE);

                $names = array_filter(explode(';', $memo['attach_name']));
                $encrypted_names = array_filter(explode(';', $memo['attach']));

                foreach ($names as $index => $original_name) {
                    $original_name = trim($original_name);
                    $file_matched = (stripos($original_name, $keyword) !== FALSE);

                    // Jika JUDUL cocok OR NAMA FILE cocok, masukkan ke daftar hasil
                    if ($title_matched || $file_matched) {
                        $encrypted_name = isset($encrypted_names[$index]) ? trim($encrypted_names[$index]) : '';

                        $data['files'][] = array(
                            'memo_id' => $memo['Id'],
                            'memo_judul' => $memo['judul'],
                            'file_index' => $index,
                            'original_name' => $original_name,
                            'encrypted_name' => $encrypted_name,
                            'date_memo' => $memo['tanggal']
                        );
                    }
                }
            }
        }
        $this->load->view('index', $data);
    }

    // Fungsi pengunduhan file
    public function download($memo_id, $file_index)
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '805') !== false) {
            $memo = $this->M_file->get_memo_by_id($memo_id);

            if (!$memo) {
                show_404();
            }

            $names = array_filter(explode(';', $memo['attach_name']));
            $encrypted_names = array_filter(explode(';', $memo['attach']));

            if (isset($names[$file_index]) && isset($encrypted_names[$file_index])) {
                $original_name = trim($names[$file_index]);
                $encrypted_name = trim($encrypted_names[$file_index]);

                // Sesuaikan path lokasi penyimpanan file terenkripsi Anda
                $file_path = FCPATH . 'upload/att_memo/' . $encrypted_name;

                if (file_exists($file_path)) {
                    force_download($original_name, file_get_contents($file_path));
                } else {
                    show_error('File fisik tidak ditemukan di server.', 404);
                }
            } else {
                show_404();
            }
        } else {
            $this->session->set_flashdata('msg_error', 'Forbidden!');
            redirect('home');
        }
    }
}
