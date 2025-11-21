<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>Create Invoice</strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('invoice') ?>" class="btn btn-warning mb-4">Kembali</a>
          <form action="<?= base_url('invoice/insert') ?>" method="post" enctype="multipart/form-data" id="form-invoice">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="label" class="form-label">Tanggal</label>
                  <input type="date" name="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="label" class="form-label">Surat Penunjukan</label>
                  <select name="penunjukan" id="penunjukan" class="form-control select2">
                    <option value=""> :: SURAT PENUNJUKAN ::</option>
                    <?php foreach ($penunjukan->result_array() as $pen) { ?>
                      <option value="<?= $pen['Id'] ?>"><?= $pen['no_surat'] ?></option>
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
                      <option value="<?= $cust['Id'] ?>"><?= $cust['nama_customer'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="label" class="form-label">Nama Kapal</label>
                  <input type="text" name="kapal" id="kapal" class="form-control">
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
                <tr class="tr_clone">
                  <td>
                    <label for="label" class="form-label">Uraian Pekerjaan</label>
                    <input type="text" class="form-control" name="uraian[]" id="uraian">
                  </td>
                  <td width="100px">
                    <label for="label" class="form-label">Satuan</label>
                    <input type="text" class="form-control uang" name="satuan[]" id="satuan">
                  </td>
                  <td width="300px">
                    <label for="label" class="form-label">Harga</label>
                    <input type="text" class="form-control uang" name="harga[]" id="harga">
                  </td>
                  <td width="100px">
                    <label for="label" class="form-label">#</label><br>
                    <button type="button" class="btn btn-danger btn-sm hapusRow"><i class="fe fe-trash"></i></button>
                    <button type="button" class="btn btn-success btn-sm add-row"><i class="fe fe-plus" aria-hidden="true"></i></button>
                  </td>
                </tr>
              </table>
            </div>
            <hr>
            <div>
              <button type="submit" class="btn btn-primary btn-sm btn-submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->