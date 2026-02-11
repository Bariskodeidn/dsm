<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Daftar Item PDA </h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header">
          <p class="card-title"><strong>List Customer</strong></p>
        </div> -->
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalItemTambah">
                <i class="fe fe-plus"></i> Tambah Item
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-12 form-group pull-right top_search">
              <form class="form-horizontal form-label-left" method="get" action="<?= site_url('agency/item_pda') ?>">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                    <a href="<?= site_url('agency/item_pda') ?>" class="btn btn-warning" style="color:white;">Reset</a>
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
                  <th>Desc</th>
                  <th>Remarks</th>
                  <th>Jenis</th>
                  <th>Port</th>
                  <th>Estimasi</th>
                  <th>Hpp Ril</th>
                  <th>Title</th>
                  <th>#</th>
                </tr>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($item_pda) {
                  foreach ($item_pda->result_array() as $value) : ?>
                    <tr>
                      <td><?= ++$page; ?></td>
                      <td><?= $value['desc'] ?></td>
                      <td><?= $value['remarks'] ?? "-" ?></td>
                      <td><?= $value['jenis'] == 1 ? 'LONGTOWING' : "STS" ?></td>
                      <td><?= $value['port'] ?></td>
                      <td><?= $value['est'] ?></td>
                      <td><?= $value['hpp_rill'] ?></td>
                      <td><?= $value['title'] ?></td>
                      <td>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalItemUbah<?= $value['Id'] ?>"><i class="fe fe-edit" aria-hidden="true"></i> Ubah</button>
                        <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/update_item_pda/') . $value['Id'] ?>">
                          <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalItemUbah<?= $value['Id'] ?>">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="myModalLabel">
                                    Ubah Item
                                  </h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group row">
                                    <div class="col-12">
                                      <label for="desc" class="form-label">Description</label>
                                      <textarea name="desc" id="desc" class="form-control"><?= $value['desc'] ?></textarea>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                      <label for="nama" class="form-label">Remarks</label>
                                      <input type="text" class="form-control" name="remarks" id="remarks" value="<?= $value['remarks'] ?>">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                      <label for="nama" class="form-label">Jenis</label>
                                      <select name="jenis" id="jenis" class="form-control">
                                        <option value="2" <?= $value['jenis']  == 2 ? 'selected' : '' ?>>STS</option>
                                        <option value="1" <?= $value['jenis']  == 1 ? 'selected' : '' ?>>LONGTOWING</option>
                                      </select>
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="nama" class="form-label">Port</label>
                                      <select name="port" id="port" class="form-control select2">
                                        <?php foreach ($port as $p) : ?>
                                          <option value="<?= $p->kode ?>" <?= $value['port']  == $p->kode ? 'selected' : '' ?>><?= $p->nama ?></option>
                                        <?php endforeach ?>
                                      </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                      <label for="est" class="form-label">Harga Estimasi</label>
                                      <input type="text" name="est" id="est" class="form-control" value="<?= $value['est'] ?>">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                      <label for="hpp_rill" class="form-label">HPP Rill</label>
                                      <input type="text" name="hpp_rill" id="hpp_rill" class="form-control" value="<?= $value['hpp_rill'] ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                      <label for="title" class="form-label">Title</label>
                                      <select name="title" id="title" class="form-control select2">
                                        <option value="DESC" <?= $value['title']  == 'DESC' ? 'selected' : '' ?>>DESC</option>
                                        <option value="AGENCY REMUNERATION" <?= $value['title']  == 'AGENCY REMUNERATION' ? 'selected' : '' ?>>AGENCY REMUNERATION</option>
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

<form class="form-horizontal form-label-left" method="POST" action="<?= base_url('agency/store_item_pda') ?>">
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalItemTambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            Tambah Item
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-12">
              <label for="desc" class="form-label">Description</label>
              <textarea name="desc" id="desc" class="form-control"></textarea>
            </div>
            <div class="col-md-6 mt-3">
              <label for="nama" class="form-label">Remarks</label>
              <input type="text" class="form-control" name="remarks" id="remarks">
            </div>
            <div class="col-md-6 mt-3">
              <label for="nama" class="form-label">Jenis</label>
              <select name="jenis" id="jenis" class="form-control">
                <option value="2">STS</option>
                <option value="1">LONGTOWING</option>
              </select>
            </div>
            <div class="col-12 mt-3">
              <label for="nama" class="form-label">Port</label>
              <select name="port" id="port" class="form-control select2">
                <?php foreach ($port as $p) : ?>
                  <option value="<?= $p->kode ?>"><?= $p->nama ?></option>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-md-6 mt-3">
              <label for="est" class="form-label">Harga Estimasi</label>
              <input type="text" name="est" id="est" class="form-control">
            </div>
            <div class="col-md-6 mt-3">
              <label for="hpp_rill" class="form-label">HPP Rill</label>
              <input type="text" name="hpp_rill" id="hpp_rill" class="form-control">
            </div>
            <div class="col-12 mt-3">
              <label for="title" class="form-label">Title</label>
              <select name="title" id="title" class="form-control select2">
                <option value="DESC">DESC</option>
                <option value="AGENCY REMUNERATION">AGENCY REMUNERATION</option>
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