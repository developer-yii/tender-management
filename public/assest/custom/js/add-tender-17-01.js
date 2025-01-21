function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// start Folder with File
document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.getElementById("addFolder");
    const accordion = document.getElementById("accordionExample");

    // Handle folder form submission
    addForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent normal form submission

        const folderName = document.getElementById("folder_name").value.trim();
        if (folderName === "") {
            alert("Folder name cannot be empty!");
            return;
        }

        // Generate unique ID for the folder
        const folderId = "folder" + Math.random().toString(36).substr(2, 9);

        // Create the folder section dynamically
        const newFolder = document.createElement("div");
        newFolder.classList.add("accordion-item");
        newFolder.setAttribute("data-folder-id", folderId); // Set the folder ID dynamically

        newFolder.innerHTML = `
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#${folderId}" aria-expanded="true" aria-controls="${folderId}">
                    ${folderName}
                </button>
            </h2>
            <div id="${folderId}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="file-list"></div>
                    <label>Select File(s): </label>
                    <input type="file" class="authorize_document" name="folder_doc[${folderId}][]">
                </div>
            </div>
        `;

        // Append the folder to the accordion
        accordion.appendChild(newFolder);

        // Handle file selection within the folder
        const fileInput = newFolder.querySelector(".authorize_document");
        const fileList = newFolder.querySelector(".file-list");

        // Add event listener for file input
        fileInput.addEventListener("change", function () {
            if (this.files && this.files.length > 0) {
                Array.from(this.files).forEach((file) => {
                    const fileName = file.name;

                    // Display selected files in the file list
                    const fileLink = document.createElement("a");
                    fileLink.href = "javascript:void(0)";
                    fileLink.innerHTML = `
                        <i class="fa-solid fa-file-circle-plus"></i> ${fileName}
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeFile(this)">X</button>
                    `;
                    fileLink.classList.add("d-block", "mt-1");
                    fileList.appendChild(fileLink);

                    // Create hidden input for each file and store the file
                    const fileHiddenInput = document.createElement("input");
                    fileHiddenInput.type = "hidden";
                    fileHiddenInput.name = `folder_doc[${folderId}][]`; // Use folder ID dynamically
                    fileHiddenInput.value = fileName; // Store file name or any file data
                    newFolder.appendChild(fileHiddenInput);

                    // Create hidden input for folder name, indexed by folderId
                    const folderHiddenInput = document.createElement("input");
                    folderHiddenInput.type = "hidden";
                    folderHiddenInput.name = `folder_name[${folderId}]`; // Use folderId as key
                    folderHiddenInput.value = folderName; // Store the folder name
                    newFolder.appendChild(folderHiddenInput);
                });

                this.style.display = 'none'; // Hide the file input
                // Add a new file input to the folder for future selections
                const newFileInput = document.createElement("input");
                newFileInput.type = "file";
                newFileInput.classList.add("authorize_document");
                newFileInput.name = `folder_doc[${folderId}][]`;
                newFolder.querySelector('.accordion-body').appendChild(newFileInput);

                // Attach event listener to the new input
                newFileInput.addEventListener("change", function () {
                    handleFolderFileInputChange(newFileInput, fileList, folderId, folderName);
                });
            }
        });

        // Reset folder modal form
        addForm.reset();
        const modal = bootstrap.Modal.getInstance(document.getElementById("addFolderModal"));
        modal.hide();
    });
});

// Function to handle file input change, display files, and add new input
function handleFolderFileInputChange(fileInput, fileList, folderId, folderName) {
    if (fileInput.files && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach((file) => {
            const fileName = file.name;

            // Display selected files in the file list
            const fileLink = document.createElement("a");
            fileLink.href = "javascript:void(0)";
            fileLink.innerHTML = `
                <i class="fa-solid fa-file-circle-plus"></i> ${fileName}
                <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeFile(this)">X</button>
            `;
            fileLink.classList.add("d-block", "mt-1");
            fileList.appendChild(fileLink);

            // Create hidden input for each file and store the file
            const fileHiddenInput = document.createElement("input");
            fileHiddenInput.type = "hidden";
            fileHiddenInput.name = `folder_doc[${folderId}][]`; // Use folder ID dynamically
            fileHiddenInput.value = fileName; // Store file name or any file data
            fileList.closest('.accordion-item').appendChild(fileHiddenInput);

            // Create hidden input for folder name, indexed by folderId
            const folderHiddenInput = document.createElement("input");
            folderHiddenInput.type = "hidden";
            folderHiddenInput.name = `folder_name[${folderId}]`; // Use folderId as key
            folderHiddenInput.value = folderName; // Store the folder name
            fileList.closest('.accordion-item').appendChild(folderHiddenInput);
        });

        // Hide the current file input and add a new one
        fileInput.style.display = 'none';
        const newFileInput = document.createElement("input");
        newFileInput.type = "file";
        newFileInput.classList.add("authorize_document");
        newFileInput.name = `folder_doc[${folderId}][]`;
        fileList.closest('.accordion-body').appendChild(newFileInput);

        // Attach event listener to the new input
        newFileInput.addEventListener("change", function () {
            handleFolderFileInputChange(newFileInput, fileList, folderId, folderName);
        });
    }
}

// Function to remove file link and hidden input
function removeFile(button) {

    const fileLink = button.closest('a');
    fileLink.remove();
    // Get the folderId and fileName from the file link
    const folderId = fileLink.closest('.accordion-item').getAttribute('data-folder-id');
    const fileName = fileLink.innerText.replace('X', '').trim();  // Get file name

    // Remove hidden input corresponding to this file
    const hiddenInputs = document.querySelectorAll(`input[name="folder_doc[${folderId}][]"]`);
    hiddenInputs.forEach((input) => {
        if (input.value === fileName) {
            console.log("file delete");
            input.remove();
        }
    });

    // Check if there are any remaining files for this folder
    const remainingFiles = document.querySelectorAll(`input[name="folder_doc[${folderId}][]"]`);
    if (remainingFiles.length === 0) {
        // If no files remain, remove the folder's name hidden input
        const folderNameInputs = document.querySelectorAll(`input[name="folder_name[${folderId}]"]`);
        folderNameInputs.forEach((input) => {
            console.log("folder delete");
            input.remove();
        });
    }
}

// end folder with file


$(document).ready(function () {
    $('.dropdown-button').on('click', function () {
        $('.dropdown-list').toggle();
    });

    $('.dropdown-list li').on('click', function () {
        var selectedText = $(this).text();
        $('.dropdown-button').text(selectedText);
        $('.dropdown-list').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown-list').hide();
        }
    });


    $( '#employee' ).select2( {
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $('#employee').data('placeholder'),
        // templateResult: formatGrid,
        closeOnSelect: false,
    } );


    //  start douments
    const addedFiles = new Set(); // Track files to prevent duplicates
    // Function to handle file input change
    function handleFileInputChange(event) {
        const fileInput = event.target;
        const fileList = document.getElementById('fileList');
        const file = fileInput.files[0];

        // Check if a file is selected and not already in the set
        if (file && !addedFiles.has(file.name)) {
            addedFiles.add(file.name);

            // Create a new list item
            const li = document.createElement('li');
            li.style.alignItems = 'center';
            li.style.marginBottom = '10px';

            // Create a link with the file name
            const link = document.createElement('a');
            link.href = 'javascript:void(0)';
            link.innerHTML = `<i class="fa-solid fa-file-circle-plus"></i> ${file.name}`;
            link.style.flexGrow = '1';

            // Create a remove button
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-sm btn-danger m-l-10';
            removeButton.textContent = 'X';

            // Add event listener to remove the item
            removeButton.addEventListener('click', () => {
                li.remove(); // Remove the list item
                addedFiles.delete(file.name); // Remove file from the set
                fileInput.remove(); // Remove the hidden input field
            });

            // Append the link and remove button to the list item
            li.appendChild(link);
            li.appendChild(removeButton);

            // Append the list item to the file list
            fileList.appendChild(li);
        }

        // Hide the current file input
        fileInput.style.display = 'none';

        // Add a new file input
        addNewFileInput();
    }

    // Function to create and append a new file input
    function addNewFileInput() {
        const fileInputsContainer = document.getElementById('fileInputsContainer');
        const newFileInput = document.createElement('input');
        newFileInput.type = 'file';
        newFileInput.name = 'documents[]';
        newFileInput.className = 'file-input';

        // Add change event listener
        newFileInput.addEventListener('change', handleFileInputChange);

        fileInputsContainer.appendChild(newFileInput);
    }

    // Add event listener to the initial file input
    document.querySelector('.file-input').addEventListener('change', handleFileInputChange);
    // end Documents

    $('#addForm').submit(function (event) {
        event.preventDefault();
        $('.error').html(""); // Clear previous errors

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const formData = new FormData($form[0]);

        $.ajax({
            type: "POST",
            url: createUrl,  // Assuming you have this URL set up on the backend
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $submitButton.prop('disabled', true);
            },
            success: function (result) {
                $submitButton.prop('disabled', false);
                if (result.status === true) {
                    $form[0].reset();
                    toastr.success(result.message);
                    $('#addModal').modal('hide');
                    if (result.isNew) {
                        window.location.href = listUrl;
                    } else {
                        window.location.reload();
                    }
                } else {
                    let first_input = "";
                    $('.error').html("");
                    $.each(result.error, function (key) {
                        if (first_input === "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    });
                    // $('#addForm').find("#" + first_input).focus();
                }
            },
            error: function (error) {
                $submitButton.prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });
});