<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_check_subscription extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Checks a user's subscription status and updates it if expired.
     *
     * @param int $user_id The ID of the user to check.
     * @return bool True if subscription is active, false otherwise (or if user not found).
     */
    public function check_and_update_subscription($user_perusahaan_id)
    {
        // Get utility data, specifically is_premium and expired_day
        $this->db->select('is_premium, expired_day');
        $this->db->where('Id', $user_perusahaan_id);
        $query = $this->db->get('utility'); // Using 'utility' table as per your query

        if ($query->num_rows() > 0) {
            $utility_data = $query->row();

            // First check: Is the entity marked as premium?
            if ($utility_data->is_premium == 1) {
                // Calculate days remaining
                $expired_timestamp = strtotime($utility_data->expired_day);
                $current_timestamp = time();
                $time_diff = $expired_timestamp - $current_timestamp;
                $days_remaining = floor($time_diff / (60 * 60 * 24));

                // If premium, then check the expiration date
                if ($utility_data->expired_day !== NULL && $expired_timestamp < $current_timestamp) {
                    // *** Subscription has EXPIRED ***
                    $data = array(
                        'kuota_invoice' => 1000,
                        'kuota_memo' => 100,
                        'kuota_pengajuan_biaya' => 1000,
                        'kuota_user' => 5,
                        'kuota_cabang' => 1,
                        'is_premium' => 0,
                        'expired_day' => NULL // Set to NULL after expiration
                    );
                    $this->db->where('Id', $user_perusahaan_id);
                    $this->db->update('utility', $data);

                    // Update the session 'is_premium' to 0
                    $this->session->set_userdata('is_premium', 0);

                    // Set flashdata for expired warning
                    $this->session->set_flashdata('swal_type', 'error');
                    // $this->session->set_flashdata('swal_title', 'Subscription Expired!');
                    // $this->session->set_flashdata('swal_text', 'Your premium subscription has ended. Please renew to continue enjoying premium features.');

                    $this->session->set_flashdata('swal_title', 'Titah Berakhir!'); // "Edict Ended!"
                    $this->session->set_flashdata('swal_text', 'Masa kebangsawanan Anda telah usai, Paduka. Mohon perbaharui titah untuk kembali menikmati hak-hak istimewa.'); // "Your nobility period has ended, Your Majesty. Please renew the edict to regain exclusive rights."


                    log_message('info', 'Perusahaan ID ' . $user_perusahaan_id . ' subscription expired and updated in DB and session.');
                    return FALSE; // Subscription is no longer active

                } else if ($utility_data->expired_day !== NULL && $days_remaining <= 7 && $days_remaining >= 0) {
                    $this->session->set_userdata('Tenggat_waktu', ($days_remaining + 1));

                    // *** Subscription expiring soon (within 7 days) ***
                    // Only show warning if not already expired
                    // $this->session->set_flashdata('swal_type', 'warning');
                    // $this->session->set_flashdata('swal_title', 'Subscription Warning!');
                    // $this->session->set_flashdata('swal_text', 'Your premium subscription will expire in ' . ($days_remaining + 1) . ' day(s)! Renew now to avoid interruption.');

                    // $this->session->set_flashdata('swal_title', 'Peringatan Bangsawan!'); // "Noble Warning!"
                    // $this->session->set_flashdata('swal_text', 'Titah kebangsawanan Anda akan berakhir dalam ' . ($days_remaining + 1) . ' hari! Segera perbaharui agar hak istimewa tetap bersemayam.'); // "Your noble edict will expire in X day(s)! Renew immediately so that exclusive rights remain."


                    log_message('info', 'Perusahaan ID ' . $user_perusahaan_id . ' subscription expiring soon (' . $days_remaining . ' days).');
                    return TRUE; // Subscription is still active for now

                } else {
                    $this->session->set_userdata('is_premium', 1);
                    $this->session->unset_userdata('Tenggat_waktu');
                    // Subscription is active and not expiring soon
                    return TRUE;
                }
            } else {
                // Entity is not premium, no need to check expiration
                return FALSE;
            }
        }
        return FALSE; // Entity not found or other issue
    }

    // Example function to retrieve user data (for demonstration)
    public function get_user_data($user_perusahaan_id)
    {
        $this->db->where('Id', $user_perusahaan_id);
        $query = $this->db->get('utility');
        return $query->row();
    }
}
