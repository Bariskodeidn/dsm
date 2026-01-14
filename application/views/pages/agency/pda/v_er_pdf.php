<!DOCTYPE html>
<html>

<head>
    <title>Expense Report - <?= $detail['request_id'] ?></title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .footer-sign {
            width: 100%;
            margin-top: 50px;
        }

        .footer-sign td {
            text-align: center;
            width: 33%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PAYMENT VOUCHER</h2>
        Request ID: <?= $detail['request_id'] ?></p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Tanggal Pengajuan:</strong> <?= date('d F Y H:i', strtotime($detail['submitted_at'])) ?></td>
            <td align="right"><strong>Status:</strong> <?= strtoupper($detail['status']) ?></td>
        </tr>
    </table>

    <table class="table" style="margin-bottom: 10px">

        <tr>
            <td width="150px">No. Penunjukan</td>
            <td width="5px">:</td>
            <td><?= $penunjukan['no_surat'] ?></td>
        </tr>
        <tr>
            <td width="150px">Principal</td>
            <td width="5px">:</td>
            <td><?= $penunjukan['nama_customer'] ?></td>
        </tr>
        <tr>
            <td width="150px">Nama Kapal</td>
            <td width="5px">:</td>
            <td><?= $penunjukan['nama_kapal'] ?></td>
        </tr>
        <tr>
            <td width="150px">Port</td>
            <td width="5px">:</td>
            <td><?= $port['nama'] . ' (' . $port['kode'] . ')' ?></td>
        </tr>
    </table>

    <?php if (!empty($detail['items']['desc'])): ?>
        <h4 style="margin-top: 20px; color: #337ab7; border-left: 3px solid #337ab7; padding-left: 10px;">Deskripsi Kegiatan</h4>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Desc</th>
                    <th>Remark</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($detail['items']['desc'] as $i):
                    $item = $this->db->get_where('t_item_pda', ['Id' => $i['id_desc']])->row_array();
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $item['desc'] ?></td>
                        <td><?= $i['remarks'] ?></td>
                        <td class="text-right"><?= $i['amount_desc'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($detail['items']['agency'])): ?>
        <h4 style="margin-top: 20px; color: #337ab7; border-left: 3px solid #337ab7; padding-left: 10px;">Agency Remuneration</h4>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Desc</th>
                    <th>Remark</th>
                    <th>Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_agency = 0;
                $no = 1;
                foreach ($detail['items']['agency'] as $i):
                    $item = $this->db->get_where('t_item_pda', ['Id' => $i['desc']])->row_array();
                    $sub_total_agency = $i['qty'] * str_replace('.', '', $i['amount']);
                    $total_agency += $sub_total_agency;
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['desc'] ?></td>
                        <td><?= $i['remark'] ?></td>
                        <td><?= $i['qty'] ?></td>
                        <td class="text-right"><?= $i['amount'] ?></td>
                        <td class="text-right"><?= number_format($sub_total_agency, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5">Total</td>
                    <td><?= number_format($total_agency, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($detail['items']['other'])): ?>
        <h4 style="margin-top: 20px; color: #337ab7; border-left: 3px solid #337ab7; padding-left: 10px;">Other Expenses</h4>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Desc</th>
                    <th>Qty</th>
                    <th class="text-right">Amount</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $total_other = 0;
                foreach ($detail['items']['other'] as $i):
                    $sub_total_other = $i['qty'] * str_replace('.', '', $i['amount']);
                    $total_other += $sub_total_other; ?>
                    <tr>
                        <td width="30" align="center"><?= $no++ ?></td>
                        <td><?= $i['desc'] ?></td>
                        <td><?= $i['qty'] ?></td>
                        <td class="text-right"><?= number_format((float)str_replace(['.', ','], ['', '.'], $i['amount']), 0, ',', '.') ?></td>
                        <td><?= number_format($sub_total_other, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4">Total</td>
                    <td><?= number_format($total_other, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <table style="width: 100%; border-top: 2px solid #333; margin-top: 10px; padding-top: 10px;">
        <tr>
            <td width="70%" style="font-style: italic; color: #777;">
                Terbilang: # <?= ucwords(terbilang($detail['subtotal'])) ?> Rupiah #
            </td>
            <td width="30%" class="text-right">
                <span style="font-size: 14px; font-weight: bold;">Grand Total: Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></span>
            </td>
        </tr>
    </table>
</body>

</html>