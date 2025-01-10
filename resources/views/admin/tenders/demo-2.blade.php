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
                            <div id="preview-{{$document->id}}" class="docx-preview"></div>
                            <p>{{$document->title}}</p>
                        </div>
                    </div>
                    <script>
                        fetch("http://tender-management.test/storage/certificates/certificate/{{$document->certificate_file}}") // Use the dynamic document file
                        .then(response => response.arrayBuffer())
                        .then(data => {
                            mammoth.convertToHtml({arrayBuffer: data})
                                .then(result => {
                                    document.getElementById("preview-{{$document->id}}").innerHTML = result.value;
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

<section class="mainSection">
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

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mammoth.js DOCX to HTML</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
</head>
<body>
    <h1>DOCX Preview with Mammoth.js</h1>
    <div id="docx-preview"></div>

    <script>
        fetch("http://tender-management.test/storage/certificates/certificate/certificates1.docx") // Replace with your DOCX URL
            .then(response => response.arrayBuffer())
            .then(data => {
                mammoth.convertToHtml({arrayBuffer: data})
                    .then(result => {
                        document.getElementById("docx-preview").innerHTML = result.value;
                    })
                    .catch(err => console.error("Error converting DOCX:", err));
            })
            .catch(err => console.error("Error fetching DOCX:", err));
    </script>
</body>
</html> --}}
