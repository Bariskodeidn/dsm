<script>
    $(document).ready(function() {
        setSelect2();
        get_cost();
        getCostTetap();

        $(".uang").mask("000.000.000.000.000", {
            reverse: true,
        });

        $('.hapusItem').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete the item?",
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

        $(".btn-upload").click(function(e) {
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

        $(".btn-submit").click(function(e) {
            e.preventDefault();
            var parent = $(this).parents("form");
            var url = parent.attr("action");
            console.log(parent);
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
                                    location.href = '<?= base_url('agency/penawaran') ?>'
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
                }
            });
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

    $(document).ready(function() {
        $("#myTable").on('click', '.remove-row', function() {
            if (countBaris() > 1) {
                $(this).closest('tr').remove();
                // get_cost();
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

        $("#myTableTetap").on('click', '.hapus-row', function() {
            if (countBarisTetap() > 1) {
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

        $("#myTable").on('click', '.add-row', function() {
            var row = $(this).parents().closest('tr');
            var newId = Date.now();

            row.find("select.description").each(function(index, value) {
                $(this).select2('destroy');
            });

            // Membuat baris baru
            var newRow = row.clone();

            newRow.find('select.description').each(function(index, value) {
                $(this).attr('id', newId)
            })

            newRow.find('input[name="cost-tambahan[]"]').each(function(index, value) {
                $(this).val(0);
                $(this).attr('id', newId)
            })

            newRow.find('input[name="remarks-tambahan[]"]').each(function(index, value) {
                $(this).val('');
            })

            newRow.insertAfter(row);
            $("select.description").select2();
            get_cost()
        });

        $("#myTableTetap").on('click', '.tambah-row', function() {
            var row = $(this).parents().closest('tr');
            var newId = Date.now();

            row.find("select.desc-tetap").each(function(index, value) {
                $(this).select2('destroy');
            });

            // Membuat baris baru
            var newRow = row.clone();

            newRow.find('select.desc-tetap').each(function(index, value) {
                $(this).val('');
                $(this).attr('id', newId)
            })

            newRow.find('input[name="cost[]"]').each(function(index, value) {
                $(this).val(0);
                $(this).attr('id', newId)
            })

            newRow.find('input[name="remarks[]"]').each(function(index, value) {
                $(this).val('');
            })

            newRow.insertAfter(row);
            $("select.desc-tetap").select2();
            getCostTetap()
        });
    });

    function getCostTetap() {
        var costTetap = document.querySelectorAll('input[name="cost[]"]');
        $.each($('.desc-tetap'), function(index, value) {
            $('#' + value.id).change(function() {
                var id = $(this).val();
                $.ajax({
                    url: "<?= base_url('agency/getCostByIdPenawaran') ?>",
                    type: "POST",
                    cache: false,
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function(res) {
                        $('input[id="' + costTetap[index].id + '"]').val(formatNumber(res.cost));
                    }
                })
            })
        })
    }

    function get_cost() {
        var costTambahan = document.querySelectorAll('input[name="cost-tambahan[]"]');
        $.each($('.description'), function(index, value) {
            $('#' + value.id).change(function() {
                var id = $(this).val();
                $.ajax({
                    url: "<?= base_url('agency/getCostByIdPenawaran') ?>",
                    type: "POST",
                    cache: false,
                    data: {
                        id: id,
                    },
                    dataType: "JSON",
                    success: function(res) {
                        $('input[id="' + costTambahan[index].id + '"]').val(formatNumber(res.cost));
                    }
                })
            })
        })
    }

    function setSelect2() {
        let select2 = document.querySelectorAll('.select2');
        for (let index = 0; index < select2.length; index++) {
            $("." + select2[index].classList[1] + "").select2({
                width: "100%"
            })
        }
    }

    function countBaris() {
        var jmlBaris = $('.baris').length;
        return jmlBaris;
    }

    function countBarisTetap() {
        var jmlBaris = $('.baris-tetap').length;
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