@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')

@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <a href="{{route('tender.add')}}" class="btn btnAdd"><i class="fa-solid fa-plus"></i> Mitarbeiter hinzuhügen</a>
        </div>
        <div class="tenderDetails">
            <div class="allTenderBox commonBackview">
                <div class="titleBox">
                    <h5>Alle Ausschreibungen</h5>
                </div>
                <div class="tenderInner">
                    @foreach ($tenders as $tender)
                        <div class="tenderName">
                            <div class="offerLeft">
                                <div class="imgBox">
                                    <img src="assest/images/offerimg1.png" alt="offerimg1">
                                </div>
                                <div class="textBox">
                                    <h5>Museum für Naturkunde Berlin</h5>
                                    <p>Ausführungszeitraum 01/2025 bis 12/2026</p>
                                </div>
                            </div>
                            <div class="textName">
                                <h6>Kathrin <br>Julia</h6>
                            </div>
                            <div class="dateTime">
                                <p>15.01.2025  |  <span>13:00</span></p>
                            </div>
                            <div class="viewstatus">
                                <div class="statusIcon">
                                    <img src="assest/images/Wait.png" alt="Wait">
                                </div>
                                <div class="text">
                                    <p>in Bearbeitung</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="employsBox commonBackview">
                <div class="employList">
                    <div class="titleBox">
                        <h5>Mitarbeiter</h5>
                    </div>
                    <div class="employMainBox">
                        <div class="employViewBox">
                            @foreach ($employees as $employee)
                                <div class="employProfile">
                                    <div class="imgBox">
                                        <img src="{{ Storage::url('employee/profile-photo/' . $employee->profile_pic) }}">
                                    </div>
                                    <div class="textBox">
                                        <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                        <p>{{ $employee->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="markusTender">
                            <div class="tenderAll">
                                <div class="titleBox">
                                    <h5>Markus Ausschreibungen:</h5>
                                </div>
                                <ul>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/Wait.png" alt="Wait">
                                        </div>
                                        <div class="text">
                                            <p>Staatliches Bau- und Liegenschaftsamt</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>Universitätsstadt Siegen</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>Stadt Ulm, Zentrale Vergabestelle VOB</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/green-dot.png" alt="green-dot">
                                        </div>
                                        <div class="text">
                                            <p>LHM, Baureferat, Verwaltung und Recht </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/gray-dot.png" alt="gray-dot">
                                        </div>
                                        <div class="text">
                                            <p>Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/Wait.png" alt="Wait">
                                        </div>
                                        <div class="text">
                                            <p>Staatliches Bau- und Liegenschaftsamt</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>Universitätsstadt Siegen</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>Stadt Ulm, Zentrale Vergabestelle VOB</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/green-dot.png" alt="green-dot">
                                        </div>
                                        <div class="text">
                                            <p>LHM, Baureferat, Verwaltung und Recht </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="statusIcon">
                                            <img src="assest/images/gray-dot.png" alt="gray-dot">
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
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    var tenderListUrl = "{{ route('tender.list') }}";
</script>
<script src="{{ $baseUrl }}custom-js/tenders.js"></script>
@endsection

