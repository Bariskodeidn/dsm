<style>
  input {
    text-align: center;
  }
</style>
<div class="row align-items-center h-100 w-100 m-0">
  <form class="col-lg-3 col-md-4 col-10 mx-auto" action="<?= site_url('auth/cek_token') ?>" method="post">
    <div class="card shadow p-4">
      <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?= base_url('/') ?>">
        <img src="<?= base_url('assets') ?>/images/logo.png" alt="logo" class="w-100">
        <br>
        <h4>Verifikasi Akun</h4>
      </a>
      <br>
      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong><?= $this->session->flashdata('error'); ?>!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="inputEmail" class="sr-only">Token</label>
        <input type="text" id="token" name="token" class="form-control form-control-lg" placeholder="Please enter Token" autofocus="true">
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Verifikasi</button>
      <!-- <div class="form-group d-flex justify-content-center"> -->
      <button id="resend-code-btn" type="button" class="btn btn-lg btn-outline-pink btn-block">Kirim Ulang Kode</button>
      <!-- </div> -->
      <p class="mt-5 mb-3 text-muted text-center">Sudah punya akun? <a href="<?= base_url('auth/logout') ?>">Masuk dengan Akun Perusahaan Anda</a></p>
      <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
    </div>
  </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    const resendBtn = $('#resend-code-btn');
    const cooldownDuration = 3 * 60; // 3 minutes in seconds
    const cooldownKey = 'resend_cooldown_end_time';

    function startCooldown() {
      // Set the cooldown end time in localStorage
      const endTime = Date.now() + (cooldownDuration * 1000);
      localStorage.setItem(cooldownKey, endTime);

      // Disable the button and start the countdown
      let timeLeft = cooldownDuration;
      resendBtn.prop('disabled', true);
      resendBtn.css('cursor', 'not-allowed');

      const timer = setInterval(function() {
        const now = Date.now();
        const remainingTime = Math.ceil((endTime - now) / 1000);

        if (remainingTime <= 0) {
          clearInterval(timer);
          resendBtn.prop('disabled', false);
          resendBtn.css('cursor', 'pointer');
          resendBtn.text('Kirim Ulang Kode');
          localStorage.removeItem(cooldownKey); // Clean up localStorage
        } else {
          const minutes = Math.floor(remainingTime / 60);
          const seconds = remainingTime % 60;
          resendBtn.text(`Tunggu ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
        }
      }, 1000);
    }

    // Check on page load if a cooldown is already active
    const storedEndTime = localStorage.getItem(cooldownKey);
    if (storedEndTime) {
      const now = Date.now();
      const remainingTime = Math.ceil((storedEndTime - now) / 1000);

      if (remainingTime > 0) {
        // If a cooldown is active, start the timer immediately
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        resendBtn.text(`Tunggu ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
        resendBtn.prop('disabled', true);
        resendBtn.css('cursor', 'not-allowed');
        startCooldown(); // Restart the timer
      } else {
        // Cooldown has expired, remove the key and reset the button
        localStorage.removeItem(cooldownKey);
        resendBtn.prop('disabled', false);
        resendBtn.css('cursor', 'pointer');
        resendBtn.text('Kirim Ulang Kode');
      }
    }

    // AJAX click event
    resendBtn.on('click', function() {
      $.ajax({
        url: "<?= base_url('auth/kirim_ulang_token/') ?>",
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function(response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message,
              showConfirmButton: false,
              timer: 2000
            });
            startCooldown();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: response.message
            });
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan. Silahkan coba lagi nanti.'
          });
        }
      });
    });
  });
</script>