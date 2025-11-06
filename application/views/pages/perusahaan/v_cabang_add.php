<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title"><?= ($this->uri->segment(3) == false) ? 'Add' : 'Edit' ?> Cabang</h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header d-flex justify-content-between align-items-center">
          <p class="card-title mb-0"><strong>Cabang</strong></p>
          <a href="<?= base_url('perusahaan/add_user') ?>" class="btn btn-primary">Add User</a>
        </div> -->
        <div class="card-body" id="user">
          <font style="font-size:14px;">
            <?php if ($this->uri->segment(3) == false) { ?> <!-- add user -->
              <?= $this->session->flashdata('msg') ?>
              <form action="<?= base_url('perusahaan/proccess_add_cabang') ?>" method="POST">
                <input type="hidden" value="add" name="add">
                <input type="hidden" value="<?= $this->uri->segment('3') ?>" name="id">
                <table>
                  <tr>
                    <th width="300">Nama Cabang</th>
                    <td width="300"> <input type="text" value="<?php echo set_value('nama_cabang'); ?>" name="nama_cabang" class="form-control"></td>
                  </tr>
                  <tr>
                    <th width="300">Alamat Cabang</th>
                    <td width="300"> <textarea class="form-control" name="alamat_cabang" id="alamat_cabang"><?php echo set_value('alamat_cabang'); ?></textarea></td>
                  </tr>
                  <div class="form-group text-left">
                    <label for="nomor_rekening">Nomor Rekening</label>
                    <input
                      type="text"
                      id="nomor_rekening"
                      name="nomor_rekening"
                      class="form-control form-control-lg"
                      required
                      pattern="[0-9]{10,16}"
                      value="<?php echo set_value('nomor_rekening'); ?>" />
                    <span id="nomor_rekening_error_message" class="error-message"></span>
                  </div>
                  <div class="form-group text-left">
                    <label for="nama_bank">Nama Bank</label>
                    <input type="text" id="nama_bank" name="nama_bank" class="form-control form-control-lg" value="<?php echo set_value('nama_bank'); ?>" required>
                    <span id="nama_bank_error_message" class="error-message"></span>
                  </div>
                  <br>
                  <tr>
                    <th>
                      <a class="btn btn-warning" href="<?= base_url('perusahaan/cabang') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    </th>
                    <td><button type="submit" class="btn btn-primary">Submit</button></td>
                  </tr>
                </table>
              </form>
            <?php  } else if ($this->uri->segment(2) == 'edit_cabang') { ?>
              </br>
              <?= $this->session->flashdata('msg') ?>
              <form action="<?= base_url('perusahaan/prosses_edit_cabang/' . $this->uri->segment('3')) ?>" method="POST">
                <input type="hidden" value="edit" name="edit">
                <input type="hidden" value="<?= $this->uri->segment('3') ?>" name="id">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Nama Cabang</label>
                      <input type="text" name="nama_cabang" class="form-control" value="<?= $cabang->nama_cabang ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Alamat Cabang</label>
                      <textarea class="form-control" name="alamat_cabang" id="alamat_cabang"><?= $cabang->alamat_cabang ?></textarea>
                    </div>
                  </div>
                  <div class="form-group text-left">
                    <label for="nomor_rekening">Nomor Rekening</label>
                    <input
                      type="text"
                      id="nomor_rekening"
                      name="nomor_rekening"
                      class="form-control form-control-lg"
                      required
                      pattern="[0-9]{10,16}"
                      value="<?= $cabang->nomor_rekening ?>" />
                    <span id="nomor_rekening_error_message" class="error-message"></span>
                  </div>
                  <div class="form-group text-left">
                    <label for="nama_bank">Nama Bank</label>
                    <input type="text" id="nama_bank" name="nama_bank" class="form-control form-control-lg" value="<?= $cabang->nama_bank ?>" required>
                    <span id="nama_bank_error_message" class="error-message"></span>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <a class="btn btn-warning" href="<?= base_url('perusahaan/cabang') ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </div>

                </div>
              </form>
              <br>
            <?php } ?>
          </font>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->