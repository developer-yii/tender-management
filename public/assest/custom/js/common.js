$(document).ready(function () {
    $( '#tags' ).select2( {
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $('#tags').data('placeholder'),
        // templateResult: formatGrid,
        closeOnSelect: false,
    } );
});

function initializeFlatpickr() {

    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const errorMessage = document.getElementById('error_message');

    if (!startDateInput || !endDateInput) {
        return;
    }else{

        function updateErrorMessage(message) {
            if (errorMessage) {
                errorMessage.textContent = message || '';
            }
        }

        let initialStartDate = startDateInput.value ? startDateInput.value : null;
        let initialEndDate = endDateInput.value ? endDateInput.value : null;

        // Convert values to Date objects for comparison
        let parsedStartDate = initialStartDate ? flatpickr.parseDate(initialStartDate, "d-m-Y") : null;
        let parsedEndDate = initialEndDate ? flatpickr.parseDate(initialEndDate, "d-m-Y") : null;

        const startDatePicker = flatpickr(startDateInput, {
            dateFormat: "d-m-Y",
            locale: "de",
            allowInput: false, // Disable manual typing
            defaultDate: initialStartDate, // Set existing date
            maxDate: parsedEndDate || null, // Ensure it's before end date if available
            onChange: function (selectedDates) {
                const startDate = selectedDates[0];
                const endDate = endDatePicker.selectedDates[0];
                if (startDate) {
                    endDatePicker.set('minDate', startDate); // Set minDate for end date
                    endDatePicker.set('disable', []); // Clear any disabled dates
                }

                // Validate date order
                if (endDate && startDate && (endDate < startDate)) {
                    updateErrorMessage('Startdatum darf nicht nach dem Enddatum liegen.');
                    startDatePicker.setDate(null);
                } else {
                    updateErrorMessage();
                }
            }
        });

        // Initialize flatpickr for end date
        const endDatePicker = flatpickr(endDateInput, {
            dateFormat: "d-m-Y",
            locale: "de",
            allowInput: false, // Disable manual typing
            defaultDate: initialEndDate, // Set existing date
            minDate: parsedStartDate || null, // Ensure it's after start date if available
            disable: [], // No disabled dates initially
            onChange: function (selectedDates) {
                const startDate = startDatePicker.selectedDates[0];
                const endDate = selectedDates[0];

                if (endDate) {
                    startDatePicker.set('maxDate', endDate); // Set maxDate for start date
                    startDatePicker.set('disable', []); // Clear any disabled dates
                }

                // Validate date order
                if (endDate && startDate && endDate < startDate) {
                    updateErrorMessage('Enddatum darf nicht vor dem Startdatum liegen.');
                    endDatePicker.setDate(null);
                } else {
                    updateErrorMessage();
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    initializeFlatpickr(); // Ensure flatpickr is initialized when the page is ready
});


function handleFormSubmission(formSelector, url, modalId, listUrl) {
    $(formSelector).submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
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
                alert('Etwas ist schiefgelaufen!', 'error');
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

document.addEventListener('DOMContentLoaded', function () {
    var previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior
            var fileExtension = fileUrl.split('.').pop().toLowerCase();
            var previewBody = document.getElementById('previewBody');
            var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

            // Render content based on file type
            if (fileExtension === 'pdf') {
                previewBody.innerHTML = `<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />`;
            } else if (fileExtension === 'doc' || fileExtension === 'docx') {
                previewBody.innerHTML = "<p>Keine Vorschau für diese Datei verfügbar.</p>";
            } else if (['jpg', 'jpeg', 'png', 'gif', 'jfif'].includes(fileExtension)) {
                previewBody.innerHTML = `<img src="${fileUrl}" alt="Image" style="width: 100%; height: auto; cursor: pointer;">`;
            } else {
                previewBody.innerHTML = "<p>Keine Vorschau für diesen Dateityp verfügbar.</p>";
            }

            // Show the modal
            previewModal.show();
        });
    } else {
        return;
    }

    // Handle download PDF button
    var downloadPdfBtn = document.getElementById('downloadPdf');
    if (downloadPdfBtn) {
        downloadPdfBtn.addEventListener('click', function () {
            var downloadLink = document.getElementById('download-pdf');
            if (downloadLink) {
                downloadLink.click(); // Trigger download action
            } else {
                console.error("Download-Link nicht gefunden.");
            }
        });
    } else {
        return;
    }

    // Handle download PDF button
    var downloadDocBtn = document.getElementById('downloadDoc');
    if (downloadDocBtn) {
        downloadDocBtn.addEventListener('click', function () {
            var downloadLink = document.getElementById('download-doc');
            if (downloadLink) {
                downloadLink.click(); // Trigger download action
            } else {
                console.error("Download-Link nicht gefunden.");
            }
        });
    } else {
        return;
    }
});