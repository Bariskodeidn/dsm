<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="<?= base_url('assets/') ?>progress-bar.css">
<style>
  /* Use a custom class controlled by JavaScript */
  .is-invalid {
    border: 2px solid red !important;
    /* Use !important to override Bootstrap/other defaults */
    box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25) !important;
  }

  input:valid {
    border: 1px solid #ced4da;
    box-shadow: none;
  }

  .error-message {
    color: red;
    font-size: 0.875em;
    margin-top: 5px;
    display: block;
  }

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
    width: 800px;
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
  <div class="col-lg-12 col-md-4 col-11 mx-auto">
    <div class="row">
      <div class="col-lg-3 col-md-4 col-10 mx-auto">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?= base_url('/') ?>">
          <img src="<?= base_url('assets') ?>/images/logo.png" alt="logo" class="w-100">
        </a>
        <br>
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
      </div>
    </div>
    <div class="row mb-5 mt-3">
      <div class="container">
        <div class="progress-container mx-auto">
          <div class="progress" id="progress"></div>
          <div class="circle active" data-label="User">1</div>
          <div class="circle" data-label="Perusahaan">2</div>
          <div class="circle" data-label="Cabang">3</div>
        </div>
      </div>
    </div>
  </div>
  <form class="col-lg-3 col-md-4 col-10 mx-auto" action="<?= site_url('auth/proccess_register') ?>" method="post">
    <div class="card shadow p-4">
      <div class="row">
        <!-- END: Progress Bar Integration -->
        <div class="col-lg-12 col-md-4 col-10 mx-auto">
          <!-- New input fields for company data -->
          <div class="form-group text-left">
            <label for="nip">Username</label>
            <input
              type="text"
              id="nip"
              name="nip"
              class="form-control form-control-lg"
              placeholder="Please enter Username"
              autofocus="true"
              required
              minlength="5" />
            <span id="nip_error_message" class="error-message"></span>
          </div>
          <div class="form-group text-left">
            <label for="nama">Nama</label>
            <input
              type="text"
              id="nama"
              name="nama"
              class="form-control form-control-lg"
              placeholder="Please enter Nama"
              required />
            <span id="nama_error_message" class="error-message"></span>
          </div>
          <div class="form-group text-left">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              class="form-control form-control-lg"
              placeholder="Password"
              required
              minlength="6" />
            <span id="password_error_message" class="error-message"></span>
          </div>

          <div class="form-group text-left">
            <label for="password_confirm">Konfirmasi Password</label>
            <input
              type="password"
              id="password_confirm"
              name="password_confirm"
              class="form-control form-control-lg"
              placeholder="Password"
              required />
            <span id="password_confirm_error_message" class="error-message"></span>
          </div>

          <div class="form-group text-left">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              class="form-control form-control-lg"
              placeholder="Email"
              required />
            <span id="email_error_message" class="error-message"></span>
          </div>
          <div class="form-group text-left">
            <label for="phone">Phone (WhatsApp)</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              class="form-control form-control-lg"
              placeholder="Phone Number"
              required
              pattern="[0-9]{10,15}" />
            <span id="phone_error_message" class="error-message"></span>
          </div>
          <!-- End new input fields -->

          <button class="btn btn-lg btn-primary btn-block btn-regis-perusahaan" type="submit">Registrasi</button>
          <!-- <p class="mt-5 mb-3 text-muted text-center">Belum punya akun? <a href="<?= base_url('auth/') ?>register">Buat Akun Perusahaan Anda</a></p> -->
          <p class="mt-5 mb-3 text-muted text-center">Sudah punya akun? <a href="<?= base_url('auth/') ?>">Masuk dengan Akun Perusahaan Anda</a></p>
          <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
        </div>
      </div>
  </form>
</div>
<!-- Custom Modal Structure -->
<div id="customModal" class="modal-overlay">
  <div class="modal-content">
    <div class="flex items-center mb-4">
      <!-- Warning Icon (SVG) -->
      <div class="mb-3" style="text-align: center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500 mr-3" viewBox="0 0 20 20" fill="#ff9966" width="64" height="64">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </div>
      <h2 class="text-2xl font-semibold text-gray-900">Penting: Kebijakan Dukungan Pengguna</h2>
    </div>
    <p class="text-gray-700 mb-6">
      Dengan menggunakan Baris Kode Indonesia (selanjutnya disebut "Cloud Base"), Anda mengakui dan setuju terhadap ketentuan berikut:
      <br>
      <li><b>Ketersediaan Layanan:</b> Meskipun kami berupaya keras untuk memastikan ketersediaan Layanan yang berkelanjutan, kami tidak menjamin bahwa Layanan akan selalu tersedia tanpa gangguan, tepat waktu, aman, atau bebas kesalahan. Gangguan layanan dapat terjadi karena pemeliharaan, masalah teknis, atau faktor di luar kendali kami.</li>

      <br>
      <li><b>Akurasi Data:</b> Kami berupaya untuk memastikan akurasi data yang ditampilkan atau diproses oleh Layanan. Namun, kami tidak menjamin kelengkapan, keakuratan, keandalan, kesesuaian, atau ketersediaan data tersebut. Anda bertanggung jawab penuh atas keakuratan data yang Anda masukkan ke dalam Layanan.</li>

      <br>
      <li><b>Kehilangan Data:</b> Meskipun kami mengambil langkah-langkah wajar untuk melindungi data Anda, kami tidak bertanggung jawab atas kehilangan data yang tidak disengaja, kerusakan data, atau akses tidak sah yang mungkin terjadi. Anda disarankan untuk secara teratur membuat cadangan data penting Anda.</li>

      <br>
      <li><b>Batasan Tanggung Jawab:</b> Sejauh diizinkan oleh hukum yang berlaku, layanan Cloud Base Baris Kode Indonesia dan afiliasinya tidak akan bertanggung jawab atas kerugian langsung, tidak langsung, insidental, khusus, konsekuensial, atau exemplary, termasuk namun tidak terbatas pada kerugian keuntungan, itikad baik, penggunaan, data, atau kerugian tidak berwujud lainnya, yang diakibatkan dari:</li>
      <br>
    <ol type="I">
      <li>penggunaan atau ketidakmampuan untuk menggunakan Layanan;</li>
      <li>akses tidak sah ke atau perubahan transmisi atau data Anda;</li>
      <li>pernyataan atau perilaku pihak ketiga di Layanan; atau</li>
      <li>hal lain yang berkaitan dengan Layanan.</li>
    </ol>
    </p>
    <div class="flex justify-end">
      <button id="closeModalBtn" class="btn btn-primary px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
        Saya Mengerti
      </button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  // Get references to the modal elements
  const customModal = document.getElementById('customModal');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const doNotShowAgainCheckbox = document.getElementById('doNotShowAgain');

  /**
   * Shows the custom modal by adding the 'show' class.
   */
  function showModal() {
    customModal.classList.add('show');
  }

  /**
   * Hides the custom modal by removing the 'show' class.
   * Also logs the state of the checkbox.
   */
  function hideModal() {
    customModal.classList.remove('show');
    // You can access the checkbox state here
    console.log('Do not show again checkbox state:', doNotShowAgainCheckbox.checked);
    // In a real application, you might save this state to localStorage
    // if (doNotShowAgainCheckbox.checked) {
    //     localStorage.setItem('hideModal', 'true');
    // }
  }

  // Event listener to show the modal when the DOM is fully loaded
  document.addEventListener('DOMContentLoaded', () => {
    // Check if the modal should be hidden based on a previous user choice (example)
    // if (localStorage.getItem('hideModal') !== 'true') {
    showModal();
    // }
  });

  // Event listener for the close button
  closeModalBtn.addEventListener('click', hideModal);

  // Optional: Close modal if clicking outside the content (on the overlay)
  customModal.addEventListener('click', (event) => {
    if (event.target === customModal) {
      hideModal();
    }
  });
</script>
<script>
  Swal.fire({
    title: 'Do you have a bike?',
    input: 'checkbox',
    inputPlaceholder: 'I have a bike'
  }).then((result) => {
    if (result.isConfirmed) {
      if (result.value) {
        Swal.fire({
          icon: 'success',
          text: 'You have a bike!'
        });
      } else {
        Swal.fire({
          icon: 'error',
          text: "You don't have a bike :("
        });
      }
    } else {
      console.log(`modal was dismissed by ${result.dismiss}`)
    }
  })
</script>
<script>
  const progress = document.getElementById("progress");
  const prev = document.getElementById("prev");
  const next = document.getElementById("next");
  const circles = document.querySelectorAll(".circle");

  let currentActive = 1;

  next.addEventListener("click", () => {
    currentActive++;
    if (currentActive > circles.length) currentActive = circles.length;
    update();
  });

  prev.addEventListener("click", () => {
    currentActive--;
    if (currentActive < 1) currentActive = 1;
    update();
  });

  const update = () => {
    circles.forEach((circle, index) => {
      if (index < currentActive) circle.classList.add("active");
      else circle.classList.remove("active");
    });
    const actives = document.querySelectorAll(".active");
    progress.style.width =
      ((actives.length - 1) / (circles.length - 1)) * 100 + "%";
    if (currentActive === 1) prev.disabled = true;
    else if (currentActive === circles.length) next.disabled = true;
    else {
      prev.disabled = false;
      next.disabled = false;
    }
  };
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (!form) {
      console.error("Form element not found!");
      return;
    }

    const inputs = {
      nip: document.getElementById('nip'),
      nama: document.getElementById('nama'),
      password: document.getElementById('password'),
      password_confirm: document.getElementById('password_confirm'),
      email: document.getElementById('email'),
      phone: document.getElementById('phone')
    };

    const errorMessages = {
      nip: document.getElementById('nip_error_message'),
      nama: document.getElementById('nama_error_message'),
      password: document.getElementById('password_error_message'),
      password_confirm: document.getElementById('password_confirm_error_message'),
      email: document.getElementById('email_error_message'),
      phone: document.getElementById('phone_error_message')
    };

    const touchedFields = {};
    for (const key in inputs) {
      if (inputs[key]) {
        touchedFields[key] = false;
      }
    }

    // --- Validation Functions ---

    function updateFieldValidation(inputElement, errorMessageSpan) {
      const isTouched = touchedFields[inputElement.id] || form.classList.contains('attempted-submit');

      let message = '';
      let isValid = true; // Assume valid initially based on HTML5 validity

      // First, check HTML5 validity constraints
      if (inputElement.validity.valueMissing) {
        message = inputElement.previousElementSibling.textContent + ' tidak boleh kosong!';
        isValid = false;
      } else if (inputElement.validity.tooShort) {
        message = inputElement.previousElementSibling.textContent + ' minimal ' + inputElement.minLength + ' karakter.';
        isValid = false;
      } else if (inputElement.validity.tooLong) {
        message = inputElement.previousElementSibling.textContent + ' maksimal ' + inputElement.maxLength + ' karakter.';
        isValid = false;
      } else if (inputElement.validity.typeMismatch && inputElement.type === 'email') {
        message = 'Format email tidak valid.';
        isValid = false;
      } else if (inputElement.validity.patternMismatch && inputElement.type === 'tel') {
        message = 'Nomor telepon hanya boleh angka (10-15 digit).';
        isValid = false;
      }

      // Specific handling for password_confirm (additional JS logic)
      if (inputElement.id === 'password_confirm') {
        if (inputs.password_confirm.value.trim() === '') { // Use .trim() for empty check
          message = 'Konfirmasi Password tidak boleh kosong!';
          isValid = false;
        } else if (inputs.password.value !== inputs.password_confirm.value) {
          message = 'Konfirmasi password tidak cocok dengan password.';
          isValid = false;
        }
        // If it's valid based on the above, clear any message
        if (isValid && message === '') {
          message = ''; // Ensure no message if passwords match and not empty
        }
      }
      // Specific handling for password field if confirmation is also being checked
      if (inputElement.id === 'password' && touchedFields['password_confirm'] && inputs.password_confirm) {
        // Re-run validation for password_confirm when password changes
        updateFieldValidation(inputs.password_confirm, errorMessages.password_confirm);
      }


      // Only display message and apply border if the field has been touched or form submitted
      if (isTouched) {
        errorMessageSpan.textContent = message;
        if (!isValid) {
          inputElement.classList.add('is-invalid');
        } else {
          inputElement.classList.remove('is-invalid');
        }
      } else {
        // If not touched and not submitted, ensure no message or red border
        errorMessageSpan.textContent = '';
        inputElement.classList.remove('is-invalid');
      }
    }

    // --- Attach Event Listeners ---
    for (const key in inputs) {
      if (inputs[key]) {
        const input = inputs[key];
        const errorMessage = errorMessages[key];

        // On BLUR: Mark as touched and run validation
        input.addEventListener('blur', function() {
          touchedFields[input.id] = true;
          updateFieldValidation(input, errorMessage);
        });

        // On INPUT: Run validation if already touched
        input.addEventListener('input', function() {
          if (touchedFields[input.id] || form.classList.contains('attempted-submit')) {
            updateFieldValidation(input, errorMessage);
          }
          // Specifically re-validate password confirm if password changes
          if (input.id === 'password' && touchedFields['password_confirm']) {
            updateFieldValidation(inputs.password_confirm, errorMessages.password_confirm);
          }
        });

        // On INVALID: Prevent browser default message and force validation display
        input.addEventListener('invalid', function(event) {
          event.preventDefault(); // Stop default browser validation popup
          touchedFields[input.id] = true; // Mark as touched
          updateFieldValidation(input, errorMessage);
        });
      }
    }

    // --- Form Submission Validation ---
    form.addEventListener('submit', function(event) {
      // Mark all fields as touched for submission
      for (const key in inputs) {
        if (inputs[key]) {
          touchedFields[key] = true;
        }
      }
      form.classList.add('attempted-submit'); // Indicate form submission attempt

      let formIsValid = true;
      // Run validation for all fields and check overall validity
      for (const key in inputs) {
        if (inputs[key]) {
          updateFieldValidation(inputs[key], errorMessages[key]);
          // Re-check validity after update, including custom password confirmation
          if (!inputs[key].validity.valid ||
            (key === 'password_confirm' && inputs.password.value !== inputs.password_confirm.value) ||
            (key === 'password_confirm' && inputs.password_confirm.value.trim() === '')) { // Ensure password confirm isn't empty either
            formIsValid = false;
          }
        }
      }

      if (!formIsValid) {
        event.preventDefault(); // Stop the form from submitting
        // Focus on the first invalid field
        for (const key in inputs) {
          if (inputs[key] && (!inputs[key].validity.valid || (key === 'password_confirm' && (inputs.password.value !== inputs.password_confirm.value || inputs.password_confirm.value.trim() === '')))) {
            inputs[key].focus();
            break;
          }
        }
      } else {
        form.classList.remove('attempted-submit'); // Clear class if valid
      }
    });
  });
</script>