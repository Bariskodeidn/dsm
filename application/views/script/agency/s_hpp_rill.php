<script>
  <?php
  $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
  $penunjukan = $this->db->select('a.jenis')->from('t_penunjukan a')->where('Id', $pda['penunjukan'])->get()->row_array();
  ?>
  $(document).ready(function() {

    initSelect2('.items');

    const autoNumericOptions = {
      digitGroupSeparator: '.',
      decimalCharacter: ',',
      decimalPlaces: 2, // Ubah jadi 2 jika butuh sen
      unformatOnSubmit: true // Sangat penting agar yang dikirim ke PHP tetap angka murni
    };

    function initAutoNumeric() {
      // Hanya inisialisasi input yang belum memiliki AutoNumeric
      $('.hitung, .amount, .amount_item').each(function() {
        if (!AutoNumeric.getAutoNumericElement(this)) {
          new AutoNumeric(this, autoNumericOptions);
        }
      });
    }

    // 1. Inisialisasi awal untuk baris pertama
    initAutoNumeric();

    // Inisialisasi awal untuk Grand Total
    const anGrandTotal = new AutoNumeric('#grandTotal', autoNumericOptions);

    // Fungsi untuk menghitung total dari semua baris
    function hitungGrandTotal() {
      var total = 0;
      $('.amount').each(function() {
        var val = AutoNumeric.getAutoNumericElement(this).getNumber();
        total += parseFloat(val) || 0;
      });
      anGrandTotal.set(total);
    }

    function hitungBaris(row) {
      let grt = 0;
      let tarif = 0;
      let activity = 0;

      if (row.find('.chk-grt')[0].checked) {
        grt = AutoNumeric.getAutoNumericElement(row.find('.grt')[0]).getNumber();
      } else {
        grt = 1;
      }

      if (row.find('.chk-tarif')[0].checked) {
        tarif = AutoNumeric.getAutoNumericElement(row.find('.tarif')[0]).getNumber();
      } else {
        tarif = 1;
      }

      if (row.find('.chk-activity')[0].checked) {
        activity = AutoNumeric.getAutoNumericElement(row.find('.activity')[0]).getNumber();
      } else {
        activity = 1;
      }

      if (row.find('.chk-grt')[0].checked || row.find('.chk-tarif')[0].checked || row.find('.chk-activity')[0].checked) {
        row.find('.amount')[0].readOnly = true;
        var hasil = grt * tarif * activity;
        AutoNumeric.getAutoNumericElement(row.find('.amount')[0]).set(hasil);
      } else {
        row.find('.amount')[0].readOnly = false;
        // AutoNumeric.getAutoNumericElement(row.find('.amount')[0]).set(hasil);
      }

      // AutoNumeric.getAutoNumericElement(row.find('.amount')[0]).set(total);
    }

    $('#table1').on('input', '.hitung', function() {
      hitungBaris($(this).closest('tr'));
      hitungGrandTotal();
    });

    $('.baris').each(function() {
      hitungBaris($(this));
    });

    hitungGrandTotal();

    $('#table1').on('change', '.chk-grt, .chk-tarif, .chk-activity', function() {
      hitungBaris($(this).closest('tr'));
      hitungGrandTotal();
    });

    // Hitung saat user mengubah amount secara manual
    $('#table1').on('input', '.amount', function() {
      hitungGrandTotal();
    });

    $(".uang").mask("000.000.000.000.000", {
      reverse: true,
    });

    $(document).on('click', '.hapusRowDesc', function() {
      // 1. Ambil baris yang akan dihapus
      var row = $(this).closest('tr');

      // 2. Beri konfirmasi (opsional agar tidak sengaja terhapus)
      if (confirm("Apakah Anda yakin ingin menghapus baris ini?")) {

        // 3. Hapus baris dari tabel
        row.remove();

        // 4. JALANKAN ULANG PERHITUNGAN GRAND TOTAL
        // Ini akan menyisir ulang semua input .amount yang tersisa di tabel
        hitungGrandTotal();

        console.log("Baris dihapus, menghitung ulang total...");
      }
    });

    // setSelect2();
    // ambilDataItem();
    $(document).on('click', '.add-row', function() {
      var row = $(this).closest('.tr_clone');
      var uniqueId = 'item_' + Date.now();

      row.find('.items').select2('destroy');

      // Membuat baris baru
      var newRow = row.clone();

      newRow.find('select.items').attr('id', uniqueId).removeAttr('data-select2-id');

      newRow.find('input').val('');
      newRow.find('.items').empty().append('<option value="">Cari Item...</option>');

      newRow.insertAfter(row);
      initSelect2('.items');

      initAutoNumeric();
    });

    $('#table-item-pda').on('click', '.hapusRow', function() {
      if (countBaris() > 1) {
        $(this).closest('tr').remove();
      } else {
        Swal.fire({
          icon: "error",
          title: "The first form can't be deleted",
          showConfirmButton: false,
          timer: 1500,
        }).then(function() {
          Swal.close();
        });
      }
    });

    $('#table-other').on('click', '.hapusRowOther', function() {
      if (countBarisOther() > 1) {
        $(this).closest('tr').remove();
      } else {
        Swal.fire({
          icon: "error",
          title: "The first form can't be deleted",
          showConfirmButton: false,
          timer: 1500,
        }).then(function() {
          Swal.close();
        });
      }
    });

    $("#table-other").on('click', '.add-row-other', function() {
      var row = $(this).parents().closest('tr');
      var newId = Date.now();

      // Membuat baris baru
      var newRow = row.clone();

      newRow.find('textarea[name="desc-other[]"]').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })

      newRow.find('input[name="amount-other[]"]').each(function(index, value) {
        $(this).val(0);
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })

      newRow.find('input[name="remark-other[]"]').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })

      newRow.find('input[name="qty-other[]"]').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })
      newRow.find('input[name="mulai-other[]"]').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })
      newRow.find('input[name="selesai-other[]"]').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId);
        $(this).attr('readonly', false);
      })

      newRow.insertAfter(row);
    });
  })

  $(".btn-simpan").click(function(e) {
    e.preventDefault();
    var parent = $(this).parents("form");
    var url = parent.attr("action");
    var formData = new FormData(parent[0]);
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
  });

  function initSelect2(selector) {
    $(selector).select2({
      width: "100%",
      placeholder: "cari item",
      ajax: {
        url: '<?= base_url('pda/get_item') ?>',
        dataType: 'JSON',
        delay: 150,
        data: function(params) {
          return {
            q: params.term || '', // Search term entered by user
            page: params.page || 1, // Current page for pagination
            jenis: <?= $penunjukan['jenis'] ?>,
            port: '<?= $port['kode'] ?>',
            desc: 'AGENCY REMUNERATION'
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          return {
            results: data.items, // Items to show in the dropdown
            pagination: {
              more: data.more // Whether more pages exist
            }
          };
        },
        cache: true
      },
    })
  }

  $(document).on('change', 'select.items', function() {
    var $this = $(this);
    var itemId = $this.val();
    var $row = $this.closest('tr');
    var $amountInput = $row.find('.amount_item');

    if (!itemId) {
      $amountInput.val('');
      return;
    }

    $.ajax({
      url: "<?= base_url('pda/getItemById/') ?>",
      type: "GET",
      data: {
        id: itemId
      },
      dataType: "JSON",
      success: function(res) {
        if (res.data) {
          // $amountInput.val(res.data.est)
          var domElement = $amountInput[0];

          // 2. Cek apakah elemen ini sudah diinisialisasi sebagai AutoNumeric
          var anElement = AutoNumeric.getAutoNumericElement(domElement);

          anElement.set(res.data.est);
        }
      }
    });
  });

  $(document).on('click', '.btn-upload-dokumen', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');

    $('#item-name').val(name);
    $('#id_item').val(id);

    $('#modalUploadDokumen').modal('show');
  });

  function countBarisOther() {
    var jmlBaris = $('.baris-other').length;
    return jmlBaris;
  }

  function countBaris() {
    var jmlBaris = $('.tr_clone').length;
    return jmlBaris;
  }

  function formatNumber(number) {
    // Pisahkan bagian integer dan desimal
    let parts = number.toString().split(".");

    // Format bagian integer dengan pemisah ribuan
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Gabungkan bagian integer dan desimal dengan koma sebagai pemisah desimal
    return parts.join(",");
  }
</script>