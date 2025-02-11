@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', "Admin | " . trans('message.tenderlist'))
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <a href="{{route('tender.add')}}" class="btn btnAdd"><i class="fa-solid fa-plus"></i> {{ trans('message.addtender') }}</a>
        </div>
        <div class="tenderDetails">
            <div class="allTenderBox commonBackview">
                <div class="titleBox">
                    <h5>Alle Ausschreibungen</h5>
                </div>
                <div class="tenderInner">
                    @if($tenders->isNotEmpty())
                        @foreach ($tenders as $tender)
                            <div class="tenderName">
                                <a href="{{ route('tender.details', [$tender->id])}}">
                                    <div class="offerLeft">
                                        <div class="imgBox">
                                            <img src="{{ getTenderMainImage($tender) }}" loading="lazy" alt="{{$tender->tender_name}}">
                                        </div>
                                        <div class="textBox">
                                            <h5>{{ $tender->tender_name }}</h5>
                                            <p>Ausführungszeitraum {{ formatDate($tender->period_from, 'm/Y') }} bis {{ formatDate($tender->period_to, 'm/Y') }}</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="textName">
                                    <h6>
                                        @if($tender->users && $tender->users->isNotEmpty())
                                            {{ $tender->users->map(fn($user) => $user->first_name . ' ' . $user->last_name)->implode(', ') }}
                                        @else
                                            No users associated
                                        @endif
                                    </h6>
                                </div>
                                <div class="dateTime">
                                    <p> {{ formatDate($tender->offer_period_expiration, 'd.m.Y | h:i') }}</p>
                                </div>
                                <div class="viewstatus">
                                    <div class="statusIcon">
                                        <img src="{{ $tender->tenderStatus->getIconUrl() }}" loading="lazy" alt="{{ $tender->tenderStatus->title }}">
                                    </div>
                                    <div class="text">
                                        <p>{{ $tender->tenderStatus->title }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>{{ trans('message.no data available') }}</p>
                    @endif
                </div>
            </div>
            <div class="employsBox commonBackview">
                <div class="employList">
                    <div class="titleBox">
                        <h5>Mitarbeiter</h5>
                    </div>
                    @if($employees->isNotEmpty())
                        <div class="employMainBox">
                            <div class="employViewBox">
                                @foreach ($employees as $employee)
                                    <div class="employProfile" data-employee-id="{{ $employee->id }}">
                                        <div class="imgBox">
                                            <img src="{{ $employee->getProfilePicUrl() }}" loading="lazy" alt="Profile Picture">
                                        </div>
                                        <div class="textBox">
                                            <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                            <p>{{ $employee->email }}</p>
                                            <button class="btn iconEdit"><i class="bi bi-eye-fill"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="markusTender">
                                <div class="tenderAll">
                                    <div class="titleBox">
                                        <h5 id="employee-name"></h5>
                                    </div>
                                    <ul id="tenderList">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="employMainBox">
                            <p>{{ trans('message.no data available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script>
    var tenderListUrl = "{{ route('tender.list') }}";
    var assignTenderListUrl = "{{ route('tender.assign-tenders') }}";
    const baseUrl = "{{ $baseUrl }}";
</script>
<script src="{{ $baseUrl }}custom/js/tenders.js"></script>
@endsection

