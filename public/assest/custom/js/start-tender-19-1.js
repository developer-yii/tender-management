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
                        var tenderDochtml = `
                            <div class="clickTo">
                                <input type="checkbox" class="keyword-checkbox" id="file-${item.id}" value="${item.id}">
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

    $(document).on('change', '.keyword-checkbox', function() {
        var selectedData = {
            team: [],
            certificates: [],
            references: [],
            documents: []
        };

        // Collect selected data from each section
        $('.keyword-checkbox[data-section="team"]:checked').each(function() {
            selectedData.team.push($(this).val());
        });

        $('.keyword-checkbox[data-section="certificate"]:checked').each(function() {
            selectedData.certificates.push($(this).val());
        });

        $('.keyword-checkbox[data-section="reference"]:checked').each(function() {
            selectedData.references.push($(this).val());
        });

        $('.keyword-checkbox[data-section="document"]:checked').each(function() {
            selectedData.documents.push($(this).val());
        });

        $.ajax({
            url: previewUrl,  // Use the correct URL here
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                selectedData: selectedData
            },
            success: function(response) {
                updatePreview(response);
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            }
        });
    });

    function updatePreview(response) {
        $('.all-doc-preview').html(response.docPreview);
        $('.all-pdf-preview').html(response.pdfPreview);

        // Initialize PDF.js for each canvas element
        response.teamMembers.forEach(function(member) {
            var docCanvas = document.getElementById('team-doc-preview-' + member.id);
            var pdfCanvas = document.getElementById('team-pdf-preview-' + member.id);

            if (docCanvas) {
                renderPDF(docCanvas, member.doc_preview_url);
            }

            if (pdfCanvas) {
                renderPDF(pdfCanvas, member.pdf_preview_url);
            }
        });

        response.certificates.forEach(function(certificate) {
            var docCanvas = document.getElementById('certificate-doc-preview-' + certificate.id);
            var pdfCanvas = document.getElementById('certificate-pdf-preview-' + certificate.id);

            if (docCanvas) {
                renderPDF(docCanvas, certificate.doc_preview_url);
            }

            if (pdfCanvas) {
                renderPDF(pdfCanvas, certificate.pdf_preview_url);
            }
        });

        response.references.forEach(function(reference) {
            var docCanvas = document.getElementById('reference-doc-preview-' + reference.id);
            var pdfCanvas = document.getElementById('reference-pdf-preview-' + reference.id);

            if (docCanvas) {
                renderPDF(docCanvas, reference.doc_preview_url);
            }

            if (pdfCanvas) {
                renderPDF(pdfCanvas, reference.pdf_preview_url);
            }
        });

        response.documents.forEach(function(documentData) {
            var pdfCanvas = document.getElementById('document-pdf-preview-' + documentData.id);

            if (pdfCanvas) {
                renderPDF(pdfCanvas, documentData.pdf_preview_url);
            }
        });
    }

    function renderPDF(canvas, url) {
        if (!canvas || !url) {
            console.error("Invalid canvas or PDF URL");
            return;
        }

        var context = canvas.getContext('2d');

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            // Render the first page
            pdf.getPage(1).then(function(page) {
                var viewport = page.getViewport({ scale: 0.2 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // Render the page to the canvas
                var renderTask = page.render({
                    canvasContext: context,
                    viewport: viewport
                });

                // Ensure that the render task is completed before another render can begin
                renderTask.promise.then(function() {
                    console.log("Render completed for canvas: " + canvas.id);
                }).catch(function(error) {
                    console.error("Error during rendering: ", error);
                });
            }).catch(function(error) {
                console.error("Error loading PDF page: ", error);
            });
        }).catch(function(error) {
            console.error("Error loading PDF document: ", error);
        });
    }

    $('.dropdown-button').on('click', function () {
        $('.dropdown-list').toggle();
    });

    $('.dropdown-list li').on('click', function () {
        var selectedText = $(this).text();
        $('.dropdown-button').text(selectedText);
        $('.dropdown-list').hide();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown-list').hide();
        }
    });
});