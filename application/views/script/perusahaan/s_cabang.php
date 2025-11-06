<script>
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
                url: "<?php echo site_url('perusahaan/ajax_cabang_list') ?>",
                type: "POST"
            },
            order: [],
            iDisplayLength: 10,
            columnDefs: [{
                targets: -1, // Adjust target index if 'Action' column is not always the 9th column (index 8)
                orderable: false
            }],
            // layout: {
            //     topStart: 'search',
            //     topEnd: '',
            //     bottomStart: 'info',
            //     bottomEnd: 'paging'
            // }
        });
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
                    url: "<?= base_url('perusahaan/hapus_cabang/') ?>", // Use POST for ID, don't append to URL unless it's a RESTful DELETE
                    type: 'POST', // Keep as POST
                    data: {
                        id: id
                    },
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        console.log(response);
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

    document.getElementById('addCabangBtn').addEventListener('click', function(event) {
        // Prevent the default link behavior immediately
        event.preventDefault();

        // This PHP snippet retrieves the session data and outputs it as a JavaScript variable.
        // It's crucial this is rendered by PHP on the server side.
        const isPremium = <?php echo json_encode($this->session->userdata('is_premium')); ?>;
        const redirectUrl = "<?= base_url('perusahaan/add_cabang') ?>";

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