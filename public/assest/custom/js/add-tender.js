
// start Folder with File
document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.getElementById("addFolder");
    const accordion = document.getElementById("accordionExample");
    const bindingPeriod = document.getElementById('binding_period');
    const applicantQuestionsDate = document.getElementById('applicant_questions_date');
    const expiryOfferDate = document.getElementById('expiry_offer_date');

    flatpickr("#execution_period", {
        mode: "range", // This makes it a date range picker
        dateFormat: "d-m-Y", // You can adjust the format
        locale: "de",
        firstDayOfWeek: 1 // Optional: Set the first day of the week to Monday

    });

    flatpickr(bindingPeriod, {
        dateFormat: "d-m-Y",
        locale: "de",
        allowInput: false, // Disable manual typing
    });

    flatpickr(applicantQuestionsDate, {
        enableTime: true, // Enable time selection
        dateFormat: "d-m-Y H:i", // Set the date and time format
        time_24hr: true, // Use 24-hour time format
        locale: "de",
        allowInput: false, // Disable manual typing
    });

    flatpickr(expiryOfferDate, {
        enableTime: true, // Enable time selection
        dateFormat: "d-m-Y H:i", // Set the date and time format
        time_24hr: true, // Use 24-hour time format
        locale: "de",
        allowInput: false, // Disable manual typing
    });

    // Handle folder form submission
    addForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const folderName = document.getElementById("folder_name").value.trim();
        if (folderName === "") {
            alert("Ordnername darf nicht leer sein!");
            return;
        }

        const existingFolders = accordion.querySelectorAll(".accordion-item");
        for (let folder of existingFolders) {
            const folderTitle = folder.querySelector(".accordion-button").childNodes[0].textContent.trim(); // Get only the folder name

            if (folderTitle === folderName) {
                alert("Ordnername existiert bereits!");
                return;
            }
        }

        // Generate unique ID for the folder
        const folderId = "folder" + Math.random().toString(36).substr(2, 9);

        // Create the folder section dynamically
        const newFolder = document.createElement("div");
        newFolder.classList.add("accordion-item");
        newFolder.setAttribute("data-folder-id", folderId);

        newFolder.innerHTML = `
        <h2 class="accordion-header">
          <button class="accordion-button cursor-default" type="button">
            ${folderName}
            <a class="btn btn-sm btn-danger ms-2 remove-btn" onclick="removeFolder(this)">X</a>
          </button>
        </h2>
        <div id="${folderId}">
          <div class="accordion-body">
            <div class="file-list"></div>
            <input type="file" class="authorize_document custom-file-input" name="folder_doc[${folderId}][]">
          </div>
        </div>
      `;

        // Append the folder to the accordion
        accordion.appendChild(newFolder);

        const fileInput = newFolder.querySelector(".authorize_document");
        const fileList = newFolder.querySelector(".file-list");

        // Add event listener for file input
        fileInput.addEventListener("change", function () {
            handleFolderFileInputChange(fileInput, fileList, folderId, folderName);
        });

        // Reset the form and hide the modal
        addForm.reset();
        const modal = bootstrap.Modal.getInstance(
            document.getElementById("addFolderModal")
        );
        modal.hide();
    });
});

$('#addFolderModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addFolder')[0].reset();
});

// Handle file input change
function handleFolderFileInputChange(fileInput, fileList, folderId, folderName) {
    if (fileInput.files && fileInput.files.length > 0) {
        Array.from(fileInput.files).forEach((file) => {
            const fileName = file.name;

            // Check if the file already exists in the list
            const existingFile = Array.from(fileList.querySelectorAll("a")).find(
                (link) => link.innerText.trim().includes(fileName)
            );
            if (existingFile) {
                alert("Diese Datei wurde bereits hinzugefügt!");
                return;
            }

            // Create a new file link and add it to the end of the file list
            const fileLink = document.createElement("a");
            fileLink.href = "javascript:void(0)";
            fileLink.innerHTML = `
                <i class="fa-solid fa-file-circle-plus"></i> ${fileName}
                <button type="button" class="btn btn-sm btn-danger ms-2 remove-btn" onclick="removeFile(this)">X</button>
            `;
            fileLink.classList.add("d-block", "mt-1", "a-txt", 'cursor-default');

            // To ensure appending at the end, we move any additional logic that could interfere with the order
            fileList.appendChild(fileLink);

            // Add hidden input for the uploaded file
            const fileHiddenInput = document.createElement("input");
            fileHiddenInput.type = "hidden";
            fileHiddenInput.name = `folder_doc[${folderId}][]`;
            fileHiddenInput.value = fileName;
            fileList.closest(".accordion-item").appendChild(fileHiddenInput);

            // Create hidden input for folder name, indexed by folderId
            const folderHiddenInput = document.createElement("input");
            folderHiddenInput.type = "hidden";
            folderHiddenInput.name = `folder_name[${folderId}]`; // Use folderId as key
            folderHiddenInput.value = folderName; // Store the folder name
            fileList.closest('.accordion-item').appendChild(folderHiddenInput);
        });

        // Hide the current file input
        fileInput.style.display = "none";

        // Create a new file input for future uploads
        const newFileInput = document.createElement("input");
        newFileInput.type = "file";
        newFileInput.classList.add("authorize_document", "mt-1", "custom-file-input");
        newFileInput.name = `folder_doc[${folderId}][]`;

        // Add the new file input after the file list
        fileList.closest(".accordion-body").appendChild(newFileInput);

        // Attach event listener to the new file input
        newFileInput.addEventListener("change", function () {
            handleFolderFileInputChange(newFileInput, fileList, folderId, folderName);
        });

        // Show the new input if hidden
        newFileInput.style.display = "block";
    }
}

// Function to attach event listeners after page load
document.addEventListener("DOMContentLoaded", function () {
    const folderItems = document.querySelectorAll(".accordion-item");

    folderItems.forEach(folder => {
        const folderId = folder.getAttribute("data-folder-id");
        const fileInput = folder.querySelector(".authorize_document");
        const fileList = folder.querySelector(".accordion-body");
        const folderName = folder.querySelector(".accordion-button").firstChild.textContent.trim();

        // Add event listener to file input for adding new files
        fileInput.addEventListener("change", function () {
            handleFolderFileInputChange(fileInput, fileList, folderId, folderName);
        });
    });
});

// Function to remove file and corresponding hidden input
function removeFile(button) {
    // Find the file link element
    const fileLink = button.closest("a");

    // Find the accordion item (parent folder) containing this file
    const accordionItem = fileLink.closest(".accordion-item");

    // Extract the file name from the link text
    const fileName = fileLink.querySelector("i").nextSibling.nodeValue.trim();

    const hiddenInput = Array.from(accordionItem.querySelectorAll("input[type='hidden']"))
        .find(input => input.value === fileName);

    // Remove the hidden input if found
    if (hiddenInput) {
        hiddenInput.remove();
    }

    const fileInputs = accordionItem.querySelectorAll("input[type='file']");

    // If a file input exists, remove it
    Array.from(fileInputs).forEach(input => {
        // Extract the file name from the input's value (after the fake path)
        const fileInputName = input.value.split('\\').pop().split('/').pop();  // Extracts the file name

        // Compare with the actual file name
        if (fileInputName === fileName) {
            input.remove();  // Remove the file input if it matches
        }
    });
    // Remove the file link from the list
    fileLink.remove();
}

// Function to remove folder and all its related files
function removeFolder(button) {
    // Find the accordion item (folder) to be removed
    const accordionItem = button.closest(".accordion-item");

    // Find all file links inside this folder and remove them
    const fileLinks = accordionItem.querySelectorAll(".file-list a");
    fileLinks.forEach(link => link.remove());

    // Find all hidden inputs inside this folder and remove them
    const hiddenInputs = accordionItem.querySelectorAll("input[type='hidden']");
    hiddenInputs.forEach(input => input.remove());

    // Find all file inputs inside this folder and remove them
    const fileInputs = accordionItem.querySelectorAll("input[type='file']");
    fileInputs.forEach(input => input.remove());

    // Remove the folder itself (accordion item)
    accordionItem.remove();
}
// end folder with file

//start edit
function removeOldFile(event, button) {
    // Prevent form submission when clicking the remove button
    event.preventDefault();

    // Find the <li> element that contains the file and remove it
    const fileItem = button.closest('li');

    if (!fileItem) return; // If there's no <li> element, exit the function

    // Get the file name from the <a> tag (within the <li>)
    const fileLink = fileItem.querySelector('a');
    const fileName = fileLink ? fileLink.textContent.trim() : ''; // Get the file name from the link text

    // Find the hidden input element associated with the file
    const hiddenInput = Array.from(document.querySelectorAll("input[type='hidden']"))
        .find(input => input.value === fileName); // Match the value of the hidden input

    // If the hidden input is found, remove it
    if (hiddenInput) {
        hiddenInput.remove();
    }

    // Now, remove the entire <li> containing the file and the remove button
    fileItem.remove();
}
// end edit


$(document).ready(function () {
    $( '#employees' ).select2( {
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $('#employees').data('placeholder'),
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
        if (file) {

            const oldFileList = document.querySelectorAll('#oldFileList input[name="old_documents[]"]');
            for (let oldFile of oldFileList) {
                if (oldFile.value === file.name) {
                    alert("Diese Datei wurde bereits hinzugefügt!");
                    fileInput.value = '';
                    return;
                }
            }

            if (addedFiles.has(file.name)) {
                alert("Diese Datei wurde bereits hinzugefügt!");
                fileInput.value = '';
                return;
            }

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
            link.classList.add("a-txt");

            // Create a remove button
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-sm btn-danger ms-2 remove-btn';
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
        newFileInput.classList.add("file-input", "custom-file-input");

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
        const formData = new FormData($form[0]);

        $.ajax({
            type: "POST",
            url: createUrl,  // Assuming you have this URL set up on the backend
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loaderOverlay").fadeIn();
            },
            success: function (result) {
                $("#loaderOverlay").fadeOut();
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
                    let processedFields = {};

                    $.each(result.error, function (key, errorMessages) {
                        if (first_input === "") first_input = key;

                        // Handle general fields (only show the first error message for each field)
                        if (!processedFields[key]) {
                            let errorMessage = errorMessages[0]; // Take the first error message for the field
                            $('#' + key).closest('.form-group').find('.error').html(errorMessage);
                            processedFields[key] = true;
                        }

                        // Handle "documents" field
                        if (key.startsWith("documents") && !processedFields["documents"]) {
                            let errorMessage = errorMessages[0]; // Take the first error message for the field
                            $('#documents').closest('.form-group').find('.error').html(errorMessage);
                            processedFields["documents"] = true;
                        }

                        // Handle "folder_doc" field
                        if (key.startsWith("folder_doc") && !processedFields["folder_doc"]) {
                            let errorMessage = errorMessages[0];
                            $('.accordion-item').closest('.form-group').find('.error').html(errorMessage);
                            processedFields["folder_doc"] = true;
                        }
                    });
                }
            },
            error: function (error) {
                $("#loaderOverlay").fadeOut();
                alert('Etwas ist schiefgelaufen!', 'error');
            }
        });
    });
});

clearErrorOnInput('#addForm');