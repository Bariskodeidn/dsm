<style>
  .open-memo {
    cursor: pointer;
  }
</style>


<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <strong class="card-title">List Banner</strong>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
              <button type="button" class="btn mb-4 btn-primary" data-toggle="modal" data-target="#defaultModal">
                Tambah Banner
              </button>
              <form action="<?= site_url('perusahaan/banner') ?>" method="get">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="cari nama banner" name="search" id="search" value="<?= $this->input->get('search') ?>">
                  <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit">
                      Cari
                    </button>
                    <a href="<?= site_url('perusahaan/banner') ?>" class="btn btn-warning">Tampilkan Semua</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <table class="table table-hover table-sm">
            <thead style="background-color:#3498db;">
              <tr>
                <th style="color: white;">No</th>
                <th style="color: white;">File Name</th>
                <th style="color: white;">Image</th>
                <th style="color: white;">#</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (empty($data_banner)) { ?>
                <tr>
                  <td colspan="4" class="text-center">Data tidak ditemukan</td>
                </tr>
                <?php } else {
                foreach ($data_banner as $data) {
                ?>
                  <tr>
                    <td><?= ++$page; ?></td>
                    <td><?= $data->file_name ?></td>
                    <td>
                      <img src="<?= base_url('assets/images/banner/') . $data->file ?>" alt="banner" width="100px">
                    </td>
                    <td>
                      <button type="button" class="btn mb-4 btn-success" data-toggle="modal" data-target="#defaultModal<?= $data->Id ?>">
                        Ubah
                      </button>
                      <form action="<?= site_url('perusahaan/update_banner/') . $data->Id ?>" enctype="multipart/form-data" method="post">
                        <div class="modal fade show" id="defaultModal<?= $data->Id ?>" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-modal="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="defaultModalLabel">
                                  Update Banner
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                              </div>

                              <div class="modal-body">
                                <div class="row mb-3">
                                  <div class="col-md-6">
                                    <div class="form-group mb-3">
                                      <input type="file" id="file" name="attach" class="form-control-file">
                                    </div>
                                  </div>
                                </div>

                                <div class="row mb-3">
                                  <div class="col-md-12">
                                    <img src="<?= site_url('assets/images/banner/') . $data->file ?>" alt="banner" width="50%">
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
              <?php }
              } ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav aria-label="Table Paging" class="mb-0">
            <?= $pagination ?>
          </nav>

        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<form action="<?= site_url('perusahaan/store_banner') ?>" enctype="multipart/form-data" method="post">
  <div class="modal fade show" id="defaultModal" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="defaultModalLabel">
            Tambah Banner
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <input type="file" id="file" name="attach" class="form-control-file">
              </div>
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