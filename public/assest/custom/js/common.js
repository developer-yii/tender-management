$(document).ready(function () {
    $( '#tags' ).select2( {
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $('#tags').data('placeholder'),
        // templateResult: formatGrid,
        closeOnSelect: false,
    } );
});

function handleFormSubmission(formSelector, url, modalId, listUrl) {
    $(formSelector).submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const dataString = new FormData($form[0]);

        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            contentType: false,
            processData: false,
            cache: false,
            async: true,
            beforeSend: function () {
                $("#loaderOverlay").fadeIn();
            },
            success: function (result) {
                $("#loaderOverlay").fadeOut();
                if (result.status === true) {
                    $form[0].reset();
                    toastr.success(result.message);
                    $(`#${modalId}`).modal('hide');
                    if (result.isNew) {
                        window.location.href = listUrl;
                    } else {
                        window.location.reload();
                    }
                } else {
                    handleErrors(result.error, formSelector);
                }
            },
            error: function () {
                $("#loaderOverlay").fadeOut();
                alert('Something went wrong!', 'error');
            }
        });
    });
}

function handleErrors(errors, formSelector) {
    let firstInput = "";
    $('.error').html("");

    $.each(errors, function (key, messages) {
        if (!firstInput) firstInput = key;
        const $input = $(`#${key}`);

        if (key === 'start_date' || key === 'end_date') {
            if (errors.start_date && errors.end_date) {
                $('#start_date').closest('.dateSelect').find('.error').html(errors.start_date.join(', '));
            } else if (errors.end_date) {
                $('#end_date').closest('.dateSelect').find('.error').html(errors.end_date.join(', '));
            } else if (errors.start_date) {
                $('#start_date').closest('.dateSelect').find('.error').html(errors.start_date.join(', '));
            }
        } else {
            $input.closest('.form-group').find('.error').html(messages.join(', '));
        }
    });

    $(formSelector).find(`#${firstInput}`).focus();
}

document.getElementById('previewBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    var fileExtension = fileUrl.split('.').pop().toLowerCase();

    var previewBody = document.getElementById('previewBody');
    var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

    if (fileExtension === 'pdf') {
        previewBody.innerHTML = `<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />`;
    } else if (fileExtension === 'doc' || fileExtension === 'docx') {
        previewBody.innerHTML = "<p>No preview available for this file.</p>";
    } else {
        previewBody.innerHTML = "<p>No preview available for this file type.</p>";
    }
    previewModal.show();
});

document.getElementById('downloadPdf').addEventListener('click', function() {
    var downloadLink = document.getElementById('download-pdf');
    downloadLink.click(); // Trigger download action
});

document.getElementById('downloadDoc').addEventListener('click', function() {
    var downloadLink = document.getElementById('download-doc');
    downloadLink.click(); // Trigger download action
});
