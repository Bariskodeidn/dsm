<div class="row align-items-center h-100 w-100">
  <form class="col-lg-3 col-md-4 col-10 mx-auto" action="<?= site_url('auth/proccess_register') ?>" method="post">
    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?= base_url('/') ?>">
      <img src="<?= base_url('assets') ?>/images/logo.png" alt="logo" class="w-100">
    </a>
    <br>
    <?php if ($this->session->flashdata('error')) : ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><?= $this->session->flashdata('error'); ?>!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><?= $this->session->flashdata('success'); ?>!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
    <?php endif; ?>
    <h4 class="mt-2 mb-3 text-center">Register</h4>
    <div class="form-group">
      <label for="nama">Nama</label>
      <input type="text" id="nama" name="nama" class="form-control form-control-lg" placeholder="Please enter Nama" autofocus="true">
    </div>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Please enter Username" autofocus="true">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password1" name="password" class="form-control form-control-lg" placeholder="Password">
    </div>

    <div class="form-group">
      <label for="password">Konfirmasi Password</label>
      <input type="password" id="password2" name="password_confirm" class="form-control form-control-lg" placeholder="Password">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email">
    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" id="phone" name="phone" class="form-control form-control-lg" placeholder="Phone Number">
    </div>
    <!-- <div class="form-group">
      <label for="nip">NIP</label>
      <input type="text" id="nip" name="nip" class="form-control form-control-lg" placeholder="NIP (Nomor Induk Pegawai)">
    </div> -->
    <button class="btn btn-lg btn-primary btn-block btn-register" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted text-center">Sudah punya akun? <a href="<?= base_url('auth/') ?>register">Masuk dengan Akun Perusahaan Anda</a></p>
    <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
  </form>
</div>