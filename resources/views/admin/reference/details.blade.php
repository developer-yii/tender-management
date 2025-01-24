@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Referenz Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="referanceDetailsSec">
            <div class="addCommonBtn newAdd">
                <a href="{{ route('reference.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück Zu Allen</a>
                <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addReferenceModal" ><i class="fa-solid fa-plus"></i> Referenz hinzufügen</button>
            </div>
            <div class="refInnerDetail">
                <div class="refCenterBox">
                    <div class="recenterleft">
                        <div class="titleBox">
                            <h5>Projekt: {{ $reference->project_title }}</h5>
                            <h6>Music Support Group</h6>
                        </div>
                        <div class="musicSupportText">

                            <div class="comTextmusic">
                                <p class="pre">{{ $reference->scope }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="refcenterright">
                        <div class="comWhiteBox whiteFirst">
                            <h6>Verwendete Technologie-Stacks & Tools</h6>
                            @foreach($reference->tags as $referenceTag)
                                <button class="btn btnCommonTag cursor-default">{{$referenceTag->name}}</button>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="endbtnSection">
                    <button class="btn btnCom" id="previewBtn">
                        <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> REFERENZ VORSCHAU
                    </button>
                    <button class="btn btnCom" id="downloadPdf">
                        <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="Downloads-Pdf"></span> REFERENZ PDF HERUNTERLADEN
                        <a id="download-pdf" href="{{ $reference->file_pdf_url }}" download style="display:none;"></a>
                    </button>
                    <button class="btn btnCom" id="downloadDoc">
                        <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="Downloads Docx"></span> REFERENZ DOKUMENT HERUNTERLADEN
                        <a id="download-doc" href="{{ $reference->file_word_url }}" download style="display:none;"></a>
                    </button>
                    <button class="btn btnCom edit-reference" data-bs-toggle="modal" data-bs-target="#addReferenceModal" data-id="{{ $reference->id }}">
                        <span><img src="{{$baseUrl}}images/replace.png" alt="enter-file"></span>INFOS BEARBEITEN
                    </button>
                </div>

                <div class="multyTagsBox">
                    <div class="titleBox">
                        <h5>Suche Mitarbeiter nach Tag</h5>
                    </div>
                    <div class="multyBtntag">
                        @foreach($tags as $tag)
                            <a href="{{route('reference.index')}}#{{$tag->name}}" class="btn btnCommonTag">{{$tag->name}}</a>
                        @endforeach
                    </div>
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
<script src="{{ $baseUrl }}custom/js/reference.js"></script>
@endsection



