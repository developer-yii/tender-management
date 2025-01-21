@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
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
        <div class="addCommonBtn">
            <button class="btn btnAdd"><i class="fa-solid fa-plus"></i> Vorlage hinzufügen</button>
        </div>
        <form id="addForm" enctype="multipart/form-data">
            @csrf
            <div class="new-TenderSec">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="leftnewBox">
                            <div class="topPlusBox form-group">
                                <img id="blah" src="{{$baseUrl}}images/plus-Icon.png" alt="plus-Icon">
                                <input type="file" class="file-upload" onchange="readURL(this);" id="file_upload" name="file_upload">
                                <div class="edit-icon">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                                <span class="error"></span>
                            </div>
                            <div class="centerBox">
                                <div class="titleBox form-group">
                                    <input type="text" placeholder="Ausschreibung Name" name="ausschreibung_name" id="ausschreibung_name">
                                    <span class="error"></span>
                                </div>
                                <div class="multyDateBox">
                                    <ul>
                                        <li class="form-group">
                                            <label for="">Ausführungszeitraum</label>
                                            <input type="text" class="flat input-daterange-datepicker" placeholder="Eine Frist festlegen" name="execution_period" id="execution_period">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">Bindefrist</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen"  onfocus="(this.type='date')" onclick="this.showPicker()" name="binding_period" id="binding_period">
                                            <span class="error"></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="">Bewerberfragen bis</label>
                                            <input type="datetime-local" class="flat" placeholder="Eine Frist festlegen" name="applicant_questions_date" id="applicant_questions_date">
                                            <span class="error"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ourThreeBox">
                                    <div class="firstBox box-box form-group">
                                        <label for="">Ablauf Angebotsfrist</label>
                                        <input type="datetime-local" class="flat" placeholder="Eine Frist festlegen" name="expiry_offer_date" id="expiry_offer_date">
                                        <span class="error"></span>
                                    </div>
                                    <div class="secondBox box-box form-group">
                                        <label for="">STATUS</label>
                                        <select name="status" id="status">
                                            <option value="" style="display: none;">Select Status</option>
                                            @foreach ($tenderStatus as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </div>
                                    <div class="thirdBox box-box form-group">
                                        <p>VERANTWORTLICHE PERSON</p>
                                        <div class="newBox">
                                            <select name="employees[]" id="employees" data-placeholder="Choose Employee" multiple>
                                                @foreach ($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->first_name}}  {{$employee->last_name}}</option>
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
                                    <div class="docListOur">
                                        <ul id="fileList" class="list-unstyled mt-2"></ul>
                                        <div id="fileInputsContainer">
                                            <input type="file" name="documents[]" class="file-input">
                                        </div>
                                        <div class="openDocBtn">
                                            <a class="btn btnOpen"><i class="bi bi-caret-down-fill"></i></a>
                                        </div>
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
                                    <input type="text" name="performance_title" id="performance_title" placeholder="Fügen Sie eine Leistungstitel">
                                    <span class="error"></span>
                                </div>
                                <div class="sameBox form-group">
                                    <h6>Fügen Sie eine kurze Beschreibung hinzu</h6>
                                    <textarea name="kurze_beschreibung" id="kurze_beschreibung" placeholder="Fügen Sie eine kurze Beschreibung hinzu"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="newaddRegister">
                                <div class="addRegister">
                                    <div class="form-group">
                                        <h6>Vergabestelle</h6>
                                        <textarea name="vergabestelle" id="vergabestelle" placeholder="Fügen Sie eine Vergabestelle hinzu"></textarea>
                                        <span class="error"></span>
                                    </div>

                                    <div class="form-group">
                                        <h6>Abgabeform</h6>
                                        <select name="abgabeform" id="abgabeform">
                                            <option value="" style="display: none;">Wählen Sie aus</option>
                                            @foreach ($abgabeForms as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="addRightText form-group">
                                    <h6>Ausführungsort</h6>
                                    <textarea name="execution_location" id="execution_location" placeholder="Fügen Sie eine Ausführungsort hinzu"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="awardInputSec">
                                <h6>Vergabe</h6>
                                <ul>
                                    <li class="form-group">
                                        <label for="">Vergabeordnung</label>
                                        <input type="text" name="vergabeordnung" id="vergabeordnung">
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Vergabeverfahren</label>
                                        <input type="text" name="vergabeverfahren" id="vergabeverfahren">
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Unterteilung in Lose</label>
                                        <select name="subdivision_lots" id="subdivision_lots">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Nebenangebote zulässig</label>
                                        <select name="side_offers_allowed" id="side_offers_allowed">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                    <li class="form-group">
                                        <label for="">Mehrere Hauptangebote zul.</label>
                                        <select name="main_offers_allowed" id="main_offers_allowed">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="error"></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="fileSection">
                                <h6>Von der Vergabestelle bereitgestellte Dokumente</h6>
                                <div class="accordion" id="accordionExample">
                                    {{-- <div class="accordion-item">
                                    </div> --}}
                                </div>
                                <a class="btn folderBtn mt-2" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                                    <i class="fa-solid fa-plus"></i> Add Folder
                                </a>
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


    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#execution_period", {
            mode: "range", // This makes it a date range picker
            dateFormat: "Y-m-d", // You can adjust the format
            locale: {
                firstDayOfWeek: 1 // Optional: Set the first day of the week to Monday
            }
        });
    });

    </script>
    <script src="{{ $baseUrl }}custom/js/add-tender.js"></script>
@endsection


