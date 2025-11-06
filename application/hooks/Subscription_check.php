<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Subscription_check extends CI_Controller
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('M_check_subscription'); // Load your user model
    }

    public function check_user_subscription()
    {
        // Only run this check if a user is logged in (has a user ID in session)
        if ($this->CI->session->userdata('user_perusahaan_id')) {
            $user_perusahaan_id = $this->CI->session->userdata('user_perusahaan_id');
            $this->CI->M_check_subscription->check_and_update_subscription($user_perusahaan_id);
        }
    }
}
