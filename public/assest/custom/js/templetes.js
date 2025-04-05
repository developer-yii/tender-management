document.addEventListener("DOMContentLoaded", function () {
    const pdfCanvases = document.querySelectorAll("canvas[data-url]");
    pdfCanvases.forEach(canvas => {
        const url = canvas.getAttribute("data-url");
        fetch(url)
            .then(response => response.arrayBuffer())
            .then(data => {
                const loadingTask = pdfjsLib.getDocument({ data: data });
                loadingTask.promise.then(pdf => {
                    pdf.getPage(1).then(page => {
                        const context = canvas.getContext('2d');

                        const viewport = page.getViewport({ scale: 0.5 });
                        canvas.height = viewport.height / 2;
                        canvas.width = viewport.width;

                        const halfViewport = page.getViewport({
                            scale: 0.5,
                            offsetY: 0
                        });

                        page.render({
                            canvasContext: context,
                            viewport: halfViewport
                        });
                    });
                }).catch(err => {
                    console.error("Error loading PDF:", err);
                });
            })
            .catch(err => {
                console.error("Error fetching PDF:", err);
            });
    });
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('download-btn')) {
        const fileUrl = event.target.getAttribute('data-url');
        if (fileUrl) {
            const tempLink = document.createElement('a');
            tempLink.href = fileUrl;
            tempLink.download = ''; // Use the filename from the URL or customize it
            document.body.appendChild(tempLink);
            tempLink.click();
            document.body.removeChild(tempLink);
        } else {
            console.error('Download URL not found!');
        }
    }
});

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
            $('#addModal').find('#exampleModalLabel').html("Vorlage bearbeiten");
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
        content = `<a href="${filePath}" target="_blank" class="btn btn-info">Siehe Datei</a>`;
    } else {
        content = `<a href="${filePath}" target="_blank" class="btn btn-info" style="cursor: pointer;">Siehe Datei</a>`;
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