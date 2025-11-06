<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Daftar Customer </h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header">
          <p class="card-title"><strong>List Customer</strong></p>
        </div> -->
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalCustomerTambah">
                <i class="fe fe-plus"></i> Tambah Customer
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-12 form-group pull-right top_search">
              <form class="form-horizontal form-label-left" method="get" action="<?= site_url('agency/customer') ?>">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                    <a href="<?= site_url('agency/customer') ?>" class="btn btn-warning" style="color:white;">Reset</a>
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
                  <th>Nama Customer</th>
                  <th width="300px">Alamat</th>
                  <th>Telpon</th>
                  <th>Kode</th>
                  <th>Cabang</th>
                  <th>#</th>
                </tr>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($customer) {
                  foreach ($customer->result_array() as $value) : ?>
                    <tr>
                      <td><?= ++$page; ?></td>
                      <td><?= $value['nama_customer'] ?></td>
                      <td><?= $value['alamat'] ?></td>
                      <td><?= $value['telepon'] ?></td>
                      <td><?= $value['kode'] ?></td>
                      <td><?= $value['nama_cabang'] ?></td>
                      <td>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalCustomerUbah<?= $value['Id'] ?>"><i class="fe fe-edit" aria-hidden="true"></i> Ubah</button>
                        <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/update_customer/') . $value['Id'] ?>">
                          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalCustomerUbah<?= $value['Id'] ?>">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel">
                                    Ubah Customer
                                  </h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <label for="nama" class="form-label">Nama Customer</label>
                                      <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama customer..." value="<?= $value['nama_customer'] ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="nama" class="form-label">Nama Customer</label>
                                      <textarea name="alamat" id="alamat" class="form-control"><?= $value['alamat'] ?></textarea>
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="telpon" class="form-label">No. Telpon</label>
                                      <input type="text" name="telpon" id="telpon" class="form-control" placeholder="Masukkan no telpon customer..." value="<?= $value['telepon'] ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="kode" class="form-label">Kode</label>
                                      <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode customer..." value="<?= $value['kode'] ?>">
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

<form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/store_customer') ?>">
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalCustomerTambah">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            Tambah Customer
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-12">
              <label for="nama" class="form-label">Nama Customer</label>
              <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama customer...">
            </div>
            <div class="col-12 mt-3">
              <label for="nama" class="form-label">Nama Customer</label>
              <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat customer..."></textarea>
            </div>
            <div class="col-12 mt-3">
              <label for="telpon" class="form-label">No. Telpon</label>
              <input type="text" name="telpon" id="telpon" class="form-control" placeholder="Masukkan no telpon customer...">
            </div>
            <div class="col-12 mt-3">
              <label for="kode" class="form-label">Kode</label>
              <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode customer...">
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