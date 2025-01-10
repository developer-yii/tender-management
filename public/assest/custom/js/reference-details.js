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

            $('#oldFileWord').html(`<a href="${data.file_word_url}" target="_blank" class="btn btn-primary">View File</a>`);
            $('#oldFilePdf').html(`<a href="${data.file_pdf_url}" target="_blank" class="btn btn-primary">View File</a>`);

            $('#addReferenceModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addReferenceModal').find('#exampleModalLabel').html("Referenz bearbeiten");
            $('#addReferenceModal').find('#file-word-label').html("File(Word)");
            $('#addReferenceModal').find('#file-pdf-label').html("File(PDF)");
        }
    });
});