@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn newAdd">
            <a href="{{ route('certificate.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück Zu Allen</a>
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addCertificateModal"><i class="fa-solid fa-plus"></i> Zertifizierung hinzufügen</button>
        </div>
        <div class="employsSection">
            <div class="row">
                <div class="col-lg-5">
                    <div class="profiledetails">
                        <div class="mainSec">
                            <div class="user-img">
                                <img src="{{ $certificate->getLogoUrl() }}" alt="{{$certificate->title}}">
                            </div>
                            <div class="textSec">
                                <div class="topText">
                                    <h6>{{$certificate->title}}</h6>
                                    <span>{{$certificate->category_name}}</span>
                                </div>
                                <div class="bottomText">
                                    <p>{{$certificate->description}}</p>
                                </div>
                                <br><br>
                                <div class="bottomText">
                                    <p>Gültig vom {{ formatDateToGerman($certificate->valid_from_date) }} bis {{ formatDateToGerman($certificate->valid_to_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="btncenterSec">
                            <button class="btn btnCom" id="previewBtn">
                                <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> ZERTIFIKAT VORSCHAU
                            </button>
                            <button class="btn btnCom" id="downloadPdf">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> ZERTIFIKAT PDF HERUNTERLADEN
                                <a id="download-pdf" href="{{ $certificate->certificate_pdf_url }}" download style="display:none;"></a>
                            </button>
                            <button class="btn btnCom" id="downloadDoc">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> ZERTIFIKAT DOKUMENT HERUNTERLADEN
                                <a id="download-doc" href="{{ $certificate->certificate_word_url }}" download style="display:none;"></a>
                            </button>
                            <button class="btn btnGray edit-certificate" data-bs-toggle="modal" data-bs-target="#addCertificateModal" data-id="{{ $certificate->id }}">INFOS BEARBEITEN</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.certificate-modal')
    @include('include.preview-modal')
@endsection
@section('js')
<script>
    var createCertificateUrl = "{{ route('certificate.addupdate') }}";
    var getCertificateUrl = "{{ route('certificate.detail') }}";
    var certificateListUrl = "{{ route('certificate.index') }}";
    var fileUrl = "{{ $certificate->getCertificatePdfUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/certificates.js"></script>
<script src="{{ $baseUrl }}custom/js/certificate-details.js"></script>
@endsection


