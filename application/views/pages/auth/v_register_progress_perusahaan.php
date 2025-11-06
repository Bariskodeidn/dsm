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

  .info-message {
    color: gray;
    font-size: 0.875em;
    margin-top: 5px;
    display: block;
  }

  .preview-pict {
    max-width: 220px;
    height: auto;
    border: 1px solid #ddd;
    padding: 5px;
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
              <span aria-hidden="true">×</span>
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
          <div class="circle active" data-label="Perusahaan">2</div>
          <div class="circle" data-label="Cabang">3</div>
        </div>
      </div>
    </div>
  </div>
  <form class="col-lg-3 col-md-4 col-10 mx-auto" action="<?= site_url('auth/process_registrasi_perusahaan') ?>" method="post" enctype="multipart/form-data">
    <div class="card shadow p-4">
      <div class="row">
        <!-- END: Progress Bar Integration -->
        <div class="col-lg-12 col-md-4 col-10 mx-auto">

          <!-- New input fields for company data -->
          <div class="form-group text-left">
            <div class="justify-content-center align-items-center mt-2 mb-2" id="logo_preview_container" style="display: none;">
              <img id="logo_preview" src="#" alt="Logo Preview" class="preview-pict">
            </div>
            <label for="nama_perusahaan">Logo Perusahaan</label>
            <input type="file" class="form-control-file" id="logo_perusahaan" name="logo_perusahaan" accept=".jpg, .jpeg, .png, .gif, image/jpeg, image/png"
              required>
            <span id="logo_perusahaan_error_message" class="info-message">Ukuran file logo maksimal adalah <b>2MB</b>.</span>
          </div>
          <div class="form-group text-left">
            <label for="nama_perusahaan">Nama Perusahaan</label>
            <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control form-control-lg" required>
            <span id="nama_perusahaan_error_message" class="error-message"></span>

          </div>
          <div class="form-group text-left">
            <label for="nama_singkat">Nama Singkat</label>
            <input type="text" id="nama_singkat" name="nama_singkat" class="form-control form-control-lg" required>
            <span id="nama_singkat_error_message" class="error-message"></span>
          </div>
          <div class="form-group text-left d-none">
            <label for="nama_ppn">Nama PPN</label>
            <input type="text" id="nama_ppn" name="nama_ppn" class="form-control form-control-lg" required value="PPN 12%">
            <span id="nama_ppn_error_message" class="error-message"></span>

          </div>
          <div class="form-group text-left d-none">
            <label for="besaran_ppn">Besaran PPN (%)</label>
            <input type="number" id="besaran_ppn" name="besaran_ppn" class="form-control form-control-lg" step="1" min="1" max="100" required value="12">
            <span id="besaran_ppn_error_message" class="error-message"></span>
          </div>
          <!-- <div class="form-group text-left">
            <label for="nomor_rekening">Nomor Rekening</label>
            <input
              type="text"
              id="nomor_rekening"
              name="nomor_rekening"
              class="form-control form-control-lg"
              required
              pattern="[0-9]{10,16}" />
            <span id="nomor_rekening_error_message" class="error-message"></span>
          </div>
          <div class="form-group text-left">
            <label for="nama_bank">Nama Bank</label>
            <input type="text" id="nama_bank" name="nama_bank" class="form-control form-control-lg" required>
            <span id="nama_bank_error_message" class="error-message"></span>
          </div> -->
          <div class="form-group text-left">
            <label for="alamat_perusahaan">Alamat Perusahaan</label>
            <textarea id="alamat_perusahaan" name="alamat_perusahaan" class="form-control form-control-lg" rows="3" required></textarea>
            <span id="alamat_perusahaan_error_message" class="error-message"></span>

          </div>
          <div class="form-group text-left">
            <label for="nama_akronim">Akronim Invoice (3-4 Character)</label>
            <input type="text" id="nama_akronim" name="nama_akronim" class="form-control form-control-lg" maxlength="4" required>
            <span id="nama_akronim_error_message" class="info-message">Akronim di gunakan untuk pembuatan kode penomoran invoice</span>

          </div>
          <!-- End new input fields -->

          <button class="btn btn-lg btn-primary btn-block btn-regis-perusahaan" type="submit">Registrasi</button>
          <p class="mt-5 mb-3 text-muted text-center">Sudah punya akun? <a href="<?= base_url('auth/logout') ?>">Masuk dengan Akun Perusahaan Anda</a></p>
          <p class="mt-5 mb-3 text-muted text-center">IT BARIS KODE INDONESIA © <?= date('Y') ?></p>
        </div>
      </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  const progress = document.getElementById("progress");
  const prev = document.getElementById("prev");
  const next = document.getElementById("next");
  const circles = document.querySelectorAll(".circle");

  let currentActive = 2;


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

  update();
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (!form) {
      console.error("Form element not found!");
      return;
    }

    const inputs = {
      logo_perusahaan: document.getElementById('logo_perusahaan'),
      nama_perusahaan: document.getElementById('nama_perusahaan'),
      nama_singkat: document.getElementById('nama_singkat'),
      nama_ppn: document.getElementById('nama_ppn'),
      besaran_ppn: document.getElementById('besaran_ppn'),
      nomor_rekening: document.getElementById('nomor_rekening'),
      nama_bank: document.getElementById('nama_bank'),
      alamat_perusahaan: document.getElementById('alamat_perusahaan'),
      nama_akronim: document.getElementById('nama_akronim')
    };

    const errorMessages = {
      logo_perusahaan: document.getElementById('logo_perusahaan_error_message'),
      nama_perusahaan: document.getElementById('nama_perusahaan_error_message'),
      nama_singkat: document.getElementById('nama_singkat_error_message'),
      nama_ppn: document.getElementById('nama_ppn_error_message'),
      besaran_ppn: document.getElementById('besaran_ppn_error_message'),
      nomor_rekening: document.getElementById('nomor_rekening_error_message'),
      nama_bank: document.getElementById('nama_bank_error_message'),
      alamat_perusahaan: document.getElementById('alamat_perusahaan_error_message'),
      nama_akronim: document.getElementById('nama_akronim_error_message')
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
      } else if (inputElement.validity.patternMismatch && inputElement.id === 'nomor_rekening') {
        message = 'Nomor Rekening hanya boleh angka (10-16 digit).';
        isValid = false;
      }

      if (inputElement.id === 'logo_perusahaan' && inputElement.files.length > 0) {
        const file = inputElement.files[0];
        const allowedTypes = ['image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
          message = 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan.';
          isValid = false;
        }
        // You might also want to add file size validation here
        // For example, if (file.size > 2 * 1024 * 1024) { message = 'Ukuran file terlalu besar (maks 2MB).'; isValid = false; }
      }

      // This is the new, crucial part for the akronim field
      if (inputElement.id === "nama_akronim") {
        if (!isValid) {
          errorMessageSpan.textContent = 'Akronim tidak valid.';
          errorMessageSpan.classList.remove("info-message");
          errorMessageSpan.classList.add("error-message");
        } else {
          errorMessageSpan.textContent = 'Akronim di gunakan untuk pembuatan kode penomoran invoice';
          errorMessageSpan.classList.remove("error-message");
          errorMessageSpan.classList.add("info-message");
        }
      }

      // Only display message and apply border if the field has been touched or form submitted
      if (isTouched) {
        if (inputElement.id !== "nama_akronim") {
          errorMessageSpan.textContent = message;
        }
        // errorMessageSpan.textContent = message;
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
          if (!inputs[key].validity.valid) { // Ensure password confirm isn't empty either
            formIsValid = false;
          }
        }
      }

      if (!formIsValid) {
        event.preventDefault(); // Stop the form from submitting
        // Focus on the first invalid field
        for (const key in inputs) {
          if (inputs[key] && (!inputs[key].validity.valid)) {
            inputs[key].focus();
            break;
          }
        }
      } else {
        form.classList.remove('attempted-submit'); // Clear class if valid
      }
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logo_perusahaan');
    const logoPreview = document.getElementById('logo_preview');
    const logoPreviewContainer = document.getElementById('logo_preview_container');
    const logoErrorMessage = document.getElementById('logo_perusahaan_error_message');

    logoInput.addEventListener('change', function(event) {
      const file = event.target.files[0]; // Get the first selected file

      // Clear previous error messages
      logoErrorMessage.textContent = '';

      if (file) {
        const fileType = file.type;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif']; // Add any other types you accept

        if (validImageTypes.includes(fileType)) {
          const reader = new FileReader();

          reader.onload = function(e) {
            // Set the source of the image tag to the base64 encoded URL
            logoPreview.src = e.target.result;
            logoPreviewContainer.style.display = 'flex'; // Show the preview container
          };

          // Read the file as a Data URL (base64 encoded string)
          reader.readAsDataURL(file);
        } else {
          // Invalid file type
          logoPreview.src = '#'; // Clear any existing preview
          logoPreviewContainer.style.display = 'none'; // Hide the preview container
          logoErrorMessage.textContent = 'Please select a valid image file (JPG, JPEG, PNG, GIF).';
          logoErrorMessage.classList.remove("info-message");
          logoErrorMessage.classList.add("error-message");

          logoInput.value = ''; // Clear the file input
        }
      } else {
        // No file selected or file input cleared
        logoPreview.src = '#'; // Clear any existing preview
        logoPreviewContainer.style.display = 'none'; // Hide the preview container
      }
    });
  });
</script>