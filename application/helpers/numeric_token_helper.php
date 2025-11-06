<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('generate_numeric_token')) {
    /**
     * Generate a cryptographically secure random numeric token of a specified length.
     *
     * @param int $length The desired length of the token (e.g., 5 for 5 digits).
     * @return string The generated numeric token.
     * @throws Exception If a sufficient source of randomness cannot be found.
     */
    function generate_numeric_token($length = 5)
    {
        // Hitung nilai minimum dan maksimum untuk token dengan panjang yang diminta
        // Contoh: untuk length 5, min = 10000, max = 99999
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;

        try {
            // random_int() menghasilkan angka acak yang aman secara kriptografis
            return (string) random_int($min, $max);
        } catch (Exception $e) {
            // Log error jika random_int gagal (jarang terjadi)
            log_message('error', 'Gagal membuat Token numerik aman: ' . $e->getMessage());

            // Sebagai fallback (kurang aman, hanya jika random_int benar-benar tidak bisa digunakan)
            // Anda bisa memilih untuk melempar exception atau mengembalikan nilai default/false
            // Untuk token verifikasi, lebih baik gagal daripada menggunakan token yang lemah.
            // return (string) mt_rand($min, $max); // Fallback ke mt_rand
            throw new Exception('Tidak dapat membuat Token verifikasi aman.');
        }
    }
}
