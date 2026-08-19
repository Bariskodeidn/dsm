<script>
  $(document).on('click', '.hapusRow', function() {
    $(this).closest('.tr_clone').remove();
  });

  const autoNumericOptions = {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    decimalPlaces: 2, // Ubah jadi 2 jika butuh sen
    unformatOnSubmit: true // Sangat penting agar yang dikirim ke PHP tetap angka murni
  };

  function initAutoNumeric() {
    // Hanya inisialisasi input yang belum memiliki AutoNumeric
    $('.uang').each(function() {
      if (!AutoNumeric.getAutoNumericElement(this)) {
        new AutoNumeric(this, autoNumericOptions);
      }
    });
  }

  // 1. Inisialisasi awal untuk baris pertama
  initAutoNumeric();

  $(document).ready(function() {
    initSelect2('.select2');

    $('#penunjukan').change(function() {
      var value = $(this).val();
      $.ajax({
        url: "<?= base_url('invoice/get_penunjukan') ?>",
        dataType: "JSON",
        method: "GET",
        data: {
          id_penunjukan: value
        },
        success: function(res) {
          $('#kapal').val(res.data.name);
        }
      })
    })



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

    $('#tableKapalAgency').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": '<?= site_url('agency/kapal_ajax_list') ?>',
        "type": "POST",
      },
      "columnDefs": [{
        "targets": [0, 6],
        "orderable": false
      }],
      "responsive": true
    })
  })

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
    initAutoNumeric();
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
    initAutoNumeric();
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
    initAutoNumeric();
  });

  $(document).ready(function() {
    initSelect2('.select2');

    $('#penunjukan').change(function() {
      var value = $(this).val();
      $.ajax({
        url: "<?= base_url('invoice/get_penunjukan') ?>",
        dataType: "JSON",
        method: "GET",
        data: {
          id_penunjukan: value
        },
        success: function(res) {
          $('#kapal').val(res.data.name);
        }
      })
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

    $('#tableKapalAgency').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": '<?= site_url('agency/kapal_ajax_list') ?>',
        "type": "POST",
      },
      "columnDefs": [{
        "targets": [0, 6],
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

  function closePenunjukan(id) {
    Swal.fire({
      title: "Are you sure?",
      text: "You want close this project?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '<?= base_url('agency/close_penunjukan') ?>',
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
            if (res.success) {
              Swal.fire({
                icon: "success",
                title: `${res.msg}`,
                showConfirmButton: false,
                timer: 1500,
              }).then(function() {
                Swal.close();
                location.reload();
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
            console.log(error)
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
  }

  function initSelect2(selector) {
    $(selector).select2({
      width: "100%",
    })
  }
</script>