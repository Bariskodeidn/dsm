<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <h1 class="page-title">Daftar Penawaran </h1>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a href="<?= base_url('agency/create_penawaran') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> BUat Penawaran</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 form-group pull-right top_search">
                            <form class="form-horizontal form-label-left" method="get" action="<?= site_url('agency/penawaran') ?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-secondary" type="submit">Go!</button>
                                        <a href="<?= site_url('agency/penawaran') ?>" class="btn btn-warning" style="color:white;">Reset</a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>No. Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                    <th>Customer</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($penawaran->num_rows() > 0) {
                                    $no = 1;
                                    foreach ($penawaran->result_array() as $value) {
                                ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $value['no_surat'] ?></td>
                                            <td><?= $value['perihal'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($value['tanggal'])) ?></td>
                                            <td><?= $value['nama'] ?></td>
                                            <td><?= $value['nama_customer'] ?></td>
                                            <td>
                                                <?php
                                                if (!$value['file'] or !$value['file_name']) {
                                                ?>
                                                    <a href="<?= base_url('agency/ubah_penawaran/') . $value['Id'] ?>" class="btn btn-success btn-sm"><i class="fe fe-edit" aria-hidden="true"></i></a>
                                                    <!-- <a href="<?= base_url('agency/word_penawaran/') . $value['Id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-file-word-o" aria-hidden="true"></i> File Word</a> -->
                                                <?php } ?>
                                                <a href="<?= base_url('agency/view_penawaran/') . $value['Id'] ?>" class="btn btn-warning btn-sm"><i class="fe fe-eye" aria-hidden="true"></i></a>
                                                <?php if (!$value['file'] and !$value['file_name']) { ?>
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalPenawaran<?= $value['Id'] ?>"><i class="fe fe-upload" aria-hidden="true"></i> Upload</a>
                                                <?php } else { ?>
                                                    <a href="<?= base_url('upload/penawaran/') . $value['file_name'] ?>" class="btn btn-success btn-sm" target="_blank"><i class="fe fe-download" aria-hidden="true"></i> Download</a>
                                                <?php } ?>

                                                <!-- Modal Upload File Penawaran -->
                                                <div class="modal fade" id="myModalPenawaran<?= $value['Id'] ?>" role="dialog">
                                                    <div class="modal-dialog">
                                                        <!-- Modal content-->
                                                        <div class="modal-content modal-center">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Upload File Penawaran <?= $value['no_surat'] ?></h6>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="<?= base_url('agency/upload_penawaran/' . $value['Id']) ?>" enctype="multipart/form-data" method="post">
                                                                    <div class="form-group">
                                                                        <label for="form-label">Upload File</label>
                                                                        <input type="file" class="form-control" name="file-penawaran" id="file-penawaran">
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <button type="submit" class="btn btn-primary btn-sm btn-upload"><i class="fe fe-save" aria-hidden="true"></i> Simpan</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr align="center">
                                        <td colspan="7">Tidak ada data</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 text-right">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->