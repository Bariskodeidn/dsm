<script>
  <?php
  if ($this->session->flashdata('message_name')) {
  ?>
    Swal.fire({
      title: "Success!! ",
      text: '<?= $this->session->flashdata('message_name') ?>',
      type: "success",
      icon: "success",
    });
  <?php
    // $this->session->sess_destroy('message_name');
    unset($_SESSION['message_name']);
  } ?>

  <?php
  if ($this->session->flashdata('message_error')) {
  ?>
    Swal.fire({
      title: "Error!! ",
      text: '<?= $this->session->flashdata('message_error') ?>',
      type: "error",
      icon: "error",
    });
  <?php
    // $this->session->sess_destroy('message_error');
    unset($_SESSION['message_error']);
  } ?>
</script>