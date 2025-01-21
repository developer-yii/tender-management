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
                                    <img src="{{ $baseUrl }}images/offerimg1.png" alt="offerimg1">
                                </div>
                                <div class="textBox">
                                    <h5>{{ $tender->tender_name }}</h5>
                                    <p>Ausführungszeitraum {{ \Carbon\Carbon::parse($tender->period_from)->format('d/m/Y') }} bis {{ \Carbon\Carbon::parse($tender->period_to)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="textName">
                                <h6>Kathrin <br>Julia</h6>
                            </div>
                            <div class="dateTime">
                                @php
                                    $formattedExpiration = $tender->offer_period_expiration
                                    ? \Carbon\Carbon::parse($tender->offer_period_expiration)->format('d.m.Y | h:i')
                                    : 'No expiration set';
                                @endphp
                                <p>{{ $formattedExpiration }}</p>
                            </div>
                            <div class="viewstatus">
                                <div class="statusIcon">
                                    <img src="{{ $baseUrl .'images/'. $tender->status_icon }}" alt="{{ $tender->status_text }}">
                                </div>
                                <div class="text">
                                    <p>{{ $tender->status_text }}</p>
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
                                <div class="employProfile" data-employee-id="{{ $employee->id }}">
                                    <div class="imgBox">
                                        <img src="{{ $employee->getProfilePicUrl() }}">
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
                                    @foreach ($tenders as $tender)
                                        <li data-employee-id="2">
                                            <div class="statusIcon">
                                                <img src="{{ $baseUrl }}images/Wait.png" alt="Wait">
                                            </div>
                                            <div class="text">
                                                <p>2---Staatliches Bau- und Liegenschaftsamt</p>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li data-employee-id="2">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>2---Universitätsstadt Siegen</p>
                                        </div>
                                    </li>

                                    <li data-employee-id="3">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>3---Stadt Ulm, Zentrale Vergabestelle VOB</p>
                                        </div>
                                    </li>

                                   <li data-employee-id="3">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/green-dot.png" alt="green-dot">
                                        </div>
                                        <div class="text">
                                            <p>4---LHM, Baureferat, Verwaltung und Recht </p>
                                        </div>
                                    </li>

                                    <li data-employee-id="2">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/gray-dot.png" alt="gray-dot">
                                        </div>
                                        <div class="text">
                                            <p>2---Senatsverwaltung für Stadtentwicklung, Bauen...</p>
                                        </div>
                                    </li>

                                    <li data-employee-id="4">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/Wait.png" alt="Wait">
                                        </div>
                                        <div class="text">
                                            <p>4---Staatliches Bau- und Liegenschaftsamt</p>
                                        </div>
                                    </li>

                                    <li data-employee-id="4">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>4---Universitätsstadt Siegen</p>
                                        </div>
                                    </li>

                                    <li data-employee-id="2">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/orange-dot.png" alt="orange-dot">
                                        </div>
                                        <div class="text">
                                            <p>2---Stadt Ulm, Zentrale Vergabestelle VOB</p>
                                        </div>
                                    </li>

                                    <li data-employee-id="2">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/green-dot.png" alt="green-dot">
                                        </div>
                                        <div class="text">
                                            <p>2---LHM, Baureferat, Verwaltung und Recht </p>
                                        </div>
                                    </li>

                                    <li data-employee-id="4">
                                        <div class="statusIcon">
                                            <img src="{{ $baseUrl }}images/gray-dot.png" alt="gray-dot">
                                        </div>
                                        <div class="text">
                                            <p>4---Senatsverwaltung für Stadtentwicklung, Bauen...</p>
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
<script src="{{ $baseUrl }}custom/js/tenders.js"></script>
@endsection

