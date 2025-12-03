<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <h1 class="page-title">Form Penawawran</h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a href="<?= site_url('agency/penawaran') ?>" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action="<?= base_url('agency/insert_penawaran') ?>" method="post">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="tgl">Tanggal</label>
                                            <input type="date" name="tgl" id="tgl" class="form-control" value="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer">Agency</label>
                                            <select name="agency" id="agency" class="form-control select2" style="width: 100%;">
                                                <option value=""> :: Pilih Agency</option>
                                                <?php
                                                $agency = $this->db->get('agent')->result_array();
                                                foreach ($agency as $ag) : ?>
                                                    <option value="<?= $ag['Id'] ?>"><?= $ag['nama'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <div class="form-group">
                                            <label for="customer">Customer</label>
                                            <select name="cust" id="cust" class="form-control select2" style="width: 100%;">
                                                <option value=""> :: Pilih Customer</option>
                                                <?php foreach ($customer as $cus) : ?>
                                                    <option value="<?= $cus['Id'] ?>"><?= $cus['nama_customer'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="attn">Attn</label>
                                            <input type="text" name="attn" id="attn" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="perihal">Perihal</label>
                                            <input type="text" name="perihal" id="perihal" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="isi">Isi Penawaran</label>
                                            <textarea name="isi" id="isi" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: 1px solid black;">
                                <div>
                                    <div class="text-center">
                                        <h3>BIAYA TETAP</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="myTableTetap">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="400px">Description</th>
                                                    <th>Cost</th>
                                                    <th>Remarks</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($penawaran_tetap as $row) : ?>
                                                    <tr class="baris-tetap">
                                                        <td>
                                                            <select name="item_tetap[]" id="item_tetap-<?= $row['Id'] ?>" class="form-control select2 desc-tetap">
                                                                <?php foreach ($penawaran_tetap_all as $val) { ?>
                                                                    <option value="<?= $val['Id'] ?>" <?= $val['Id'] == $row['Id'] ? "selected" : "" ?>><?= $val['nama_penawaran'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="cost[]" id="cost-<?= $row['Id'] ?>" class="form-control uang" value="<?= number_format($row['cost']) ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="remarks[]" id="remarks" class="form-control">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm hapus-row"><i class="fe fe-trash" aria-hidden="true"></i></button>
                                                            <button type="button" class="btn btn-success btn-sm tambah-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <h3>BIAYA TAMBAHAN (JIKA ADA)</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table" id="myTable">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th width="400px">Description</th>
                                                    <th>Cost</th>
                                                    <th>Remarks</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="baris">
                                                    <td>
                                                        <select name="desc[]" id="desc-0" class="form-control select2 description" style="width: 100%;">
                                                            <option value="">:: Pilih Item Penawaran</option>
                                                            <?php foreach ($penawaran_tambahan as $val) : ?>
                                                                <option value="<?= $val['Id'] ?>"><?= $val['nama_penawaran'] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="cost-tambahan[]" id="cost-tambahan-0" class="form-control uang cost-tambahan">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="remarks-tambahan[]" id="remarks-tambahan-0" class="form-control">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fe fe-trash" aria-hidden="true"></i></button>
                                                        <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr style="border: 1px solid black;">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="Notes">Notes</label>
                                            <textarea name="notes" id="notes" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <a href="<?= base_url('penawaran') ?>" class="btn btn-warning btn-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a>
                                        <button type="submit" class="btn btn-success btn-sm btn-submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->