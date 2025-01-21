$(document).ready(function () {
    $('#addfield').click(function () {
        // Increment the counter for a new row
        addExtraFieldSection();
    });

    // Handle the remove button click
    $(document).on('click', '.remove-field', function () {
        const fieldId = $(this).data('id');
        $(`#${fieldId}`).remove(); // Remove the extra field
    });
});

let fieldCounter = 0;
function addExtraFieldSection(paramName = null, paramValue = null) {
    fieldCounter++; // Increment field counter for unique IDs

    // HTML structure for the new extra field
    const newField = `
        <div class="row mb-2 field-section" id="field_${fieldCounter}">
            <div class="col-md-5 form-group">
                <input type="text" name="param_name[]" class="form-control" placeholder="Parameter Name" value="${paramName || ''}">
                <span class="error text-danger"></span>
            </div>
            <div class="col-md-5 form-group">
                <input type="text" name="param_value[]" class="form-control" placeholder="Parameter Value" value="${paramValue || ''}">
                <span class="error text-danger"></span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-field" data-id="field_${fieldCounter}">Remove</button>
            </div>
        </div>
    `;

    // Append the new field to the container
    $('#extraFieldContainer').append(newField);
}

$('#adddocument').submit(function (event) {
    event.preventDefault();
    $('.error').html("");

    const $form = $(this);
    const $submitButton = $form.find('button[type="submit"]');
    const dataString = new FormData($form[0]);

    $.ajax({
        type: "POST",
        url: createUrl,
        data: dataString,
        contentType: false,
        processData: false,
        cache: false,
        async: false,
        beforeSend: function () {
            $submitButton.prop('disabled', true);
        },
        success: function (result) {
            $submitButton.prop('disabled', false);
            if (result.status == true) {
                $form[0].reset();
                toastr.success(result.message);
                $('#addDocumentModal').modal('hide');
                if(result.isNew){
                    window.location.href = listUrl;
                }else{
                    window.location.reload();
                }
            } else {
                first_input = "";
                $('.error').html("");
                $.each(result.error, function (key, value) {
                    // if (first_input == "") first_input = key;

                    if (key.startsWith('param_name.') || key.startsWith('param_value.')) {
                        // Extract the index from the key (e.g., param_name.0 -> 0)
                        const index = parseInt(key.split('.').pop());
                        const field = key.split('.')[0]; // param_name or param_value

                        // Locate the specific row by ID (e.g., field_1, field_2)
                        const fieldId = `field_${index + 1}`;

                        // Display the error in the appropriate input field's error span
                        $(`#${fieldId}`)
                            .find(`[name="${field}[]"]`)
                            .closest('.form-group')
                            .find('.error')
                            .html(value[0]);
                    }
                    else{
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    }

                });
                $('#adddocument').find("#" + first_input).focus();
            }
        },
        error: function (error) {
            $submitButton.prop('disabled', false);
            alert('Something went wrong!', 'error');
        }
    });
});

// handleFormSubmission('#adddocument', createDocumentUrl, 'addDocumentModal', documentListUrl);

$('#addDocumentModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#adddocument')[0].reset();
    $('#document_id').val('');
    $('#extraFieldContainer').empty();
    $('#oldDocumentPdf').html('');
    $('#addDocumentModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addDocumentModal').find('#exampleModalLabel').html("Unterlagen / Bestätigungen hinzufügen");
    $('#addDocumentModal').find('#document-pdf-label').html("Unterlagen / Bestätigungen(PDF)*");
});

$('body').on('click', '.edit-document', function () {
    var id = $(this).data('id');
    $(".document_id").val(id);
    $.ajax({
        type: "POST",
        url: getUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            $('#category').val(data.category_name);
            $('#title').val(data.title);
            $('#oldDocumentPdf').html(`<a href="${data.document_pdf_url}" target="_blank" class="btn btn-primary">View Document</a>`);

            var storedParameters = data.parameters;
            storedParameters.forEach(function (parameter) {
                var paramName = parameter.param_name;
                var paramValue = parameter.param_value;
                addExtraFieldSection(paramName, paramValue);
            });

            $('#addDocumentModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addDocumentModal').find('#exampleModalLabel').html("Unterlagen / Bestätigungen bearbeiten");
            $('#addDocumentModal').find('#document-pdf-label').html("Unterlagen / Bestätigungen(PDF)");

        }
    });
});

