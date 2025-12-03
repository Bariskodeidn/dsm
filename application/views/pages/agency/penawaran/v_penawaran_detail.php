<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <h5 class="page-title">Detail Penawaran <?= $penawaran['no_surat'] ?> </h1>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="<?= site_url('agency/penawaran') ?>" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                        <div class="text-right">
                            <p>Jakarta, <?= tgl_indo($penawaran['tanggal']) ?></p>
                        </div>
                        <div class="detail">
                            <table class="table">
                                <tr>
                                    <td width="100px">No. Surat</td>
                                    <td width="10px">:</td>
                                    <td><?= $penawaran['no_surat'] ?></td>
                                </tr>
                                <tr>
                                    <td>Perihal</td>
                                    <td>:</td>
                                    <td><?= $penawaran['perihal'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tujuan">
                            <p>Kepada Yth. :</p>
                            <p><?= $penawaran['nama_customer'] ?></p>
                            <?php
                            if ($penawaran['attn']) {
                            ?>
                                <p>Attn: Ibu/Bapak <?= $penawaran['attn'] ?></p>
                            <?php } ?>
                        </div>
                        <br>
                        <div class="isi">
                            <div class="pengantar">
                                <?= $penawaran['isi'] ?>
                            </div>
                            <br>
                            <div class="item-penawaran">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>Description</th>
                                            <th>Cost</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="5"><b>BIAYA TETAP</b></td>
                                        </tr>
                                        <?php
                                        $biaya_tetap = json_decode($penawaran['item_tetap']);
                                        foreach ($biaya_tetap->cost as $item) {
                                            $cost[] = $item;
                                        }

                                        foreach ($biaya_tetap->remarks as $item) {
                                            $remarks[] = $item;
                                        }

                                        $no = 1;
                                        foreach ($biaya_tetap->id as $key => $item) {
                                            $this->db->select('nama_penawaran');
                                            $item_penawaran[] = $this->db->get_where('t_item_penawaran', ['Id' => $item])->row_array();
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $item_penawaran[$key]['nama_penawaran'] ?></td>
                                                <td><?= $cost[$key] != 0 ? 'Rp.' . number_format($cost[$key], 0, ',', '.') : '-' ?></td>
                                                <td><?= $remarks[$key] ?></td>
                                            </tr>
                                        <?php } ?>

                                        <?php
                                        $biaya_tambahan = json_decode($penawaran['item_tambahan']);
                                        if ($biaya_tambahan->id[0] != "") {
                                        ?>
                                            <tr style="text-align: center;">
                                                <td colspan="4"><b>BIAYA TAMBAHAN</b></td>
                                            </tr>

                                            <?php
                                            $biaya_tambahan = json_decode($penawaran['item_tambahan']);
                                            foreach ($biaya_tambahan->cost as $item) {
                                                $cost_tambahan[] = $item;
                                            }

                                            foreach ($biaya_tambahan->remarks as $item) {
                                                $remarks_tambahan[] = $item;
                                            }

                                            $n = 1;
                                            foreach ($biaya_tambahan->id as $k => $item) {
                                                $this->db->select('nama_penawaran');
                                                $item_penawaran_tambahan[] = $this->db->get_where('t_item_penawaran', ['Id' => $item])->row_array();
                                            ?>
                                                <tr>
                                                    <td><?= $n++ ?></td>
                                                    <td><?= $item_penawaran_tambahan[$k]['nama_penawaran'] ?></td>
                                                    <td><?= $cost_tambahan[$k] != 0 ? 'Rp.' . number_format($cost_tambahan[$k], 0, ',', '.') : '-' ?></td>
                                                    <td><?= $remarks_tambahan[$k] ?></td>
                                                </tr>
                                            <?php } ?>

                                        <?php } ?>

                                        <?php
                                        $biaya_tambahan = json_decode($penawaran['item_tambahan']);
                                        foreach ($biaya_tambahan->cost as $item) {
                                            $cost_tambahan[] = $item;
                                        }

                                        foreach ($biaya_tambahan->remarks as $item) {
                                            $remarks_tambahan[] = $item;
                                        }

                                        $n = 1;
                                        foreach ($biaya_tambahan->id as $k => $item) {
                                            $this->db->select('nama_penawaran');
                                            $item_penawaran_tambahan[] = $this->db->get_where('t_item_penawaran', ['Id' => $item])->row_array();
                                        ?>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="notes">
                                <?= $penawaran['catatan'] ?>
                            </div>

                        </div>
                    </div>
                </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->