@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn newAdd">
            <a href="{{ route('employee.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück Zu Allen</a>
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fa-solid fa-plus"></i> Mitarbeiter hinzuhügen</button>
        </div>
        <div class="employsSection">
            <div class="row">
                <div class="col-lg-5">
                    <div class="profiledetails">
                        <div class="mainSec">
                            <div class="user-img">
                                <img src="{{ $employee->getProfilePicUrl() }}" alt="{{$employee->first_name}}">
                            </div>
                            <div class="textSec">
                                <div class="topText">
                                    <h6>{{$employee->first_name}} {{$employee->last_name}}</h6>
                                    <span>{{$employee->email}}</span>
                                </div>
                                <div class="bottomText">
                                    <p>{{$employee->description}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="btncenterSec">
                            <button class="btn btnCom" id="previewBtn">
                                <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> LEBENSLAUF VORSCHAU
                            </button>
                            <button class="btn btnCom" id="downloadBtn">
                                <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> LEBENSLAUF HERUNTERLADEN
                                <a id="download-link" href="{{ $employee->cv_url }}" download style="display:none;"></a>
                            </button>

                            <button class="btn btnGray edit-employee" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" data-id="{{ $employee->id }}">INFOS BEARBEITEN</button>
                        </div>
                        <div class="multyBnt">
                            @foreach($employee->tags as $employeeTag)
                                <button class="btn btnCommonTag">{{$employeeTag->name}}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="markusTender">
                        <div class="tenderAll">
                            <div class="titleBox">
                                <h5>Markus Ausschreibungen:</h5>
                            </div>
                            <ul>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/Wait.png" alt="Wait">
                                    </div>
                                    <div class="text">
                                        <p>Staatliches Bau- und Liegenschaftsamt</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/orange-dot.png" alt="orange-dot">
                                    </div>
                                    <div class="text">
                                        <p>Universitätsstadt Siegen</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/orange-dot.png" alt="orange-dot">
                                    </div>
                                    <div class="text">
                                        <p>Stadt Ulm, Zentrale Vergabestelle VOB</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/green-dot.png" alt="green-dot">
                                    </div>
                                    <div class="text">
                                        <p>LHM, Baureferat, Verwaltung und Recht&nbsp;</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/gray-dot.png" alt="gray-dot">
                                    </div>
                                    <div class="text">
                                        <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/gray-dot.png" alt="gray-dot">
                                    </div>
                                    <div class="text">
                                        <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/gray-dot.png" alt="gray-dot">
                                    </div>
                                    <div class="text">
                                        <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/gray-dot.png" alt="gray-dot">
                                    </div>
                                    <div class="text">
                                        <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="statusIcon">
                                        <img src="{{$baseUrl}}images/gray-dot.png" alt="gray-dot">
                                    </div>
                                    <div class="text">
                                        <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                    </div>
                                </li>
                            </ul>
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
    @include('include.employee-modal')

    <div class="modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Lebenslauf Vorschau</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewBody">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script>
    var employeeListUrl = "{{ route('employee.index') }}";
    var createEmployeeUrl = "{{ route('employee.addupdate') }}";
    var getEmployeeUrl = "{{ route('employee.detail') }}";
    // var fileUrl = "{{ $employee->cv_url }}";
    var fileUrl = "{{ $employee->getCvUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/employees.js"></script>
<script src="{{ $baseUrl }}custom/js/employee-details.js"></script>
@endsection


