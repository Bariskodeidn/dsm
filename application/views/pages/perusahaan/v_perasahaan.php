<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<style>
  .col-xs-3 {
    width: 25%;
    background-color: #004e81;
  }


  .btn_footer_panel .tag_ {
    padding-top: 37px;
  }

  tr>th {
    background-color: #004e81;
    color: white;
  }

  .col-centered {
    float: none;
    margin: 0 auto;
  }
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <h1 class="page-title">Perusahaan List</h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header">
          <p class="card-title"><strong>List Perusahaan</strong></p>
        </div> -->
        <div class="card-body">
          <div class="row text-center mb-5">
            <div class="col-md-3">
              <button class="btn btn-primary btn-block" onclick="showUser()">User List</button>
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary btn-block" onclick="showTeam()">Team List</button>
            </div>
            <?php
            if ($this->session->userdata('level_jabatan') >= 3) {
            ?>
              <div class="col-md-3">
                <button class="btn btn-primary btn-block" onclick="showApproval()">Approval List</button>
              </div>
            <?php
            }
            ?>
            <div class="col-md-3">
              <button class="btn btn-success btn-block" onclick="showExport()"><i class="fa fa-file-excel-o"></i> Export List</button>
            </div>
          </div>
        </div>
        <div class="card-body" id="user">
          <div class="table-responsive">
            <table id="user-table" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nip</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Tanggal</th>
                  <th class="text-center">Waktu</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Lokasi</th>
                  <th class="text-center">Tipe</th>
                  <th class="text-center">Gambar</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->