@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fa-solid fa-plus"></i> Mitarbeiter hinzuhügen</button>
        </div>

        <div class="allEmploysSec">

            {{-- {!! $htmlContent !!} --}}


            <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Preview</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
</head>
<body>
    <h1>PDF Preview</h1>
    <canvas id="pdfCanvas"></canvas>

    <script>
        var url = 'http://tender-management.test/storage/documents/ss.pdf'; // Your PDF file path here

        console.log(url);
        // Initialize PDF.js
        pdfjsLib.getDocument(url).promise.then(function (pdf) {
            // Fetch the first page of the PDF
            pdf.getPage(1).then(function (page) {
                var canvas = document.getElementById('pdfCanvas');
                var context = canvas.getContext('2d');

                var viewport = page.getViewport({ scale: 1 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // Render the page into the canvas context
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        });
    </script>
</body>
</html>

        </div>
    </div>
</section>
@endsection

