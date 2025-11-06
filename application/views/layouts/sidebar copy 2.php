<style>
  .navbar-light .navbar-nav .nav-link.active {
    background: #f8f9fa;
    color: #1b68ff;
  }


  /* Optional: Add some styling to visually indicate disabled links */
  .disabled-link {
    pointer-events: none;
    /* Prevents click events */
    opacity: 0.6;
    /* Makes it look slightly faded */
    cursor: not-allowed;
    /* Changes cursor on hover */
  }
</style>



<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
  <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
    <i class="fe fe-x"><span class="sr-only"></span></i>
  </a>
  <nav class="vertnav navbar navbar-light">
    <!-- nav bar -->
    <div class="w-100 mb-4 d-flex">
      <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?= site_url('/') ?>">
        <img src="<?php echo ($this->session->userdata('icon')) ? $this->session->userdata('icon') : $utility['logo']; ?>" alt="logo" style="width: 70%;">
      </a>
    </div>

    <?php
    $current_uri = uri_string();
    $controller = $this->uri->segment(1);
    ?>

    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item w-100">
        <a class="nav-link <?= ($current_uri == 'home' or $current_uri == '') ? 'active' : '' ?>" href="<?= site_url('home') ?>">
          <i class="fe fe-home fe-16"></i>
          <span class="ml-3 item-text">Dashboard</span>
        </a>
      </li>
    </ul>
    <?php
    // Assuming $this->cb and $this->db are already loaded and configured
    $this->cb = $this->load->database('corebank', TRUE);

    $this->db->from('users');
    $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_user = $this->db->get()->num_rows();

    // Define the crown SVG once
    $crown_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';

    // Check if user is premium once
    $is_premium_user = $this->session->userdata('is_premium');

    $this->cb->from('t_cabang');
    $this->cb->where('uid', $this->session->userdata('kode_cabang'));
    $cabang_now = $this->cb->get()->row();
    $cek_saldo_awal = $cabang_now->generate_sawal;

    if ($total_user >= 5 && $cek_saldo_awal == 1) {
    ?>
      <ul class="navbar-nav flex-fill w-100 mb-2">
        <?php if ($menus): ?>
          <?php foreach ($menus as $menu): ?>
            <?php
            $menu_url = $menu->url;
            $menu_mahkota = '';
            $menu_disabled_class = '';
            $menu_onclick_attr = ''; // To show a SweetAlert for premium features

            // Determine URL and crown for main menu
            if ($menu->premium == 1) { // If it's a premium menu
              $menu_mahkota = $crown_svg;
              if (!$is_premium_user) { // If user is NOT premium
                $menu_url = '#'; // Change URL to a placeholder
                $menu_disabled_class = 'disabled-link'; // Add a class for styling/JS
                $menu_onclick_attr = "onclick=\"Swal.fire('Fitur Premium', 'Fitur ini hanya tersedia untuk pengguna premium.', 'info'); return false;\"";
              }
            }
            ?>

            <?php if (empty($menu->submenus)): ?>
              <li class="nav-item w-100">
                <a class="nav-link <?= ($controller == $menu->controller) ? 'active' : '' ?> <?= $menu_disabled_class ?>"
                  href="<?= site_url($menu_url) ?>" <?= $menu_onclick_attr ?>>
                  <i class="<?= $menu->icon ?>"></i>
                  <span class="ml-3 item-text"><?= $menu->menu_name ?> <?= $menu_mahkota ?>
                    <?php
                    if ($menu->menu_name == "Tello") {
                      $nip = $this->session->userdata('nip');

                      // $sql4 = "SELECT COUNT(id) FROM task WHERE (FIND_IN_SET('$nip', REPLACE(`member`, ';', ',')) > 0 OR FIND_IN_SET('$nip', REPLACE(`pic`, ';', ',')) > 0) AND activity = '1'";

                      $sql4 = "SELECT COUNT(id) FROM task WHERE (`member` LIKE '%$nip%' or `pic` like '%$nip%') and activity='1'";
                      // $sql4 = "SELECT COUNT(id) FROM task WHERE (`member` LIKE '%$nip%') and activity='1'";
                      $query4 = $this->db->query($sql4);
                      $res4 = $query4->result_array();
                      $result4 = $res4[0]['COUNT(id)'];
                      $jumlah_notifikasi = $result4;
                      // $jumlah_notifikasi = 5;
                      if ($jumlah_notifikasi > 0) {
                    ?>
                        <span class="badge rounded-pill bg-danger text-white ml-2"><?= $jumlah_notifikasi ?></span>
                    <?php
                      }
                    }
                    ?>
                  </span>
                </a>
              </li>
            <?php else: // Has submenus 
            ?>
              <li class="nav-item <?= ($controller == $menu->controller) ? 'active' : '' ?> dropdown">
                <a href="#<?= $menu->url ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link <?= $menu_disabled_class ?>"
                  <?= $menu_onclick_attr ?>>
                  <i class="<?= $menu->icon ?>"></i>
                  <span class="ml-3 item-text"><?= $menu->menu_name ?> <?= $menu_mahkota ?>
                    <?php
                    if ($menu->menu_name == "Digital Memo") {
                      $nip = $this->session->userdata('nip');
                      // Mengubah string nip_cc dengan mengganti ';' menjadi ',' agar bisa dipakai FIND_IN_SET
                      // $sql = "SELECT COUNT(Id) FROM memo WHERE FIND_IN_SET('$nip', REPLACE(nip_cc, ';', ',')) AND NOT FIND_IN_SET('$nip', REPLACE(`read`, ';', ',')) = 0;";
                      $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
                      // $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_kpd LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
                      // $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
                      $query = $this->db->query($sql);
                      $res2 = $query->result_array();
                      $result = $res2[0]['COUNT(Id)'];
                      $jumlah_notifikasi = $result;
                      // $jumlah_notifikasi = 5;
                      if ($jumlah_notifikasi > 0) {
                    ?>
                        <span class="badge rounded-pill bg-danger text-white ml-2"><?= $jumlah_notifikasi ?></span>
                    <?php
                      }
                    } ?>
                  </span>
                </a>
                <ul class="collapse <?= ($controller == $menu->controller) ? 'show' : '' ?> list-unstyled pl-4 w-100" id="<?= $menu->url ?>">
                  <?php foreach ($menu->submenus as $submenu): ?>
                    <?php
                    $submenu_url = $submenu->url;
                    $submenu_mahkota = '';
                    $submenu_disabled_class = '';
                    $submenu_onclick_attr = '';

                    // Determine URL and crown for submenu
                    if ($submenu->premium == 1) { // If it's a premium submenu
                      $submenu_mahkota = $crown_svg;
                      if (!$is_premium_user) { // If user is NOT premium
                        $submenu_url = '#'; // Change URL to a placeholder
                        $submenu_disabled_class = 'disabled-link';
                        $submenu_onclick_attr = "onclick=\"Swal.fire('Fitur Premium', 'Fitur ini hanya tersedia untuk pengguna premium.', 'info'); return false;\"";
                      }
                    }
                    ?>
                    <li class="nav-item">
                      <a class="nav-link <?= ($current_uri == $submenu->url) ? 'active' : '' ?> pl-3 <?= $submenu_disabled_class ?>"
                        href="<?= site_url($submenu_url) ?>" <?= $submenu_onclick_attr ?>>
                        <i class="<?= $submenu->icon ?>"></i>
                        <span class="ml-1 item-text"><?= $submenu->menu_name ?> <?= $submenu_mahkota ?>
                        </span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    <?php
    }
    ?>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item w-100">
        <a class="nav-link" href="<?= site_url('auth/logout') ?>">
          <i class="fe fe-power fe-16"></i>
          <span class="ml-3 item-text">Logout</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>