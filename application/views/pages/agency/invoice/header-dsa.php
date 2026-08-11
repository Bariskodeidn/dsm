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