<script>
  $(document).ready(function() {
    $('#tablePenunjukan').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": '<?= site_url('agency/penunjukan_ajax_list') ?>',
        "type": "POST",
      },
      "columnDefs": [{
        "targets": [0, 4],
        "orderable": false
      }],
      "responsive": true
    })
  })
  $(".btn-submit").click(function(e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let action = form.attr('action');
    var formData = new FormData(form[0]);

    Swal.fire({
      title: "Are you sure?",
      text: "You want to submit the form?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: action,
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
                location.href = `${res.reload}`;
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
  });

  $('.select2').select2();

  function modalMonitor(id) {
    $('#myModalMonitor').modal('show');
    $.ajax({
      url: "<?= site_url('agency/monitorPenunjukan') ?>",
      method: "GET",
      dataType: "JSON",
      data: {
        penunjukanId: id
      },
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
        $('#monitoring-table').html(res.data);
        $('span[class="no-penunjukan"]').html(res.no_penunjukan)
        Swal.close()
      }
    })
  }
</script>