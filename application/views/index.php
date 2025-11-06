<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link rel="icon" href="favicon.ico"> -->
  <link rel="icon" type="image/png" href="<?php echo ($this->session->userdata('icon')) ? $this->session->userdata('icon') : $utility['logo']; ?>">
  <!-- <title>Bariskode - <?= $title ?></title> -->
  <title><?php echo ($this->session->userdata('nama_perusahaan')) ? $this->session->userdata('nama_perusahaan') : $utility['nama_perusahaan']; ?> - <?= $title ?></title>

  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/simplebar.css">
  <!-- Fonts CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/feather.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/select2.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/dropzone.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/uppy.min.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/jquery.steps.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/jquery.timepicker.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/quill.snow.css">
  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/css/app-dark.css" id="darkTheme" disabled>
  <!-- Sweetalert2 -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/sweetalert2/css/sweetalert2.min.css">
  <!-- Datatables -->
  <!-- <link rel="stylesheet" href="<?= base_url('assets') ?>/vendors/bootstrap/dist/css/bootstrap.min.css"> -->

  <!-- <link rel="stylesheet" href="<?= base_url('assets') ?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" href="<?= base_url('assets') ?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" href="<?= base_url('assets') ?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css"> -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/dataTables/css/datatables.min.css">
  <link rel="manifest" href="<?= base_url() ?>assets/_manifest.json" />

</head>

<body class="vertical  light  ">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('layouts/navbar.php') ?>
    <!-- Sidebar -->
    <?php
    $margin_subs = '';
    if ($this->uri->segment(1) != "subscription") {
      $this->load->view('layouts/sidebar.php');
    } else {
      $margin_subs = 'style="margin-left: 0;"';
    }
    ?>
    <!-- Main Content -->
    <main role="main" class="main-content" <?= $margin_subs ?>>
      <?php if (isset($pages)) $this->load->view($pages); ?>
    </main> <!-- main -->
  </div> <!-- .wrapper -->
  <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/popper.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/moment.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/bootstrap.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/simplebar.min.js"></script>
  <script src='<?= base_url('assets') ?>/js/daterangepicker.js'></script>
  <script src='<?= base_url('assets') ?>/js/jquery.stickOnScroll.js'></script>
  <script src="<?= base_url('assets') ?>/js/tinycolor-min.js"></script>
  <script src="<?= base_url('assets') ?>/js/config.js"></script>
  <script src="<?= base_url('assets') ?>/js/d3.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/topojson.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/datamaps.all.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/datamaps-zoomto.js"></script>
  <script src="<?= base_url('assets') ?>/js/datamaps.custom.js"></script>
  <script src="<?= base_url('assets') ?>/js/Chart.min.js"></script>
  <script>
    /* defind global options */
    // Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
    // Chart.defaults.global.defaultFontColor = colors_dashboard.mutedColor;
  </script>
  <script src="<?= base_url('assets') ?>/js/gauge.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/jquery.sparkline.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/apexcharts.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/apexcharts.custom.js"></script>
  <script src='<?= base_url('assets') ?>/js/jquery.mask.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/select2.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/jquery.steps.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/jquery.validate.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/jquery.timepicker.js'></script>
  <script src='<?= base_url('assets') ?>/js/dropzone.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/uppy.min.js'></script>
  <script src='<?= base_url('assets') ?>/js/quill.min.js'></script>
  <!-- CKEditor -->
  <script type="text/javascript" src="<?= base_url(); ?>/assets/ckeditor/ckeditor.js"></script>
  <!-- Sweetalert -->
  <script src="<?= base_url('assets') ?>/sweetalert2/js/sweetalert2.all.min.js"></script>
  <!-- Cleave JS -->
  <script src="<?= base_url('assets') ?>/js/cleave.min.js"></script>
  <!-- DataTables -->
  <script src="<?= base_url('assets') ?>/dataTables/js/datatables.min.js"></script>

  <!-- My Script -->
  <?php if (isset($pages_script)) $this->load->view($pages_script); ?>

  <!-- Your SweetAlert2 JS check (as provided previously) -->

  <script src="<?= base_url('assets') ?>/js/apps.js"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>

</body>

</html>