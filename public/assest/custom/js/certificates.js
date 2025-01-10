handleFormSubmission('#addcertificate', createCertificateUrl, 'addCertificateModal', certificateListUrl);

$('#addCertificateModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addcertificate')[0].reset();
    $('#certificate_id').val('');
    $('#oldLogo').html('');
    $('#oldCertificatePdf').html('');
    $('#oldCertificateWord').html('');
    $('#addCertificateModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addCertificateModal').find('#exampleModalLabel').html("Zertifizierung hinzufügen");
    $('#addCertificateModal').find('#logo-label').html("Logo*");
    $('#addCertificateModal').find('#certificate-word-label').html("Certificate(Word)*");
    $('#addCertificateModal').find('#certificate-pdf-label').html("Certificate(PDF)*");
});