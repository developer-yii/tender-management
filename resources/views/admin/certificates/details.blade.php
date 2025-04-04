@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn newAdd">
            <a href="{{ route('certificate.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück zur Übersicht</a>
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addCertificateModal"><i class="fa-solid fa-plus"></i> Zertifizierung hinzufügen</button>
        </div>
        <div class="softwareDevlop">
            <div class="titleBox">
                <h5>{{$certificate->category_name}} <br> {{$certificate->title}}</h5>
            </div>
            <div class="softwareDevlopBox">
                <div class="softwareLeftBox">
                    <div class="integrationBox">
                        <div class="imgBox">
                            <img src="{{ $certificate->getLogoUrl() }}" alt="{{$certificate->title}}">
                        </div>
                        <div class="textBox">
                            <h6>{{$certificate->description}}</h6>
                        </div>
                    </div>
                    <div class="integratiDetail">
                        <ul>
                            <li>
                                <p>Gültig vom {{ formatDateToGerman($certificate->valid_from_date) }} bis {{ formatDateToGerman($certificate->valid_to_date) }}</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="softwareRightBox">
                    <div class="imgBox">
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ $certificate->certificate_word_url }}" width="100%" style="min-height: 600px;"></iframe>
                    </div>
                </div>
            </div>
            <div class="endbtnSection">
                <button class="btn btnCom" id="previewBtn">
                    <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> VORSCHAU
                </button>
                <button class="btn btnCom" id="downloadPdf">
                    <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> PDF HERUNTERLADEN
                    <a id="download-pdf" href="{{ $certificate->certificate_pdf_url }}" download style="display:none;"></a>
                </button>
                <button class="btn btnCom" id="downloadDoc">
                    <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> DOKUMENT HERUNTERLADEN
                    <a id="download-doc" href="{{ $certificate->certificate_word_url }}" download style="display:none;"></a>
                </button>
                <button class="btn btnCom edit-certificate" data-bs-toggle="modal" data-bs-target="#addCertificateModal" data-id="{{ $certificate->id }}"><span><img src="{{$baseUrl}}images/replace.png" alt="enter-file"></span> BEARBEITEN</button>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.certificate-modal')
    @include('include.preview-modal', ['pagename' => 'Zertifizierung'])
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
@endsection


