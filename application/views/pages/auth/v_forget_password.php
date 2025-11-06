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
    <form class="col-lg-3 col-md-4 col-10 mx-auto" id="login_username" method="POSTx">

      <div class="card shadow p-4">
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
        <div id="usernameSection">
          <div class=" form-group">
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Please enter username" autofocus="true">
          </div>
        </div>
        <!-- <div class="form-group">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password">
      </div> -->
        <button id="checkUsernameBtn" class="btn btn-lg btn-primary btn-block" type="button" onclick="onCheck()">
          Konfirmasi
        </button>
        <button id="loginbtn" class="btn btn-lg btn-primary btn-block btn-login d-none" type="button" disabled>Login</button>
        <a class="mt-5 mb-3 text-center" href="<?= base_url('auth/login') ?>">Login</a>
        <p class="mb-3 text-muted text-center">Belum punya akun? <a href="<?= base_url('auth/') ?>register">Daftarkan Akun Perusahaan Anda</a></p>
        <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
      </div>
    </form>
  </div>
  <!-- Sweetalert -->
  <script src="<?= base_url('assets') ?>/sweetalert2/js/sweetalert2.all.min.js"></script>

  <script>
    function check() {
      $('#passwordSection').removeClass('d-none');
      $('#loginbtn').removeAttr('disabled');

    }
    const passwordInput = document.getElementById('password');
    const usernameInput = document.getElementById('username');
    // Event listener for the username input field
    usernameInput.addEventListener('keypress', function(event) {
      // Check if the pressed key is Enter (key code 13 or 'Enter')
      if (event.key === 'Enter') {
        event.preventDefault(); // Prevent default form submission if any
        onCheck(); // Trigger the onCheck function
      }
    });

    passwordInput.addEventListener('keypress', function(event) {
      if (event.key === 'Enter') {
        event.preventDefault(); // Prevent default form submission if any
        var parent = $(this).parents("form");
        var url = parent.attr("action");
        console.log(parent);
        var formData = new FormData(parent[0]);


        $.ajax({
          url: url,
          method: "POST",
          data: formData,
          processData: false,
          contentType: false,
          dataType: "JSON",
          beforeSend: () => {
            Swal.fire({
              title: "Loading....",
              timerProgressBar: true,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(res) {
            if (res.success) {
              Swal.fire({
                icon: "success",
                title: `${res.msg}`,
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                Swal.close();
                location.href = `${res.reload}`
                // location.reload();
              });
            } else {
              Swal.fire({
                icon: "error",
                title: `${res.msg}`,
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                Swal.close();
              });
            }
          },
          error: function(xhr, status, error) {
            console.log(xhr);
            Swal.fire({
              icon: "error",
              title: `${error}`,
              showConfirmButton: false,
              timer: 1500,
            });
          },
        });
      }
    });

    function onCheck() {
      const username = $('#username').val();
      const loginUsernameElement = document.getElementById('login_username');
      const loginForm = loginUsernameElement.closest('form');
      const passwordSection = document.getElementById('passwordSection');
      const url = "<?= base_url() ?>"

      $.ajax({
        url: "<?= base_url('auth/proses_lupa_password/') ?>", // Use POST for ID, don't append to URL unless it's a RESTful DELETE
        type: 'POST', // Keep as POST
        data: {
          username: username
        },
        dataType: 'json', // Expect JSON response
        success: function(response) {
          let iconType = 'error'; // Default to error
          if (response.status == 'success') {
            // iconType = 'success';
            Swal.fire(
              response.status === 'success' ? 'Berhasil!' : 'Perhatian!', // Dynamic title
              response.message, // Display the message from the backend
              iconType = 'success',
            )
            // Only reload the table if it was a success or a clear 'info' (already deleted) case
            // if (response.status === 'success' || response.status === 'info') {
            // Assuming your DataTables ID is 'datatable', not 'table1' based on previous snippets
            // $('#passwordSection').removeClass('d-none');
            // // $('#nsAddressDisplay').html('NS Address : ' + response.ns_address);
            // $('#loginbtn').removeClass('d-none');
            // $('#checkUsernameBtn').addClass('d-none');
            // $('#loginbtn').removeAttr('disabled');
            // passwordInput.focus();
            // loginForm.setAttribute('action', response.ns_address + '/login');
            // loginForm.setAttribute('action', 'https://admin.kodesis.id/login/login_form');
            // loginForm.setAttribute('action', 'https://' + response.ns_address + '/auth/login');
            // loginForm.setAttribute('action', url + '/auth/login');
            // loginForm.setAttribute('action', 'http://localhost/new-template/auth/login');
            // window.location.href = 'https://' + response.ns_address + '/auth/login_continue/' + username;
            // window.location.href = '<?= base_url('/auth/login_continue/') ?>' + username; // Adjust this URL as needed
            // loginForm.setAttribute('action', );


            // }
            // });
          } else {
            Swal.fire(
              title = 'Perhatian',
              response.message, // Display the message from the backend
              iconType = 'error',
            ).then(() => {
              // Only reload the table if it was a success or a clear 'info' (already deleted) case
              // if (response.status === 'success' || response.status === 'info') {
              // Assuming your DataTables ID is 'datatable', not 'table1' based on previous snippets
              // $('#passwordSection').removeClass('d-none');
              // $('#nsAddressDisplay').html('NS Address : ' + response.ns_address);
              // $('#loginbtn').removeClass('d-none');
              // $('#checkUsernameBtn').addClass('d-none');
              // $('#loginbtn').removeAttr('disabled');
              // loginForm.setAttribute('action', response.ns_address + '/login');
              // loginForm.setAttribute('action', 'https://admin.kodesis.id/login/login_form');
              // loginForm.setAttribute('action', 'https://' + response.ns_address + '/login/login_form');
              // }
            });
          }


        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', status, error, xhr.responseText); // Log full error for debugging
          Swal.fire(
            'Kesalahan Jaringan!', // More specific error message
            'Terjadi kesalahan komunikasi dengan server. Silakan coba lagi.',
            'error'
          );
        }
      });
    }
  </script>
  <?php
  // Check for success message first, as it's typically the most important
  if ($this->session->flashdata('message_name')) {
  ?>
    <script>
      Swal.fire({
        title: "Success!! ",
        text: '<?= $this->session->flashdata('message_name') ?>',
        icon: "success",
        confirmButtonText: 'Lanjut Login',

      });
    </script>
  <?php
    unset($_SESSION['message_name']);
  }
  // Then check for error message
  else if ($this->session->flashdata('message_error')) {
  ?>
    <script>
      Swal.fire({
        title: "Error!! ",
        text: '<?= $this->session->flashdata('message_error') ?>',
        icon: "error",
      });
    </script>
  <?php
    unset($_SESSION['message_error']);
  }
  // If no flash data messages are present, show the default warning
  ?>