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
                                <img src="assest/images/boximg1.png" alt="boximg1">
                                <div class="tenderName">
                                    <h6>Museum für Naturkunde Berlin</h6>
                                </div>
                            </div>
                            <div class="detailsFull">
                                <ul>
                                    <li>
                                        <p>Ausführungszeitraum <span>01/2025 bis 12/2026</span></p>
                                    </li>
                                    <li>
                                        <p>Ablauf Angebotsfrist <span>15.01.2025  |  13:00</span> noch 28 Tage</p>
                                    </li>
                                    <li>
                                        <p>Bindefrist <span>14.02.2025</span> noch 58 Tage</p>
                                    </li>
                                    <li>
                                        <p>Bewerberfragen bis <span>08.01.2025  |  13:00</span> noch 21 Tage</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="detailsView">
                                <button class="btn btnDetails">DETAILS ANSEHEN</button>
                                <div class="viewstatus">
                                    <div class="statusIcon">
                                        <img src="assest/images/Wait.png" alt="Wait">
                                    </div>
                                    <div class="text">
                                        <p>in Bearbeitung</p>
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


