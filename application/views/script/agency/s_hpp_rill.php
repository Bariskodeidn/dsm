<script>
  <?php
  $port = $this->db->get_where('agency_port', ['Id' => $pda['port']])->row_array();
  $penunjukan = $this->db->select('a.jenis')->from('t_penunjukan a')->where('Id', $pda['penunjukan'])->get()->row_array();
  ?>
  $(document).ready(function() {
    setSelect2();
    ambilDataItem();
    $("#table-item-pda").on('click', '.add-row', function() {
      var row = $(this).parents().closest('tr');
      var newId = Date.now();

      row.find("select.items").each(function(index, value) {
        $(this).select2('destroy');
      })

      // Membuat baris baru
      var newRow = row.clone();

      newRow.find('select.items').each(function(index, value) {
        $(this).val('');
        $(this).attr('id', newId)
      })

      newRow.find('input[name="amount[]"]').each(function(index, value) {
        $(this).val(0);
        $(this).attr('id', newId)
      })

      newRow.find('input[name="qty[]"]').each(function(index, value) {
        $(this).val(1);
      })

      newRow.find('input[name="mulai[]"]').each(function(index, value) {
        $(this).val(0);
      })

      newRow.find('input[name="selesai[]"]').each(function(index, value) {
        $(this).val(0);
      })

      newRow.find('input[name="remark[]"]').each(function(index, value) {
        $(this).val('');
      })

      newRow.insertAfter(row);
      setSelect2()
      ambilDataItem()
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
  })


  function setSelect2() {
    let select2 = document.querySelectorAll('select.items');
    for (let index = 0; index < select2.length; index++) {
      $("." + select2[index].classList[1] + "").select2({
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
  }

  function ambilDataItem() {
    var amount = document.querySelectorAll('input[name="amount[]"]');
    $.each($("select.items"), function(index, value) {
      $('#' + value.id).change(function() {
        var id = $(this).val();
        $.ajax({
          url: "<?= base_url('pda/getItemById/') ?>",
          type: "GET",
          chace: false,
          data: {
            id: id,
          },
          dataType: "JSON",
          success: function(res) {
            $('input[id="' + amount[index].id + '"]').val((res.data.est));
          }
        })
      });
    })
  }

  function countBarisOther() {
    var jmlBaris = $('.baris-other').length;
    return jmlBaris;
  }

  function countBaris() {
    var jmlBaris = $('.tr_clone').length;
    return jmlBaris;
  }
</script>