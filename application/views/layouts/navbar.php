<style>
  /* This is a general rule for positioning notification badges. 
   Adjust values as needed for your specific theme. */
  .badge-counter {
    position: absolute;
    top: 5px;
    /* Adjust this value to move the badge up or down */
    right: 5px;
    /* Adjust this value to move the badge left or right */
    border-radius: 50%;
    font-size: 10px;
    padding: 3px 5px;
  }

  /* Hide the "Become the" text on screen sizes up to 767px (common mobile breakpoint) */
  @media (max-width: 767px) {
    .desktop-only {
      display: none;
    }
  }
</style>

<nav class="topnav navbar navbar-light">
  <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">

    <?php
    if ($this->uri->segment(1) != "subscription") {
    ?>
      <i class="fe fe-menu navbar-toggler-icon"></i>
    <?php
    }
    ?>
  </button>


  <ul class="nav">

    <li class="nav-item">
      <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
        <i class="fe fe-sun fe-16"></i>
      </a>
    </li>

    <?php
    $nip = $this->session->userdata('nip');

    // Count unread memos
    // $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
    $sql = "SELECT COUNT(Id) FROM memo WHERE (CONCAT(';', nip_kpd, ';') LIKE '%;$nip;%' OR CONCAT(';', nip_cc, ';') LIKE '%;$nip;%') AND (CONCAT(';', `read`, ';') NOT LIKE '%;$nip;%');";
    $query = $this->db->query($sql);
    $res2 = $query->result_array();
    $jumlah_notifikasi_memo = $res2[0]['COUNT(Id)'];

    // Count unread tasks
    // $sql4 = "SELECT COUNT(id) FROM task WHERE (`member` LIKE '%$nip%' or `pic` like '%$nip%') and activity='1'";
    $sql4 = "SELECT COUNT(id) FROM task WHERE (CONCAT(';', `member`, ';') LIKE '%;$nip;%' OR CONCAT(';', `pic`, ';') LIKE '%;$nip%') and activity='1'";
    $query4 = $this->db->query($sql4);
    $res4 = $query4->result_array();
    $jumlah_notifikasi_tello = $res4[0]['COUNT(id)'];

    $jumlah_notifikasi = $jumlah_notifikasi_memo + $jumlah_notifikasi_tello;

    if ($this->session->userdata('level_jabatan') == 3) {

      // Count unread tasks
      $sql5 = "SELECT COUNT(id_cuti) FROM cuti WHERE atasan = '$nip' AND status_atasan is NULL";
      $query5 = $this->db->query($sql5);
      $res5 = $query5->result_array();
      $jumlah_notifikasi_cuti = $res5[0]['COUNT(id_cuti)'];

      $jumlah_notifikasi = $jumlah_notifikasi + $jumlah_notifikasi_cuti;
    }
    ?>


    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle text-muted pr-0 my-2" href="#" id="navbarDropdownNotification" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fe fe-bell fe-16"></i>
        <?php if ($jumlah_notifikasi > 0): ?>
          <span class="badge badge-pill badge-danger badge-counter"><?= $jumlah_notifikasi ?></span>
        <?php endif; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownNotification">
        <a class="dropdown-item" href="<?= site_url('app/inbox') ?>">
          Inbox Memo
          <?php if ($jumlah_notifikasi_memo > 0): ?>
            <span class="badge badge-pill badge-danger float-right"><?= $jumlah_notifikasi_memo ?></span>
          <?php endif; ?>
        </a>
        <a class="dropdown-item" href="<?= site_url('task') ?>">
          Tello
          <?php if ($jumlah_notifikasi_tello > 0): ?>
            <span class="badge badge-pill badge-danger float-right"><?= $jumlah_notifikasi_tello ?></span>
          <?php endif; ?>
        </a>
        <?php
        if ($this->session->userdata('level_jabatan') == 3) {
        ?>
          <a class="dropdown-item" href="<?= site_url('cuti/data_approve_atasan_view') ?>">
            Cuti
            <?php if ($jumlah_notifikasi_cuti > 0): ?>
              <span class="badge badge-pill badge-danger float-right"><?= $jumlah_notifikasi_cuti ?></span>
            <?php endif; ?>
          </a>
        <?php
        }
        ?>
      </div>
    </li>
    <span id="memo-notification-count" data-count="<?= $jumlah_notifikasi_memo ?>" style="display:none;"></span>

    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="avatar avatar-sm mt-2">
          <!-- <img src="<?= base_url('assets') ?>/avatars/face-1.jpg" alt="..." class="avatar-img rounded-circle"> -->
          <img src="<?= base_url('assets') ?>/avatars/avatar.png" alt="..." class="avatar-img rounded-circle">
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="#"><?= $this->session->userdata('nama') ?></a>
        <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">Logout</a>
      </div>
    </li>
  </ul>
</nav>