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
      <!-- <h1 class="page-title">User List</h1> -->
      <?php
      if ($total_user < 4 || $cek_coa_cabang == 0) {
      ?>
        <?php
        $user_counts = isset($user_counts) ? $user_counts : [];
        $roles = [

          5 => 'Direktur',
          3 => 'Keuangan',
          2 => 'Manager',
          1 => 'Staff',

        ];
        $active_p = 0;

        ?>

        <div class="card shadow mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <!-- <p class="card-title mb-0"><strong>Tambahkan 4 User Role (Staff, Manager, Keuangan, Direktur) untuk Memulai Hidup Baru! (<?= $total_user . '/' . $max_users_for_100_percent ?>)</strong></p> -->
            <?php
            foreach ($roles as $value => $label) {
              if (isset($user_counts[$value]) && $user_counts[$value] >= 1) {
                continue;
              }
              if ($active_p == 0) {
                $active_p = 1;
            ?>
                <p class="card-title mb-0"><strong>Ayo buat Akun <?= $label ?> (<?= $total_user . '/' . $max_users_for_100_percent ?>)</strong></p>
              <?php
              }
            }

            if ($max_users_for_100_percent == $total_user && $cek_coa_cabang == 0) { ?>
              <p class="card-title mb-0"><strong>Ayo buat COA Sekarang</strong></p>

            <?php
            } ?>
          </div>
          <div class="card-body">
            <div class="container">
              <div class="progress-container mx-auto">
                <div class="progress" id="progress"></div>
                <?php
                $i = 1;
                $active_href = 0;
                foreach ($roles as $value => $label) {
                  if (isset($user_counts[$value]) && $user_counts[$value] >= 1) {
                    // $active_fishbone = 'active';
                ?>
                    <a href="#">
                      <div class="circle-success active" data-label="<?= $label ?>">
                        <div class="triangle-right-success"></div>
                      </div>
                    </a>
                  <?php
                    $i++;
                    continue;
                  }

                  if ($active_href == 0) {
                    $active_href = 1;
                    $button_now = base_url('perusahaan/add_user/' . $value . '/' . $label);

                    $label_now = "<a class='btn btn-pink' href='$button_now'>" . $label . "</a>";
                  ?>
                    <a href="<?= $button_now ?>">
                      <div class="circle-current" data-label="<?= $label ?>">
                        <div class="triangle-right-primary"></div>
                      </div>
                    </a>
                  <?php
                  } else {
                  ?>
                    <a href="#">
                      <div class="circle" data-label="<?= $label ?>">
                        <div class="triangle-right-secondary"></div>
                      </div>
                    </a>
                  <?php
                  }
                  $i++;
                }

                if ($max_users_for_100_percent < $total_user) {
                  ?>
                  <a href="#">
                    <div class="circle" data-label="COA">
                      <div class="triangle-right-secondary"></div>
                    </div>
                  </a>
                  <?php
                } else if ($max_users_for_100_percent == $total_user) {
                  $button_now = base_url('financial_first/force_make_coa_sbb');
                  // $label = "COA"
                  if ($cek_coa_cabang == 0) {
                  ?>
                    <a href="<?= $button_now ?>">
                      <div class="circle-current" data-label="Setting COA Dan SAWAL">
                        <div class="triangle-right-primary"></div>
                      </div>
                    </a>
                  <?php
                  } else {
                  ?>
                    <a href="<?= $button_now ?>">
                      <div class="circle-success active" data-label="Setting COA Dan SAWAL">
                        <div class="triangle-right-success"></div>
                      </div>
                    </a>
                <?php
                  }
                }

                ?>
                <!-- <a href="#">
                  <div class="circle" data-label="Setting SAWAL">
                    <div class="triangle-right-secondary"></div>
                  </div>
                </a> -->
                <!-- <div class="circle active" data-label="User">1</div>
              <div class="circle" data-label="Perusahaan">2</div>
              <div class="circle" data-label="Cabang">3</div> -->
              </div>
            </div>
          </div>
        </div>

      <?php
      }
      ?>
      <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <p class="card-title mb-0"><strong>List User</strong></p>
          <?php
          // if ($total_user <= 4) {
          ?>
          <!-- <a href="<?= base_url('perusahaan/add_user') ?>" class="btn btn-primary">Add User</a> -->
          <?php
          // } else {
          ?>
          <a href="#" id="addUserBtn" class="btn btn-primary">
            Add User
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16">
              <path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z" />
            </svg>
          </a>
          <?php
          // }
          ?>
        </div>
        <div class="card-body" id="user">
          <div class="table-responsive">
            <table id="user-table" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Username</th>
                  <th class="text-center">Nip</th>
                  <th class="text-center">Nama Jabatan</th>
                  <!-- <th class="text-center">User Access</th> -->
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