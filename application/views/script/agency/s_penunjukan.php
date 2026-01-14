<script>
    $(document).ready(function() {
        initSelect2('.select2');
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
                                    $.each(res.errors, function(key, value) {
                                        $('#' + key + '_error').html(value);
                                    });
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

        $('#type').change(function() {
            var id = $(this).val();

            if (id == '') {
                $('#kapal').html('<option value=""> :: Pilih Kapal</option>');
                return;
            }

            $.ajax({
                url: "<?= base_url('agency/getKapalByType'); ?>",
                method: "POST",
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                beforeSend: function() {
                    $("#loading").show();
                    $('#kapal').attr('disabled', true);
                },
                success: function(data) {
                    var html = '<option value="">:: Pilih Kapal</option>';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].Id + '>' + data[i].name + '</option>';
                    }
                    $('#kapal').html(html);
                },
                error: function(xhr, status, error) {
                    // Tampilkan pesan error di konsol untuk developer
                    console.error("Status: " + status);
                    console.error("Error: " + error);

                    // Beri tahu pengguna bahwa ada masalah
                    alert("Gagal mengambil data. Silakan periksa koneksi internet Anda atau coba lagi nanti.");

                    // Kembalikan dropdown ke kondisi semula
                    $('#kapal').html('<option value="">Gagal memuat data</option>');
                },
                complete: function() {
                    $('#loading').hide();
                    $('#kapal').attr('disabled', false);
                }
            })
        })

        $('#kapal').change(function() {
            var id = $(this).val();

            if (id == '') {
                $('#kapal').html('<option value=""> :: Pilih Kapal</option>');
                return;
            }


            $.ajax({
                url: "<?= base_url('agency/getKapalById'); ?>",
                method: "POST",
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                beforeSend: function() {
                    $("#loading").show();
                    $('#kapal').attr('disabled', true);
                },
                success: function(data) {
                    $('#grt').val((data.grt));
                    $('#grt_barge').val((data.grt_barge));
                },
                error: function(xhr, status, error) {
                    // Tampilkan pesan error di konsol untuk developer
                    console.error("Status: " + status);
                    console.error("Error: " + error);

                    // Beri tahu pengguna bahwa ada masalah
                    alert("Gagal mengambil data. Silakan periksa koneksi internet Anda atau coba lagi nanti.");

                    // Kembalikan dropdown ke kondisi semula
                    $('#kapal').html('<option value="">Gagal memuat data</option>');
                },
                complete: function() {
                    $('#loading').hide();
                    $('#kapal').attr('disabled', false);
                }
            })
        })
    })

    document.querySelectorAll('.nominal').forEach(function(input) {
        formatNominal(input);
    });

    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('nominal')) return;

        formatNominal(e.target);
    })

    function initSelect2(selector) {
        $(selector).select2({
            width: "100%",
        })
    }

    function formatNominal(input) {
        let value = input.value;
        if (!value) return;


        value = value.replace(/[^0-9.]/g, '');

        let parts = value.split('.');
        let integer = parts[0];
        let decimal = parts[1];

        // Hapus leading zero 
        integer = integer.replace(/^0+(?=\d)/, '') || '0';

        // Format ribuan
        integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        input.value = decimal !== undefined ? integer + '.' + decimal : integer;
    }
</script>