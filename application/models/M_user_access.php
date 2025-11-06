<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user_access extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all users (adjust 'users' and column names to your actual users table)
    public function get_all_users()
    {
        // Assuming your user table is named 'users' and has 'id' and 'nama'
        // If 'nip' is the user identifier, use 'nip' instead of 'id' in the select and join/where clauses
        return $this->db->select('id, nip, nama, username, email') // Select relevant user fields
            ->from('users')
            ->get()
            ->result();
    }

    // Get a specific user by ID (adjust 'id' to 'nip' if NIP is the primary identifier)
    public function get_user_by_id($user_id)
    {
        return $this->db->get_where('users', ['id' => $user_id])->row(); // Assuming 'id' is the unique identifier for users
        // If NIP is the unique identifier:
        // return $this->db->get_where('users', ['nip' => $user_id])->row();
    }

    public function get_all_menus_hierarchical()
    {

        if (!$this->session->userdata('is_premium')) {
            // $this->db->where_in('premium', '0');
            // $this->db->or_where('premium IS NULL', NULL, FALSE); // Checks for premium IS NULL
        }
        $this->db->order_by('sort_order', 'ASC'); // Order by sort_order
        $query = $this->db->get('menus');
        $menus = $query->result();

        $menu_tree = [];
        $children = [];

        foreach ($menus as $menu) {
            if ($menu->parent_id == 0) { // Assuming 0 means it's a top-level parent menu
                $menu_tree[$menu->Id] = $menu;
                $menu_tree[$menu->Id]->children = []; // Initialize children array
            } else {
                $children[$menu->parent_id][] = $menu; // Store children grouped by parent_id
            }
        }

        // Attach children to their respective parents
        foreach ($children as $parent_id => $child_menus) {
            if (isset($menu_tree[$parent_id])) {
                $menu_tree[$parent_id]->children = $child_menus;
            }
            // Handle orphaned children or sub-children if your hierarchy goes deeper
            // For simplicity, this assumes only one level of children directly under parent_id=0
            // If you have deeper nested menus (parent_id pointing to another child), you'd need a recursive function
        }

        // Convert associative array to indexed array for easier iteration in view if needed
        return array_values($menu_tree);
    }

    // Get all menu items
    public function get_all_menus()
    {
        // You might want to order them hierarchically or by sort_order

        if (!$this->session->userdata('is_premium')) {
            // $this->db->where_in('premium', '0');
            // $this->db->or_where('premium IS NULL', NULL, FALSE); // Checks for premium IS NULL
        }
        return $this->db->order_by('sort_order', 'ASC')
            ->get('menus')
            ->result();
    }

    // Get current menu access for a specific user
    public function get_user_access($user_primary_id) // Renamed parameter for clarity
    {
        // First, get the NIP from the users table using the user's primary ID
        $this->db->select('nip');
        $this->db->from('users');
        $this->db->where('id', $user_primary_id); // Assuming 'id' is the primary key in 'users'
        $user_row = $this->db->get()->row();

        if ($user_row && !empty($user_row->nip)) {
            $user_nip = $user_row->nip;

            // Now, use the obtained NIP to query the user_menu_access table
            return $this->db->get_where('user_menu_access', ['user_id' => $user_nip])->row();
        }

        return null; // Return null if user not found or NIP is empty
    }

    // Save/Update user access
    // This function now expects the PRIMARY KEY of the users table (e.g., 'id')
    // but will use 'nip' to store/update in the user_menu_access table.


    // Save/Update user access
    // public function save_user_access($user_id, $menu_id_string)
    // {
    //     $data = ['menu_id' => $menu_id_string];

    //     // Check if a record already exists for this user_id
    //     $this->db->where('user_id', $user_id);
    //     $query = $this->db->get('user_menu_access');

    //     if ($query->num_rows() > 0) {
    //         // Update existing record
    //         $this->db->where('user_id', $user_id);
    //         return $this->db->update('user_menu_access', $data);
    //     } else {
    //         // Insert new record
    //         $data['user_id'] = $user_id; // Add user_id for insert
    //         return $this->db->insert('user_menu_access', $data);
    //     }
    // }

    public function save_user_access($user_nip, $menu_id_string) // Renamed parameter for clarity
    {
        // First, get the NIP from the users table using the user's primary ID

        $data = ['menu_id' => $menu_id_string];

        // Check if a record already exists for this user_nip in user_menu_access
        $this->db->where('user_id', $user_nip);
        $query = $this->db->get('user_menu_access');

        if ($query->num_rows() > 0) {
            // Update existing record
            $this->db->where('user_id', $user_nip);
            return $this->db->update('user_menu_access', $data);
        } else {
            // Insert new record
            $data['user_id'] = $user_nip; // Add the NIP for insert
            return $this->db->insert('user_menu_access', $data);
        }
    }
}

/* End of file M_user_access.php */
/* Location: ./application/models/M_user_access.php */