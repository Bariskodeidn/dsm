<style>
  .col-xs-3 {
    width: 25%;
    background-color: #008080;
  }

  .row {
    margin-left: 0px;
  }

  .container-fluid {
    padding-right: 0px;
    padding-left: 0px
  }

  .btn_footer_panel .tag_ {
    padding-top: 37px;
  }

  table thead th {
    background-color: #F5F5F5;
  }

  .progressbar-wrapper {
    background: #fff;
    width: 100%;
    padding-top: 10px;
    padding-bottom: 5px;
    margin-left: -12%;
  }

  .progressbar li {
    list-style-type: none;
    width: 20%;
    float: left;
    font-size: 8px;
    position: relative;
    text-align: center;
    text-transform: uppercase;
    color: #7d7d7d;
  }

  .progressbar li:before {
    width: 30px;
    height: 30px;
    content: "";
    line-height: 60px;
    border: 2px solid #7d7d7d;
    display: block;
    text-align: center;
    margin: 0 auto 3px auto;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    background-color: #fff;
  }

  .progressbar li:after {
    width: 100%;
    height: 2px;
    content: '';
    position: absolute;
    background-color: #7d7d7d;
    top: 15px;
    left: -50%;
    z-index: 0;
  }

  .progressbar li:first-child:after {
    content: none;
  }

  .progressbar li.active {
    color: black;
    font-weight: bold;
  }

  .progressbar li.active:before {
    border-color: #55b776;
    background: green;
  }

  .progressbar li.active+li:after {
    background-color: #55b776;
  }


  .progressbar li.active:before {
    background: #55b776 url(../assets/icons/check-list.png) no-repeat center center;
    background-size: 60%;
  }

  /* on progress */
  .progressbar li.onprogress {
    color: black;
    font-weight: bold;
  }

  .progressbar li.onprogress:before {
    border-color: rgb(244, 254, 64);
    background: rgb(244, 254, 64);
  }

  .progressbar li.onprogress+li:after {
    background-color: #7d7d7d;
  }


  .progressbar li.onprogress:before {
    background: rgb(244, 254, 64) url(../assets/icons/onprogress.png) no-repeat center center;
    background-size: 60%;
  }

  .progressbar li::before {
    background: #F5F5f5;
    background-size: 60%;
  }

  /* Green */
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <!-- <h1 class="page-title">Daftar Penunjukan </h1> -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <p class="card-title"><strong>List Penunjukan</strong></p>
        </div>
        <div class="card-body">
          <a href="<?= site_url('agency/create_penunjukan') ?>" class="btn btn-primary">
            <i class="fe fe-plus"></i> Buat Penunjukan
          </a>
          <div class="table-responsive">
            <table id="tablePenunjukan" class="table table-bordered table-sm" style="width:100%">
              <thead class="thead-dark">
                <tr>
                  <th width="45px">No.</th>
                  <th>Customer</th>
                  <th>Penunjukan</th>
                  <th>Progress</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="myModalMonitor">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          Monitoring Progress Penunjukan <span class="no-penunjukan"></span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="monitoring-table">

      </div>
    </div>
  </div>
</div>