<thead>
    <tr>
        <td colspan="2" rowspan="5" width="60px" style="padding: 1px; vertical-align: middle;text-align: right;">
            <img src="<?= base_url('assets/images/logo-kbs.png') ?>" alt="" width="70%">
        </td>
        <td></td>
        <td colspan="6" style="font-size: 14px; font-weight: bold;">PT. Kencana Bayu Sejahtra</td>
        <td width="40px" rowspan="3" style="text-align: right;vertical-align: top;">
            <img src="<?= base_url('assets/images/header-kbs.png') ?>" alt="" width="100">
        </td>
    </tr>
   
    <tr>
        <td></td>
        <td style="font-size: 12px; vertical-align: top;" colspan="" width="60px">Office</td>
        <td style="font-size: 12px; vertical-align: top;" width="3px">:</td>
        <td style="font-size: 12px; vertical-align: top;" colspan="5">Komp. Perkantoran Citra Indah Blok A No.1 Batam Centre - Kota Batam Kepulauan Riau</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 12px;vertical-align: top;"></td>
        <td style="font-size: 12px; vertical-align: top;">:</td>
        <td style="font-size: 12px; vertical-align: top;" colspan="4">Gedung Tranka, Jl Raya Pasar Minggu No. 17 Jakarta Selatan</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 12px;vertical-align: top;">Phone</td>
        <td style="font-size: 12px; vertical-align: top;">:</td>
        <td style="font-size: 12px; vertical-align: top;" colspan="4">021 - 7980421</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 12px;vertical-align: top;">Email</td>
        <td style="font-size: 12px; vertical-align: top;">:</td>
        <td style="font-size: 12px; vertical-align: top;" colspan="4"><a href="mailto:sales@kbsshipping.co.id">sales@kbsshipping.co.id</a> - <a href="https://kbsshipping.co.id">https://kbsshipping.co.id</a></td>
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

    <tr style="background-color: #DCDCDC;">
        <td class="text-center border-full" colspan="11" style="font-size: 10px;"><b><u><span>INVOICE</span></u></b></td>
    </tr>
    <tr>
        <td class="text-center border-left border-right" colspan="11" style="font-size: 10px;">NO : <?= $invoice['referensi'] ?></td>
    </tr>
    <tr>
        <td class="text-center border-left border-right" colspan="11" style="font-size: 10px;">TANGGAL : <?= tgl_indo(date('Y-m-d', strtotime($invoice['tanggal']))) ?></td>
    </tr>
    <tr>
        <td class="text-center border-left border-right" colspan="11" style="font-size: 10px;">SURAT PENUNJUKAN : <?= $penunjukan['no_surat'] ?></td>
    </tr>
    <tr>
        <td class="border-bottom text-center border-left border-right" colspan="11"></td>
    </tr>
</thead>