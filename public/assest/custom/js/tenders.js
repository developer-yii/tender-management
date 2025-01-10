// $(document).ready(function () {

//     var tenderTable = $('#tenderTable').DataTable({
//         processing: true,
//         serverSide: true,
//         pageLength: 25,
//         ajax: {
//             type: "GET",
//             url: employeeListUrl,
//         },
//         order: [0, 'desc'],
//         columns: [
//             { data: 'id', name: 'id'},
//             { data: 'email', name: 'email' },
//             { data: 'first_name', name: 'first_name' },
//             { data: 'last_name', name: 'last_name' },
//             { data: 'action', orderable: false, searchable: false, className: 'text-center' },
//         ],
//     });
// });

$(document).ready(function () {
    $( '#employee' ).select2( {
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $('#employee').data('placeholder'),
        // templateResult: formatGrid,
        closeOnSelect: false,
    } );

    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('fileList');

    // Keep track of added files to avoid duplicates
    const addedFiles = new Set();

    // Event listener for file input change
    fileInput.addEventListener('change', () => {
        // Loop through the selected files
        Array.from(fileInput.files).forEach(file => {
            // Check if the file has already been added
            if (!addedFiles.has(file.name)) {
                addedFiles.add(file.name);

                // Create a new list item
                const li = document.createElement('li');
                // li.style.display = 'flex';
                li.style.alignItems = 'center';
                li.style.marginBottom = '10px';

                // Create a link with the file name
                const link = document.createElement('a');
                link.href = 'javascript:void(0)';
                link.innerHTML = `<i class="fa-solid fa-file-circle-plus"></i> ${file.name}`;
                link.style.flexGrow = '1';

                // Create a remove button with Bootstrap classes
                const removeButton = document.createElement('button');
                removeButton.className = 'btn btn-sm btn-danger m-l-10';
                removeButton.textContent = 'X';

                // Add event listener to remove the item
                removeButton.addEventListener('click', () => {
                    // Remove the list item
                    li.remove();

                    // Remove the file from the addedFiles set
                    addedFiles.delete(file.name);
                });

                // Append the link and remove button to the list item
                li.appendChild(link);
                li.appendChild(removeButton);

                // Append the list item to the file list
                fileList.appendChild(li);
            }
        });

        // Clear the file input so the same files can be selected again if needed
        fileInput.value = '';
    });

});