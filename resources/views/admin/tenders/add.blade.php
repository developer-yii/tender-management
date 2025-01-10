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
        <form id="addtender">
            @csrf
            <div class="new-TenderSec">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="leftnewBox">
                            <div class="topPlusBox">
                                <img id="blah" src="{{$baseUrl}}images/plus-Icon.png" alt="plus-Icon">
                                <input type="file" class="file-upload" onchange="readURL(this);">
                                <div class="edit-icon">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </div>
                            </div>
                            <div class="centerBox">
                                <div class="titleBox">
                                    <input type="text" placeholder="Ausschreibung Name" name="ausschreibung_name" id="ausschreibung_name">
                                </div>
                                <div class="multyDateBox">
                                    <ul>
                                        <li>
                                            <label for="">Ausführungszeitraum</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen" onfocus="(this.type='date')" onclick="this.showPicker()" name="execution_period" id="execution_period">
                                        </li>
                                        <li>
                                            <label for="">Bindefrist</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen"  onfocus="(this.type='date')" onclick="this.showPicker()" name="binding_period" id="binding_period">
                                        </li>
                                        <li>
                                            <label for="">Bewerberfragen bis</label>
                                            <input type="text" class="flat" placeholder="Eine Frist festlegen"  onfocus="(this.type='date')" onclick="this.showPicker()" name="applicant_questions_date" id="applicant_questions_date">
                                        </li>
                                    </ul>
                                </div>
                                <div class="ourThreeBox">
                                    <div class="firstBox box-box">
                                        <label for="">Ablauf Angebotsfrist</label>
                                        <input type="text" class="flat" placeholder="Eine Frist festlegen"  onfocus="(this.type='date')" onclick="this.showPicker()" name="expiry_offer_date" id="expiry_offer_date">
                                    </div>
                                    <div class="secondBox box-box">
                                        <label for="">STATUS</label>
                                        <select name="status" id="status">
                                            <option value="" style="display: none;">Select Status</option>
                                            @foreach ($tenderStatus as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="thirdBox box-box">
                                        <p>VERANTWORTLICHE PERSON</p>
                                        <div class="newBox">
                                            <select name="employee" id="employee" data-placeholder="Choose Employee" multiple>
                                                @foreach ($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->first_name}}  {{$employee->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="collectDocumentSec">
                                    <div class="docTitle">
                                        <h5>Gesammelte Dokumente:</h5>
                                    </div>
                                    <div class="docListOur">
                                        <ul id="fileList">
                                        </ul>
                                        <input type="file" name="documents" id="documents" multiple>
                                        <div class="openDocBtn">
                                            <a class="btn btnOpen"><i class="bi bi-caret-down-fill"></i></a>
                                        </div>
                                    </div>
                                    {{-- <div class="endbtnSection">
                                        <button class="btn btnCom">SPEICHERN</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="rightnewBox">
                            <div class="shortDiscription">
                                <div class="sameBox">
                                    <h6>Leistungstitel</h6>
                                    <input type="text" name="performance_title" id="performance_title" placeholder="Fügen Sie eine Leistungstitel">
                                </div>
                                <div class="sameBox">
                                    <h6>Fügen Sie eine kurze Beschreibung hinzu</h6>
                                    <textarea name="kurze_beschreibung" id="kurze_beschreibung" placeholder="Fügen Sie eine kurze Beschreibung hinzu"></textarea>
                                </div>
                            </div>
                            <div class="newaddRegister">
                                <div class="addRegister">
                                    <h6>Vergabestelle</h6>
                                    <textarea name="vergabestelle" id="vergabestelle" placeholder="Fügen Sie eine Vergabestelle hinzu"></textarea>
                                    <h6>Abgabeform</h6>
                                    <select name="abgabeform" id="abgabeform">
                                        <option value="">Wählen Sie aus</option>
                                        <option value="1">Option 1</option>
                                        <option value="2">Option 2</option>
                                        <option value="3">Option 3</option>
                                    </select>
                                </div>
                                <div class="addRightText">
                                    <h6>Ausführungsort</h6>
                                    <textarea name="execution_location" id="execution_location" placeholder="Fügen Sie eine Ausführungsort hinzu"></textarea>
                                </div>
                            </div>
                            <div class="awardInputSec">
                                <h6>Vergabe</h6>
                                <ul>
                                    <li>
                                        <label for="">Vergabeordnung</label>
                                        <input type="text" name="vergabeordnung" id="vergabeordnung">
                                    </li>
                                    <li>
                                        <label for="">Vergabeverfahren</label>
                                        <input type="text" name="vergabeverfahren" id="vergabeverfahren">
                                    </li>
                                    <li>
                                        <label for="">Unterteilung in Lose</label>
                                        <select name="subdivision_lots" id="subdivision_lots">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </li>
                                    <li>
                                        <label for="">Nebenangebote zulässig</label>
                                        <select name="side_offers_allowed" id="side_offers_allowed">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </li>
                                    <li>
                                        <label for="">Mehrere Hauptangebote zul.</label>
                                        <select name="main_offers_allowed" id="main_offers_allowed">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div class="fileSection">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Laden Sie das Dokument herunter
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-file-circle-plus"></i>Laden Sie das Dokument herunter</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Laden Sie das Dokument herunter
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-file-circle-plus"></i>Laden Sie das Dokument herunter</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="endbtnSection">
                                    <button class="btn btnCom">SPEICHERN</button>
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
@section('js')
<script>
    var tenderListUrl = "{{ route('tender.list') }}";

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        $('.dropdown-button').on('click', function () {
            $('.dropdown-list').toggle();
        });

        $('.dropdown-list li').on('click', function () {
            var selectedText = $(this).text();
            $('.dropdown-button').text(selectedText);
            $('.dropdown-list').hide();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-list').hide();
            }
        });
    });
</script>
<script src="{{ $baseUrl }}custom/js/tenders.js"></script>
@endsection

