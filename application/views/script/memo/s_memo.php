<script>
  $(document).ready(function() {
    $('#tujuan, #cc').select2({
      placeholder: 'Search user',
      ajax: {
        url: '<?= base_url('app/search_user_memo') ?>',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            q: params.term || '',
            page: params.page || 1
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;

          return {
            results: data.items,
            pagination: {
              more: data.more
            }
          };
        },
        cache: true
      }
    });

    <?php if (isset($selected_item)) { ?>
      // Optional: Load preselected items (e.g., in edit form)
      var preselected = <?= json_encode($selected_item) ?>; // Example: [{id: 1, text: "Item A"}, {id: 2, text: "Item B"}]
      preselected.forEach(function(item) {
        var option = new Option(item.text, item.id, true, true);
        $('#tujuan').append(option).trigger('change');
      });
    <?php } ?>

    <?php if (isset($selected_item_cc)) { ?>
      // Optional: Load preselected items (e.g., in edit form)
      var preselectedcc = <?= json_encode($selected_item_cc) ?>; // Example: [{id: 1, text: "Item A"}, {id: 2, text: "Item B"}]
      preselectedcc.forEach(function(item) {
        var optioncc = new Option(item.text, item.id, true, true);
        $('#cc').append(optioncc).trigger('change');
      });
    <?php } ?>



    $(".btn-send").click(function(e) {
      e.preventDefault();
      for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
      }
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
</script>

<script>
  function openMemo(id) {
    location.href = "<?= site_url('app/memo_view/') ?>" + id
  }
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const isPremium = <?php echo json_encode($this->session->userdata('is_premium')); ?>;
    const upgradeUrl = '<?= base_url('subscription/upgrade') ?>'; // Adjust this URL as needed

    function showPremiumDeniedSwal() {
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

    // ... (your existing JavaScript for other buttons) ...

    // Disable Attachment Input if Not Premium
    const attachmentInput = document.getElementById('attach');
    if (attachmentInput) { // Check if the element exists
      if (!isPremium) {
        attachmentInput.disabled = true; // Disable the input field
        // Optional: Add a tooltip or message near the input to explain why it's disabled
        const parentDiv = attachmentInput.closest('.col-sm-9'); // Find the parent div
        if (parentDiv) {
          const message = document.createElement('small');
          message.classList.add('text-danger', 'form-text');
          message.textContent = 'Upgrade to premium to upload attachments.';
          parentDiv.appendChild(message);
        }
      }
    }

    const editorElement = document.getElementById('ckeditor');
    if (typeof CKEDITOR !== 'undefined' && editorElement) {
      let editorConfig = {}; // Start with an empty config object

      // Initialize CKEditor first, potentially with its default toolbar
      // We are NOT using removeButtons or removePlugins here
      if (CKEDITOR.instances.ckeditor) {
        CKEDITOR.instances.ckeditor.destroy(true);
      }
      CKEDITOR.replace('ckeditor', editorConfig); // Initialize with default or basic config

      // Once the editor is ready, attach event listeners if not premium
      CKEDITOR.on('instanceReady', function(evt) {
        const editor = evt.editor; // Get the editor instance

        if (!isPremium) {
          // Make the editor content area read-only (optional but good for non-premium)
          // editor.setReadOnly(true);

          // Add a visual cue to the editor itself
          const editorContainer = editor.container.$;
          if (editorContainer) {
            editorContainer.style.opacity = '0.7';
            editorContainer.style.backgroundColor = '#f0f0f0';
            // You might also disable the toolbar visually to indicate read-only
            // editor.ui.space('toolbar').setStyle('pointer-events', 'none');
          }

          // --- Add the message below the CKEditor ---
          const parentDiv = editorElement.closest('.col-sm-12'); // Find the parent div of the textarea
          if (parentDiv) {
            const message = document.createElement('small');
            message.classList.add('text-danger', 'form-text');
            message.textContent = 'Upgrade to premium to Use Image, Link, and Table feature.';
            parentDiv.appendChild(message);
          }

          // --- Event Listeners for Premium Features ---

          // 1. Image (e.g., clicking image button, drag & drop, paste)
          editor.on('fileUploadRequest', function(event) {
            // This event fires when an image is dragged & dropped or pasted
            event.cancel(); // Prevent the upload
            showPremiumDeniedSwal();
            editor.execCommand('undo'); // Undo the paste/drop if possible (might vary)
          });
          editor.on('paste', function(event) {
            // Check if the paste data contains image data (e.g., base64)
            if (event.data.dataTransfer && event.data.dataTransfer.getFilesCount() > 0) {
              event.cancel(); // Prevent the paste
              showPremiumDeniedSwal();
            }
          }, null, null, 999); // Higher priority to ensure it runs first

          // Prevent opening the image dialog directly if the button is clicked
          editor.on('beforeCommandExec', function(event) {
            // The 'image' command is triggered by the image button
            if (event.data.name === 'image' || event.data.name === 'imagebutton') {
              event.cancel(); // Stop the command from executing
              showPremiumDeniedSwal();
            }
            // Also for "table" and "link"
            if (event.data.name === 'table' || event.data.name === 'link') {
              event.cancel();
              showPremiumDeniedSwal();
            }
          });

          // You might also need to explicitly handle context menu options if they allow inserting these
          // This often requires specific plugin knowledge or overriding context menu behavior.
          // For example, to prevent 'Image Properties' or 'Table Properties' from context menu:
          editor.on('menuShow', function(event) {
            if (event.data.editor.contextMenu) {
              const items = event.data.editor.contextMenu._.menuItems;
              for (const name in items) {
                if (items.hasOwnProperty(name)) {
                  // Common commands related to images, tables, links
                  if (name === 'image' || name === 'table' || name === 'link' ||
                    name.startsWith('table') || name.startsWith('cell') || name.startsWith('row') || name.startsWith('column')) {
                    items[name].state = CKEDITOR.TRISTATE_DISABLED; // Disable the menu item
                  }
                }
              }
            }
          });

        } else {
          // Premium user: editor remains fully functional (no readOnly, no event listeners)
        }
      });
    }

  });
</script>