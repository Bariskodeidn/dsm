<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
  .input-edit {
    border-radius: 0.25rem 0 0 0.25rem;
  }

  .btns-edit {
    border-radius: 0 0.25rem 0.25rem 0;
  }

  .info-message {
    color: gray;
    font-size: 0.875em;
    margin-top: 5px;
    display: block;
  }
</style>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Detail Perusahaan</h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header d-flex justify-content-between align-items-center">
          <p class="card-title mb-0"><strong>Perusahaan</strong></p>
          <a href="<?= base_url('perusahaan/add_user') ?>" class="btn btn-primary">Add User</a>
        </div> -->
        <div class="card-body" id="user">
          <font style="font-size:14px;">
            </br>
            <?= $this->session->flashdata('msg') ?>
            <form action="<?= base_url('perusahaan/prosses_edit_perusahaan/') ?>" id="update_perusahaan_form" method="POST" enctype="multipart/form-data">
              <input type="hidden" value="edit" name="edit">
              <input type="hidden" value="<?= $this->session->userdata('user_perusahaan_id') ?>" name="id">
              <style>
                /* Custom styling for readonly inputs to make them appear disabled */
                .is-readonly {
                  background-color: #e9ecef;
                  cursor: not-allowed;
                  opacity: 1;
                  /* Override some default browser disabled styles */
                }
              </style>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <!-- <label>Logo</label> -->
                    <br>
                    <!-- <div class="mt-3 mb-3">
                      <img class="border border-primary" src="<?= ($perusahaan->logo) ? $perusahaan->logo : $utility['logo']; ?>" alt="logo" style="width: 200px">
                    </div> -->
                    <!-- Container for side-by-side logo comparison -->
                    <div class="row">
                      <div class="col-12 col-md-6 mb-3 mb-md-0 d-flex justify-content-around align-items-center mt-3 mb-3">
                        <div class="card text-center p-4 rounded-lg shadow-md bg-white">
                          <p>Current Logo</p>
                          <img id="current_logo" class="" src="<?= ($perusahaan->logo) ? $perusahaan->logo : $utility['logo']; ?>" alt="Current Logo" style="width: 200px">
                        </div>
                      </div>
                      <div class="col-12 col-md-6 d-flex justify-content-around align-items-center mt-3 mb-3">
                        <div class="card text-center p-4 rounded-lg shadow-md bg-white">
                          <p>New Preview</p>
                          <img id="logo_preview" class="" src="#" alt="New Logo Preview" style="width: 200px; display: none;">
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center">
                      <!-- The logo input is now disabled by default and has the is-readonly class for styling -->
                      <input type="file" name="logo_perusahaan" id="logo_input" class="form-control input-edit is-readonly" onchange="previewImage(event)" disabled>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="logo_input"><i class="fe fe-edit-2"></i></button>
                    </div>
                    <span id="logo_perusahaan_error_message" class="info-message">Unggah logo perusahaan Anda dalam format <b>JPG, JPEG, PNG,</b> atau <b>GIF</b>. Ukuran file dibatasi hingga <b>2MB</b>.</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Perusahaan</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_perusahaan ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nama_perusahaan"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Singkat Perusahaan</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="nama_singkat" id="nama_singkat" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_singkat ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nama_singkat"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama PPN</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="nama_ppn" id="nama_ppn" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_ppn ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nama_ppn"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Besaran PPN (%)</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="besaran_ppn" id="besaran_ppn" class="form-control input-edit is-readonly" value="<?= $perusahaan->besaran_ppn * 100 ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="besaran_ppn"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nomor Rekening</label>
                    <div class="d-flex justify-content-center">
                      <textarea class="form-control is-readonly" name="nomor_rekening" id="nomor_rekening" rows="5" readonly><?= $perusahaan->nomor_rekening ?></textarea>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nomor_rekening"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Bank</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="nama_bank" id="nama_bank" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_bank ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nama_bank"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Alamat Perusahaan</label>
                    <div class="d-flex justify-content-center">
                      <textarea class="form-control is-readonly" name="alamat_perusahaan" id="alamat_perusahaan" rows="5" readonly><?= $perusahaan->alamat_perusahaan ?></textarea>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="alamat_perusahaan"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group ">
                    <label>Nama Akronim</label>
                    <div class="d-flex justify-content-center">
                      <input type="text" name="nama_akronim" id="nama_akronim" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_akronim ?>" readonly>
                      <button type="button" class="btn btn-secondary btns-edit" data-target="nama_akronim"><i class="fe fe-edit-2"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group ">
                    <label>COA PPN KELUARAN</label>
                    <div class="d-flex justify-content-center">
                      <!-- <input type="text" name="nama_akronim" id="nama_akronim" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_akronim ?>" readonly> -->
                      <!-- <button type="button" class="btn btn-secondary btns-edit" data-target="nama_akronim"><i class="fe fe-edit-2"></i></button> -->
                      <select class="form-control form-select" name="coa_ppn_keluaran" id="coa_ppn_Keluaran">
                        <!-- <option value="default">23011 :: PPN KELUARAN :: Default</option> -->
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group ">
                    <label>COA UTANG PPH</label>
                    <div class="d-flex justify-content-center">
                      <!-- <input type="text" name="nama_akronim" id="nama_akronim" class="form-control input-edit is-readonly" value="<?= $perusahaan->nama_akronim ?>" readonly> -->
                      <!-- <button type="button" class="btn btn-secondary btns-edit" data-target="nama_akronim"><i class="fe fe-edit-2"></i></button> -->
                      <select class="form-control form-control-select" name="coa_utang_pph" id="coa_utang_pph">
                        <!-- <option value="default">23011 :: PPN KELUARAN :: Default</option> -->
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <a class="btn btn-warning" href="<?= base_url('perusahaan/cabang') ?>"><i class="fe fe-arrow-left" aria-hidden="true"></i> Back</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </div>
            </form>
            <br>
          </font>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->