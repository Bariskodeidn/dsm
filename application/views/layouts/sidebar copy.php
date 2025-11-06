<style>
  .navbar-light .navbar-nav .nav-link.active {
    background: #f8f9fa;
    color: #1b68ff;
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
    $this->cb = $this->load->database('corebank', TRUE);

    $this->db->from('users');
    $this->db->join($this->cb->database . '.t_cabang', 't_cabang.uid = users.id_cabang');
    $this->db->where('t_cabang.id_perusahaan', $this->session->userdata('user_perusahaan_id'));
    $total_user = $this->db->get()->num_rows(); // Get the number of rows
    if ($total_user >= 5) {
    ?>
      <ul class="navbar-nav flex-fill w-100 mb-2">
        <?php
        if ($menus) {
          foreach ($menus as $menu): ?>
            <?php if (empty($menu->submenus)):
              $url = $menu->url;
              $mahkota = '';
              if ($menu->premium == 1 && $this->session->userdata('is_premium')) {
                $url = $menu->url;
                $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
              } else if ($menu->premium == 1 && !$this->session->userdata('is_premium')) {
                $url = '';
                $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
              }
            ?>
              <li class="nav-item w-100">
                <!-- <a class="nav-link <?= ($controller == $menu->controller) ? 'active' : '' ?>" href="<?= site_url($menu->url) ?>"> -->
                <a class="nav-link <?= ($controller == $menu->controller) ? 'active' : '' ?>" href="<?= site_url($url) ?>">
                  <i class="<?= $menu->icon ?>"></i>
                  <span class="ml-3 item-text"><?= $menu->menu_name ?> <?= $mahkota ?></span>
                </a>
              </li>
            <?php else:
              $url = $menu->url;
              $mahkota = '';

              if ($menu->premium == 1 && $this->session->userdata('is_premium')) {
                $url = $menu->url;
                $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
              } else if ($menu->premium == 1 && !$this->session->userdata('is_premium')) {
                $url = '';
                $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
              } else
            ?>
              <li class="nav-item <?= ($controller == $menu->controller) ? 'active' : '' ?> dropdown">
                <!-- <a href="#<?= $menu->url ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"> -->
                <a href="#<?= $url ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                  <i class="<?= $menu->icon ?>"></i>
                  <span class="ml-3 item-text"><?= $menu->menu_name ?> <?= $mahkota ?></span>
                </a>
                <ul class="collapse <?= ($controller == $menu->controller) ? 'show' : '' ?> list-unstyled pl-4 w-100" id="<?= $menu->url ?>">
                  <?php foreach ($menu->submenus as $submenu):
                    $url = $submenu->url;
                    $mahkota = '';
                    if ($submenu->premium == 1 && $this->session->userdata('is_premium')) {
                      $url = $submenu->url;
                      $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
                    } else if ($submenu->premium == 1 && !$this->session->userdata('is_premium')) {
                      $url = '';
                      $mahkota = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>';
                    } else
                  ?>
                    <li class="nav-item">
                      <!-- <a class="nav-link <?= ($current_uri == $submenu->url) ? 'active' : '' ?> pl-3" href="<?= site_url($submenu->url) ?>"><i class="<?= $submenu->icon ?>"></i><span class="ml-1 item-text"><?= $submenu->menu_name ?></span> -->
                      <a class="nav-link <?= ($current_uri == $submenu->url) ? 'active' : '' ?> pl-3" href="<?= site_url($url) ?>"><i class="<?= $submenu->icon ?>"></i><span class="ml-1 item-text"><?= $submenu->menu_name ?> <?= $mahkota ?></span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endif; ?>
        <?php endforeach;
        } ?>
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