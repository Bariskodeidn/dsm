<script>
  $(document).on('click', '.hapusRow', function() {
    $(this).closest('.tr_clone').remove();
  });

  $("#uraian-invoice").on('click', '.add-row', function() {
    var row = $(this).parents().closest('tr');
    var newId = Date.now();

    // Membuat baris baru
    var newRow = row.clone();

    newRow.find('input[name="uraian[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('');
    })
    newRow.find('input[name="satuan[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('1');
    })
    newRow.find('input[name="harga[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('0');
    })

    newRow.insertAfter(row);
  });

  $("#uraian-invoice-2").on('click', '.add-row', function() {
    var row = $(this).parents().closest('tr');
    var newId = Date.now();

    // Membuat baris baru
    var newRow = row.clone();

    newRow.find('textarea[name="uraian[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('');
    })
    newRow.find('input[name="mulai[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('');
    })
    newRow.find('input[name="selesai[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('');
    })
    newRow.find('input[name="satuan[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('1');
    })
    newRow.find('input[name="harga[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('0');
    })
    newRow.find('select[name="kategori[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('1');
    })

    newRow.insertAfter(row);
  });

  $("#uraian-invoice-1").on('click', '.add-row', function() {
    var row = $(this).parents().closest('tr');
    var newId = Date.now();

    // Membuat baris baru
    var newRow = row.clone();

    newRow.find('input[name="uraian[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('');
    })
    newRow.find('input[name="satuan[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('1');
    })
    newRow.find('input[name="harga[]"]').each(function(index, value) {
      $(this).attr('id', newId)
      $(this).val('0');
    })

    newRow.insertAfter(row);
  });

  $(document).ready(function() {
    $(".uang").mask("000.000.000.000.000", {
      reverse: true,
    });

    $('#tableInvoiceAgency').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": '<?= site_url('invoice/invoice_ajax_list') ?>',
        "type": "POST",
        // "success": function(res) {
        //   console.log(res)
        // }
      },
      "columnDefs": [{
        "targets": [0, 1, 9],
        "orderable": false
      }],
    })

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


  $('.btn-delete').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete the document?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: url,
          method: "POST",
          // data: formData,
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
                location.href = res.reload
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
  })

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

<script>
  function modalBayar(id) {
    $('#myModalBayar').modal('show');
    $('input[name="invoice-id"]').val(id);
    $.ajax({
      url: "invoice/getInvoiceById",
      method: "GET",
      dataType: "JSON",
      data: {
        invoiceId: id
      },
      success: function(res) {
        console.log(res);
        $('input[name="referensi"]').val(res.data.referensi);
      }
    })
  }

  function modalKirim(id) {
    $('#myModalKirim').modal('show');
    $('input[name="invoice-id"]').val(id);
    $.ajax({
      url: "invoice/getInvoiceById",
      method: "GET",
      dataType: "JSON",
      data: {
        invoiceId: id
      },
      success: function(res) {
        console.log(res);
        $('input[name="referensi"]').val(res.data.referensi);
      }
    })
  }

  function modalUpload(id) {
    $('#myModalUpload').modal('show');
    $('input[name="invoice-id"]').val(id);
    $.ajax({
      url: "invoice/getInvoiceById",
      method: "GET",
      dataType: "JSON",
      data: {
        invoiceId: id
      },
      success: function(res) {
        console.log(res);
        $('input[name="referensi"]').val(res.data.referensi);
      }
    })
  }
</script>