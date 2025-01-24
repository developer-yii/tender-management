@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Bescheinigung ')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
</script>
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addDocumentModal"><i class="fa-solid fa-plus"></i> Bescheinigung hinzufügen</button>
        </div>
        <div class="document_confirmSec">
            @if ($categoriesWithDocuments->isNotEmpty())
                @foreach($categoriesWithDocuments as $category => $documents)
                    <div class="insurance_confirm">
                        <div class="titleBox">
                            <h5>{{ $category }}</h5>
                        </div>
                        <div class="innerConfirm">
                            @foreach ($documents as $document)
                                <div class="insuranceBox">
                                    <h6>{{ $document->title }}</h6>
                                    @foreach ($document->parameters as $parameter)
                                        <p>{{ $parameter->param_name }}: {{ $parameter->param_value }}</p>
                                    @endforeach
                                    <div class="imgDoc">
                                        <canvas id="preview-{{$document->id}}" style="height: 150px; width: 100%;"></canvas>
                                    </div>
                                    <a href="{{ route('document.details', [$document->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                                </div>

                                <script>
                                    fetch("{{$document->getDocumentPdfUrl()}}")
                                    .then(response => response.arrayBuffer())
                                    .then(data => {
                                        const loadingTask = pdfjsLib.getDocument({data: data});
                                        loadingTask.promise.then(pdf => {
                                            // Get the first page of the PDF
                                            pdf.getPage(1).then(page => {
                                                // Prepare the canvas for rendering
                                                const canvas = document.getElementById("preview-{{$document->id}}");
                                                const context = canvas.getContext('2d');

                                                // Get the full viewport size
                                                const viewport = page.getViewport({ scale: 0.5 }); // Scale to fit the canvas size
                                                canvas.height = viewport.height / 4;  // Only render half of the page (top half)
                                                canvas.width = viewport.width;

                                                // Define the viewport for only the top half of the page
                                                const halfViewport = page.getViewport({
                                                    scale: 0.5,
                                                    offsetY: 0 // To display the top half
                                                });

                                                // Render the top half of the page onto the canvas
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
                                </script>

                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="insurance_confirm">
                    <div class="titleBox">
                        <h5>Data Not Found</h5>
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.document-modal')
@endsection
@section('js')
<script>
    var createUrl = "{{ route('document.addupdate') }}";
    var listUrl = "{{ route('document.index') }}";
</script>
<script src="{{ $baseUrl }}custom/js/documents.js"></script>
@endsection

