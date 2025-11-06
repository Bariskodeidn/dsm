<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="<?= base_url('assets') ?>/images/logo.png">
  <title>Login</title>
  <!-- Simple bar CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>/css/simplebar.css">
  <!-- Fonts CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!-- Icons CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>/css/feather.css">
  <!-- Date Range Picker CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>/css/daterangepicker.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>/css/app-light.css" id="lightTheme">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>/css/app-dark.css" id="darkTheme" disabled>
  <!-- Sweetalert -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/sweetalert2/css/sweetalert2.min.css">
  <link rel="manifest" href="<?= base_url() ?>assets/_manifest.json" />
</head>

<body class="light ">
  <div class="wrapper vh-100">
    <?php if (isset($pages)) $this->load->view($pages); ?>
  </div>
  <script src="<?= base_url('assets') ?>/js/jquery.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/popper.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/moment.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/bootstrap.min.js"></script>
  <script src="<?= base_url('assets') ?>/js/simplebar.min.js"></script>
  <script src='<?= base_url('assets') ?>/js/daterangepicker.js'></script>
  <script src='<?= base_url('assets') ?>/js/jquery.stickOnScroll.js'></script>
  <script src="<?= base_url('assets') ?>/js/tinycolor-min.js"></script>
  <script src="<?= base_url('assets') ?>/js/config.js"></script>
  <script src="<?= base_url('assets') ?>/js/apps.js"></script>
  <!-- Sweetalert -->
  <script src="<?= base_url('assets') ?>/sweetalert2/js/sweetalert2.all.min.js"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');
  </script>

  <script>
    $(document).ready(function() {
      $(".btn-login").click(function(e) {
        e.preventDefault();
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
      });
    })

    // $(document).ready(function() {
    //   $(".btn-register").click(function(e) {
    //     e.preventDefault();
    //     var parent = $(this).parents("form");
    //     var url = parent.attr("action");
    //     console.log(parent);
    //     var formData = new FormData(parent[0]);


    //     $.ajax({
    //       url: url,
    //       method: "POST",
    //       data: formData,
    //       processData: false,
    //       contentType: false,
    //       dataType: "JSON",
    //       beforeSend: () => {
    //         Swal.fire({
    //           title: "Loading....",
    //           timerProgressBar: true,
    //           allowOutsideClick: false,
    //           didOpen: () => {
    //             Swal.showLoading();
    //           },
    //         });
    //       },
    //       success: function(res) {
    //         if (res.success) {
    //           Swal.fire({
    //             icon: "success",
    //             title: `${res.msg}`,
    //             showConfirmButton: false,
    //             timer: 1500,
    //           }).then(function() {
    //             Swal.close();
    //             // location.href = `${res.reload}`
    //             // location.reload();
    //           });
    //         } else {
    //           Swal.fire({
    //             icon: "error",
    //             title: `${res.msg}`,
    //             showConfirmButton: false,
    //             timer: 1500,
    //           }).then(function() {
    //             Swal.close();
    //           });
    //         }
    //       },
    //       error: function(xhr, status, error) {
    //         console.log(xhr);
    //         Swal.fire({
    //           icon: "error",
    //           title: `${error}`,
    //           showConfirmButton: false,
    //           timer: 1500,
    //         });
    //       },
    //     });
    //   });
    // })

    // $(document).ready(function() {
    //   $(".btn-regis-perusahaan").click(function(e) {
    //     e.preventDefault();
    //     var parent = $(this).parents("form");
    //     var url = parent.attr("action");
    //     console.log(parent);
    //     var formData = new FormData(parent[0]);


    //     $.ajax({
    //       url: url,
    //       method: "POST",
    //       data: formData,
    //       processData: false,
    //       contentType: false,
    //       dataType: "JSON",
    //       beforeSend: () => {
    //         Swal.fire({
    //           title: "Loading....",
    //           timerProgressBar: true,
    //           allowOutsideClick: false,
    //           didOpen: () => {
    //             Swal.showLoading();
    //           },
    //         });
    //       },
    //       success: function(res) {
    //         if (res.success) {
    //           Swal.fire({
    //             icon: "success",
    //             title: `${res.msg}`,
    //             showConfirmButton: false,
    //             timer: 1500,
    //           }).then(function() {
    //             Swal.close();
    //             location.href = `${res.reload}`
    //             // location.reload();
    //           });
    //         } else {
    //           Swal.fire({
    //             icon: "error",
    //             title: `${res.msg}`,
    //             showConfirmButton: false,
    //             timer: 1500,
    //           }).then(function() {
    //             Swal.close();
    //           });
    //         }
    //       },
    //       error: function(xhr, status, error) {
    //         console.log(xhr);
    //         Swal.fire({
    //           icon: "error",
    //           title: `${error}`,
    //           showConfirmButton: false,
    //           timer: 1500,
    //         });
    //       },
    //     });
    //   });
    // })
  </script>
</body>

</html>
</body>

</html>