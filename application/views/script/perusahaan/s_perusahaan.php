<script>
    const allCoa = <?= json_encode($coa_all) ?>;
    // Get the PHP variables for initial selection
    const selectedNamaPerkiraan = '<?= $perusahaan->nama_coa_ppn_keluaran ?? '' ?>';
    const selectedNoSbb = '<?= $perusahaan->nomor_coa_ppn_keluaran ?? '' ?>';

    $(document).ready(function() {
        // --- 1. Prepare the Data ---
        // Default Option
        const defaultOption = {
            id: '23011 :: PPN KELUARAN :: Default',
            text: '23011 :: PPN KELUARAN :: Default',
            isDefault: true,
            originalNoSbb: '23011',
            originalNamaPerkiraan: 'PPN KELUARAN'
        };

        // Format other COA data
        const formattedCoa = allCoa.map(item => {
            const displayText = `${item.no_sbb} :: ${item.nama_perkiraan}`;
            return {
                id: displayText,
                text: displayText,
                isDefault: false,
                originalNoSbb: item.no_sbb,
                originalNamaPerkiraan: item.nama_perkiraan
            };
        });

        // Combine all options
        const combinedCoa = [defaultOption, ...formattedCoa];

        // --- 2. Initialize Select2 ---
        const $selectElement = $('#coa_ppn_Keluaran');

        $selectElement.select2({
            placeholder: 'Cari Kode BB...',
            minimumInputLength: 0,
            data: combinedCoa,
            templateResult: function(option) {
                return option.text;
            },
            templateSelection: function(option) {
                return option.text;
            }
        });

        // --- 3. Set Initial Selected Value ---
        let selectedId = null;

        // Check if the default option should be selected
        if (selectedNoSbb === defaultOption.originalNoSbb && selectedNamaPerkiraan === defaultOption.originalNamaPerkiraan) {
            selectedId = defaultOption.id;
        } else {
            // Search for the matching item in the formatted data
            const matchedItem = formattedCoa.find(item =>
                item.originalNoSbb === selectedNoSbb &&
                item.originalNamaPerkiraan === selectedNamaPerkiraan
            );

            if (matchedItem) {
                selectedId = matchedItem.id;
            }
        }

        // Set the selected value if a match was found
        if (selectedId !== null) {
            $selectElement.val(selectedId).trigger('change');
        }

        // --- 4. Handle Form Submission ---
        // This part is the same as our previous solution to ensure values are sent to PHP
        $('#update_perusahaan_form').on('submit', function() {
            const selectedData = $selectElement.select2('data')[0];
            let finalNoSbb = '',
                finalNamaPerkiraan = '';

            if (selectedData) {
                if (selectedData.isDefault) {
                    finalNoSbb = selectedData.originalNoSbb;
                    finalNamaPerkiraan = selectedData.originalNamaPerkiraan;
                } else {
                    finalNoSbb = selectedData.originalNoSbb;
                    finalNamaPerkiraan = selectedData.originalNamaPerkiraan;
                }
            }

            // Add hidden inputs to the form before submission
            $(this).append($('<input>').attr({
                type: 'hidden',
                name: 'coa_ppn_keluaran_no_sbb',
                value: finalNoSbb
            }));
            $(this).append($('<input>').attr({
                type: 'hidden',
                name: 'coa_ppn_keluaran_nama_perkiraan',
                value: finalNamaPerkiraan
            }));
        });
    });
</script>
<script>
    // const allCoa = <?= json_encode($coa_all) ?>;
    // Get the PHP variables for initial selection
    const selectedNamaPerkiraan_pph = '<?= $perusahaan->nama_coa_utang_pph23 ?? '' ?>';
    const selectedNoSbb_pph = '<?= $perusahaan->nomor_coa_utang_pph23 ?? '' ?>';

    $(document).ready(function() {
        // --- 1. Prepare the Data ---
        // Default Option
        const defaultOption = {
            id: '23014 :: UTANG PPH 23 :: Default',
            text: '23014 :: UTANG PPH 23 :: Default',
            isDefault: true,
            originalNoSbb: '23014',
            originalNamaPerkiraan: 'UTANG PPH 23'
        };

        // Format other COA data
        const formattedCoa = allCoa.map(item => {
            const displayText = `${item.no_sbb} :: ${item.nama_perkiraan}`;
            return {
                id: displayText,
                text: displayText,
                isDefault: false,
                originalNoSbb: item.no_sbb,
                originalNamaPerkiraan: item.nama_perkiraan
            };
        });

        // Combine all options
        const combinedCoa = [defaultOption, ...formattedCoa];

        // --- 2. Initialize Select2 ---
        const $selectElement = $('#coa_utang_pph');

        $selectElement.select2({
            placeholder: 'Cari Kode BB...',
            minimumInputLength: 0,
            data: combinedCoa,
            templateResult: function(option) {
                return option.text;
            },
            templateSelection: function(option) {
                return option.text;
            }
        });

        // --- 3. Set Initial Selected Value ---
        let selectedId = null;

        console.log(selectedNoSbb_pph);
        console.log(selectedNamaPerkiraan_pph);

        // Check if the default option should be selected
        if (selectedNoSbb_pph === defaultOption.originalNoSbb && selectedNamaPerkiraan_pph === defaultOption.originalNamaPerkiraan) {
            selectedId = defaultOption.id;
        } else {
            // Search for the matching item in the formatted data
            const matchedItem = formattedCoa.find(item =>
                item.originalNoSbb === selectedNoSbb_pph &&
                item.originalNamaPerkiraan === selectedNamaPerkiraan_pph
            );

            if (matchedItem) {
                selectedId = matchedItem.id;
            }
        }

        // Set the selected value if a match was found
        if (selectedId !== null) {
            $selectElement.val(selectedId).trigger('change');
        }

        // --- 4. Handle Form Submission ---
        $('#update_perusahaan_form').on('submit', function() {
            const selectedData = $selectElement.select2('data')[0];
            let finalNoSbb = '',
                finalNamaPerkiraan = '';

            if (selectedData) {
                if (selectedData.isDefault) {
                    finalNoSbb = selectedData.originalNoSbb;
                    finalNamaPerkiraan = selectedData.originalNamaPerkiraan;
                } else {
                    finalNoSbb = selectedData.originalNoSbb;
                    finalNamaPerkiraan = selectedData.originalNamaPerkiraan;
                }
            }

            // Add hidden inputs to the form before submission
            $(this).append($('<input>').attr({
                type: 'hidden',
                name: 'coa_utang_pph_no_sbb',
                value: finalNoSbb
            }));
            $(this).append($('<input>').attr({
                type: 'hidden',
                name: 'coa_utang_pph_nama_perkiraan',
                value: finalNamaPerkiraan
            }));
        });
    });
</script>
<script>
    function previewImage(event) {
        // Get the image preview element
        const imagePreview = document.getElementById('logo_preview');
        // Get the selected file
        const file = event.target.files[0];

        // Ensure a file was selected and it's an image
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Set the image source to the data URL of the selected file
                imagePreview.src = e.target.result;
                // Show the preview image
                imagePreview.style.display = 'block';
            };

            // Read the image as a Data URL
            reader.readAsDataURL(file);
        } else {
            // If no file is selected or it's not an image, hide the preview image
            imagePreview.style.display = 'none';
        }
    }

    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get all buttons with the class 'btns-edit'
        const editButtons = document.querySelectorAll('.btns-edit');

        // Loop through each button and attach a click event listener
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the ID of the target input field from the data-target attribute
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                if (targetInput) {
                    // Check if the target is the logo input
                    if (targetId === 'logo_input') {
                        // Toggle the disabled attribute and the custom class
                        if (targetInput.disabled) {
                            // If currently disabled, enable it for editing
                            targetInput.disabled = false;
                            targetInput.classList.remove('is-readonly');
                            this.classList.remove('btn-secondary');
                            this.classList.add('btn-primary');
                            this.innerHTML = '<i class="fe fe-check"></i>';
                        } else {
                            // If currently enabled, check if a file is present
                            if (targetInput.files.length > 0) {
                                // If a file is selected, do not disable it. Just apply the readonly styling.
                                targetInput.classList.add('is-readonly');
                                this.classList.remove('btn-primary');
                                this.classList.add('btn-secondary');
                                this.innerHTML = '<i class="fe fe-edit-2"></i>';
                            } else {
                                // If no file is selected, disable the input
                                targetInput.disabled = true;
                                targetInput.classList.add('is-readonly');
                                this.classList.remove('btn-primary');
                                this.classList.add('btn-secondary');
                                this.innerHTML = '<i class="fe fe-edit-2"></i>';
                            }
                        }
                    } else {
                        // Logic for other inputs using readonly
                        if (targetInput.readOnly) {
                            targetInput.readOnly = false;
                            targetInput.classList.remove('is-readonly');
                            // Add a visual indicator, like changing the button's color
                            this.classList.remove('btn-secondary');
                            this.classList.add('btn-primary');
                            this.innerHTML = '<i class="fe fe-check"></i>';
                        } else {
                            targetInput.readOnly = true;
                            targetInput.classList.add('is-readonly');
                            // Revert the visual indicator
                            this.classList.remove('btn-primary');
                            this.classList.add('btn-secondary');
                            this.innerHTML = '<i class="fe fe-edit-2"></i>';
                        }
                    }
                }
            });
        });
    });
</script>

<script>
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
</script>