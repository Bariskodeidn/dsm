<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kwitansi</title>

  <style>
    * {
      font-family: 'Calibri';
      font-size: 10pt;
    }

    .border-full {
      border: 2px solid black;
    }

    .border-right {
      border-right: 2px solid black;
    }

    .border-left {
      border-left: 2px solid black;
    }

    .border-bottom {
      border-bottom: 2px solid black;
    }

    .border-top {
      border-top: 2px solid black;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .bg-yellow {
      background-color: yellow;
    }

    .bg-blue {
      background-color: #00B0F0;
    }

    .text-bold {
      font-weight: bolder;
    }

    table {
      border-collapse: collapse;
    }
  </style>
</head>

<body>
  <?php
  $jual = json_decode($pda['harga_jual']);
  $agency_remuneration_jual = $jual->agency_remuneration;
  $desc_jual = $jual->desc;

  $search_index = array_search($this->uri->segment(4), $agency_remuneration_jual->desc);
  $item = $this->db->get_where('t_item_pda', ['Id' => $agency_remuneration_jual->desc[$search_index]])->row_array();

  $penunjukan = $this->db->select('agency')->from('t_penunjukan')->where('Id', $pda['penunjukan'])->get()->row_array();
  $agent = $this->db->select('kode')->from('agent')->where('Id', $penunjukan['agency'])->get()->row_array();
  ?>
  <table width="100%">
    <tr>
      <td class="border-full text-center"><b>Kwitansi Pembayaran</b></td>
      <td colspan="2" class="border-full text-center"><b>No. Voucher : <?= sprintf("%03d", $search_index + 1) . '/' . $agent['kode'] . '/' . 'ADM/' . date('Y') ?></b></td>
    </tr>
    <tr>
      <td colspan="3" class="border-full">
        <br>
      </td>
    </tr>
    <tr>
      <td class="border-full">Tujuan Penggunaan:</td>
      <td class="border-full">Jumlah (Rp):</td>
      <td class="border-full text-right"><?= number_format(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$search_index]) * ($agency_remuneration_jual->qty[$search_index] ? $agency_remuneration_jual->qty[$search_index] : 1)) ?></td>
    </tr>
    <tr>
      <td colspan="2" class="border-left"><br><?= $item['desc'] ?> <br><br></td>
      <td rowspan="2" class="text-center border-right"><img src="<?= base_url('assets/icons/lunas.jpeg') ?>" alt="" width="100px "></td>
    </tr>
    <tr>
      <td colspan="2" class="border-bottom border-left"><?= $pda['vessel_name'] ?> <br><br></td>
    </tr>
    <tr>
      <td class="border-full"></td>
      <td class="border-full">Total Biaya:</td>
      <td class="border-full text-right"><?= number_format(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$search_index]) * ($agency_remuneration_jual->qty[$search_index] ? $agency_remuneration_jual->qty[$search_index] : 1)) ?></td>
    </tr>
    <tr>
      <td class="border-full"></td>
      <td class="border-full">Terbilang:</td>
      <td class="border-full text-right"><?= terbilang(preg_replace('/[^a-zA-Z0-9\']/', '', preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$search_index]) * ($agency_remuneration_jual->qty[$search_index] ? $agency_remuneration_jual->qty[$search_index] : 1))) ?> Rupiah</td>
    </tr>
    <tr>
      <td colspan="3" class="border-full">
        <br>
      </td>
    </tr>
    <tr>
      <td class="border-left border-right text-center">Diajukan</td>
      <td class="border-right text-center">Disetujui</td>
      <td class="border-right text-center">Dibayarkan</td>
    </tr>
    <tr>
      <td class="border-left border-right text-center">
        <img src="<?= base_url('img/ttd/hana-ttd.png') ?>" alt="" width="100px">
      </td>
      <td class="border-right text-center">
        <img src="<?= base_url('img/ttd/andre.png') ?>" alt="" width="100px">
      </td>
      <td class="border-right text-center">
        <img src="<?= base_url('img/ttd/imron.png') ?>" alt="" width="100px">
      </td>
    </tr>
    <tr>
      <td class="border-left border-right border-bottom text-center">
        User 1
      </td>
      <td class="border-right border-bottom text-center">
        User 2
      </td>
      <td class="border-right border-bottom text-center">
        User 3
      </td>
    </tr>
  </table>

</body>

</html>