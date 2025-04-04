@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn newAdd">
            <a href="{{ route('document.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück zur Übersicht</a>
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addDocumentModal"><i class="fa-solid fa-plus"></i> Bescheinigung hinzufügen</button>
        </div>
        <div class="employsSection">
            <div class="row">
                <div class="col-lg-5">
                    <div class="profiledetails">
                        <div class="mainSec">

                            <div class="textSec">
                                <div class="topText">
                                    <h6>{{$document->title}}</h6>
                                    @foreach ($document->parameters as $parameter)
                                        <span>{{ $parameter->param_name }}: {{ $parameter->param_value }}</span><br>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="btncenterSec">
                            <button class="btn btnCom" id="previewBtn">
                                <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> LEBENSLAUF VORSCHAU
                            </button>
                            <button class="btn btnCom" id="downloadPdf">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> LEBENSLAUF HERUNTERLADEN
                                <a id="download-pdf" href="{{ $document->document_pdf_url }}" download style="display:none;"></a>
                            </button>
                            <button class="btn btnGray edit-document" data-bs-toggle="modal" data-bs-target="#addDocumentModal" data-id="{{ $document->id }}">INFOS BEARBEITEN</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.document-modal')
    @include('include.preview-modal', ['pagename' => 'Lebenslauf'])
@endsection
@section('js')
<script>
    var listUrl = "{{ route('document.index') }}";
    var createUrl = "{{ route('document.addupdate') }}";
    var getUrl = "{{ route('document.detail') }}";
    var fileUrl = "{{ $document->getDocumentPdfUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/documents.js"></script>
@endsection


