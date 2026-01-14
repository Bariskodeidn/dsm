<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <p class="card-title"><strong>Daftar Request ER</strong></p>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <form action="<?= base_url('expense/bulk_approve_process') ?>" method="POST">
                            <div class="panel panel-primary">
                                <div class="panel-heading" style="display:flex; justify-content:space-between;">
                                    <!-- <button type="submit" class="btn btn-xs btn-success mb-3" id="btnBulk" disabled>Approve Terpilih</button> -->
                                </div>
                                <?php if ($this->session->flashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible my-3" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <i class="fe fe-check-circle"></i> <?= $this->session->flashdata('success'); ?>
                                    </div>
                                <?php endif;
                                unset($_SESSION['success']) ?>

                                <?php if ($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible my-3" role="alert">
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <i class="glyphicon glyphicon-error"></i> <?= $this->session->flashdata('error'); ?>
                                    </div>
                                <?php endif;
                                unset($_SESSION['error']);
                                ?>

                                <table class="table table-condensed table-hover" id="tableRequestAll">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Penunjukan</th>
                                            <th>Request ID</th>
                                            <th>Tanggal</th>
                                            <th>User</th>
                                            <th>Nominal</th>
                                            <th>Status/Nota</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade" id="modalDetailReq" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 8px; overflow: hidden;">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" style="color:white;">
                    <i class="glyphicon glyphicon-search"></i> VERIFIKASI PENGAJUAN: <span id="det-id"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;">&times;</button>
            </div>
            <div class="modal-body" style="background: #f9f9f9;">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <table class="table table-condensed">
                            <tr>
                                <td width="100">Penunjukan</td>
                                <td>: <strong id="det-penunjukan"></strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="text-muted">Status Saat Ini:</div>
                        <span id="det-status" class="label label-info" style="font-size: 14px;"></span>
                    </div>
                </div>

                <div id="det-content" class="table-responsive" style="background: white; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            </div>
            <div class="modal-footer" style="background: #eee;">
                <div class="pull-left" id="footer-actions">
                </div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNota" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bukti Nota: <span id="nota-rid"></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="img-nota" class="img-responsive" style="margin: auto; max-height: 500px;">
                <iframe src="" id="pdf-nota" style="width:100%; height:500px; display:none;"></iframe>
            </div>
            <div class="modal-footer" style="background: #eee;">
                <div class="pull-left" id="footer-actions">
                </div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>