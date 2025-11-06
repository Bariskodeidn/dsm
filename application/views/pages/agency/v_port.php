<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Daftar Port </h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header">
          <p class="card-title"><strong>List Customer</strong></p>
        </div> -->
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalPortTambah">
                <i class="fe fe-plus"></i> Tambah Port
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-12 form-group pull-right top_search">
              <form class="form-horizontal form-label-left" method="get" action="<?= site_url('agency/port') ?>">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                    <a href="<?= site_url('agency/port') ?>" class="btn btn-warning" style="color:white;">Reset</a>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table id="" class="table table-bordered table-sm" style="width:100%">
              <thead class="thead-dark">
                <tr>
                <tr>
                  <th width="45px">No.</th>
                  <th>Nama</th>
                  <th>Kode</th>
                  <th>Cabang</th>
                  <th width="80px">#</th>
                </tr>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($port) {
                  foreach ($port->result_array() as $value) : ?>
                    <tr>
                      <td><?= ++$page; ?></td>
                      <td><?= $value['nama'] ?></td>
                      <td><?= $value['kode'] ?></td>
                      <td><?= $value['nama_cabang'] ?></td>
                      <td>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalPortUbah<?= $value['Id'] ?>"><i class="fe fe-edit" aria-hidden="true"></i> Ubah</button>
                        <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/update_port/') . $value['Id'] ?>">
                          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalPortUbah<?= $value['Id'] ?>">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel">
                                    Ubah Port
                                  </h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <label for="port" class="form-label">Nama</label>
                                      <input type="text" name="port" id="port" class="form-control" placeholder="Masukkan nama port..." value="<?= $value['nama'] ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="kode" class="form-label">Kode</label>
                                      <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode port..." value="<?= $value['kode'] ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="cabang" class="form-label">Cabang</label>
                                      <select name="cabang" id="cabang" class="form-control">
                                        <?php foreach ($cabang as $cab) : ?>
                                          <option value="<?= $cab->Id ?>" <?= $cab->Id == $value['id_cabang'] ? 'selected' : '' ?>><?= $cab->nama ?></option>
                                        <?php endforeach ?>
                                      </select>
                                    </div>
                                  </div>
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary btn-submit">
                                    Simpan
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </td>
                    </tr>
                  <?php
                  endforeach;
                } else {
                  ?>
                  <tr>
                    <td colspan="5">Tidak ada data yang ditampilkan</td>
                  </tr>
                <?php
                } ?>
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

<form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/store_port') ?>">
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalPortTambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            Tambah Port
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-12">
              <label for="port" class="form-label">Nama</label>
              <input type="text" name="port" id="port" class="form-control" placeholder="Masukkan nama port...">
            </div>
            <div class="col-12 mt-3">
              <label for="kode" class="form-label">Kode</label>
              <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode port...">
            </div>
            <div class="col-12 mt-3">
              <label for="cabang" class="form-label">Cabang</label>
              <select name="cabang" id="cabang" class="form-control">
                <?php foreach ($cabang as $cab) : ?>
                  <option value="<?= $cab->Id ?>"><?= $cab->nama ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-submit">
            Simpan
          </button>
        </div>
      </div>
    </div>
  </div>
</form>