@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Referenz Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn newAdd">
            <a href="{{ route('reference.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück Zu Allen</a>
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addReferenceModal" ><i class="fa-solid fa-plus"></i> Referenz hinzufügen</button>
        </div>
        <div class="employsSection">
            <div class="row">
                <div class="col-lg-5">
                    <div class="profiledetails">
                        <div class="mainSec">
                            <div class="textSec">
                                <div class="topText">
                                    <h6>Projekt: {{ $reference->project_title }}</h6>
                                </div>
                                <br>
                                <div class="bottomText">
                                    <div class="projectBox">
                                        <p>Umfang:</p>
                                        <pre class="pre">{{ $reference->scope }}</pre>
                                        <p class="date">Leistungszeitraum:
                                            <span>{{formatDateRange($reference->start_date, $reference->end_date)}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btncenterSec">
                            <button class="btn btnCom" id="previewBtn">
                                <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> REFERENZ VORSCHAU
                            </button>
                            <button class="btn btnCom" id="downloadPdf">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> REFERENZ PDF HERUNTERLADEN
                                <a id="download-pdf" href="{{ $reference->file_pdf_url }}" download style="display:none;"></a>
                            </button>
                            <button class="btn btnCom" id="downloadDoc">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> REFERENZ DOKUMENT HERUNTERLADEN
                                <a id="download-doc" href="{{ $reference->file_word_url }}" download style="display:none;"></a>
                            </button>
                            <button class="btn btnGray edit-reference" data-bs-toggle="modal" data-bs-target="#addReferenceModal" data-id="{{ $reference->id }}">INFOS BEARBEITEN</button>
                        </div>
                        <div class="multyBnt">
                            @foreach($reference->tags as $referenceTag)
                                <button class="btn btnCommonTag">{{$referenceTag->name}}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="multyTagsBox">
                <div class="titleBox">
                    <h5>Suche Mitarbeiter nach Tag</h5>
                </div>
                <div class="multyBtntag">
                    @foreach($tags as $tag)
                        <a href="{{route('employee.index')}}#{{$tag->name}}" class="btn btnCommonTag">{{$tag->name}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.reference-modal')
    @include('include.preview-modal')
@endsection
@section('js')
<script>
    var createReferenceUrl = "{{ route('reference.addupdate') }}";
    var getReferenceUrl = "{{ route('reference.detail') }}";
    var referenceListUrl = "{{ route('reference.index') }}";
    var fileUrl = "{{ $reference->getFilePdfUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/references.js"></script>
<script src="{{ $baseUrl }}custom/js/reference-details.js"></script>
@endsection



