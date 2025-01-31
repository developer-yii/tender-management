handleFormSubmission('#addcertificate', createCertificateUrl, 'addCertificateModal', certificateListUrl);

$('body').on('click', '.edit-certificate', function () {
    var id = $(this).data('id');
    $(".certificate_id").val(id);
    $.ajax({
        type: "POST",
        url: getCertificateUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            $('#category').val(data.category_name);
            $('#title').val(data.title);
            $('#description').val(data.description);
            $('#start_date').val(data.valid_from_date);
            $('#end_date').val(data.valid_to_date);

            $('#oldLogo').html(`<img src="${data.logo_url}" alt="Logo" class="img-thumbnail" style="width:70px;">`);
            $('#oldCertificatePdf').html(`<a href="${data.certificate_pdf_url}" target="_blank" class="btn btn-info">View PDF</a>`);
            $('#oldCertificateWord').html(`<a href="${data.certificate_word_url}" target="_blank" class="btn btn-info">View Word</a>`);

            $('#addCertificateModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addCertificateModal').find('#exampleModalLabel').html("Zertifizierung bearbeiten");
            $('#addCertificateModal').find('#logo-label').html("Logo");
            $('#addCertificateModal').find('#certificate-word-label').html("Zertifikat(Wort)");
            $('#addCertificateModal').find('#certificate-pdf-label').html("Zertifikat(PDF)");
            initializeFlatpickr();
        }
    });
});

$('#addCertificateModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addcertificate')[0].reset();
    $('#certificate_id').val('');
    $('#oldLogo').html('');
    $('#oldCertificatePdf').html('');
    $('#oldCertificateWord').html('');
    initializeFlatpickr();
    $('#addCertificateModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addCertificateModal').find('#exampleModalLabel').html("Zertifizierung hinzufügen");
    $('#addCertificateModal').find('#logo-label').html("Logo*");
    $('#addCertificateModal').find('#certificate-word-label').html("Zertifikat(Wort)*");
    $('#addCertificateModal').find('#certificate-pdf-label').html("Zertifikat(PDF)*");
});

