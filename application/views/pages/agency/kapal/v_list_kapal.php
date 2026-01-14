<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <p class="card-title"><strong>Daftar Kapal</strong></p>
                </div>
                <div class="card-body">
                    <a href="<?= site_url('agency/add_kapal') ?>" class="btn btn-primary">
                        <i class="fe fe-plus"></i> Tambah Kapal
                    </a>

                    <div class="table-responsive">
                        <table id="tableKapalAgency" class="table table-bordered table-sm" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 2%;">No.</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Flag</th>
                                    <th>GRT</th>
                                    <th>DWT</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- .col-12 -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->