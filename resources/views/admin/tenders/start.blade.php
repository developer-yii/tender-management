@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="selectTenerSec">
            <div class="selectTender">
                <div class="titleBox">
                    <h5>Ausschreibung wählen:</h5>
                    <div class="dropdown">
                        <div class="dropdown-button">Auswählen</div>
                        <ul class="dropdown-list">
                            @foreach ($tenders as $tender)
                                <li data-value="{{$tender->id}}">{{$tender->tender_name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="requiredField">
                    <div class="reduiredBox">
                        <h5>Benötigten Daten:</h5>
                        <div class="fieldSelect tender-document-list">
                        </div>
                    </div>
                </div>

                <div class="mainDataBox">
                    <div class="dataSeclect">
                        <div class="titleBox">
                            <h5>Daten auswählen:</h5>
                        </div>
                        <div class="multyFileTab">
                            <div class="dataBoxFiled row">
                                <div class="treeView col-lg-4">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Team
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                <div class="accordion-body team-member">
                                                    @foreach($teamMembers as $member)
                                                        <div class="clickTo">
                                                            <input type="checkbox" id="member-{{$member->id}}" class="keyword-checkbox" data-section="team"
                                                            value="{{$member->id}}"
                                                            data-doc-preview-url="{{$member->getDocxPreviewUrl()}}"
                                                            data-pdf-preview-url="{{$member->getCvUrl()}}">
                                                            <label for="member-{{$member->id}}" class="keyword-label">{{$member->first_name}} {{$member->last_name}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView col-lg-4">
                                    <div class="accordion" id="accordionExample2">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                    Zertifikate
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionExample2">
                                                <div class="accordion-body certificate-list">
                                                    @foreach($certificates as $certificate)
                                                        <div class="clickTo">
                                                            <input type="checkbox" id="certificate-{{$certificate->id}}" class="keyword-checkbox" data-section="certificate"
                                                                value="{{$certificate->id}}"
                                                                data-doc-preview-url="{{$certificate->getDocxPreviewUrl()}}"
                                                                data-pdf-preview-url="{{$certificate->getCertificatePdfUrl()}}">
                                                            <label for="certificate-{{$certificate->id}}" class="keyword-label">{{$certificate->title}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView col-lg-4">
                                    <div class="accordion" id="accordionExample3">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                    Referenzen
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordionExample3">
                                                <div class="accordion-body reference-list">
                                                   @foreach($references as $reference)
                                                        <div class="clickTo">
                                                            <input type="checkbox" id="reference-{{$reference->id}}" class="keyword-checkbox" data-section="reference"
                                                                value="{{$reference->id}}"
                                                                data-doc-preview-url="{{$reference->getDocxPreviewUrl()}}"
                                                                data-pdf-preview-url="{{$reference->getFilePdfUrl()}}">
                                                            <label for="reference-{{$reference->id}}" class="keyword-label">{{$reference->project_title}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="treeView col-lg-4">
                                    <div class="accordion" id="accordionExample4">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                    Unterlagen / Bestätigungen
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse show" data-bs-parent="#accordionExample4">
                                                <div class="accordion-body document-list">
                                                    @foreach($documents as $document)
                                                        <div class="clickTo">
                                                            <input type="checkbox" id="document-{{$document->id}}" class="keyword-checkbox" data-section="document"
                                                                value="{{$document->id}}"
                                                                data-doc-preview-url=""
                                                                data-pdf-preview-url="{{$document->getDocumentPdfUrl()}}">
                                                            <label for="document-{{$document->id}}" class="keyword-label">{{$document->title}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView col-lg-4">
                                    <div class="accordion" id="accordionExample5">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                    Company Details Documents
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse show" data-bs-parent="#accordionExample5">
                                                <div class="accordion-body company-document-list">
                                                    <div class="clickTo">
                                                        <input type="checkbox" id="company-presentation" class="keyword-checkbox" data-section="presentation"
                                                            value="{{$companyDocument->id}}"
                                                            data-doc-preview-url="{{ getDocumentPath($companyDocument->company_presentation_docx_preview, 'company-presentation') }}"
                                                            data-pdf-preview-url="{{ getDocumentPath($companyDocument->company_presentation_pdf, 'company-presentation') }}">
                                                        <label for="company-presentation" class="keyword-label">Company Presentation</label>
                                                    </div>
                                                    <div class="clickTo">
                                                        <input type="checkbox" id="agile-framework" class="keyword-checkbox" data-section="framework"
                                                            value="{{$companyDocument->id}}"
                                                            data-doc-preview-url="{{ getDocumentPath($companyDocument->agile_framework_docx_preview, 'agile-framework') }}"
                                                            data-pdf-preview-url="{{ getDocumentPath($companyDocument->agile_framework_pdf, 'agile-framework') }}">
                                                        <label for="agile-framework" class="keyword-label">Agile Framework</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="docxFileForm" style="display: none;">
                    <div class="mb-5 docx-list">
                        <div class="seriesSec">
                            <div class="secriesPart">
                                <div class="titleBox">
                                    <h5>Reihenfolge Word (DOCX)</h5>
                                </div>
                                <div class="allSeriesBox all-doc-preview" id="all-doc-preview">
                                </div>
                            </div>
                        </div>
                        <div class="endbtnSection">
                            <button class="btn btnCom" id="preview-doc"><span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> VORSCHAU</button>
                            <button class="btn btnCom"  id="download-doc"><span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> WORD HERUNTERLADEN</button>
                        </div>
                    </div>
                </form>

                <form id="pdfFileForm" style="display: none;">
                    <div class="pdf-list">
                        <div class="seriesSec">
                            <div class="secriesPart">
                                <div class="titleBox">
                                    <h5>Reihenfolge PDF (PDF)</h5>
                                </div>
                                <div class="allSeriesBox all-pdf-preview">
                                </div>
                            </div>
                        </div>
                        <div class="endbtnSection">
                            <button class="btn btnCom" id="preview-pdf"><span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> VORSCHAU</button>
                            <button class="btn btnCom" id="download-pdf"><span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> PDF HERUNTERLADEN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        var getDocumentsUrl = "{{ route('tender.documents') }}";
        var mergeDocxUrl = "{{ route('tender.merge-docx') }}";
        var mergePdfUrl = "{{ route('tender.merge-pdf') }}";
        var previewDocxUrl = "{{ route('tender.preview-docx') }}";
        var previewPdfUrl = "{{ route('tender.preview-pdf') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/start-tender.js"></script>
@endsection



