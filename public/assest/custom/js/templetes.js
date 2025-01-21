handleFormSubmission('#addForm', createUrl, 'addModal', listUrl);

$('body').on('click', '.edit-templete', function () {
    var id = $(this).data('id');
    $(".edit_id").val(id);
    $.ajax({
        type: "POST",
        url: getUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {

            $('#title').val(data.title);
            generateFilePreview(data.templete_file_url, '#addModal #oldFile');

            $('#addModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addModal').find('#exampleModalLabel').html("Zertifizierung bearbeiten");
            $('#addModal').find('#logo-label').html("Logo");
            $('#addModal').find('#certificate-word-label').html("Certificate(Word)");
            $('#addModal').find('#certificate-pdf-label').html("Certificate(PDF)");

        }
    });
});

function generateFilePreview(filePath, container) {
    const fileExtension = filePath.split('.').pop().toLowerCase();
    let content = '';

    if (['jpg', 'jpeg', 'png', 'gif', 'jfif'].includes(fileExtension)) {
        content = `<img src="${filePath}" alt="Image" style="width: 100px; height: auto; cursor: pointer;" onclick="window.open('${filePath}', '_blank');">`;
    } else if (fileExtension === 'pdf' || fileExtension === 'docx' || fileExtension === 'doc') {
        content = `<a href="${filePath}" target="_blank" class="btn btn-primary">View File</a>`;
    } else {
        content = `<a href="${filePath}" target="_blank" style="cursor: pointer;">View File</a>`;
    }

    $(container).empty().html(content);
}

$('#addModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addForm')[0].reset();
    $('#edit_id').val('');
    $('#oldFile').html('');
    $('#addModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addModal').find('#exampleModalLabel').html("Vorlage hinzufügen");
    $('#addModal').find('#file-label').html("File*");
});