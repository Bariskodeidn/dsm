<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function tgl_indo($tanggal)
{
  $bulan = array(
    1 =>   'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $pecahkan = explode('-', $tanggal);

  // variabel pecahkan 0 = tanggal
  // variabel pecahkan 1 = bulan
  // variabel pecahkan 2 = tahun

  return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function rupiah($angka)
{
  $hasil = 'Rp' . number_format($angka, 2, ",", ".");
  return $hasil;
}

if (!function_exists('format_indo')) {
  function format_indo($date)
  {
    date_default_timezone_set('Asia/Jakarta');
    // array hari dan bulan
    $Hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    $Bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "October", "November", "Desember");

    // pemisahan tahun, bulan, hari, dan waktu
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl = substr($date, 8, 2);
    $waktu = substr($date, 11, 5);
    $hari = date("w", strtotime($date));
    // $result = $Hari[$hari] . ", " . $tgl . " " . $Bulan[(int)$bulan - 1] . " " . $tahun . " " . $waktu;
    $result = $tgl . " " . $Bulan[(int)$bulan - 1] . " " . $tahun . " " . $waktu;

    return $result;
  }
}

function terbilang($angka)
{
  $angka = floatval($angka);
  $bilangan = [
    '',
    'Satu',
    'Dua',
    'Tiga',
    'Empat',
    'Lima',
    'Enam',
    'Tujuh',
    'Delapan',
    'Sembilan',
    'Sepuluh',
    'Sebelas'
  ];

  if ($angka < 12) {
    return $bilangan[$angka];
  } else if ($angka < 20) {
    return $bilangan[$angka - 10] . ' Belas';
  } else if ($angka < 100) {
    return $bilangan[floor($angka / 10)] . ' Puluh ' . $bilangan[$angka % 10];
  } else if ($angka < 200) {
    return 'Seratus ' . terbilang($angka - 100);
  } else if ($angka < 1000) {
    return $bilangan[floor($angka / 100)] . ' Ratus ' . terbilang($angka % 100);
  } else if ($angka < 2000) {
    return 'Seribu ' . terbilang($angka - 1000);
  } else if ($angka < 1000000) {
    return terbilang(floor($angka / 1000)) . ' Ribu ' . terbilang($angka % 1000);
  } else if ($angka < 1000000000) {
    return terbilang(floor($angka / 1000000)) . ' Juta ' . terbilang($angka % 1000000);
  } else if ($angka < 1000000000000) {
    return terbilang(floor($angka / 1000000000)) . ' Miliar ' . terbilang($angka % 1000000000);
  } else if ($angka < 1000000000000000) {
    return terbilang(floor($angka / 1000000000000)) . ' Triliun ' . terbilang($angka % 1000000000000);
  } else if ($angka < 1000000000000000000) {
    return terbilang(floor($angka / 1000000000000000)) . ' Kuadriliun ' . terbilang($angka % 1000000000000000);
  } else {
    return 'Angka terlalu besar';
  }
}

function potong_nama($string, $limit)
{
  // Pastikan input adalah string dan limit adalah integer positif
  if (!is_string($string) || !is_numeric($limit) || $limit <= 0) {
    return $string;
  }

  // Cek apakah string lebih panjang dari limit
  if (mb_strlen($string, 'UTF-8') > $limit) {
    // Potong string ke limit yang ditentukan
    $string = mb_substr($string, 0, $limit, 'UTF-8') . '...';
  }

  return $string;
}
