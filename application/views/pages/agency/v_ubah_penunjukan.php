<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Ubah Penunjukan <?= $detail['no_surat'] ?></strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('agency/penunjukan') ?>" class="btn btn-warning mb-4">Kembali</a>

          <form action="<?= base_url('agency/update_penunjukan/') . $detail['Id'] ?>" enctype="multipart/form-data" method="post">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="form-label">Cabang</label>
                      <select class="form-control select2" name="cabang" id="cabang">
                        <?php if ($cabang->num_rows() > 0) {
                          foreach ($cabang->result_array() as $cab) {
                        ?>
                            <option value="<?= $cab['Id'] ?>" <?= $detail['id_cabang'] == $cab['Id'] ? 'selected' : '' ?>><?= $cab['nama'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="form-label">Customer</label>
                      <select class="form-control select2" name="customer" id="customer">
                        <option value=""> Pilih Customer </option>
                        <?php if ($customer->num_rows() > 0) {
                          foreach ($customer->result_array() as $cust) {
                        ?>
                            <option value="<?= $cust['Id'] ?>" <?= $detail['customer'] == $cust['Id'] ? 'selected' : '' ?>><?= $cust['nama_customer'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="form-label">Agency</label>
                      <select class="form-control select2" name="agency" id="agency">
                        <option value=""> Pilih Agency </option>
                        <?php if ($agency->num_rows() > 0) {
                          foreach ($agency->result_array() as $ag) {
                        ?>
                            <option value="<?= $ag['Id'] ?>" <?= $detail['agency'] == $ag['Id'] ? 'selected' : '' ?>><?= $ag['nama'] ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Type Kapal</label>
                      <select name="type" id="type" class="form-control select2">
                        <option value=""> :: Pilih Type Kapal</option>
                        <?php foreach ($kategori_kapal as $kat) : ?>
                          <option value="<?= $kat->Id ?>" <?= $kat->Id == $detail['type'] ? 'selected' : '' ?>><?= $kat->nama_kategori ?></option>
                        <?php endforeach ?>
                      </select>
                      <small class="error" id="type_error" style="color:red"></small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">Nama Kapal <span id="loading" style="display:none; color: blue; font-style: italic;">(Loading...)</span></label>
                      <select name="kapal" id="kapal" class="form-control select2">
                        <option value=""> :: Pilih Kapal</option>
                        <?php foreach ($kapal as $kap) : ?>
                          <option value="<?= $kap->Id ?>" <?= $kap->Id == $detail['nama_kapal'] ? 'selected' : '' ?>><?= $kap->name ?></option>
                        <?php endforeach ?>
                      </select>
                      <small class="error" id="kapal_error" style="color:red"></small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="form-label">Surat Penawaran</label>
                      <select class="form-control select2" name="surat-penawaran" id="surat-penawaran">
                        <option value="0">Tidak ada surat penawaran</option>
                        <?php
                        foreach ($penawaran->result_array() as $p) {
                        ?>
                          <option value="<?= $p['Id'] ?>" <?= $detail['penawaran'] == $p['Id'] ? 'selected' : '' ?>><?= $p['no_surat'] ?></option>
                        <?php
                        } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="form-label">No. Surat Penunjukan</label>
                      <input type="text" class="form-control" name="surat-penunjukan" id="surat-penunjukan" value="<?= $detail['no_surat'] ?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class=" form-group">
                      <label for="form-label">Surat Penunjukan</label>
                      <input type="file" class="form-control" name="file-penunjukan-ubah" id="file-penunjukan">
                      <span>File: <a href="<?= base_url('upload/penunjukan/') . $detail['file_name'] ?>" target="_blank"><?= $detail['file'] ?></a></span>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="form-label">Jenis</label>
                      <select name="jenis" id="jenis" class="form-control select2">
                        <option value=""> :: PILIH JENIS :: </option>
                        <option value="1" <?= $detail['jenis'] == 1 ? 'selected' : '' ?>>LONGTOWING</option>
                        <option value="2" <?= $detail['jenis'] == 2 ? 'selected' : '' ?>>STS</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="port" class="form-label">Port</label>
                      <select name="port" id="port" class="form-control select2">
                        <option value=""> -- Pilih Port -- </option>
                        <?php if ($port->num_rows() > 0) {
                          foreach ($port->result_array() as $val) {
                        ?>
                            <option value="<?= $val['Id'] ?>" <?= $data_pda['port'] == $val['Id'] ? 'selected' : '' ?>><?= $val['nama'] ?></option>
                          <?php }
                        } else { ?>
                          <option value="" disabled>Data tidak ditemukan</option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class=" form-group">
                      <label for="form-label">ETA</label>
                      <input type="date" class="form-control" name="eta" id="eta" value="<?= $data_pda ? $data_pda['eta'] : '' ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class=" form-group">
                      <label for="grt" class="form-label">GRT</label>
                      <input type="text" class="form-control uang" name="grt" id="grt" value="<?= $data_pda ? number_format($data_pda['grt']) : '' ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class=" form-group">
                      <label for="grt_barge" class="form-label">GRT BARGE</label>
                      <input type="text" class="form-control nominal" name="grt_barge" id="grt_barge" value="<?= $data_pda ? number_format($data_pda['grt_barge']) : '' ?>">
                      <small class="error" id="grt_barge_error" style="color:red"></small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm btn-submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->