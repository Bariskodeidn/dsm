  <!-- Sweetalert2 -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/sweetalert2/css/sweetalert2.min.css">

  <style>
    /* Overlay for the modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      /* Semi-transparent black */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal-overlay.show {
      opacity: 1;
      visibility: visible;
    }

    /* Modal content box */
    .modal-content {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 0.75rem;
      /* rounded-xl */
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      /* shadow-2xl */
      max-width: 90%;
      width: 600px;
      /* Increased width for a larger modal */
      max-height: 80vh;
      /* Set a max height for scrollability */
      overflow-y: auto;
      /* Enable vertical scrolling */
      transform: translateY(-20px);
      opacity: 0;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .modal-overlay.show .modal-content {
      transform: translateY(0);
      opacity: 1;
    }
  </style>

  <div class="row align-items-center h-100 w-100 m-0">
    <!-- <form class="col-lg-3 col-md-4 col-10 mx-auto" action="<?= site_url('auth/login') ?>" method="post"> -->
    <form class="col-lg-3 col-md-4 col-10 mx-auto" method="POST" action="<?= site_url('auth/login') ?>">
      <div class="card shadow p-4">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?= base_url('/') ?>">
          <img src="<?= base_url('assets') ?>/images/logo.png" alt="logo" class="w-50">
        </a>
        <br>
        <?php if ($this->session->flashdata('error')) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?= $this->session->flashdata('error'); ?>!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
        <?php endif; ?>
        <div id="usernameSection">
          <div class=" form-group">
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Please enter username" autofocus="true">
          </div>
        </div>
        <div id="passwordSection">
          <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" autofocus>
          </div>
        </div>

        <button class="btn btn-lg btn-primary btn-block btn-login" type="submit">Login</button>
        <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
      </div>
    </form>
  </div>
  <!-- Sweetalert -->
  <script src="<?= base_url('assets') ?>/sweetalert2/js/sweetalert2.all.min.js"></script>