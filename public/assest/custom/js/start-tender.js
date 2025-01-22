$(document).ready(function () {
    $('body').on('click', '.dropdown-list li', function () {
        var id = $(this).data('value'); // Get the id from the clicked <li>

        $.ajax({
            type: "POST",
            url: getDocumentsUrl,
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                // Handle Tender Documents
                if (response.tenderDocuments && response.tenderDocuments.length > 0) {
                    var tenderDocumentList = $('.tender-document-list');
                    tenderDocumentList.empty();
                    response.tenderDocuments.forEach(function (item) {
                        console.log(item);
                        var tenderDochtml = `
                            <div class="clickTo">
                            <input type="checkbox" class="keyword-checkbox" data-section="tender"
                                                                value="${item.id}"
                                                                data-doc-preview-url="${item.docx_preview_url}"
                                                                data-pdf-preview-url="${item.pdf_preview_url}">
                            <label for="file-${item.id}" class="keyword-label">${item.original_file_name}</label>
                            </div>
                        `;
                        tenderDocumentList.append(tenderDochtml);
                    });
                } else {
                    alert("No documents found for this tender.");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", error);
                alert("An error occurred while fetching the documents.");
            }
        });
    });

    $(document).on('change', '.keyword-checkbox', function () {
        var isChecked = $(this).is(':checked');
        var section = $(this).data('section');
        var id = $(this).val();

        // Fetch preview URLs (optional: you may store these URLs in data attributes to avoid multiple backend calls)
        var docPreviewUrl = $(this).data('doc-preview-url');
        var pdfPreviewUrl = $(this).data('pdf-preview-url');

        if (isChecked) {
            // Add previews to the respective sections
            if (docPreviewUrl) {
                var docHtml = `
                    <div class="secriesBox" id="doc-preview-${section}-${id}" draggable="true">
                        <div class="imgbox">
                            <input type="hidden" name="loadedDocx[]" value="${section}-${id}">
                            <canvas id="${section}-doc-preview-${id}"></canvas>
                        </div>
                        <div class="seriestext">
                            <p>${$(this).next('label').text()}</p>
                        </div>
                    </div>
                `;
                $('.all-doc-preview').append(docHtml);
                renderPDF(document.getElementById(`${section}-doc-preview-${id}`), docPreviewUrl);
            }

            if (pdfPreviewUrl) {
                var pdfHtml = `
                    <div class="secriesBox" id="pdf-preview-${section}-${id}" draggable="true">
                        <div class="imgbox">
                        <input type="hidden" name="loadedPdf[]" value="${section}-${id}">
                            <canvas id="${section}-pdf-preview-${id}"></canvas>
                        </div>
                        <div class="seriestext">
                            <p>${$(this).next('label').text()}</p>
                        </div>
                    </div>
                `;
                $('.all-pdf-preview').append(pdfHtml);
                renderPDF(document.getElementById(`${section}-pdf-preview-${id}`), pdfPreviewUrl);
            }
        } else {
            // Remove previews for the unchecked item
            $(`#doc-preview-${section}-${id}`).remove();
            $(`#pdf-preview-${section}-${id}`).remove();
        }
    });

    function renderPDF(canvas, url) {
        if (!canvas || !url) {
            console.error("Invalid canvas or PDF URL");
            return;
        }

        var context = canvas.getContext('2d');
        pdfjsLib.getDocument(url).promise.then(function (pdf) {
            pdf.getPage(1).then(function (page) {
                var viewport = page.getViewport({ scale: 0.2 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                page.render({
                    canvasContext: context,
                    viewport: viewport,
                });
            });
        });
    }

    let draggedElement = null;

    $(document).on('dragstart', '.secriesBox', function (event) {
        draggedElement = this;
        event.originalEvent.dataTransfer.setData('text/plain', this.id);
    });

    $('.all-doc-preview').on('dragover', function (event) {
        event.preventDefault(); // Necessary to allow drop
    });

    $('.all-doc-preview').on('drop', function (event) {
        event.preventDefault();
        const draggedId = event.originalEvent.dataTransfer.getData('text/plain');
        const draggedElement = document.getElementById(draggedId);

        const targetElement = $(event.target).closest('.secriesBox')[0];
        if (targetElement && targetElement !== draggedElement) {
            // Insert before or after the target element
            if (targetElement) {
                $(targetElement).before(draggedElement);
            }
        }
    });

    $('.all-pdf-preview').on('dragover', function (event) {
        event.preventDefault(); // Necessary to allow drop
    });

    $('.all-pdf-preview').on('drop', function (event) {
        event.preventDefault();
        const draggedId = event.originalEvent.dataTransfer.getData('text/plain');
        const draggedElement = document.getElementById(draggedId);

        const targetElement = $(event.target).closest('.secriesBox')[0];
        if (targetElement && targetElement !== draggedElement) {
            $(targetElement).before(draggedElement);
        }
    });


    // for docs merge, preview and download
    function handleFileAction(actionType, fileType) {
        const $form = $(`#${fileType}FileForm`);
        const $submitButton = $(`#${actionType}-${fileType}`);
        const dataString = new FormData($form[0]);
        dataString.append('action', actionType);

        $.ajax({
            type: "POST",
            url: fileType === 'docx' ? mergeDocxUrl : mergePdfUrl,
            data: dataString,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $submitButton.prop('disabled', true);
            },
            success: function (result) {
                $submitButton.prop('disabled', false);

                if (result.status) {
                    const fileUrl = result.file_url;
                    if (actionType === 'preview') {
                        const redirectUrl = `${fileType === 'docx' ? previewDocxUrl : previewPdfUrl}?file_url=${encodeURIComponent(fileUrl)}`;
                        window.open(redirectUrl, '_blank');
                    } else if (actionType === 'download') {
                        const a = document.createElement('a');
                        a.href = fileUrl;
                        a.download = result.file_name;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }
                } else {
                    alert(result.message || 'An error occurred.');
                }
            },
            error: function () {
                $submitButton.prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    }

    // Attach event listeners for docx and pdf actions
    $('#preview-doc, #download-doc').click(function (event) {
        event.preventDefault();
        const action = $(this).attr('id') === 'preview-doc' ? 'preview' : 'download';
        handleFileAction(action, 'docx');
    });

    $('#preview-pdf, #download-pdf').click(function (event) {
        event.preventDefault();
        const action = $(this).attr('id') === 'preview-pdf' ? 'preview' : 'download';
        handleFileAction(action, 'pdf');
    });


});