<style>
  .dt-length label {
    margin-left: 8px;
    /* Adjust this value (e.g., 5px, 10px, 0.5em) as needed */
  }

  .balance-info {
    margin-top: 20px;
    padding: 15px;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
  }

  .balance-info.balanced {
    background-color: #dcfce7;
    color: #16a34a;
  }

  .balance-info.unbalanced {
    background-color: #fee2e2;
    color: #dc2626;
  }

  .uppercase {
    text-transform: uppercase;
  }
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Customer </h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header">
          <p class="card-title"><strong>List Customer</strong></p>
        </div> -->
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahCustomer">
                <i class="fe fe-user-plus"></i> Tambah Customer
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-12 form-group pull-right top_search">
              <form class="form-horizontal form-label-left" method="post" action="<?= base_url('financial/list_customer') ?>">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="Search for...">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                    <a href="<?= base_url('financial/reset_customer') ?>" class="btn btn-warning" style="color:white;">Reset</a>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table id="" class="table table-bordered table-sm" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th>No.</th>
                  <th>Nama</th>
                  <th>No. Hp</th>
                  <th>Alamat</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($customers) {
                  foreach ($customers as $cust) : ?>
                    <tr>
                      <td><?= ++$page ?>.</td>
                      <td>
                        <button class="btn btn-xs" type="button" data-toggle="modal" data-target="#editCustomer<?= $cust['slug'] ?>">
                          <?= $cust['nama_customer'] ?>
                        </button>
                      </td>
                      <td><?= $cust['telepon_customer'] ?></td>
                      <td><?= $cust['alamat_customer'] ?></td>
                      <td><?= $cust['status_customer'] ?></td>
                    </tr>
                    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="editCustomer<?= $cust['slug'] ?>">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                              Ubah Customer
                            </h4>
                          </div>
                          <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('financial/store_customer/') . $cust['slug'] ?>">
                            <div class="modal-body">
                              <div class="form-group row">
                                <div class="col-12" style="display: none;">
                                  <label for="status_customer" class="form-label">Jenis Jurnal</label>
                                  <select name="status_customer" id="status_customer" class="form-control">
                                    <!-- <option value=""> :: Pilih jenis customer</option> -->
                                    <option value="reguler" selected>Reguler</option>
                                    <!-- <option value="khusus">Khusus</option> -->
                                  </select>
                                </div>
                                <div class="col-12 mt-3">
                                  <label for="nama_customer" class="form-label">Nama</label>
                                  <input type="text" name="nama_customer" id="nama_customer" class="form-control uppercase" placeholder="Masukkan nama customer..." value="<?= $cust['nama_customer'] ?>">
                                </div>
                                <div class="col-12 mt-3">
                                  <label for="alamat_customer" class="form-label">Alamat</label>
                                  <textarea name="alamat_customer" id="alamat_customer" class="form-control" placeholder="Masukkan alamat customer untuk ditampilkan saat cetak invoice..."><?= $cust['alamat_customer'] ?></textarea>
                                </div>
                                <div class="col-12 mt-3">
                                  <label for="telepon_customer" class="form-label">No. Kontak</label>
                                  <input type="text" name="telepon_customer" id="telepon_customer" class="form-control" placeholder="Masukkan nomor kontak customer..." value="<?= $cust['telepon_customer'] ?>">
                                </div>
                                <div class="col-12 mt-3">
                                  <label for="no_npwp" class="form-label">NPWP</label>
                                  <input type="text" name="no_npwp" id="no_npwp" class="form-control" placeholder="Masukkan NPWP Customer ..." value="<?= $cust['no_npwp'] ?>">
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">
                                Tambah Customer
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
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
            <div class="col-md-12 col-xs-12">
              <h6>*klik nama customer untuk edit</h6>
            </div>
            <div class="col-md-12 col-xs-12 text-right">
              <?= $this->pagination->create_links() ?>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="tambahCustomer">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          Tambah Customer Baru
        </h4>
      </div>
      <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('financial/store_customer') ?>">
        <div class="modal-body">
          <div class="form-group row">
            <!-- <div class="col-12">
              <label for="status_customer" class="form-label">Jenis Jurnal</label>
              <select name="status_customer" id="status_customer" class="form-control">
                <option value=""> :: Pilih jenis customer</option>
                <option value="reguler">Reguler</option>
                <option value="khusus">Khusus</option>
              </select>
            </div> -->
            <div class="col-12 mt-3">
              <label for="nama_customer" class="form-label">Nama</label>
              <input type="text" name="nama_customer" id="nama_customer" class="form-control uppercase" placeholder="Masukkan nama customer...">
            </div>
            <div class="col-12 mt-3">
              <label for="alamat_customer" class="form-label">Alamat</label>
              <textarea name="alamat_customer" id="alamat_customer" class="form-control" placeholder="Masukkan alamat customer untuk ditampilkan saat cetak invoice..."></textarea>
            </div>
            <div class="col-12 mt-3">
              <label for="telepon_customer" class="form-label">No. Kontak</label>
              <input type="text" name="telepon_customer" id="telepon_customer" class="form-control" placeholder="Masukkan nomor kontak customer...">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            Tambah Customer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>