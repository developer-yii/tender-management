@php
    $baseUrl = asset('assest')."/";
    $title = $tender ? 'Edit' : 'Add';
@endphp
@extends('layouts.app-main')
@section('title', "Admin | {$title} Tender")
@section('extra_css')
<style>
    .edit-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        height: 100%;
        width: 100%;
        transform: translate(-50%, -50%);
        display: none;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
    }

    .edit-icon i {
        font-size: 22px;
        color: #fff;
    }
    .topPlusBox:hover .edit-icon {
        cursor: pointer;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <form id="addForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tender_id" value="{{ $tender ? $tender->id : '' }}">
            <div class="new-TenderSec">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="leftnewBox">

                            <div class="topPlusBox form-group">
                                @if($tender)
                                    <img id="blah" src="{{ asset('storage/tenders/tender' . $tender->id . '/' . $tender->main_image) }}" alt="{{ $tender->tender_name }}">
                                @else
                                <img id="blah" src="{{$baseUrl}}images/plus-Icon.png" alt="plus-Icon">
                                @endif
                                <input type="file" class="file-upload" onchange="readURL(this);" id="file_upload" name="file_upload">
                                <div class="edit-icon">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                                <span class="error"></span>
                            </div>

                            <div class="centerBox">
                                <div class="multyDateBox">
                                    <ul>
                                        <li class="form-group">
                                            <label for="">Ausschreibung Name</label>
                                            <input type="text" placeholder="Ausschreibung Name" name="ausschreibung_name" id="ausschreibung_name" value="{{ $tender ? $tender->tender_name : '' }}">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            @php
                                                $executionPeriod = $tender
                                                    ? $tender->period_from . ' to ' . $tender->period_to
                                                    : '';
                                                // $executionPeriod = $tender
                                                //     ? formatDate($tender->period_from, 'Y-m-d') . ' to ' . formatDate($tender->period_to, 'Y-m-d')
                                                //     : '';
                                            @endphp
                                            <label for="">Ausführungszeitraum</label>
                                            <input type="text" class="flat input-daterange-datepicker" placeholder="Eine Frist festlegen" name="execution_period" id="execution_period" value="{{$executionPeriod}}">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">Bindefrist</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen" name="binding_period" id="binding_period" value="{{ $tender ? $tender->binding_period : '' }}">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">Bewerberfragen bis</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen" name="applicant_questions_date" id="applicant_questions_date" value="{{ $tender ? $tender->question_ask_last_date : '' }}">
                                            <span class="error"></span>
                                       </li>

                                        <li class="form-group">
                                            <label for="">Ablauf Angebotsfrist</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen" name="expiry_offer_date" id="expiry_offer_date" value="{{ $tender ? $tender->offer_period_expiration : '' }}">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">STATUS</label>
                                            <select name="status" id="status">
                                                <option value="" style="display: none;">Select Status</option>
                                                @foreach ($tenderStatus as $key => $value)
                                                    <option value="{{ $key }}" {{ isset($tender) && $key == $tender->status ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error"></span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ourThreeBox">
                                    <div class="thirdBox box-box form-group">
                                        <p>VERANTWORTLICHE PERSON</p>
                                        <div class="newBox">
                                            <select name="employees[]" id="employees" data-placeholder="Choose Employee" multiple>
                                                @foreach ($employees as $employee)
                                                    <option value="{{$employee->id}}"
                                                        @if (isset($tender) && $tender->users->contains('id', $employee->id))
                                                            selected
                                                        @endif
                                                    >
                                                    {{$employee->first_name}} {{$employee->last_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="collectDocumentSec">
                                    <div class="docTitle">
                                        <h5>Gesammelte Dokumente:</h5>
                                    </div>
                                    <div class="docListOur form-group">
                                        <ul id="oldFileList" class="list-unstyled mt-2">
                                            @if($tender)
                                                @foreach($tender->files as $document)
                                                    @if($document->type == "documents")
                                                        <li>
                                                            <input type="hidden" name="old_documents[]" value="{{$document->original_file_name}}">
                                                            <a href="javascript:void(0)" style="flex-grow: 1;" class="a-txt">
                                                                <span class="cursor-default">
                                                                    <i class="fa-solid fa-file-circle-plus"></i> {{ $document->original_file_name }}
                                                                </span>
                                                            </a>
                                                            <button class="btn btn-sm btn-danger ms-2 remove-btn" onclick="removeOldFile(event, this)">X</button>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                        <ul id="fileList" class="list-unstyled mt-2"></ul>
                                        <div id="fileInputsContainer">
                                            <input type="file" name="documents[]" class="file-input custom-file-input" id="documents">
                                        </div>
                                        <span class="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="rightnewBox">
                            <div class="shortDiscription">
                                <div class="sameBox form-group">
                                    <h6>Leistungstitel</h6>
                                    <input type="text" name="performance_title" id="performance_title" placeholder="Fügen Sie eine Leistungstitel" value="{{ $tender ? $tender->title : '' }}">
                                    <span class="error"></span>
                                </div>
                                <div class="sameBox form-group">
                                    <h6>Fügen Sie eine kurze Beschreibung hinzu</h6>
                                    <textarea name="kurze_beschreibung" id="kurze_beschreibung" placeholder="Fügen Sie eine kurze Beschreibung hinzu">{{ $tender ? $tender->description : '' }}</textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="newaddRegister">
                                <div class="addRegister">
                                    <div class="form-group">
                                        <h6>Vergabestelle</h6>
                                        <textarea name="vergabestelle" id="vergabestelle" placeholder="Fügen Sie eine Vergabestelle hinzu">{{ $tender ? $tender->vergabestelle : '' }}</textarea>
                                        <span class="error"></span>
                                    </div>

                                    <div class="form-group">
                                        <h6>Abgabeform</h6>
                                        <select name="abgabeform" id="abgabeform">
                                            <option value="" style="display: none;">Wählen Sie aus</option>
                                            @foreach ($abgabeForms as $key => $value)
                                                <option value="{{ $key }}" {{ isset($tender) && $key == $tender->abgabeform ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="addRightText form-group">
                                    <h6>Ausführungsort</h6>
                                    <textarea name="execution_location" id="execution_location" placeholder="Fügen Sie eine Ausführungsort hinzu">{{ $tender ? $tender->place_of_execution : '' }}</textarea>
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="awardInputSec">
                                <h6>Vergabe</h6>
                                <div class="newFullBox">
                                    <ul>
                                        <li class="form-group">
                                            <label for="">Vergabeordnung</label>
                                            <input type="text" name="vergabeordnung" id="vergabeordnung" value="{{ $tender ? $tender->procurement_regulations : '' }}">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">Vergabeverfahren</label>
                                            <input type="text" name="vergabeverfahren" id="vergabeverfahren" value="{{ $tender ? $tender->procurement_procedures : '' }}">
                                            <span class="error"></span>
                                        </li>
                                    </ul>
                                </div>
                                <ul>
                                    <li class="form-group">
                                        <label for="">Unterteilung in Lose</label>
                                        <select name="subdivision_lots" id="subdivision_lots">
                                            @foreach ($options as $key => $value)
                                            <option value="{{ $key }}" {{ isset($tender) && $key == $tender->is_subdivision_lots ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Nebenangebote zulässig</label>
                                        <select name="side_offers_allowed" id="side_offers_allowed">
                                            @foreach ($options as $key => $value)
                                            <option value="{{ $key }}" {{ isset($tender) && $key == $tender->is_side_offers_allowed ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Mehrere Hauptangebote zul.</label>
                                        <select name="main_offers_allowed" id="main_offers_allowed">
                                            @foreach ($options as $key => $value)
                                            <option value="{{ $key }}" {{ isset($tender) && $key == $tender->is_main_offers_allowed ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="fileSection">
                                <h6>Von der Vergabestelle bereitgestellte Dokumente</h6>
                                <div class="form-group">
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($folder_files as $folder_name => $files)
                                            <div class="accordion-item" data-folder-id="{{$folder_name}}">
                                                <h2 class="accordion-header">
                                                <button class="accordion-button cursor-default" type="button" data-bs-toggle="collapse" data-bs-target="#{{$folder_name}}" aria-expanded="true" aria-controls="{{$folder_name}}">
                                                    {{$folder_name}}
                                                    <a class="btn btn-sm btn-danger ms-2 remove-btn" onclick="removeFolder(this)">X</a>
                                                </button>
                                                </h2>
                                                <div id="{{$folder_name}}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        @foreach ($files as $file)
                                                            <div class="file-list">
                                                                <a href="javascript:void(0)" class="d-block mt-1 a-txt">
                                                                    <span class="cursor-default">
                                                                        <i class="fa-solid fa-file-circle-plus"></i> {{$file->original_file_name}}
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm btn-danger ms-2 remove-btn" onclick="removeFile(this)">X</button>
                                                                </a>
                                                            </div>
                                                            <input type="hidden" name="old_folder_doc[{{$folder_name}}][]" value="{{$file->original_file_name}}">
                                                            <input type="hidden" name="old_folder_name[{{$folder_name}}]" value="{{$folder_name}}">
                                                        @endforeach
                                                        <input type="file" class="authorize_document mt-1 custom-file-input" name="folder_doc[{{$folder_name}}][]">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <span class="error"></span>
                                    <div>
                                        <a class="btn folderBtn mt-2" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                                            <i class="fa-solid fa-plus"></i> Add Folder
                                        </a>
                                    </div>
                                </div>
                                <div class="endbtnSection">
                                    <button type="submit" class="btn btnCom">SPEICHERN</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('modal')
    @include('include.folder-modal')
@endsection
@section('js')
    <script>
        var createUrl = "{{ route('tender.createupdate') }}";
        var getUrl = "{{ route('tender.detail') }}";
        var listUrl = "{{ route('tender.index') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/add-tender.js"></script>
@endsection


