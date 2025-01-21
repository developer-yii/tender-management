@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')

@section('content')

{{-- <section class="mainSection">
    <div class="homeSectionPart">
        <div class="selectTenerSec">
            <div class="selectTender">
                <div class="allSeriesBox">
                    @foreach($documents as $document)
                    <div class="secriesBox">
                        <label>{{$document->certificate_file}}</label>
                        <div class="seriestext">
                            <div id="preview-{{$document->id}}" class="docx-preview" style="height: 200px; width: 150px; overflow: hidden; border: 1px solid #ccc;"></div>
                            <p>{{$document->title}}</p>
                        </div>
                    </div>
                    <script>
                        fetch("http://tender-management.test/storage/certificates/certificate/{{$document->certificate_file}}")
                        .then(response => response.arrayBuffer())
                        .then(data => {
                            mammoth.convertToHtml({arrayBuffer: data})
                                .then(result => {
                                    // Limit the content to simulate a 1-page preview
                                    const previewContent = result.value;
                                    // Extract only the first page content, trimming content to about 200 words or a similar limit
                                    const truncatedContent = previewContent.substring(0, previewContent.indexOf('</p>', 200)) + '</p>';

                                    document.getElementById("preview-{{$document->id}}").innerHTML = truncatedContent;
                                })
                                .catch(err => console.error("Error converting DOCX:", err));
                        })
                        .catch(err => console.error("Error fetching DOCX:", err));
                    </script>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> --}}

{{-- <section class="mainSection">
    <div class="homeSectionPart">
        <div class="selectTenerSec">
            <div class="selectTender">
                <div class="allSeriesBox" id="documents-container">
                    @foreach($documents as $document)
                    <div class="secriesBox" id="document-{{$document->id}}" draggable="true" ondragstart="dragStart(event)">
                        <label>{{$document->certificate_file}}</label>
                        <div class="seriestext">
                            <div id="preview-{{$document->id}}" class="docx-preview" style="height: 200px; width: 150px; overflow: hidden; border: 1px solid #ccc;"></div>
                            <p>{{$document->title}}</p>
                        </div>
                    </div>
                    <script>
                        fetch("http://tender-management.test/storage/certificates/certificate/{{$document->certificate_file}}")
                        .then(response => response.arrayBuffer())
                        .then(data => {
                            mammoth.convertToHtml({arrayBuffer: data})
                                .then(result => {
                                    // Limit the content to simulate a 1-page preview
                                    const previewContent = result.value;
                                    // Extract only the first page content, trimming content to about 200 words or a similar limit
                                    const truncatedContent = previewContent.substring(0, previewContent.indexOf('</p>', 200)) + '</p>';

                                    document.getElementById("preview-{{$document->id}}").innerHTML = truncatedContent;
                                })
                                .catch(err => console.error("Error converting DOCX:", err));
                        })
                        .catch(err => console.error("Error fetching DOCX:", err));
                    </script>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> --}}

{{-- <section class="mainSection">
    <div class="homeSectionPart">
        <div class="selectTenerSec">
            <div class="selectTender">
                <div class="allSeriesBox" id="documents-container">
                    @foreach($documents as $document)
                        <div class="secriesBox" id="document-{{$document->id}}" draggable="true" ondragstart="dragStart(event)">
                            <label>{{$document->certificate_file}}</label>
                            <div class="seriestext">

                                <iframe id="preview-{{$document->id}}" style="height: 200px; width: 150px; border: 1px solid #ccc;" frameborder="0"></iframe>
                                <p>{{$document->title}}</p>
                            </div>
                        </div>
                        <script>
                            // Dynamically generate the file URL for each document
                            const fileUrl{{$document->id}} = "https://sample-videos.com/doc/Sample-doc-file-100kb.doc";

                            // Microsoft Office Online Viewer URL
                            // const officeViewerUrl{{$document->id}} = "https://view.officeapps.live.com/op/view.aspx?src=" + encodeURIComponent(fileUrl{{$document->id}});

                            // const officeViewerUrl{{$document->id}} = "https://view.officeapps.live.com/op/view.aspx?src=" + encodeURIComponent(fileUrl{{$document->id}}) + "&embedded=true";

                            // // Set the iframe's source to the Office Online Viewer URL for each document
                            // document.getElementById("preview-{{$document->id}}").src = officeViewerUrl{{$document->id}};


                            const googleDocsViewerUrl{{$document->id}} = "https://docs.google.com/gview?url=" + encodeURIComponent(fileUrl{{$document->id}}) + "&embedded=true";

                            // Set the iframe's source to the Google Docs Viewer URL
                            document.getElementById("preview-{{$document->id}}").src = googleDocsViewerUrl{{$document->id}};

                        </script>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="selectTenerSec">
            <div class="selectTender">
                <div class="allSeriesBox" id="documents-container">
                    @foreach($documents as $document)
                    <div class="secriesBox" id="document-{{$document->id}}" draggable="true" ondragstart="dragStart(event)">
                        <label>{{$document->certificate_file}}</label>
                        <div class="seriestext">
                            <!-- Canvas for rendering PDF preview -->
                            <canvas id="preview-{{$document->id}}" style="height: 200px; width: 150px; border: 1px solid #ccc;"></canvas>
                            <p>{{$document->title}}</p>
                        </div>
                    </div>
                    <script>
                        // Fetch the PDF file and render the first page using PDF.js
                        fetch("http://tender-management.test/storage/certificates/certificate{{$document->id}}/{{$document->docx_preview}}")
                        .then(response => response.arrayBuffer())
                        .then(data => {
                            const loadingTask = pdfjsLib.getDocument({data: data});
                            loadingTask.promise.then(pdf => {
                                // Get the first page of the PDF
                                pdf.getPage(1).then(page => {
                                    // Prepare the canvas for rendering
                                    const canvas = document.getElementById("preview-{{$document->id}}");
                                    const context = canvas.getContext('2d');
                                    const viewport = page.getViewport({ scale: 0.5 }); // Scale to fit the canvas size
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    // Render the page onto the canvas
                                    page.render({
                                        canvasContext: context,
                                        viewport: viewport
                                    });
                                });
                            }).catch(err => {
                                console.error("Error loading PDF:", err);
                            });
                        })
                        .catch(err => {
                            console.error("Error fetching PDF:", err);
                        });
                    </script>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('js')
<script>
    // Drag and Drop Logic
    let draggedElement = null;

    function dragStart(event) {
        draggedElement = event.target;
        // Store the current order in case the drop fails or we want to restore it
        event.dataTransfer.setData("text/plain", draggedElement.id);
    }

    document.getElementById("documents-container").addEventListener("dragover", (event) => {
        event.preventDefault(); // Necessary to allow drop
    });

    document.getElementById("documents-container").addEventListener("drop", (event) => {
        event.preventDefault();
        const draggedId = event.dataTransfer.getData("text/plain");
        const draggedElement = document.getElementById(draggedId);

        // Get the target element to determine where to insert the dragged element
        const targetElement = event.target.closest('.secriesBox');
        if (targetElement && targetElement !== draggedElement) {
            // Insert the dragged element before or after the target element
            if (targetElement !== draggedElement) {
                const container = document.getElementById("documents-container");
                container.insertBefore(draggedElement, targetElement);
            }
        }
    });
    </script>
@endsection

{{-- complete work --}}
{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF First Page Preview</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
</head>
<body>
    <h1>PDF First Page Preview</h1>

    <!-- Container to display the PDF first page -->
    <canvas id="pdf-preview"></canvas>

    <ul>
        <li>
            <a href="http://tender-management.test/storage/employees/employee2/67810af49efe4_1736510196_first_page.pdf" target="_blank">View Full PDF</a>
        </li>
    </ul>

    <script>
        // PDF.js loading and rendering
        var url = 'http://tender-management.test/storage/employees/employee2/67810af49efe4_1736510196_first_page.pdf';

        // The canvas element where the first page will be rendered
        var canvas = document.getElementById('pdf-preview');
        var context = canvas.getContext('2d');

        // Loading the PDF
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            // Get the first page
            pdf.getPage(1).then(function(page) {
                var viewport = page.getViewport({ scale: 1.5 });

                // Set canvas size to match PDF's page size
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // Render the page
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        });
    </script>
</body>
</html> --}}

