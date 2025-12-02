<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harga Jual</title>

    <style>
        * {
            font-family: 'Calibri';
            font-size: 10pt;
        }

        .border-full {
            border: 1px solid black;
        }

        .border-right {
            border-right: 1px solid black;
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

        .bg-green {
            background-color: #92D050;
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

    $sql = "SELECT a.penunjukan, b.nama_kapal,b.customer, b.no_surat, c.nama_customer FROM t_pda a LEFT JOIN t_penunjukan b ON a.penunjukan = b.Id LEFT JOIN agency_customer c ON c.Id = b.customer WHERE a.Id = '$pda[Id]'";
    $result = $this->db->query($sql)->row_array();

    // header("Content-type: application/vnd-ms-excel");
    // header("Content-Disposition: attachment; filename=Harga Jual " . $result['no_surat'] . ".xls");
    // header("Pragma: no-cache");
    // header("Expires: 0");

    $est = json_decode($pda['est']);
    $agency_remuneration_est = $est->agency_remuneration;
    $desc_est = $est->desc;

    $hpp = json_decode($pda['hpp_rill']);
    $agency_remuneration_hpp = $hpp->agency_remuneration;
    $desc_hpp = $hpp->desc;

    $jual = json_decode($pda['harga_jual']);
    $agency_remuneration_jual = $jual->agency_remuneration;
    $desc_jual = $jual->desc;
    ?>
    <div class="title">
        <!-- <h3 class="text-center bg-yellow">PRA PDA</h3> -->
    </div>
    <div>
        <table class="table">
            <tr>
                <th width="250px">No. Penunjukan</th>
                <td width="5px">:</td>
                <td><?= $result['no_surat'] ?></td>
            </tr>
            <tr>
                <th width="250px">Principal</th>
                <td width="5px">:</td>
                <td><?= $result['nama_customer'] ?></td>
            </tr>
            <tr>
                <th width="250px">Nama Kapal</th>
                <td width="5px">:</td>
                <td><?= $result['nama_kapal'] ?></td>
            </tr>
            <tr>
                <th width="250px">ETA</th>
                <td width="5px">:</td>
                <td><?= date('d-m-Y', strtotime($pda['eta'])) ?></td>
            </tr>
        </table>

    </div>
    <div>
        <table style="width: 100%;">
            <thead>
                <tr class="bg-yellow">
                    <th class="border-full" rowspan="2">Description</th>
                    <th class="border-top border-right border-bottom" rowspan="2">Remarks</th>
                    <th class="border-top border-right border-bottom" rowspan="2">Remark</th>
                    <th class="border-top border-bottom border-right" rowspan="2">Estimasi (PRA PDA)</th>
                    <th class="border-top border-bottom border-right" rowspan="2">HPP Rill</th>
                    <th class="border-top border-bottom border-right">Harga Jual</th>
                    <th class="border-top border-bottom border-right">Selisih</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $total_est = 0;
                $total_hpp = 0;
                $total_hargajual = 0;
                foreach ($desc_hpp->id_desc as $key => $val) {
                    $this->db->select('desc');
                    $item_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
                    $total_est += $desc_est->amount_desc[$key];
                    $total_hpp += $desc_hpp->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc_hpp->amount_desc[$key]) : 0;
                    $total_hargajual += $desc_jual->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc_jual->amount_desc[$key]) : 0;
                ?>
                    <tr>
                        <td class="border-full"><?= $item_desc['desc'] ?></td>
                        <td class="border-full"><?= $desc_est->remarks[$key] ?></td>
                        <td class="border-full"><?= $desc_est->remark_desc[$key] ?></td>
                        <td class="border-full text-right"><?= number_format($desc_est->amount_desc[$key]) ?></td>
                        <td class="border-full text-right"><?= number_format(($desc_hpp->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc_hpp->amount_desc[$key]) : 0)) ?></td>
                        <td class="border-full text-right"><?= number_format(($desc_jual->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc_jual->amount_desc[$key]) : 0)) ?></td>
                        <td class="border-full text-right"><?= number_format(($desc_hpp->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '', $desc_hpp->amount_desc[$key]) : 0) - ($desc_hpp->amount_desc[$key] ? preg_replace('/[^a-zA-Z0-9\']/', '',  $desc_hpp->amount_desc[$key]) : 0)) ?></td>
                    </tr>
                <?php } ?>

                <?php
                foreach ($agency_remuneration_hpp->desc as $k => $value) {
                    $this->db->select('desc');
                    $item_pda = $this->db->get_where('t_item_pda', ['Id' => $value])->row_array();
                    $total_est += $agency_remuneration_est->amount[$k] ?? 0;
                    $total_hpp += preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_hpp->amount[$k]);
                    $total_hargajual += preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$k]);
                ?>
                    <tr>
                        <td class="border-full"><?= $item_pda['desc'] ?></td>
                        <td class="border-full"></td>
                        <td class="border-full"><?= $agency_remuneration_est->remark[$k] ?? "-" ?></td>
                        <td class="border-full text-right"><?= number_format($agency_remuneration_est->amount[$k] ?? 0) ?></td>
                        <td class="border-full text-right"><?= number_format(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_hpp->amount[$k])) ?></td>
                        <td class="border-full text-right"><?= number_format(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$k])) ?></td>
                        <td class="border-full text-right"><?= number_format(preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_jual->amount[$k]) - ($agency_remuneration_hpp->amount[$k] ? preg_replace('/[^a-zA-Z0-9\']/', '', $agency_remuneration_hpp->amount[$k]) : 0)) ?></td>
                    </tr>
                <?php } ?>
                <tr style="font-weight: bolder; background-color: #00B0F0;">
                    <td colspan="3" class="border-full text-center">Total</td>
                    <td class="border-full text-right"><?= number_format($total_est) ?></td>
                    <td class="border-full text-right"><?= number_format($total_hpp) ?></td>
                    <td class="border-full text-right"><?= number_format($total_hargajual) ?></td>
                    <td class="border-full text-right"><?= number_format($total_hargajual - $total_hpp) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>