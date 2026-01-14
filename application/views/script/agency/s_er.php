<script>
    $(document).ready(function() {
        const autoNumericOptions = {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0, // Ubah jadi 2 jika butuh sen
            unformatOnSubmit: true // Sangat penting agar yang dikirim ke PHP tetap angka murni
        };

        function initAutoNumeric() {
            // Hanya inisialisasi input yang belum memiliki AutoNumeric
            $('.hitung, .amount').each(function() {
                if (!AutoNumeric.getAutoNumericElement(this)) {
                    new AutoNumeric(this, autoNumericOptions);
                }
            });
        }

        // 1. Inisialisasi awal untuk baris pertama
        initAutoNumeric();

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

        $("#globalSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".category-table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // 2. SELECT ALL PER KATEGORI
        $(".select-all").on("click", function() {
            var targetClass = $(this).data('target');
            var $table = $(this).closest('.panel').find('table');

            // Cari checkbox yang tidak disabled DAN sedang tampil (tidak terfilter oleh search)
            var $checkboxes = $table.find('input.' + targetClass + ':not(:disabled):visible');

            // Cek apakah sudah ada yang dicentang atau belum untuk toggle
            var allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;

            $checkboxes.prop('checked', !allChecked);

            // Ubah teks tombol untuk feedback visual
            if (!allChecked) {
                $(this).html('<i class="fe fe-x"></i> Unselect All');
                $(this).addClass('btn-warning').removeClass('btn-default');
            } else {
                $(this).html('<i class="fe fe-check-circle"></i> Select All');
                $(this).addClass('btn-default').removeClass('btn-warning');
            }
        });

        $('.btn-detail-view').on('click', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var items = $(this).data('items'); // Membaca data-items JSON

            $('#view-id').text(id);
            var html = '';

            var allItems = <?php echo json_encode($this->db->get('t_item_pda')->result_array()); ?>;

            // -- I. SECTION DESC --
            var total_desc = 0;
            if (items.desc && items.desc.length > 0) {
                html += '<div class="alert alert-info"><strong>I. DESKRIPSI KEGIATAN</strong></div>';
                html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Desc</th><th>Remarks</th><th>GRT</th><th>Tarif</th><th>Activity</th><th class="text-right">Amount</th></tr></thead><tbody>';
                $.each(items.desc, function(i, v) {
                    total_desc += v.amount_desc.replace(/[^0-9]/g, '') * 1;
                    var itemDetail = allItems.find(item => item.Id == v.id_desc);
                    var desc = itemDetail ? itemDetail.desc : 'Tidak ditemukan';
                    html += '<tr><td>' + desc + '</td><td>' + v.remarks + '</td><td>' + v.grt + '</td><td>' + v.tarif + '</td><td>' + v.activity + '</td><td class="text-right"><b>' + v.amount_desc + '</b></td></tr>';
                });
                html += '<tr class="bg-warning text-white"><td colspan="5" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_desc) + '</td></tr></tbody></table>';
            }

            // -- II. SECTION AGENCY --
            var total_agency = 0;
            if (items.agency && items.agency.length > 0) {

                html += '<div class="alert alert-warning"><strong>II. AGENCY REMUNERATION</strong></div>';
                html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Keterangan</th><th class="text-center">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Sub Total</th></tr></thead><tbody>';
                $.each(items.agency, function(i, v) {
                    var sub_total_agency = v.qty.replace(/[^0-9]/g, '') * v.amount.replace(/[^0-9]/g, '');
                    total_agency += sub_total_agency;
                    var itemDetail = allItems.find(item => item.Id == v.desc);
                    var desc = itemDetail ? itemDetail.desc : 'Tidak ditemukan';
                    html += '<tr><td>' + desc + '</td><td class="text-center">' + v.qty + '</td><td class="text-right">' + v.amount + '</td><td class="text-right">' + formatRupiah(sub_total_agency) + '</td></tr>';
                });
                html += '<tr class="bg-warning text-white"><td colspan="3" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_agency) + '</td></tr></tbody></table>';
            }

            // -- III. SECTION OTHER --
            var total_other = 0;
            if (items.other && items.other.length > 0) {
                html += '<div class="alert alert-danger"><strong>III. OTHER EXPENSES</strong></div>';
                html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Keterangan</th><th>Qty</th><th class="text-right">Amount</th><th class="text-right">Sub Total</th></tr></thead><tbody>';
                $.each(items.other, function(i, v) {
                    var sub_total = v.qty.replace(/[^0-9]/g, '') * v.amount.replace(/[^0-9]/g, '');
                    total_other += sub_total;
                    html += '<tr><td>' + v.desc + '</td><td>' + v.qty + '</td><td class="text-right">' + v.amount + '</td><td class="text-right">' + formatRupiah(sub_total) + '</td></tr>';
                });
                html += '<tr class="bg-warning text-white"><td colspan="3" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_other) + '</td></tr></tbody></table>';
            }


            html += '<span class="text-primary">Total: ' + formatRupiah(total_agency + total_other + total_desc) + ' </span>'

            $('#view-content').html(html);
            $('#modalDetail').modal('show');
        });

    })

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0 // Untuk menghilangkan .00
        }).format(angka).replace('Rp', '').trim(); // Hapus 'Rp' dan spasi di depannya
    }

    function setSelect2() {
        let select2 = document.querySelectorAll('select.items');
        for (let index = 0; index < select2.length; index++) {
            $("." + select2[index].classList[1] + "").select2({
                width: "100%"
            })
        }
    }

    $('#masterCheck').click(function() {
        $('.sub-check').prop('checked', this.checked);
        toggleBtn();
    });
    $('.sub-check').change(toggleBtn);

    function toggleBtn() {
        $('#btnBulk').prop('disabled', $('.sub-check:checked').length === 0);
    }

    $(document).on('click', '.btn-detail-view', function() {
        // 1. Ambil data dari atribut tombol
        var rid = $(this).data('id');
        var oid = $(this).data('oid');
        var penunjukan = $(this).data('penunjukan'); // Pastikan tambahkan data-oid di link
        var items = $(this).data('items');
        var staff = $(this).data('staff');
        var stat = $(this).data('status');

        // 2. Isi Header Modal
        $('#det-id').text(rid);
        $('#det-penunjukan').text('#' + penunjukan);
        $('#det-staff').text(staff);
        $('#det-status').text(stat);

        // 3. Render Tabel Item
        var html = '';

        var total = 0;
        var allItems = <?php echo json_encode($this->db->get('t_item_pda')->result_array()); ?>;

        // -- I. SECTION DESC --
        var total_desc = 0;
        if (items.desc && items.desc.length > 0) {
            html += '<div class="alert alert-info"><strong>I. DESKRIPSI KEGIATAN</strong></div>';
            html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Desc</th><th>Remarks</th><th>GRT</th><th>Tarif</th><th>Activity</th><th class="text-right">Amount</th></tr></thead><tbody>';
            $.each(items.desc, function(i, v) {
                total_desc += v.amount_desc.replace(/[^0-9]/g, '') * 1;
                var itemDetail = allItems.find(item => item.Id == v.id_desc);
                var desc = itemDetail ? itemDetail.desc : 'Tidak ditemukan';
                html += '<tr><td>' + desc + '</td><td>' + v.remarks + '</td><td>' + v.grt + '</td><td>' + v.tarif + '</td><td>' + v.activity + '</td><td class="text-right"><b>' + v.amount_desc + '</b></td></tr>';
            });
            html += '<tr class="bg-warning text-white"><td colspan="5" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_desc) + '</td></tr></tbody></table>';
        }

        // -- II. SECTION AGENCY --
        var total_agency = 0;
        if (items.agency && items.agency.length > 0) {

            html += '<div class="alert alert-warning"><strong>II. AGENCY REMUNERATION</strong></div>';
            html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Keterangan</th><th class="text-center">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Sub Total</th></tr></thead><tbody>';
            $.each(items.agency, function(i, v) {
                var sub_total_agency = v.qty.replace(/[^0-9]/g, '') * v.amount.replace(/[^0-9]/g, '');
                total_agency += sub_total_agency;
                var itemDetail = allItems.find(item => item.Id == v.desc);
                var desc = itemDetail ? itemDetail.desc : 'Tidak ditemukan';
                html += '<tr><td>' + desc + '</td><td class="text-center">' + v.qty + '</td><td class="text-right">' + v.amount + '</td><td class="text-right">' + formatRupiah(sub_total_agency) + '</td></tr>';
            });
            html += '<tr class="bg-warning text-white"><td colspan="3" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_agency) + '</td></tr></tbody></table>';
        }

        // -- III. SECTION OTHER --
        var total_other = 0;
        if (items.other && items.other.length > 0) {
            html += '<div class="alert alert-danger"><strong>III. OTHER EXPENSES</strong></div>';
            html += '<table class="table table-bordered small"><thead class="thead-dark"><tr class="active"><th>Keterangan</th><th>Qty</th><th class="text-right">Amount</th><th class="text-right">Sub Total</th></tr></thead><tbody>';
            $.each(items.other, function(i, v) {
                var sub_total = v.qty.replace(/[^0-9]/g, '') * v.amount.replace(/[^0-9]/g, '');
                total_other += sub_total;
                html += '<tr><td>' + v.desc + '</td><td>' + v.qty + '</td><td class="text-right">' + v.amount + '</td><td class="text-right">' + formatRupiah(sub_total) + '</td></tr>';
            });
            html += '<tr class="bg-warning text-white"><td colspan="3" class"text-right">Total</td><td class="text-right">' + formatRupiah(total_other) + '</td></tr></tbody></table>';
        }


        // html += '<span class="text-primary">Total: ' + formatRupiah(total_agency + total_other + total_desc) + ' </span>'


        html += '</tbody><tfoot style="background:#eee;"><tr><th colspan="2" class="text-right">GRAND TOTAL</th><th class="text-right">Rp ' + (total_agency + total_other + total_desc).toLocaleString('id-ID') + '</th></tr></tfoot></table>';

        $('#det-content').html(html);


        // Tampilkan Modal
        $('#modalDetailReq').modal('show');
    });

    var table = $('#tableRequestAll').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [], // Initial order handled by Controller
        "ajax": {
            "url": "<?= base_url('pda/fetch_request_serverside') ?>",
            "type": "POST"
        },
        "columns": [{
                "data": "penunjukan"
            },
            {
                "data": "request_id"
            },
            {
                "data": "submitted_at"
            },
            {
                "data": "submitted_by"
            },
            {
                "data": "nominal"
            },
            {
                "data": "status"
            },
            {
                "data": "action",
                "orderable": false,
                "className": "text-center"
            }
        ],
        "language": {
            "processing": "<div class='loader'>Memuat Data...</div>",
            "search": "Cari (ID/Staff):",
            "lengthMenu": "_MENU_ baris per halaman"
        },
        "columnDefs": [{
            "targets": 0,
            "orderable": false,
        }]
    });

    $(document).on('click', '.btn-upload-settle', function() {
        var rid = $(this).data('rid');
        var oid = $(this).data('oid');
        var nominal = $(this).data('nominal');

        $('#up_rid').val(rid);
        $('#up_oid').val(oid);
        $('#display_rid').text(rid);
        $('#display_nominal').text('Rp ' + nominal.toLocaleString('id-ID'));

        $('#modalUploadSettle').modal('show');
    });

    $(document).on('click', '.btn-view-nota', function() {
        var file = $(this).data('file');
        var rid = $(this).data('rid');
        $('#nota-rid').text(rid);

        if (file.toLowerCase().endsWith('.pdf')) {
            $('#img-nota').hide();
            $('#pdf-nota').attr('src', file).show();
        } else {
            $('#pdf-nota').hide();
            $('#img-nota').attr('src', file).show();
        }
        $('#modalNota').modal('show');
    });
</script>