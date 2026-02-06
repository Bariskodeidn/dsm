<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Ubah Invoice <?= $invoice['referensi'] ?></strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('invoice') ?>" class="btn btn-warning mb-4">Kembali</a>
          <?php if ($invoice['jenis'] == 1) { ?>
            <form action="<?= base_url('invoice/update/') . $invoice['Id'] ?>" method="post" enctype="multipart/form-data" id="form-invoice-ubah">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="label" class="form-label">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d', strtotime($invoice['tanggal'])) ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Surat Penunjukan</label>
                    <select name="penunjukan" id="penunjukan" class="form-control select2">
                      <option value=""> :: SURAT PENUNJUKAN ::</option>
                      <?php foreach ($penunjukan->result_array() as $pen) { ?>
                        <option value="<?= $pen['Id'] ?>" <?= $invoice['penunjukan'] == $pen['Id'] ? "selected" : "" ?>><?= $pen['no_surat'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Customer</label>
                    <select name="customer" id="customer" class="form-control select2">
                      <option value=""> :: CUSTOMER ::</option>
                      <?php foreach ($customer->result_array() as $cust) { ?>
                        <option value="<?= $cust['Id'] ?>" <?= $invoice['customer'] == $cust['Id'] ? "selected" : "" ?>><?= $cust['nama_customer'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Nama Kapal</label>
                    <input type="text" name="kapal" id="kapal" class="form-control" value="<?= $invoice['nama_kapal'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Jumlah Muatan BS</label>
                    <input type="text" name="jml_muatan_bs" id="jml_muatan_bs" class="form-control" value="<?= $invoice['jml_muatan_bs'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Muat BS</label>
                    <input type="text" name="pel_muat_bs" id="pel_muat_bs" class="form-control" value="<?= $invoice['pel_muat_bs'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Bongkar BS</label>
                    <input type="text" name="pel_bongkar_bs" id="pel_bongkar_bs" class="form-control" value="<?= $invoice['pel_bongkar_bs'] ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Jumlah Muatan BB</label>
                    <input type="text" name="jml_muatan_bb" id="jml_muatan_bb" class="form-control" value="<?= $invoice['jml_muatan'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Muat BB</label>
                    <input type="text" name="pel_muat_bb" id="pel_muat_bb" class="form-control" value="<?= $invoice['pel_muat'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Bongkar BB</label>
                    <input type="text" name="pel_bongkar_bb" id="pel_bongkar_bb" class="form-control" value="<?= $invoice['pel_bongkar'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Cargo</label>
                    <input type="text" name="cargo" id="cargo" class="form-control" value="<?= $invoice['cargo'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">TA/NOR</label>
                    <input type="date" name="ta_nor" id="ta_nor" class="form-control" value="<?= $invoice['ta_nor'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">TD</label>
                    <input type="date" name="td" id="td" class="form-control" value="<?= $invoice['td'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Materai</label>
                    <select name="materai" id="materai" class="form-control">
                      <option value="1" <?= $invoice['materai'] == 1 ? "selected" : '' ?>>YA</option>
                      <option value="0" <?= $invoice['materai'] == 0 ? "selected" : '' ?>>TIDAK</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">PPN 12%</label>
                    <select name="ppn" id="ppn" class="form-control">
                      <option value="1" <?= $invoice['ppn'] == 1 ? "selected" : '' ?>>YA</option>
                      <option value="0" <?= $invoice['ppn'] == 0 ? "selected" : '' ?>>TIDAK</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">PPH</label>
                    <input type="text" name="pph" id="pph" class="form-control uang" value="<?= $invoice['nominal_pph'] ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Down Payment</label>
                    <input type="text" name="dp" id="dp" class="form-control uang" value="<?= $invoice['down_payment'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="label" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control"><?= $invoice['notes'] ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <table class="table" id="uraian-invoice-1">
                  <?php
                  $detail_invoice = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id']])->result_array();
                  foreach ($detail_invoice as $di) {
                  ?>
                    <tr class="tr_clone">
                      <input type="hidden" name="id_detail[]" value="<?= $di['Id'] ?>">
                      <td>
                        <label for="label" class="form-label">Uraian Pekerjaan</label>
                        <input type="text" class="form-control" name="uraian[]" id="uraian" value="<?= $di['uraian'] ?>">
                      </td>
                      <td width="100px">
                        <label for="label" class="form-label">Satuan</label>
                        <input type="text" class="form-control uang" name="satuan[]" id="satuan" value="<?= $di['satuan'] ?>">
                      </td>
                      <td width="300px">
                        <label for="label" class="form-label">Harga</label>
                        <input type="text" class="form-control uang" name="harga[]" id="harga" value="<?= $di['jumlah'] ?>">
                      </td>
                      <td width="300px">
                        <label for="label" class="form-label">Kategori</label>
                        <select name="kategori[]" id="kategori" class="form-control">
                          <option value="1" <?= $di['kategori'] == 1 ? 'selected' : "" ?>>PORT CHARGES</option>
                          <option value="2" <?= $di['kategori'] == 2 ? 'selected' : "" ?>>PORT CLEARENCE IN/OUT EXPENSE</option>
                          <option value="3" <?= $di['kategori'] == 3 ? 'selected' : "" ?>>MISCLEANNEOUS</option>
                        </select>
                      </td>
                      <td width="100px">
                        <label for="label" class="form-label">#</label><br>
                        <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                        <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  <?php } ?>
                </table>
              </div>
              <hr>
              <div>
                <button type="submit" class="btn btn-primary btn-sm btn-submit">Simpan</button>
              </div>
            </form>
          <?php }
          if ($invoice['jenis'] == 2) {
          ?>
            <form action="<?= base_url('invoice/update/') . $invoice['Id'] ?>" method="post" enctype="multipart/form-data" id="form-invoice-ubah">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="label" class="form-label">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d', strtotime($invoice['tanggal'])) ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Surat Penunjukan</label>
                    <select name="penunjukan" id="penunjukan" class="form-control select2">
                      <option value=""> :: SURAT PENUNJUKAN ::</option>
                      <?php foreach ($penunjukan->result_array() as $pen) { ?>
                        <option value="<?= $pen['Id'] ?>" <?= $invoice['penunjukan'] == $pen['Id'] ? "selected" : "" ?>><?= $pen['no_surat'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Customer</label>
                    <select name="customer" id="customer" class="form-control select2">
                      <option value=""> :: CUSTOMER ::</option>
                      <?php foreach ($customer->result_array() as $cust) { ?>
                        <option value="<?= $cust['Id'] ?>" <?= $invoice['customer'] == $cust['Id'] ? "selected" : "" ?>><?= $cust['nama_customer'] ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Nama Kapal</label>
                    <input type="text" name="kapal" id="kapal" class="form-control" value="<?= $invoice['nama_kapal'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Jumlah Muatan BS</label>
                    <input type="text" name="jml_muatan_bs" id="jml_muatan_bs" class="form-control" value="<?= $invoice['jml_muatan_bs'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Muat BS</label>
                    <input type="text" name="pel_muat_bs" id="pel_muat_bs" class="form-control" value="<?= $invoice['pel_muat_bs'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Bongkar BS</label>
                    <input type="text" name="pel_bongkar_bs" id="pel_bongkar_bs" class="form-control" value="<?= $invoice['pel_bongkar_bs'] ?>">
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Jumlah Muatan BB</label>
                    <input type="text" name="jml_muatan_bb" id="jml_muatan_bb" class="form-control" value="<?= $invoice['jml_muatan'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Muat BB</label>
                    <input type="text" name="pel_muat_bb" id="pel_muat_bb" class="form-control" value="<?= $invoice['pel_muat'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Pelabuhan Bongkar BB</label>
                    <input type="text" name="pel_bongkar_bb" id="pel_bongkar_bb" class="form-control" value="<?= $invoice['pel_bongkar'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">Cargo</label>
                    <input type="text" name="cargo" id="cargo" class="form-control" value="<?= $invoice['cargo'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">TA/NOR</label>
                    <input type="date" name="ta_nor" id="ta_nor" class="form-control" value="<?= $invoice['ta_nor'] ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="label" class="form-label">TD</label>
                    <input type="date" name="td" id="td" class="form-control" value="<?= $invoice['td'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Materai</label>
                    <select name="materai" id="materai" class="form-control">
                      <option value="1" <?= $invoice['materai'] == 1 ? "selected" : '' ?>>YA</option>
                      <option value="0" <?= $invoice['materai'] == 0 ? "selected" : '' ?>>TIDAK</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">PPN 12%</label>
                    <select name="ppn" id="ppn" class="form-control">
                      <option value="1" <?= $invoice['ppn'] == 1 ? "selected" : '' ?>>YA</option>
                      <option value="0" <?= $invoice['ppn'] == 0 ? "selected" : '' ?>>TIDAK</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">PPH</label>
                    <input type="text" name="pph" id="pph" class="form-control uang" value="<?= $invoice['nominal_pph'] ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="label" class="form-label">Down Payment</label>
                    <input type="text" name="dp" id="dp" class="form-control uang" value="<?= $invoice['down_payment'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="label" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-control"><?= $invoice['notes'] ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <table class="table" id="uraian-invoice-2">
                  <?php
                  $detail_invoice = $this->db->get_where('t_detail_invoice', ['id_invoice' => $invoice['Id']])->result_array();
                  foreach ($detail_invoice as $di) {
                  ?>
                    <tr class="tr_clone">
                      <td width="250px">
                        <label for="label" class="form-label">Uraian Pekerjaan</label>
                        <textarea name="uraian[]" id="uraian" class="form-control"><?= $di['uraian'] ?></textarea>
                      </td>
                      <td>
                        <label for="label" class="form-label">Mulai</label>
                        <?php
                        $mulai = $di['mulai'] ? date('Y-m-d', strtotime($di['mulai'])) : "";
                        $selesai = $di['selesai'] != "" ? date('Y-m-d', strtotime($di['selesai'])) : "";
                        ?>
                        <input type="date" class="form-control" name="mulai[]" id="mulai" value="<?= $mulai ?>">
                      </td>
                      <td>
                        <label for="label" class="form-label">selesai</label>
                        <input type="date" class="form-control" name="selesai[]" id="selesai" value="<?= $selesai ?>">
                      </td>
                      <td width="">
                        <label for="label" class="form-label">Satuan</label>
                        <input type="text" class="form-control uang" name="satuan[]" id="satuan" value="<?= $di['satuan'] ?>">
                      </td>
                      <td width="">
                        <label for="label" class="form-label">Harga</label>
                        <input type="text" class="form-control uang" name="harga[]" id="harga" value="<?= $di['jumlah'] ?>">
                      </td>
                      <td width="">
                        <label for="label" class="form-label">Kategori</label>
                        <select name="kategori[]" id="kategori" class="form-control">
                          <option value="1" <?= $di['kategori'] == 1 ? 'selected' : '' ?>>PORT CHARGES</option>
                          <option value="2" <?= $di['kategori'] == 2 ? 'selected' : '' ?>>PORT CLEARENCE IN/OUT EXPENSE</option>
                          <option value="3" <?= $di['kategori'] == 3 ? 'selected' : '' ?>>MISCLEANNEOUS</option>
                        </select>
                      </td>
                      <td width="100px">
                        <label for="label" class="form-label">#</label><br>
                        <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                        <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  <?php } ?>
                </table>

              </div>
              <hr>
              <div>
                <button type="submit" class="btn btn-primary btn-sm btn-submit">Simpan</button>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->