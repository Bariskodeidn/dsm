<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $invoice['referensi'] ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <style>
    @page {
      margin: 1.5mm 5mm;
    }

    * {
      font-family: "Montserrat", sans-serif;
      font-size: 7pt;
    }

    @media print {
      .page-break {
        page-break-before: always;
      }

    }

    .header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
    }

    .border-full {
      border: 1px solid black;
    }

    .border-right {
      border-right: 1px solid black;
    }

    .border-left {
      border-left: 1px solid black;
    }

    .border-bottom {
      border-bottom: 1px solid black;
    }

    .border-top {
      border-top: 1px solid black;
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

    .p-left {
      padding-left: 10px;
    }

    .p-right {
      padding-right: 10px;
    }

    table {
      border-collapse: collapse;
    }
  </style>
</head>

<body>
  <?php
  $penunjukan = $this->db->get_where('t_penunjukan', ['Id' => $invoice['penunjukan']])->row_array();
  $customer = $this->db->get_where('agency_customer', ['Id' => $invoice['customer']])->row_array();
  $detail = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id']])->result_array();
  $detail2 = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => null])->result_array();
  $agency = $this->db->get_where('agent', ['Id' => $penunjukan['agency']])->row_array();
  ?>
  <table width="100%" page-break-inside: auto>
    <thead>
      <tr>
        <td colspan="7">
          <img src="<?= base_url('assets/header-invoice/DSA.png') ?>" alt="" width="100%">
        </td>
      </tr>
      <tr>
        <td colspan="7" class="border-top"></td>
      </tr>
      <tr>
        <td colspan="4">
          Kepada Yth: <br>
          <?= $customer['nama_customer'] ?><br>
          <?= $customer['alamat'] ?>
        </td>
      </tr>
      <tr>
        <td style="height: 5px;"></td>
      </tr>
    </thead>
    <tbody>
      <tr class="" style="background-color: #DCDCDC;">
        <td class="text-center border-full" colspan="7"><b><u><span style="font-size: x-large;">INVOICE</span></u></b></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="7">NO : <?= $invoice['referensi'] ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="7">TANGGAL : <?= tgl_indo(date('Y-m-d', strtotime($invoice['tanggal']))) ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="7">SURAT PENUNJUKAN : <?= $penunjukan['no_surat'] ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="7"></td>
      </tr>
      <tr>
        <th class="border-full" width="30px">NO</th>
        <th class="border-full" colspan="3">URAIAN PEKERJAAN</th>
        <th class="border-full" width="80px">JUMLAH</th>
        <th class="border-full" width="20px">SATUAN</th>
        <th class="border-full" width="80px">TOTAL HARGA</th>
      </tr>

      <tr>
        <td class="border-left border-right"></td>
        <td class="border-left border-right p-left" colspan="3"><b><u>FINAL PORT DISB. ACCOUNT</u></b></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right p-l"></td>
        <td width="150px" class="p-left">NAMA KAPAL</td>
        <td width="15px">:</td>
        <td class="border-right"><?= $invoice['nama_kapal'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <?php if ($invoice['jml_muatan_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left">JUMLAH MUATAN BS</td>
          <td>:</td>
          <td class="border-right"><?= $invoice['jml_muatan_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
        </tr>
      <?php } ?>
      <?php if ($invoice['pel_muat_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left">PELABUHAN MUAT BS</td>
          <td>:</td>
          <td class="border-right"><?= $invoice['pel_muat_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
        </tr>
      <?php } ?>
      <?php if ($invoice['pel_bongkar_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left">PELABUHAN BONGKAR BS</td>
          <td>:</td>
          <td class="border-right"><?= $invoice['pel_bongkar_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
        </tr>
      <?php } ?>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">JUMLAH MUATAN BB</td>
        <td>:</td>
        <td class="border-right"><?= $invoice['jml_muatan'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">PELABUHAN MUAT BB</td>
        <td>:</td>
        <td class="border-right"><?= $invoice['pel_muat'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">PELABUHAN BONGKAR BB</td>
        <td>:</td>
        <td class="border-right"><?= $invoice['pel_bongkar'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">CARGO</td>
        <td>:</td>
        <td class="border-right"><?= $invoice['cargo'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">TA/NOR</td>
        <td>:</td>
        <td class="border-right"><?= date('d/m/Y', strtotime($invoice['ta_nor'])) ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left">TD</td>
        <td>:</td>
        <td class="border-right"><?= date('d/m/Y', strtotime($invoice['td'])) ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="border-right" colspan="3" style="height: 20px;"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
      </tr>

      <?php
      if ($invoice['jenis'] == 2) { ?>
        <tr>
          <td class="border-left border-right text-center">1</td>
          <td class="border-right p-left" colspan="3">PORT CHARGES</td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
        </tr>
        <?php
        $port_charges = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 1])->result_array();
        $no_pc = 1;
        foreach ($port_charges as $pc) {
        ?>
          <tr>
            <td class="border-left border-right text-center"></td>
            <td class="border-right p-left" colspan="3"><?= $pc['kategori'] . '.' . $no_pc++ ?> <?= $pc['mulai'] && $pc['selesai'] ? $pc['uraian'] . " <b>(" . date('d M y', strtotime($pc['mulai'])) . '-' . date('d M y', strtotime($pc['selesai'])) . ")</b>" : $pc['uraian'] ?></td>
            <td class="border-right text-right p-right"><?= number_format($pc['jumlah']) ?></td>
            <td class="border-right text-center"><?= $pc['satuan'] ?></td>
            <td class="border-right text-right p-right"><?= number_format($pc['total']) ?></td>
          </tr>
        <?php } ?>
        <?php
        $port_expense = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 2])->result_array();
        if ($port_expense) {
        ?>
          <tr>
            <td class="border-left border-right text-center" style="height: 20px;"></td>
            <td class="border-right p-left" colspan="3"></td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center"></td>
          </tr>
          <tr>
            <td class="border-left border-right text-center">2</td>
            <td class="border-right p-left" colspan="3">PORT CLEARENCE IN/OUT EXPENSES</td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center"></td>
          </tr>
          <?php
          $port_expense = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 2])->result_array();
          $no_pe = 1;
          foreach ($port_expense as $pe) { ?>
            <tr>
              <td class="border-left border-right text-center"></td>
              <td class="border-right p-left" colspan="3"><?= $pe['kategori'] . '.' . $no_pe++ ?> <?= $pe['mulai'] && $pe['selesai'] ? $pe['uraian'] . " <b>(" . date('d M y', strtotime($pe['mulai'])) . '-' . date('d M y', strtotime($pe['selesai'])) . ")</b>" : $pe['uraian'] ?></td>
              <td class="border-right text-right p-right"><?= number_format($pe['jumlah']) ?></td>
              <td class="border-right text-center"><?= $pe['satuan'] ?></td>
              <td class="border-right text-right p-right"><?= number_format($pe['total']) ?></td>
            </tr>
        <?php }
        } ?>

        <?php
        $miscleanneous = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 3])->result_array();
        if ($miscleanneous) {
        ?>
          <tr class="page-break">
            <td class="border-left border-right text-center"></td>
            <td class="border-right p-left" colspan="3"></td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center"></td>
            <td class="border-right text-center">
              <div style="page-break-after: always;"></div>
            </td>
          </tr>
          <tr>
            <td class="border-left border-right border-top text-center">3</td>
            <td class="border-right border-top p-left" colspan="3">MISCLEANNEOUS</td>
            <td class="border-right border-top text-center"></td>
            <td class="border-right border-top text-center"></td>
            <td class="border-right border-top text-center"></td>
          </tr>
          <?php
          $miscleanneous = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 3])->result_array();
          $no_mis = 1;
          foreach ($miscleanneous as $mis) { ?>
            <tr>
              <td class="border-left border-right text-center"></td>
              <td class="border-right p-left" colspan="3"><?= $mis['kategori'] . '.' . $no_mis++ ?> <?= $mis['mulai'] && $mis['selesai'] ? $mis['uraian'] . " <b>(" . date('d M y', strtotime($mis['mulai'])) . '-' . date('d M y', strtotime($mis['selesai'])) . ")</b>" : $mis['uraian'] ?></td>
              <td class="border-right text-right p-right"><?= number_format($mis['jumlah']) ?></td>
              <td class="border-right text-center"><?= $mis['satuan'] ?></td>
              <td class="border-right text-right p-right"><?= number_format($mis['total']) ?></td>
            </tr>
          <?php }
        }
      } else {
        $no = 1;
        foreach ($detail2 as $det) {
          ?>
          <tr>
            <td class="border-left border-right text-center"><?= $no++ ?></td>
            <td class="border-right p-left" colspan="3"><?= $det['mulai'] && $det['selesai'] ? $det['uraian'] . " <b>(" . date('d M y', strtotime($det['mulai'])) . '-' . date('d M y', strtotime($det['selesai'])) . ")</b>" : $det['uraian'] ?></td>
            <td class="border-right text-right p-right"><?= number_format($det['jumlah']) ?></td>
            <td class="border-right text-center"><?= $det['satuan'] ?></td>
            <td class="border-right text-right p-right"><?= number_format($det['total']) ?></td>
          </tr>
      <?php }
      } ?>
      <tr>
        <td class="border-top" colspan="4"></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>Sub Total</b></td>
        <td class="text-right border-left border-top border-right p-right"><b><?= number_format($invoice['sub_total']) ?></b></td>
      </tr>
      <?php if ($invoice['ppn'] == 1) { ?>
        <tr>
          <td class="" colspan="4"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>DPP NILAI LAINNYA</b></td>
          <td class="text-right border-left border-top border-right p-right"><b><?= number_format($invoice['dpp']) ?></b></td>
        </tr>
      <?php } ?>
      <?php if ($invoice['materai'] == 1) { ?>
        <tr>
          <td class="" colspan="4"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>MATERAI</b></td>
          <td class="text-right border-left border-top border-right p-right"><b><?= number_format($invoice['nominal_materai']) ?></b></td>
        </tr>
      <?php } ?>

      <?php if ($invoice['down_payment'] > 0) { ?>
        <tr>
          <td class="" colspan="4"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>DP</b></td>
          <td class="text-right border-left border-top border-right p-right"><b> - <?= number_format($invoice['down_payment']) ?></b></td>
        </tr>
      <?php } ?>
      <tr>
        <td class="" colspan="4"></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>PPN 12%</b></td>
        <td class="text-right border-left border-top border-right p-right"><b><?= number_format($invoice['nominal_ppn']) ?></b></td>
      </tr>
      <?php if ($invoice['nominal_pph'] > 0) { ?>
        <tr>
          <td class="" colspan="4"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>PPH 2%</b></td>
          <td class="text-right border-left border-top border-right p-right"><b> - <?= number_format($invoice['nominal_pph']) ?></b></td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan="2">Rekening Bank</td>
        <td>:</td>
        <td><b><?= $agency['bank'] ?></b></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>TOTAL</b></td>
        <td class="text-right border-left border-top border-right p-right"><b><?= number_format($invoice['total']) ?></b></td>
      </tr>
      <tr>
        <td colspan="2">Rekening No.</td>
        <td>:</td>
        <td><b><?= $agency['no_rekening'] ?></b></td>
        <td class="text-center border-left border-top border-right border-bottom" colspan="2" rowspan="2"><b>GRAND TOTAL</b></td>
        <td class="text-right border-left border-top border-right border-bottom p-right" rowspan="2"><b><?= number_format($invoice['total']) ?></b></td>
      </tr>
      <tr>
        <td colspan="2">Rekening a/n</td>
        <td>:</td>
        <td><b><?= $agency['nama_rekening'] ?></b></td>
      </tr>
    </tbody>
  </table>
  <div style="margin-top: 10px;">
    <b>Note:</b><br>
    Bukti transfer mohon dikirim via e-mail ke: marketing@dsagency.co.id<br>
    <?= $invoice['notes'] ?>
  </div>
  <div style="margin-top: 10px;">
    Hormat Kami,<br>
    <b><?= $agency['nama'] ?></b>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <b><u>Rudianto</u></b><br>
    <b>Direktur</b>
  </div>
</body>

</html>