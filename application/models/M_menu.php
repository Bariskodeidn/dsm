<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_menu extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function get_accessible_menus($user_id)
  {
    $menu_access = $this->db->select('user_id,menu_id')->from('user_menu_access')->where('user_id', $user_id)->get()->row();
    if ($menu_access) {
      $where_in = explode(',', $menu_access->menu_id);
    } else {
      $where_in = array('0');
    }

    // // Fetch menus that the user has access to
    $this->db->select('menus.*');
    $this->db->from('menus, user_menu_access');
    $this->db->where('user_menu_access.user_id', $user_id);
    $this->db->where('menus.is_active', 1);
    $this->db->where_in('menus.id', $where_in);
    // if (!$this->session->userdata('is_premium')) {
    //   $this->db->where_in('menus.premium', '0');
    // }
    $this->db->order_by('menus.sort_order', 'ASC');
    $query = $this->db->get();
    $menus = $query->result();

    $menu_tree = [];
    foreach ($menus as $menu) {
      if ($menu->parent_id == 0) {
        $menu->submenus = [];
        $menu_tree[$menu->Id] = $menu;
      } else {
        $menu_tree[$menu->parent_id]->submenus[] = $menu;
      }
    }

    return $menu_tree;
  }

  public function has_access()
  {
    $nip = $this->session->userdata('nip');

    $menu_access = $this->db->select('user_id,menu_id')->from('user_menu_access')->where('user_id', $nip)->get()->row();
    if ($menu_access) {
      $where_in = explode(',', $menu_access->menu_id);
    } else {
      $where_in = array('0');
    }
    $url = uri_string();
    $this->db->select('m.*');
    $this->db->from('menus m, user_menu_access uma');
    // $this->db->join('user_menu_access uma', 'm.id = uma.menu_id');
    $this->db->where_in('m.id', $where_in);
    $this->db->where('uma.user_id', $nip);
    $this->db->where('m.url', $url);
    return $this->db->get()->row(); // kembalikan baris jika ada akses
  }

  public function get_allowed_routes($nip)
  {
    $menu_access = $this->db->select('user_id,menu_id')->from('user_menu_access')->where('user_id', $nip)->get()->row();
    if ($menu_access) {
      $where_in = explode(',', $menu_access->menu_id);
    } else {
      $where_in = array('0');
    }

    $this->db->select('url');
    $this->db->from('menus');
    $this->db->where_in('menus.id', $where_in);

    $query = $this->db->get();
    return array_column($query->result_array(), 'url');
  }
}
