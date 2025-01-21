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
                            <div class="dataBoxFiled">
                                <div class="treeView">
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
                                                        <p><input type="checkbox" class="keyword-checkbox" data-section="team" id="file-{{$member->id}}" value="{{$member->id}}"><span>{{$member->first_name}} {{$member->last_name}}</span></p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView">
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
                                                        <p><input type="checkbox" class="keyword-checkbox" data-section="certificate" id="file-{{$certificate->id}}" value="{{$certificate->id}}"><span>{{$certificate->title}}</span></p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView">
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
                                                        <p><input type="checkbox" class="keyword-checkbox" data-section="reference" id="file-{{$reference->id}}" value="{{$reference->id}}"><span>{{$reference->project_title}}</span></p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dataBoxFiled">
                                <div class="treeView">
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
                                                        <p><input type="checkbox" class="keyword-checkbox" data-section="document" id="file-{{$document->id}}" value="{{$document->id}}"><span>{{$document->title}}</span></p>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="treeView">
                                    <div class="accordion" id="accordionExample5">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                    Company Details Documents
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse show" data-bs-parent="#accordionExample5">
                                                <div class="accordion-body company-document-list">
                                                    <p><input type="checkbox" class="keyword-checkbox" id="file-{{$companyDocuments->id}}" value="{{$companyDocuments->id}}">
                                                        <span>Company Presentation</span>
                                                    </p>
                                                    <p><input type="checkbox" class="keyword-checkbox" id="file-{{$companyDocuments->id}}" value="{{$companyDocuments->id}}">
                                                        <span>Agile Framework</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-b-10 docx-list">
                    <div class="seriesSec">
                        <div class="secriesPart">
                            <div class="titleBox">
                                <h5>Reihenfolge Word (DOCX)</h5>
                            </div>
                            <div class="allSeriesBox all-doc-preview">
                            </div>
                        </div>
                    </div>
                    <div class="endbtnSection">
                        <button class="btn btnCom"><span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> VORSCHAU</button>
                        <button class="btn btnCom"><span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> WORD HERUNTERLADEN</button>
                    </div>
                </div>

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
                        <button class="btn btnCom"><span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> VORSCHAU</button>
                        <button class="btn btnCom"><span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> PDF HERUNTERLADEN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        var getDocumentsUrl = "{{ route('tender.documents') }}";
        var previewUrl = "{{ route('tender.preview') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/start-tender.js"></script>
@endsection



