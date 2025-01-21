@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Vorlage ')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
</script>
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Vorlage hinzufügen</button>
        </div>
        <div class="templetsSecMain">
            @foreach ($templetes as $templete)
                <div class="tepmletBox">
                    <div class="titleBox">
                        <h5>{{ $templete->title }}</h5>
                    </div>
                    <div class="chartImg">
                        @if($templete->isPdf())
                        <canvas id="preview-{{$templete->id}}" style="height: 150px; width: 100%;"></canvas>
                        @elseif($templete->isImage())
                            <img src="{{ $templete->getTempleteFileUrl() }}" alt="Image preview" style="height: 150px; width: 100%; object-fit: cover;">
                        @else
                            <img src="{{ $baseUrl }}images/no-preview.png" alt="No preview available" style="">
                        @endif
                    </div>
                    <div class="templeBtn">
                        <button class="btn btnDetails">Herunterladen</button>
                        <a href="{{ route('templete.details', [$templete->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                    </div>
                </div>

                    @if($templete->isPdf())
                        <script>
                            fetch("{{$templete->getTempleteFileUrl()}}")
                            .then(response => response.arrayBuffer())
                            .then(data => {
                                const loadingTask = pdfjsLib.getDocument({data: data});
                                loadingTask.promise.then(pdf => {
                                    // Get the first page of the PDF
                                    pdf.getPage(1).then(page => {
                                        // Prepare the canvas for rendering
                                        const canvas = document.getElementById("preview-{{$templete->id}}");
                                        const context = canvas.getContext('2d');

                                        // Get the full viewport size
                                        const viewport = page.getViewport({ scale: 0.5 }); // Scale to fit the canvas size
                                        canvas.height = viewport.height / 2;  // Only render half of the page (top half)
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
                    @endif

                {{-- <script>
                    fetch("{{$templete->getTempleteFileUrl()}}")
                    .then(response => response.arrayBuffer())
                    .then(data => {
                        const loadingTask = pdfjsLib.getDocument({data: data});
                        loadingTask.promise.then(pdf => {
                            // Get the first page of the PDF
                            pdf.getPage(1).then(page => {
                                // Prepare the canvas for rendering
                                const canvas = document.getElementById("preview-{{$templete->id}}");
                                const context = canvas.getContext('2d');

                                // Get the full viewport size
                                const viewport = page.getViewport({ scale: 0.5 }); // Scale to fit the canvas size
                                canvas.height = viewport.height / 2;  // Only render half of the page (top half)
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
                </script> --}}
            @endforeach
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.templete-modal')
@endsection
@section('js')
<script>
    var createUrl = "{{ route('templete.addupdate') }}";
    var listUrl = "{{ route('templete.index') }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/templetes.js"></script>
@endsection

