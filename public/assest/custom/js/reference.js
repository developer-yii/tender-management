handleFormSubmission('#addreference', createReferenceUrl, 'addReferenceModal', referenceListUrl);

$('#addReferenceModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addreference')[0].reset();
    $('#reference_id').val('');
    $('#tags').val('').trigger('change');
    $('#oldFileWord').html('');
    $('#oldFilePdf').html('');
    initializeFlatpickr();
    $('#addReferenceModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addReferenceModal').find('#exampleModalLabel').html("Referenz hinzufügen");
    $('#addReferenceModal').find('#file-word-label').html("Datei(Wort)*");
    $('#addReferenceModal').find('#file-pdf-label').html("Datei(PDF)*");
});

$('body').on('click', '.edit-reference', function () {
    var id = $(this).data('id');
    $(".reference_id").val(id);
    $.ajax({
        type: "POST",
        url: getReferenceUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            $('#project_title').val(data.project_title);
            $('#scope').val(data.scope);
            $('#start_date').val(data.start_date);
            $('#end_date').val(data.end_date);

            let selectedTags = data.tags.map(tag => tag.id); // Extract tag IDs from the data
            $('#tags').val(selectedTags).trigger('change');

            $('#oldFileWord').html(`<a href="${data.file_word_url}" target="_blank" class="btn btn-info">Word-Datei anzeigen</a>`);
            $('#oldFilePdf').html(`<a href="${data.file_pdf_url}" target="_blank" class="btn btn-info">PDF-Datei anzeigen</a>`);

            $('#addReferenceModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addReferenceModal').find('#exampleModalLabel').html("Referenz bearbeiten");
            $('#addReferenceModal').find('#file-word-label').html("Datei(Wort)");
            $('#addReferenceModal').find('#file-pdf-label').html("Datei(PDF)");
            initializeFlatpickr();
        }
    });
});