@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Vorlage Details ')
@section('content')

<section class="mainSection">
    <div class="homeSectionPart">
        <div class="referanceDetailsSec">
            <div class="addCommonBtn newAdd">
                <a href="{{ route('templete.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück zur Übersicht</a>
                <button class="btn btnAdd edit-templete" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Vorlage hinzufügen</button>
            </div>
            <div class="employsSection">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="profiledetails">
                            <div class="mainSec">
                                <div class="user-img">
                                    {{-- <img src="{{ $employee->getProfilePicUrl() }}" alt="{{$employee->first_name}}"> --}}
                                </div>
                                <div class="textSec">
                                    <div class="topText">
                                        <h6>{{$templete->title}}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="btncenterSec">
                                <button class="btn btnCom" id="previewBtn">
                                    <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> LEBENSLAUF VORSCHAU
                                </button>
                                <button class="btn btnCom" id="downloadPdf">
                                    <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> LEBENSLAUF HERUNTERLADEN
                                    <a id="download-pdf" href="{{ $templete->templete_file_url }}" download style="display:none;"></a>
                                </button>

                                <button class="btn btnGray edit-templete" data-bs-toggle="modal" data-bs-target="#addModal" data-id="{{ $templete->id }}">INFOS BEARBEITEN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.templete-modal')
    @include('include.preview-modal', ['pagename' => 'Lebenslauf'])
@endsection

@section('js')
<script>
    var createUrl = "{{ route('templete.addupdate') }}";
    var getUrl = "{{ route('templete.detail') }}";
    var listUrl = "{{ route('templete.index') }}";
    var fileUrl = "{{ $templete->getTempleteFileUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/templetes.js"></script>
@endsection

