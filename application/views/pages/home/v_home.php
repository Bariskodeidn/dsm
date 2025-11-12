<style>
  /* 🔲 Ukuran Carousel lebih kecil */
  .carousel-img {
    height: 100vh;
    /* ubah sesuai selera: 280px–400px */
    object-fit: cover;
  }

  /* Overlay hitam transparan di atas gambar */
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.45);
    border-radius: 0.5rem;
  }

  /* Indikator di bawah slider */
  .carousel-indicators.custom-indicators {
    position: static;
    margin-top: 15px;
    display: flex;
    justify-content: center;
  }

  .carousel-indicators.custom-indicators button {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: none;
    background-color: #ccc;
    margin: 0 6px;
    transition: background-color 0.3s, transform 0.3s;
  }

  .carousel-indicators.custom-indicators button.active {
    background-color: #ff4081;
    transform: scale(1.2);
  }

  .carousel-indicators.custom-indicators button:hover {
    background-color: #ff80ab;
  }

  /* 🌐 Responsif untuk layar kecil (HP) */
  @media (max-width: 768px) {
    .carousel-img {
      height: 50vh;
    }
  }

  @media (max-width: 480px) {
    .carousel-img {
      height: 50vh;
    }

  }

  .gauge-div {
    /* Default alignment for mobile */
    justify-content: center;
    display: flex;
  }

  @media (min-width: 768px) {
    .gauge-div {
      /* Alignment for desktops */
      justify-content: flex-end;
    }
  }

  .gauge-text-div {
    /* Default alignment for mobile */
    text-align: center;
  }

  @media (min-width: 768px) {
    .gauge-text-div {
      /* Alignment for desktops */
      text-align: left;
    }
  }
</style>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row align-items-center mb-2">
        <div class="col">
          <h2 class="h5 page-title">Selamat Datang! <span class="text-pink"> <?= $this->session->userdata('nama') ?></span></h2>
        </div>
      </div>
    </div> <!-- .row -->


    <div class="container-fluid">
      <!-- Slider Image / Carousel Otomatis dengan Fade -->
      <div id="imageCarousel" class="carousel slide carousel-fade mb-4" data-ride="carousel" data-interval="4000">

        <!-- Gambar Slider -->
        <div class="carousel-inner rounded-lg shadow">
          <?php
          $active = 'active';
          foreach ($banner as $b) : ?>
            <div class="carousel-item <?= $active ?>">
              <img class="d-block w-100 carousel-img" src="<?= base_url('assets/images/banner/') . $b->file ?>" alt="Slide 1">
              <div class="overlay"></div>
              <div class="carousel-caption centered-caption">
                <h5>PT. DHARMA SURYA MARITIM</h5>
                <p>Shipping Solutions, Seamless and Reliable Shipping Company
                  Ship Chartering and Marine Engineering</p>
              </div>
            </div>
          <?php
            $active = '';
          endforeach ?>
        </div>
        <!-- Indikator (Tombol di Bawah) -->
        <div class="carousel-indicators custom-indicators">
          <?php
          $i = 0;
          foreach ($banner as $b) : ?>
            <button type="button" data-target="#imageCarousel" data-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>" aria-current="true" aria-label="Slide 1"></button>
          <?php
            $i++;
          endforeach ?>
        </div>

      </div>
    </div>



    <!-- Script Autoplay (Bootstrap 4) -->
    <script>
      $('#imageCarousel').carousel({
        interval: 4000, // 4 detik antar slide
        ride: 'carousel'
      });
    </script>
  </div>
</div> <!-- .container-fluid -->