<script>
    const isPremium = <?php echo json_encode($this->session->userdata('is_premium')); ?>;
    $(document).ready(function() {
        $('#user-table').dataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            // If you are using Scroller, you would add 'deferRender: true' and 'scrollY' options here,
            // and optionally 'scroller: true'.
            // Example:
            // deferRender: true,
            // scrollY: 200, // or '50vh'
            // scroller: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo site_url('perusahaan/ajax_user_list') ?>",
                type: "POST"
            },
            order: [],
            iDisplayLength: 10,
            columnDefs: [{
                targets: -1, // Adjust target index if 'Action' column is not always the 9th column (index 8)
                orderable: false
            }],
            // The 'dom' property has been replaced with the 'layout' option
            // to place the search bar at the top, and the info and pagination controls at the bottom.
            // layout: {
            //     topStart: 'search',
            //     topEnd: '',
            //     bottomStart: 'info',
            //     bottomEnd: 'paging'
            // }
        });

        $('.btn-reset-cuti').click(function(e) {
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
                    if (isPremium) {
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
                                        location.href = `${res.reload}`
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
                                    title: `${status}`,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            },
                        });
                    } else {
                        Swal.fire({
                            title: 'Siap Menjadi Raja <?= '<img src="' . base_url() . 'assets/icons/sword_gray.png" alt="Sword Icon" width="32" height="32">' ?>', // New title: "Ready to Become King?"
                            html: 'Kekuasaan untuk menambah dan mengelola pengguna dalam kendali Anda di tangan Anda! Tingkatkan akun Anda sekarang untuk membuka singgasana dan mengklaim tahta Anda..', // New text with HTML for emphasis
                            icon: 'warning', // IMPORTANT: Set icon to undefined or remove it if you're using iconHtml
                            iconHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="50" height="50"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>', // Changed icon to question, suitable for asking a choice
                            confirmButtonText: 'Ambil Mahkota Sekarang!', // New confirm button text: "Take the Crown Now!"
                            showCancelButton: true,
                            cancelButtonText: 'Nanti Saja, Belum Siap Jadi Raja', // New cancel button text: "Later, Not Ready to Be King Yet"
                            customClass: {
                                confirmButton: 'btn btn-primary', // Optional: Use your custom btn-pink class for the confirm button
                                cancelButton: 'btn btn-pink' // Optional: Style the cancel button differently
                            },
                            buttonsStyling: false // Important if you use customClass for buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Optional: Redirect to an upgrade page if 'Ambil Mahkota Sekarang!' is clicked
                                window.location.href = '<?= base_url('subscription/upgrade') ?>'; // Adjust this URL as needed
                            }
                        });
                    }
                }
            });

        })
    });

    function onDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('perusahaan/hapus_user/') ?>", // Use POST for ID, don't append to URL unless it's a RESTful DELETE
                    type: 'POST', // Keep as POST
                    data: {
                        id: id
                    },
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        let iconType = 'error'; // Default to error
                        if (response.status == 'success') {
                            iconType = 'success';
                        } else if (response.status == 'info') {
                            iconType = 'info'; // Use info icon for "not found" cases
                        }

                        Swal.fire(
                            response.status === 'success' ? 'Berhasil!' : 'Perhatian!', // Dynamic title
                            response.message, // Display the message from the backend
                            iconType
                        ).then(() => {
                            // Only reload the table if it was a success or a clear 'info' (already deleted) case
                            if (response.status === 'success' || response.status === 'info') {
                                // Assuming your DataTables ID is 'datatable', not 'table1' based on previous snippets
                                $('#user-table').DataTable().ajax.reload(null, false);
                                // location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText); // Log full error for debugging
                        Swal.fire(
                            'Kesalahan Jaringan!', // More specific error message
                            'Terjadi kesalahan komunikasi dengan server. Silakan coba lagi.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function onResetCuti(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Cuti akan di reset!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('Cuti/resetCuti/') ?>", // Use POST for ID, don't append to URL unless it's a RESTful DELETE
                    type: 'POST', // Keep as POST
                    data: {
                        nip: id
                    },
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        let iconType = 'error'; // Default to error
                        if (response.status == 'success') {
                            iconType = 'success';
                        } else if (response.status == 'info') {
                            iconType = 'info'; // Use info icon for "not found" cases
                        }

                        Swal.fire(
                            response.status === 'success' ? 'Berhasil!' : 'Perhatian!', // Dynamic title
                            response.message, // Display the message from the backend
                            iconType
                        ).then(() => {
                            // Only reload the table if it was a success or a clear 'info' (already deleted) case
                            if (response.status === 'success' || response.status === 'info') {
                                // Assuming your DataTables ID is 'datatable', not 'table1' based on previous snippets
                                $('#user-table').DataTable().ajax.reload(null, false);
                                // location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText); // Log full error for debugging
                        Swal.fire(
                            'Kesalahan Jaringan!', // More specific error message
                            'Terjadi kesalahan komunikasi dengan server. Silakan coba lagi.',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function onResetCutiAll() {

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Cuti akan di reset!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (isPremium) {
                    $.ajax({
                        url: "<?= base_url('Cuti/resetCutiAll/') ?>", // Use POST for ID, don't append to URL unless it's a RESTful DELETE
                        type: 'POST',
                        dataType: 'json', // Expect JSON response
                        success: function(response) {
                            let iconType = 'error'; // Default to error
                            if (response.status == 'success') {
                                iconType = 'success';
                            } else if (response.status == 'info') {
                                iconType = 'info'; // Use info icon for "not found" cases
                            }

                            Swal.fire(
                                response.status === 'success' ? 'Berhasil!' : 'Perhatian!', // Dynamic title
                                response.message, // Display the message from the backend
                                iconType
                            ).then(() => {
                                // Only reload the table if it was a success or a clear 'info' (already deleted) case
                                if (response.status === 'success' || response.status === 'info') {
                                    // Assuming your DataTables ID is 'datatable', not 'table1' based on previous snippets
                                    $('#user-table').DataTable().ajax.reload(null, false);
                                    // location.reload();
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error, xhr.responseText); // Log full error for debugging
                            Swal.fire(
                                'Kesalahan Jaringan!', // More specific error message
                                'Terjadi kesalahan komunikasi dengan server. Silakan coba lagi.',
                                'error'
                            );
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Siap Menjadi Raja <?= '<img src="' . base_url() . 'assets/icons/sword_gray.png" alt="Sword Icon" width="32" height="32">' ?>', // New title: "Ready to Become King?"
                        html: 'Kekuasaan untuk menambah dan mengelola pengguna dalam kendali Anda di tangan Anda! Tingkatkan akun Anda sekarang untuk membuka singgasana dan mengklaim tahta Anda..', // New text with HTML for emphasis
                        icon: 'warning', // IMPORTANT: Set icon to undefined or remove it if you're using iconHtml
                        iconHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="50" height="50"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>', // Changed icon to question, suitable for asking a choice
                        confirmButtonText: 'Ambil Mahkota Sekarang!', // New confirm button text: "Take the Crown Now!"
                        showCancelButton: true,
                        cancelButtonText: 'Nanti Saja, Belum Siap Jadi Raja', // New cancel button text: "Later, Not Ready to Be King Yet"
                        customClass: {
                            confirmButton: 'btn btn-primary', // Optional: Use your custom btn-pink class for the confirm button
                            cancelButton: 'btn btn-pink' // Optional: Style the cancel button differently
                        },
                        buttonsStyling: false // Important if you use customClass for buttons
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Optional: Redirect to an upgrade page if 'Ambil Mahkota Sekarang!' is clicked
                            window.location.href = '<?= base_url('subscription/upgrade') ?>'; // Adjust this URL as needed
                        }
                    });
                }
            }
        });
    }

    $('.parent-checkbox').on('change', function() {
        const parentId = $(this).val(); // Get the ID of the parent menu
        const isChecked = $(this).prop('checked'); // Check if parent is checked or unchecked

        // Select all child checkboxes that have this parentId and set their checked state
        $(`.child-checkbox[data-parent-id="${parentId}"]`).prop('checked', isChecked);
    });

    // Optional: When a child checkbox is clicked, check/uncheck its parent
    // This makes the parent automatically check if any child is selected, or uncheck if no children are selected
    $('.child-checkbox').on('change', function() {
        const parentId = $(this).data('parent-id');
        const $parentCheckbox = $(`#menu_${parentId}`); // Get the parent checkbox element

        // Count how many children of this parent are checked
        const totalChildren = $(`.child-checkbox[data-parent-id="${parentId}"]`).length;
        const checkedChildren = $(`.child-checkbox[data-parent-id="${parentId}"]:checked`).length;

        // If at least one child is checked, check the parent. Otherwise, uncheck.
        if (checkedChildren > 0) {
            $parentCheckbox.prop('checked', true);
        } else {
            // Only uncheck if ALL children are unchecked
            $parentCheckbox.prop('checked', false);
        }
    });

    // Optional: Initial check for parent checkboxes on page load
    // If any child is checked, ensure its parent is also checked
    $('.child-checkbox:checked').each(function() {
        const parentId = $(this).data('parent-id');
        $(`#menu_${parentId}`).prop('checked', true);
    });

    document.getElementById('addUserBtn').addEventListener('click', function(event) {
        // Prevent the default link behavior immediately
        event.preventDefault();

        // This PHP snippet retrieves the session data and outputs it as a JavaScript variable.
        // It's crucial this is rendered by PHP on the server side.
        const isPremium = <?php echo json_encode($this->session->userdata('is_premium')); ?>;
        const redirectUrl = "<?= base_url('perusahaan/add_user') ?>";

        if (isPremium) {
            // If premium, proceed to the URL
            window.location.href = redirectUrl;
        } else {
            // If not premium, show SweetAlert
            Swal.fire({
                title: 'Siap Menjadi Raja <?= '<img src="' . base_url() . 'assets/icons/sword_gray.png" alt="Sword Icon" width="32" height="32">' ?>', // New title: "Ready to Become King?"
                html: 'Kekuasaan untuk menambah dan mengelola pengguna dalam kendali Anda di tangan Anda! Tingkatkan akun Anda sekarang untuk membuka singgasana dan mengklaim tahta Anda..', // New text with HTML for emphasis
                icon: 'warning', // IMPORTANT: Set icon to undefined or remove it if you're using iconHtml
                iconHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="50" height="50"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>', // Changed icon to question, suitable for asking a choice
                confirmButtonText: 'Ambil Mahkota Sekarang!', // New confirm button text: "Take the Crown Now!"
                showCancelButton: true,
                cancelButtonText: 'Nanti Saja, Belum Siap Jadi Raja', // New cancel button text: "Later, Not Ready to Be King Yet"
                customClass: {
                    confirmButton: 'btn btn-primary', // Optional: Use your custom btn-pink class for the confirm button
                    cancelButton: 'btn btn-pink' // Optional: Style the cancel button differently
                },
                buttonsStyling: false // Important if you use customClass for buttons
            }).then((result) => {
                if (result.isConfirmed) {
                    // Optional: Redirect to an upgrade page if 'Ambil Mahkota Sekarang!' is clicked
                    window.location.href = '<?= base_url('subscription/upgrade') ?>'; // Adjust this URL as needed
                }
            });
        }
    });

    <?php if ($this->session->flashdata('swal_message')) : ?>
        const swalConfig = <?php echo json_encode($this->session->flashdata('swal_message')); ?>;

        // Remove the redirectUrl from swalConfig as it's handled separately
        const redirectUrl = swalConfig.redirectUrl || null;
        delete swalConfig.redirectUrl; // Clean up the config

        Swal.fire(swalConfig).then((result) => {
            if (result.isConfirmed && redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    <?php endif; ?>

    // If you were *not* redirecting and passing $data['swal_message'] directly:
    <?php
    /*
        if (isset($swal_message)) : ?>
            const swalConfig = <?php echo json_encode($swal_message); ?>;
            const redirectUrl = swalConfig.redirectUrl || null;
            delete swalConfig.redirectUrl;

            Swal.fire(swalConfig).then((result) => {
                if (result.isConfirmed && redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        <?php endif;
        */
    ?>
</script>
<script>
    $(document).ready(function() {

        // --- Event Listener for "Add New Option" Button (Toggles visibility of inputs) ---
        $('#addOptionBtn').on('click', function() {
            <?php
            if ($this->session->userdata('is_premium')) {
            ?>
                $('#add-bagian-tr').toggle(); // Toggles display: none/table-row
                // Optionally, scroll to the new input fields if they appear off-screen
                if ($('#add-bagian-tr').is(':visible')) {
                    $('html, body').animate({
                        scrollTop: $('#add-bagian-tr').offset().top - 100 // Adjust offset as needed
                    }, 500);
                    $('#input_kode').focus(); // Focus on the first input field
                }

                $('#add-bagian-div').toggle(); // Toggles display: none/table-row
                // Optionally, scroll to the new input fields if they appear off-screen
                if ($('#add-bagian-div').is(':visible')) {
                    $('html, body').animate({
                        scrollTop: $('#add-bagian-div').offset().top - 100 // Adjust offset as needed
                    }, 500);
                    $('#input_kode').focus(); // Focus on the first input field
                }
            <?php
            } else {
            ?>
                Swal.fire({
                    title: 'Siap Menjadi Raja <?= '<img src="' . base_url() . 'assets/icons/sword_gray.png" alt="Sword Icon" width="32" height="32">' ?>', // New title: "Ready to Become King?"
                    html: 'Kekuasaan untuk menambah dan mengelola pengguna dalam kendali Anda di tangan Anda! Tingkatkan akun Anda sekarang untuk membuka singgasana dan mengklaim tahta Anda..', // New text with HTML for emphasis
                    icon: 'warning', // IMPORTANT: Set icon to undefined or remove it if you're using iconHtml
                    iconHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="50" height="50"><path fill="#FFD43B" d="M309 106c11.4-7 19-19.7 19-34c0-22.1-17.9-40-40-40s-40 17.9-40 40c0 14.4 7.6 27 19 34L209.7 220.6c-9.1 18.2-32.7 23.4-48.6 10.7L72 160c5-6.7 8-15 8-24c0-22.1-17.9-40-40-40S0 113.9 0 136s17.9 40 40 40c.2 0 .5 0 .7 0L86.4 427.4c5.5 30.4 32 52.6 63 52.6l277.2 0c30.9 0 57.4-22.1 63-52.6L535.3 176c.2 0 .5 0 .7 0c22.1 0 40-17.9 40-40s-17.9-40-40-40s-40 17.9-40 40c0 9 3 17.3 8 24l-89.1 71.3c-15.9 12.7-39.5 7.5-48.6-10.7L309 106z"/></svg>', // Changed icon to question, suitable for asking a choice
                    confirmButtonText: 'Ambil Mahkota Sekarang!', // New confirm button text: "Take the Crown Now!"
                    showCancelButton: true,
                    cancelButtonText: 'Nanti Saja, Belum Siap Jadi Raja', // New cancel button text: "Later, Not Ready to Be King Yet"
                    customClass: {
                        confirmButton: 'btn btn-primary', // Optional: Use your custom btn-pink class for the confirm button
                        cancelButton: 'btn btn-pink' // Optional: Style the cancel button differently
                    },
                    buttonsStyling: false // Important if you use customClass for buttons
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Optional: Redirect to an upgrade page if 'Ambil Mahkota Sekarang!' is clicked
                        window.location.href = '<?= base_url('subscription/upgrade') ?>'; // Adjust this URL as needed
                    }
                });
            <?php
            }
            ?>
        });

        // --- Event Listener for "Cancel" Button ---
        $('#cancelNewBagianBtn').on('click', function() {
            $('#add-bagian-tr').hide(); // Hide the input fields
            $('#form-add-bagian')[0].reset(); // Clear the form
            $('#statusMessageBagian').empty(); // Clear any status messages
        });

        // --- Event Listener for "Submit" Button (Handles AJAX call) ---
        $('#submitNewBagianBtn').on('click', function() {
            // const inputKode = $('#input_kode').val().trim();
            const inputNama = $('#input_nama').val().trim();
            const inputKodeNama = $('#input_kode_nama').val().trim();
            const inputIdPrsh = $('#input_id_prsh').val(); // Hidden field, should always have a value
            const statusMessage = $('#statusMessageBagian');
            const targetSelect = $('#mySelect');

            // Basic client-side validation
            if (!inputNama || !inputKodeNama) {
                statusMessage.html('<div class="alert alert-warning">Please fill Nama, and Kode Nama fields!</div>');
                return;
            }

            // Disable button and show loading
            const $submitBtn = $(this);
            $submitBtn.prop('disabled', true).text('Submitting...');
            statusMessage.html('<div class="alert alert-info">Adding new bagian...</div>');

            // --- AJAX call to Backend (CodeIgniter) ---
            $.ajax({
                url: '<?php echo base_url("perusahaan/save_new_bagian"); ?>', // *** REPLACE with your actual CI URL ***
                method: 'POST',
                data: {
                    // kode: inputKode,
                    nama: inputNama,
                    kode_nama: inputKodeNama,
                    id_prsh: inputIdPrsh
                },
                dataType: 'json', // Expecting JSON response from CI backend
                success: function(response) {
                    if (response.status === 'success') {
                        // Create the new <option> element
                        // response.new_id: The ID of the newly inserted row from database
                        // response.display_text: The text to show in the dropdown (e.g., "KODE - NAMA")
                        const newOption = $('<option></option>')
                            .val(response.new_id)
                            .text(response.display_text);

                        // Append the new option to the select tag
                        targetSelect.append(newOption);

                        // Optional: Select the newly added option
                        targetSelect.val(response.new_id);

                        // --- "Refresh" the Select Tag (if using a JS library) ---
                        // If you're using Select2:
                        // targetSelect.trigger('change');
                        // If you're using Bootstrap-Select:
                        // targetSelect.selectpicker('refresh');

                        statusMessage.html('<div class="alert alert-success">Bagian added successfully!</div>');

                        // Clear input fields and hide the form
                        $('#form-add-bagian')[0].reset();
                        $('#add-bagian-tr').hide(500); // Hide with a slight animation
                    } else {
                        statusMessage.html('<div class="alert alert-danger">Error: ' + (response.message || 'Unknown error.') + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    statusMessage.html('<div class="alert alert-danger">AJAX Error: ' + status + ' - ' + error + '</div>');
                    console.error("AJAX Error (Bagian):", xhr.responseText);
                },
                complete: function() {
                    // Re-enable the submit button regardless of success/failure
                    $submitBtn.prop('disabled', false).text('Submit');
                }
            });
        });
    });
</script>