<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Buat Invoice</strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('agency/penunjukan') ?>" class="btn btn-warning mb-4">Kembali</a>
          <form action="<?= base_url('pda/insert_invoice/') . $pda['Id'] ?>" method="post" enctype="multipart/form-data" id="form-invoice">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">Tanggal</label>
                  <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Customer</label>
                  <select name="customer" id="customer" class="form-control select2">
                    <?php
                    foreach ($customer as $cust) {
                    ?>
                      <option value="<?= $cust['Id'] ?>" <?= $cust['Id'] == $pda['id_cust'] ? 'selected' : '' ?>><?= $cust['nama_customer'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="label" class="form-label">Nama Kapal</label>
                  <input type="text" name="kapal" id="kapal" class="form-control" value="<?= $pda['nama_kapal'] ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Jumlah Muatan BS</label>
                  <input type="text" name="jml_muatan_bs" id="jml_muatan_bs" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Pelabuhan Muat BS</label>
                  <input type="text" name="pel_muat_bs" id="pel_muat_bs" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Pelabuhan Bongkar BS</label>
                  <input type="text" name="pel_bongkar_bs" id="pel_bongkar_bs" class="form-control">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Jumlah Muatan BB</label>
                  <input type="text" name="jml_muatan_bb" id="jml_muatan_bb" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Pelabuhan Muat BB</label>
                  <input type="text" name="pel_muat_bb" id="pel_muat_bb" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Pelabuhan Bongkar BB</label>
                  <input type="text" name="pel_bongkar_bb" id="pel_bongkar_bb" class="form-control">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Cargo</label>
                  <input type="text" name="cargo" id="cargo" class="form-control">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">TA/NOR</label>
                  <input type="date" name="ta_nor" id="ta_nor" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">TD</label>
                  <input type="date" name="td" id="td" class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">Materai</label>
                  <select name="materai" id="materai" class="form-control">
                    <option value="1">YA</option>
                    <option value="0">TIDAK</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">PPN 12%</label>
                  <select name="ppn" id="ppn" class="form-control">
                    <option value="1">YA</option>
                    <option value="0">TIDAK</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">PPH</label>
                  <input type="text" name="pph" id="pph" class="form-control uang" value="0">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">Down Payment</label>
                  <input type="text" name="dp" id="dp" class="form-control uang" value="0">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="label" class="form-label">Note</label>
                  <textarea name="note" id="note" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <table class="table" id="uraian-invoice">
                <?php
                $harga_jual = json_decode($pda['harga_jual']);
                $desc = $harga_jual->desc;
                $agency_remuneration = $harga_jual->agency_remuneration;
                foreach ($desc->id_desc as $key => $val) {
                  $item_pda_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
                ?>
                  <tr class="tr_clone">
                    <td width="250px">
                      <label for="label" class="form-label">Uraian Pekerjaan</label>
                      <textarea name="uraian[]" id="uraian" class="form-control"><?= $item_pda_desc['desc'] ?></textarea>
                    </td>
                    <td>
                      <label for="label" class="form-label">Mulai</label>
                      <input type="date" class="form-control" name="mulai[]" id="mulai">
                    </td>
                    <td>
                      <label for="label" class="form-label">Selesai</label>
                      <input type="date" class="form-control" name="selesai[]" id="selesai">
                    </td>
                    <td width="60px">
                      <label for="label" class="form-label">Satuan</label>
                      <input type="text" class="form-control uang" name="satuan[]" id="satuan" value="<?= $desc->activity[$key] ? $desc->activity[$key] : 1 ?>">
                    </td>
                    <td width="150px">
                      <label for="label" class="form-label">Harga</label>
                      <input type="text" class="form-control uang" name="harga[]" id="harga" value="<?= $desc->amount_desc[$key] ? $desc->amount_desc[$key] : 0 ?>">
                    </td>
                    <td width="">
                      <label for="label" class="form-label">Kategori</label>
                      <select name="kategori[]" id="kategori" class="form-control">
                        <option value="1">PORT CHARGES</option>
                        <option value="2">PORT CLEARENCE IN/OUT EXPENSE</option>
                        <option value="3">MISCLEANNEOUS</option>
                      </select>
                    </td>
                    <td width="100px">
                      <label for="label" class="form-label">#</label><br>
                      <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                    </td>
                  </tr>
                <?php } ?>

                <?php
                $harga_jual = json_decode($pda['harga_jual']);
                $desc = $harga_jual->desc;
                $agency_remuneration = $harga_jual->agency_remuneration;
                foreach ($agency_remuneration->desc as $key => $val) {
                  $item_pda_desc = $this->db->get_where('t_item_pda', ['Id' => $val])->row_array();
                ?>
                  <tr class="tr_clone">
                    <td>
                      <label for="label" class="form-label">Uraian Pekerjaan</label>
                      <textarea name="uraian[]" id="uraian" class="form-control"><?= $item_pda_desc['desc'] ?></textarea>
                    </td>
                    <td>
                      <label for="label" class="form-label">Mulai</label>
                      <?php
                      $mulai = $agency_remuneration->tanggal_mulai[$key] != "" ? date('Y-m-d', strtotime($agency_remuneration->tanggal_mulai[$key])) : "";
                      $selesai = $agency_remuneration->tanggal_selesai[$key] != "" ? date('Y-m-d', strtotime($agency_remuneration->tanggal_selesai[$key])) : "";
                      ?>
                      <input type="date" class="form-control" name="mulai[]" id="mulai" value="<?= $mulai ?>">
                    </td>
                    <td>
                      <label for="label" class="form-label">selesai</label>
                      <?php
                      $mulai = $agency_remuneration->tanggal_mulai[$key] != "" ? date('Y-m-d', strtotime($agency_remuneration->tanggal_mulai[$key])) : "";
                      $selesai = $agency_remuneration->tanggal_selesai[$key] != "" ? date('Y-m-d', strtotime($agency_remuneration->tanggal_selesai[$key])) : "";
                      ?>
                      <input type="date" class="form-control" name="selesai[]" id="selesai" value="<?= $selesai ?>">
                    </td>
                    <td width="">
                      <label for="label" class="form-label">Satuan</label>
                      <input type="text" class="form-control uang" name="satuan[]" id="satuan" value="<?= $agency_remuneration->qty[$key] ? $agency_remuneration->qty[$key] : 1 ?>">
                    </td>
                    <td width="">
                      <label for="label" class="form-label">Harga</label>
                      <input type="text" class="form-control uang" name="harga[]" id="harga" value="<?= $agency_remuneration->amount[$key] ?>">
                    </td>
                    <td width="">
                      <label for="label" class="form-label">Kategori</label>
                      <select name="kategori[]" id="kategori" class="form-control">
                        <option value="1">PORT CHARGES</option>
                        <option value="2">PORT CLEARENCE IN/OUT EXPENSE</option>
                        <option value="3">MISCLEANNEOUS</option>
                      </select>
                    </td>
                    <td width="100px">
                      <label for="label" class="form-label">#</label><br>
                      <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </div>
            <hr>
            <div class="">
              <button type="submit" class="btn btn-primary btn-sm btn-submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->