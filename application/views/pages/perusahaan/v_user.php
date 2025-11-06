<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url('assets/') ?>progress-bar-dashboard.css">

<style>
  .col-xs-3 {
    width: 25%;
    background-color: #1b68ff;
  }


  .btn_footer_panel .tag_ {
    padding-top: 37px;
  }

  tr>th {
    /* background-color: #e91f62; */
    background-color: #3e51b4;
    color: white;
  }

  .col-centered {
    float: none;
    margin: 0 auto;
  }

  .dt-length label {
    margin-left: 8px;
    /* Adjust this value (e.g., 5px, 10px, 0.5em) as needed */
  }

  .triangle-right-success {
    margin-left: 4px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    /* border-left: 12px solid #3ad29f; */
    border-left: 12px solid #1b68ff;
    /* Green for success */
    border-bottom: 8px solid transparent;
  }

  .triangle-right-primary {
    margin-left: 4px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    /* border-left: 12px solid #1b68ff; */
    border-left: 12px solid #e81f63;

    /* Blue for primary */
    border-bottom: 8px solid transparent;
  }

  .triangle-right-secondary {
    margin-left: 4px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-left: 12px solid #6c757d;
    /* Grey for secondary */
    border-bottom: 8px solid transparent;
  }

  table.dataTable>thead>tr>th {
    padding: 0 5px 0 5px;
    height: 30px;
  }

  table.dataTable>tbody>tr>td {
    padding: 1px 5px 1px 5px;
  }


  .btn-di-td {
    padding: 0.125rem 0.25rem;
  }
</style>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> <?= $this->session->flashdata('error'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <!-- <strong><?= $this->session->flashdata('error'); ?>!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"> -->
            <span aria-hidden="true">x</span>
          </button>
        </div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> <?= $this->session->flashdata('success'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
      <?php endif; ?>
      <h1 class="page-title">User List</h1>
      <div class="card shadow mb-4">
        <!-- <div class="card-header d-flex justify-content-between align-items-center">
          <p class="card-title mb-0"><strong>List User</strong></p>
        </div> -->
        <div class="card-body" id="user">
          <!-- <div class="d-flex justify-content-end align-items-center"> -->
          <!-- <div class="d-flex align-items-center"> -->
          <div>


            <a href="#" id="addUserBtn" class="btn btn-primary">
              Add User
            </a>

          </div>
          <div class="table-responsive">
            <table id="user-table" class="table table-sm table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Username</th>
                  <th class="text-center">Cabang</th>
                  <th class="text-center">Nama Jabatan</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">#</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->