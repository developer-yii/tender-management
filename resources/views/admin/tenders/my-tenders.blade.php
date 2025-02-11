@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')

@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="myTendersSec">
            <div class="myTenderBox">
                <div class="tenderTitle">
                    <h5>Meine Ausschreibungen</h5>
                </div>
                <div class="tenderInnerBox">
                    @foreach($tenders as $tender)
                        <div class="myTenderCommon">
                            <div class="imgBox">
                                <img src="{{ getTenderMainImage($tender) }}" alt="{{$tender->tender_name}}">
                                <div class="tenderName">
                                    <h6>{{$tender->tender_name}}</h6>
                                </div>
                            </div>
                            <div class="detailsFull">
                                <ul>
                                    <li>
                                        <p>Ausführungszeitraum <span>{{ formatDate($tender->period_from, 'm/Y') }} bis {{ formatDate($tender->period_to, 'm/Y') }}</span></p>
                                    </li>
                                    <li>
                                        <p>Ablauf Angebotsfrist
                                            <span>{{ formatDate($tender->offer_period_expiration, 'd.m.Y | h:i') }}</span>
                                            {{ getRemainingDaysMessage($tender->offer_period_expiration) }}
                                        </p>
                                    </li>
                                    <li>
                                        <p>Bindefrist
                                            <span>{{ formatDate($tender->binding_period, 'd.m.Y') ?? '' }}</span>
                                            {{ getRemainingDaysMessage($tender->binding_period) }}
                                        </p>
                                    </li>
                                    <li>
                                        <p>Bewerberfragen bis
                                            <span>{{ formatDate($tender->question_ask_last_date, 'd.m.Y | h:i') }}</span>
                                            {{ getRemainingDaysMessage($tender->question_ask_last_date) }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="detailsView">
                                <a class="btn btnDetails" href="{{ route('tender.details', [$tender->id])}}">DETAILS ANSEHEN</a>
                                <div class="viewstatus">
                                    <div class="statusIcon">
                                        <img src="{{ $tender->tenderStatus ? $tender->tenderStatus->getIconUrl() : '' }}" alt="{{ $tender->tenderStatus ? $tender->tenderStatus->title : 'status' }}">
                                    </div>
                                    <div class="text">
                                        <p>{{ $tender->tenderStatus ? $tender->tenderStatus->title : 'Unknown' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


