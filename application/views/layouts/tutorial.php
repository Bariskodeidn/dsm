  <?php

  // $this->db->select('level_jabatan, COUNT(id) as user_count');
  // $this->db->from('users');
  // $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
  // $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
  // // $this->db->where('id_prsh', $this->session->userdata('user_perusahaan_id'));
  // $this->db->group_by('level_jabatan');
  // $query = $this->db->get();

  $this->db->select('level_jabatan, role_name, COUNT(id) as user_count');
  $this->db->from('users');
  $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
  $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
  $this->db->group_by('level_jabatan, role_name'); // Group by both columns
  $query = $this->db->get();
  $user_counts = [];
  foreach ($query->result() as $row) {
    $user_counts[$row->role_name] = (int)$row->user_count;
  }



  $this->db->from('users');
  $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
  $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
  $this->db->where('nama_jabatan !=', 'Super Admin');
  $total_user = $this->db->get()->num_rows(); // Get the number of rows

  $max_users_for_100_percent = 4; // Define your maximum limit
  // $max_users_for_100_percent = 5; // Define your maximum limit

  // $this->cb->from('v_coa_all');
  // $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
  // $cek_coa_cabang = $this->cb->get()->num_rows();

  $this->cb->from('v_coa_all');
  $this->cb->where('id_cabang', $this->session->userdata('kode_cabang'));
  // Add the OR conditions
  $this->cb->group_start(); // Start a WHERE group for the OR conditions
  // $this->cb->where('no_sbb', '23014');
  // $this->cb->or_where('no_sbb', '23011');
  $this->cb->where_not_in('no_sbb', ['23014', '23011']);
  $this->cb->group_end(); // End the WHERE group
  $cek_coa_cabang = $this->cb->get()->num_rows();
  $user_counts = isset($user_counts) ? $user_counts : [];
  $roles = [
    5 => 'Direktur',
    3 => 'Manager',
    2 => 'Keuangan',
    1 => 'Staff',
  ];
  $active_p = 0;


  $this->cb->from('t_cabang');
  $this->cb->where('uid', $this->session->userdata('kode_cabang'));
  $cabang_now = $this->cb->get()->row();
  $cek_saldo_awal = $cabang_now->generate_sawal;

  ?>
  <link rel="stylesheet" href="<?= base_url('assets/') ?>progress-bar-dashboard.css">
  <style>
    .triangle-right-success {
      margin-left: 4px;
      width: 0;
      height: 0;
      border-top: 8px solid transparent;
      /* border-left: 12px solid #3ad29f; */
      border-left: 12px solid #3f51b5;
      /* Green for success */
      border-bottom: 8px solid transparent;
    }

    .triangle-right-primary {
      margin-left: 4px;
      width: 0;
      height: 0;
      border-top: 8px solid transparent;
      /* border-left: 12px solid #3f51b5; */
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

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-10px);
        /* Adjust the height of the bounce */
      }

      60% {
        transform: translateY(-5px);
        /* Adjust the height of the bounce */
      }
    }

    /* --- Add this rule --- */
    .animated-chevron {
      animation: bounce 2s infinite;
      /* Apply the bounce animation */
      /* You can adjust '2s' for duration and 'infinite' to make it loop */
    }

    /* --- End of added rule --- */
  </style>
  <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <!-- <p class="card-title mb-0"><strong>Tambahkan 4 User Role (Staff, Manager, Keuangan, Direktur) untuk Memulai Hidup Baru! (<?= $total_user . '/' . $max_users_for_100_percent ?>)</strong></p> -->
      <?php
      foreach ($roles as $value => $label) {
        // if (isset($user_counts[$value]) && $user_counts[$value] >= 1) {
        if (isset($user_counts[$label]) && $user_counts[$label] >= 1) {
          continue;
        }
        if ($active_p == 0) {
          $active_p = 1;
      ?>
          <h5 class="card-title mb-0"><strong>Ayo buat Akun <?= $label ?> (<?= $total_user . '/' . $max_users_for_100_percent ?>)</strong></h5>
        <?php
        }
      }

      if ($max_users_for_100_percent == $total_user && $cek_coa_cabang == 0) { ?>
        <h5 class="card-title mb-0"><strong>Ayo buat COA Sekarang</strong></h5>

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
            if (isset($user_counts[$label]) && $user_counts[$label] >= 1) {
              // $active_fishbone = 'active';
          ?>
              <a href="#" style="text-decoration: none;">
                <div class="circle-success active" data-label="<?= $label ?>" style="background-color: #3f51b5; color:white;">
                  <div class="fe fe-user-check"></div>
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
                <div class="circle-current" data-label="<?= $label ?>" style="background-color: #e81f63; color:white;">
                  <div class="fe fe-chevrons-down animated-chevron"></div>
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

          if ($total_user >= $max_users_for_100_percent && $cek_saldo_awal == 0) {
            $button_now = base_url('financial_first/force_make_coa_sbb');
            // $label = "COA"
            ?>
            <a href="<?= $button_now ?>" style="text-decoration: none;">
              <!-- <div class="circle-current" data-label="Setting COA Dan SAWAL" style="background-color: #e81f63; color:white;"> -->
              <div class="circle-current" data-label="COA" style="background-color: #e81f63; color:white;">
                <div class="fe fe-chevrons-down"></div>
              </div>
            </a>
          <?php
            // } else if ($cek_coa_cabang == 0) {
          } else if ($cek_saldo_awal == 0) {
          ?>
            <a href="#">
              <div class="circle" data-label="COA">
                <div class="triangle-right-secondary"></div>
              </div>
            </a>
          <?php
          } else {
          ?>
            <a href="#">
              <div class="circle-success active" data-label="COA">
                <div class="triangle-right-success"></div>
              </div>
            </a>
          <?php
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