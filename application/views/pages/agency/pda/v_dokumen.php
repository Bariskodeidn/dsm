<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Dokumen</strong></p>
        </div>
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <a href="<?= base_url('agency/penunjukan') ?>" class="btn btn-warning btn-sm">Kembali</a>
              <?php if ($monitoringDokumen['status'] == 1) { ?>
                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalDokumen"><i class="fe fe-plus" aria-hidden="true"></i> Tambah Dokumen</a>
              <?php } ?>
              <?php if ($dokumen->num_rows() > 1) { ?>
                <a href="<?= base_url('pda/merge_file/') . $pda['Id'] ?>" class="btn btn-success btn-sm">Merge File</a>
              <?php } ?>

              <?php if ($monitoringDokumen['status'] == 1) { ?>
                <a href="<?= base_url('pda/akhiri_dokumen/') . $pda['Id'] ?>" class="btn btn-danger btn-sm" id="akhiri-upload">Akhiri Upload Dokumen</a>
              <?php } ?>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <?php
              $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
              $penunjukan = $this->db->select('a.jenis,a.no_surat,b.nama_customer,c.name as nama_kapal')->from('t_penunjukan a')->join('agency_customer b', 'b.Id = a.customer', 'left')->join('agency_kapal c', 'a.nama_kapal = c.Id', 'left')->where('a.Id', $pda['penunjukan'])->get()->row_array();
              ?>
              <table class="table">
                <tr>
                  <th width="250px">No. Penunjukan</th>
                  <td width="5px">:</td>
                  <td><?= $penunjukan['no_surat'] ?></td>
                </tr>
                <tr>
                  <th width="250px">Principal</th>
                  <td width="5px">:</td>
                  <td><?= $penunjukan['nama_customer'] ?></td>
                </tr>
                <tr>
                  <th width="250px">Nama Kapal</th>
                  <td width="5px">:</td>
                  <td><?= $penunjukan['nama_kapal'] ?></td>
                </tr>
                <tr>
                  <th width="250px">ETA</th>
                  <td width="5px">:</td>
                  <td><?= date('d-m-Y', strtotime($pda['eta'])) ?></td>
                </tr>
                <tr>
                  <th width="250px">Port</th>
                  <td width="5px">:</td>
                  <td><?= $port['nama'] . ' (' . $port['kode'] . ')' ?></td>
                </tr>
              </table>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 col-xs-12 form-group pull-right top_search">
              <form class="form-horizontal form-label-left" method="get" action="<?= site_url('pda/dokumen/') . $pda['Id'] ?>">
                <div class="input-group">
                  <input type="text" class="form-control" name="keyword" placeholder="Search for..." value="<?= $this->input->get('keyword') ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                    <a href="<?= site_url('pda/dokumen/') . $pda['Id'] ?>" class="btn btn-warning" style="color:white;">Reset</a>
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
                  <th>Kegiatan</th>
                  <th>File Name</th>
                  <th>#</th>
                </tr>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($dokumen->num_rows() > 0) {
                  foreach ($dokumen->result_array() as $value) : ?>
                    <tr>
                      <td><?= ++$page; ?></td>
                      <td><?= $value['title'] ?></td>
                      <td><a href="<?= base_url('upload/dokumen-pda/' . $value['id_pda'] . '/' . $value['file_name']) ?>" target="_blank"><?= $value['file'] ?></a></td>
                      <td>
                        <?php if ($monitoringDokumen['status'] == 1) { ?>
                          <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalDokumenUbah<?= $value['Id'] ?>"><i class="fe fe-edit" aria-hidden="true"></i> Ubah</button>
                          <a href="<?= base_url('pda/delete_dokumen/') . $value['Id'] ?>" class="btn btn-danger btn-sm btn-delete"><i class="fe fe-trash"></i> Hapus</a>
                          <!-- Modal Surat Penunjukan -->
                          <form class="form-horizontal form-label-left" method="POST" action="<?= base_url('pda/update_dokumen') ?>">
                            <input type="hidden" name="id_dokumen" id="id_dokumen" value="<?= $value['Id'] ?>">
                            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalDokumenUbah<?= $value['Id'] ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">
                                      Ubah Dokumen
                                    </h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group row">
                                      <input type="hidden" name="id_pda" id="id_pda" value="<?= $pda['Id'] ?>">
                                      <div class="col-12">
                                        <label for="title" class="form-label">Title/Nama Kegiatan</label>
                                        <input type="text" name="title" id="title" class="form-control" value="<?= $value['title'] ?>">
                                      </div>
                                      <div class="col-12 mt-3">
                                        <label for="file" class="form-label">File Upload</label>
                                        <input type="file" name="file-ubah" id="file-ubah" class="form-control">
                                        <span><a href="<?= base_url('upload/dokumen-pda/') . $pda['Id'] . "/" . $value['file_name'] ?>"><?= $value['file'] ?></a></span>
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
                        <?php } else { ?>
                          -
                        <?php } ?>
                      </td>
                    </tr>
                  <?php
                  endforeach;
                } else {
                  ?>
                  <tr>
                    <td colspan="4">Tidak ada data yang ditampilkan</td>
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

<form class="form-horizontal form-label-left" method="POST" action="<?= base_url('pda/upload') ?>">
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalDokumen">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            Tambah Dokumen
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <input type="hidden" name="id_pda" id="id_pda" value="<?= $pda['Id'] ?>">
            <div class="col-12">
              <label for="title" class="form-label">Title/Nama Kegiatan</label>
              <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="col-12 mt-3">
              <label for="file" class="form-label">File Upload</label>
              <input type="file" name="file" id="file" class="form-control">
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