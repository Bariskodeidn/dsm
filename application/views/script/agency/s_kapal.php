<script>
    document.querySelectorAll('.nominal').forEach(function(input) {
        formatNominal(input);
    });

    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('nominal')) return;

        formatNominal(e.target);
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