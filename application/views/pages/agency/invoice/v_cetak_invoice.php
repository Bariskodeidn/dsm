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

    @media print {
      .page-break {
        page-break-before: always;
      }

    }

    /* * {
      font-family: "Century Gothic";
    } */

    tbody {
      font-size: 10px;
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
      padding-left: 8px;
    }

    .p-right {
      padding-right: 8px;
    }

    table {
      border-collapse: collapse;
    }

    .money.total {
      line-height: 0.5;
    }

    .total .symbol {
      text-align: left;
    }

    .total .amount {
      text-align: right;
    }

    td .total span:first-child {
      float: left;
    }

    td .total span:last-child {
      float: right;
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
        <td colspan="2" rowspan="6" width="100px" style="padding: 3px; vertical-align: middle;"><img src="<?= base_url('assets/images/logo-dsa.png') ?>" alt="" width="100%"></td>
        <td></td>
        <td colspan="4" style="font-size: 14px; font-weight: bold;">PT. Dharma Solusi Agency</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="4" style="font-size: 11px; font-weight: bold;">Shipping Agency & Marine Supply</td>
      </tr>
      <tr>
        <td></td>
        <td style="font-size: 10px; vertical-align: top;" colspan="" width="60px">Head Office</td>
        <td style="font-size: 10px; vertical-align: top;" width="3px">:</td>
        <td style="font-size: 10px; vertical-align: top;" colspan="5">Graha Sucofindo Gedung A Lt 1, Jalan Raya Pasar Minggu Kav 34 RT 004, RW 001, Pancoran, Jakarta Selatan 12780</td>
      </tr>
      <tr>
        <td></td>
        <td style="font-size: 10px;vertical-align: top;">Branch Office</td>
        <td style="font-size: 10px; vertical-align: top;">:</td>
        <td style="font-size: 10px; vertical-align: top;" colspan="5">Perum Rimera Tin Garden Blok A2 No. 3 RT 006, RW 001, Sukamulya, Sematang Borang, Palembang – Sumatera Selatan</td>
      </tr>
      <tr>
        <td></td>
        <td style="font-size: 10px;vertical-align: top;">Phone</td>
        <td style="font-size: 10px; vertical-align: top;">:</td>
        <td style="font-size: 10px; vertical-align: top;" colspan="5">021 - 38815205</td>
      </tr>
      <tr>
        <td></td>
        <td style="font-size: 10px;vertical-align: top;">Email</td>
        <td style="font-size: 10px; vertical-align: top;">:</td>
        <td style="font-size: 10px; vertical-align: top;" colspan="5"><a href="mailto:marketing@dsagency.co.id">marketing@dsagency.co.id</a> - <a href="https://dsmshipping.co.id/dsa/">https://dsmshipping.co.id/dsa/</a></td>
      </tr>

      <tr>
        <td colspan="11">
          <hr>
        </td>
      </tr>
      <tr>
        <td colspan="10" style="font-size: 11px;">Kepada Yth:</td>
      </tr>
      <tr>
        <td colspan="10" style="font-size: 11px;"><?= $customer['nama_customer'] ?><br></td>
      </tr>
      <tr>
        <td colspan="10" style="font-size: 11px;"><?= $customer['alamat'] ?> <br><br></td>
      </tr>
    </thead>
    <tbody>
      <tr class="" style=" background-color: #DCDCDC;">
        <td class="text-center border-full" colspan="11"><b><u><span style="font-size: 20px;">INVOICE</span></u></b></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="11">NO : <?= $invoice['referensi'] ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="11">TANGGAL : <?= tgl_indo(date('Y-m-d', strtotime($invoice['tanggal']))) ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="11">SURAT PENUNJUKAN : <?= $penunjukan['no_surat'] ?></td>
      </tr>
      <tr>
        <td class="text-center border-left border-right" colspan="11"></td>
      </tr>
      <tr>
        <th class="border-full" width="25px">NO</th>
        <th class="border-full" colspan="6">URAIAN PEKERJAAN</th>
        <th class="border-full" width="100px">JUMLAH</th>
        <th class="border-full" width="80px">SATUAN</th>
        <th class="border-full" width="150px" colspan="2">TOTAL HARGA</th>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="border-left border-right p-left" colspan="6" style="font-size: 10px;"><b><u>FINAL PORT DISB. ACCOUNT</u></b></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right p-l"></td>
        <td width="150px" class="p-left" style="font-size: 10px;">NAMA KAPAL</td>
        <td width="10px" style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['nama_kapal'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <?php if ($invoice['jml_muatan_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left">JUMLAH MUATAN BS</td>
          <td>:</td>
          <td class="border-right" colspan="4"><?= $invoice['jml_muatan_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right" colspan="2"></td>
        </tr>
      <?php } ?>
      <?php if ($invoice['pel_muat_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left" style="font-size: 10px;">PELABUHAN MUAT BS</td>
          <td style="font-size: 10px;">:</td>
          <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['pel_muat_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right" colspan="2"></td>
        </tr>
      <?php } ?>
      <?php if ($invoice['pel_bongkar_bs'] != null) { ?>
        <tr>
          <td class="border-left border-right"></td>
          <td class="p-left" style="font-size: 10px;">PELABUHAN BONGKAR BS</td>
          <td style="font-size: 10px;">:</td>
          <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['pel_bongkar_bs'] ?></td>
          <td class="border-right"></td>
          <td class="border-right"></td>
          <td class="border-right" colspan="2"></td>
        </tr>
      <?php } ?>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">JUMLAH MUATAN BB</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['jml_muatan'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">PELABUHAN MUAT BB</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['pel_muat'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">PELABUHAN BONGKAR BB</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['pel_bongkar'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">CARGO</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= $invoice['cargo'] ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">TA/NOR</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;"><?= date('d/m/Y', strtotime($invoice['ta_nor'])) ?></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="p-left" style="font-size: 10px;">TD</td>
        <td style="font-size: 10px;">:</td>
        <td class="border-right" colspan="4" style="font-size: 10px;">
          <?php
          if ($invoice['td'] == '0000-00-00' or $invoice['td'] == null) {
            echo '-';
          } else {
            echo date('d/m/Y', strtotime($invoice['td']));
          }
          ?>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>
      <tr>
        <td class="border-left border-right"></td>
        <td class="border-right" colspan="6" style="height: 20px;"></td>
        <td class="border-right"></td>
        <td class="border-right"></td>
        <td class="border-right" colspan="2"></td>
      </tr>



      <?php
      $port_charges = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 1])->result_array();
      $no = 0;
      if ($port_charges) {
        $no = $no + 1;
      ?>
        <tr>
          <td class="border-left border-right text-center" style="font-size: 10px;"><?= $no; ?></td>
          <td class="border-right p-left" colspan="6" style="font-size: 10px;">PORT CHARGES</td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center" colspan="2"></td>
        </tr>
        <?php
        $no_pc = 1;
        foreach ($port_charges as $pc) {
        ?>
          <tr>
            <td class="border-left border-right text-center"></td>
            <td class="border-right p-left" colspan="6" style="font-size: 10px;"><?= $no . '.' . $no_pc++ ?> <?= $pc['mulai'] && $pc['selesai'] ? $pc['uraian'] . " <b>(" . date('d M y', strtotime($pc['mulai'])) . '-' . date('d M y', strtotime($pc['selesai'])) . ")</b>" : $pc['uraian'] ?></td>
            <td class="border-right text-right p-right">
              <div class="money">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($pc['jumlah']) ?></span>
              </div>
            </td>
            <td class="border-right text-center"><?= $pc['satuan'] ?></td>
            <td class="border-right text-right p-right" colspan="2">
              <div class="money total">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($pc['total']) ?></span>
              </div>
            </td>
          </tr>
      <?php }
      } ?>
      <?php
      $port_expense = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 2])->result_array();
      if ($port_expense) {
        $no = $no + 1;
      ?>
        <tr>
          <td class="border-left border-right text-center" style="height: 10px;"></td>
          <td class="border-right p-left" colspan="6"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center" colspan="2"></td>
        </tr>
        <tr>
          <td class="border-left border-right text-center" style="font-size: 10px;"><?= $no ?></td>
          <td class="border-right p-left" colspan="6" style="font-size: 10px;">PORT CLEARENCE IN/OUT EXPENSES</td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center" colspan="2"></td>
        </tr>
        <?php
        $no_pe = 1;
        foreach ($port_expense as $pe) { ?>
          <tr>
            <td class="border-left border-right text-center"></td>
            <td class="border-right p-left" colspan="6" style="font-size: 10px;"><?= $no . '.' . $no_pe++ ?> <?= $pe['mulai'] && $pe['selesai'] ? $pe['uraian'] . " <b>(" . date('d M y', strtotime($pe['mulai'])) . '-' . date('d M y', strtotime($pe['selesai'])) . ")</b>" : $pe['uraian'] ?></td>
            <td class="border-right text-right p-right">
              <div class="money">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($pe['jumlah']) ?></span>
              </div>
            </td>
            <td class="border-right text-center"><?= $pe['satuan'] ?></td>
            <td class="border-right text-right p-right" colspan="2">
              <div class="money total">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($pe['total']) ?></span>
              </div>
            </td>
          </tr>
      <?php }
      } ?>

      <?php
      $miscleanneous = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id'], 'kategori' => 3])->result_array();
      if ($miscleanneous) {
        $no = $no + 1;
      ?>
        <tr>
          <td class="border-left border-right text-center" style="height: 10px;"></td>
          <td class="border-right p-left" colspan="6"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center" colspan="2"></td>
        </tr>
        <tr>
          <td class="border-left border-right text-center" style="font-size: 10px;"><?= $no ?></td>
          <td class="border-right p-left" colspan="6" style="font-size: 10px;">MISCLEANNEOUS</td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center"></td>
          <td class="border-right text-center" colspan="2"></td>
        </tr>
        <?php
        $no_mis = 1;
        foreach ($miscleanneous as $mis) { ?>
          <tr>
            <td class="border-left border-right text-center"></td>
            <td class="border-right p-left" colspan="6" style="font-size: 10px;"><?= $no . '.' . $no_mis++ ?> <?= $mis['mulai'] && $mis['selesai'] ? $mis['uraian'] . " <b>(" . date('d M y', strtotime($mis['mulai'])) . '-' . date('d M y', strtotime($mis['selesai'])) . ")</b>" : $mis['uraian'] ?></td>
            <td class="border-right text-right p-right">
              <div class="money">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($mis['jumlah']) ?></span>
              </div>
            </td>
            <td class="border-right text-center"><?= $mis['satuan'] ?></td>
            <td class="border-right text-right p-right" colspan="2">
              <div class="money total">
                <span class="symbol" style="float: left;">Rp.</span>
                <span class="amount"><?= number_format($mis['total']) ?></span>
              </div>
            </td>
          </tr>
      <?php }
      } ?>
      <tr>
        <td class="border-top" colspan="7"></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>SUB TOTAL</b></td>
        <td class="text-right border-left border-top border-right p-right" colspan="2">
          <div class="money total">
            <span class="symbol" style="font-weight: bold;">Rp.</span>
            <span class="amount" style="font-weight: bold;"><?= number_format($invoice['sub_total']) ?></span>
          </div>
        </td>
      </tr>
      <?php if ($invoice['tampil_dpp'] == 1) { ?>
        <tr>
          <td class="" colspan="7"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>DPP NILAI LAIN LAIN</b></td>
          <td class="text-right border-left border-top border-right p-right" colspan="2">
            <div class="money total" style="font-weight: bold;">
              <span class="symbol">Rp.</span>
              <span class="amount" style="font-weight: bold;"><?= number_format($invoice['dpp']) ?></span>
            </div>
          </td>
        </tr>
      <?php } ?>
      <?php if ($invoice['materai'] == 1) { ?>
        <tr>
          <td class="" colspan="7"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>MATERAI / STAMP DUTY </b></td>
          <td class="text-right border-left border-top border-right p-right" colspan="2">
            <div class="money total" style="font-weight: bold;">
              <span class="symbol">Rp.</span>
              <span class="amount" style="font-weight: bold;"><?= number_format($invoice['nominal_materai']) ?></span>
            </div>
          </td>
        </tr>
      <?php } ?>

      <?php if ($invoice['down_payment'] > 0) { ?>
        <tr>
          <td class="" colspan="7"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>DP</b></td>
          <td class="text-right border-left border-top border-right p-right" colspan="2">
            <div class="money total" style="font-size: bold;">
              <span class="symbol">- Rp.</span>
              <span class="amount" style="font-weight: bold;"><?= number_format($invoice['down_payment']) ?></span>
            </div>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td class="" colspan="7"></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>PPN 11%</b></td>
        <td class="text-right border-left border-top border-right p-right" colspan="2">
          <div class="money total" style="font-weight: bold;">
            <span class="symbol">Rp.</span>
            <span class="amount" style="font-weight: bold;"><?= number_format($invoice['nominal_ppn']) ?></span>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:11px;">Rekening Bank</td>
        <td>:</td>
        <td colspan="4" style="font-size: 11px;"><b><?= $agency['bank'] ?></b></td>
        <td class="text-center border-left border-top border-right" colspan="2"><b>TOTAL</b></td>
        <td class="text-right border-left border-top border-right p-right" colspan="2">
          <div class="money total" style="font-weight: bold;">
            <span class="symbol">Rp.</span>
            <span class="amount" style="font-weight: bold;"><?= number_format($invoice['total'] + (($invoice['nominal_pph'] > 0) ? $invoice['nominal_pph'] : 0)) ?></span>
          </div>
        </td>
      </tr>
      <?php if ($invoice['nominal_pph'] > 0) { ?>
        <tr>
          <td class="" colspan="7"></td>
          <td class="text-center border-left border-top border-right" colspan="2"><b>PPH 23 2%</b></td>
          <td class="text-right border-left border-top border-right p-right" colspan="2">
            <div class="money total" style="font-weight: bold;">
              <span class="symbol">- Rp.</span>
              <span class="amount" style="font-weight: bold;"><?= number_format($invoice['nominal_pph']) ?></span>
            </div>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan="2" style="font-size:11px;">Rekening No.</td>
        <td>:</td>
        <td colspan="4" style="font-size: 11px;"><b><?= $agency['no_rekening'] ?></b></td>
        <td class="text-center border-left border-top border-right border-bottom" colspan="2" rowspan="2"><b>GRAND TOTAL <?= ($invoice['nominal_pph'] > 0) ? " (Potong PPH 23)" : '' ?></b></td>
        <td class="text-right border-left border-top border-right border-bottom p-right" rowspan="2" colspan="2">
          <div class="money total" style="font-weight: bold;">
            <span class="symbol">Rp.</span>
            <span class="amount" style="font-weight: bold;"><?= number_format($invoice['total']) ?></span>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:11px;">Rekening a/n</td>
        <td>:</td>
        <td colspan="4" style="font-size: 11px;"><b><?= $agency['nama_rekening'] ?></b></td>
      </tr>
    </tbody>
  </table>
  <div style="margin-top: 10px; font-size:11px">
    <b>Note:</b><br>
    Bukti transfer mohon dikirim via e-mail ke : marketing@dsmshipping.co.id, marketing@dsagency.co.id - CP Riyant : 0812 - 9268 1115 <br>
    <?= ($invoice['nominal_pph'] > 0) ?  " Mohon untuk PPH 23 dipotongkan dari DPP." : '' ?>
    <?= $invoice['notes'] ?>
  </div>
  <div style="margin-top: 10px; font-size: 10px">
    Hormat Kami,<br>
    <b><?= $agency['nama'] ?></b>
    <br>
    <br>
    <br>
    <br>
    <br>
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