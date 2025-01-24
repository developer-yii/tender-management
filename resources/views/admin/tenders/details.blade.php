@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Zart Details ')
@section('content')
    <section class="mainSection">
        <div class="homeSectionPart">
            <div class="mainTenderDetails">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="propertySec">
                            <div class="imgbox">
                                <img src="{{ getTenderMainImage($tender) }}" alt="{{$tender->tender_name}}">
                            </div>
                            <div class="textDetails">
                                <div class="propName">
                                    <h6>{{ $tender->tender_name }}</h6>
                                </div>
                                <ul>
                                    <li>
                                        <p class="gray">Ausführungszeitraum</p>
                                        <p>{{ formatDate($tender->period_from, 'm/Y') }} bis {{ formatDate($tender->period_to, 'm/Y') }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Ablauf Angebotsfrist</p>
                                        <p>{{ formatDate($tender->offer_period_expiration, 'd.m.Y | h:i') ?? 'No expiration set' }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->offer_period_expiration) }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Bindefrist</p>
                                        <p>{{ formatDate($tender->binding_period, 'd.m.Y') ?? '' }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->binding_period) }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Bewerberfragen bis</p>
                                        <p>{{ formatDate($tender->question_ask_last_date, 'd.m.Y | h:i') }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->question_ask_last_date) }}</p>
                                    </li>
                                </ul>
                                <div class="ourThreeBox">
                                    <div class="firstBox box-box">
                                        <p>BIS ZUM ENDE DES ANGEBOTFRIST</p>
                                        <h6>{{ getRemainingDays($tender->offer_period_expiration) }}</h6>
                                        <span>Tage</span>
                                    </div>
                                    <div class="secondBox box-box">
                                        <h6>STATUS</h6>
                                        <img src="{{ $baseUrl .'images/'. $tender->status_icon }}" alt="{{ $tender->status_text }}">
                                        <p>{{ $tender->status_text }}</p>
                                    </div>
                                    <div class="thirdBox box-box">
                                        <p>VERANTWORTLICHE PERSON</p>
                                        @foreach($tender->users as $user)
                                            <div class="newBox m-b-10">
                                                <img src="{{ $user->getProfilePicUrl() }}" alt="{{ $user->first_name}}">
                                                <h6>{{$user->first_name}} {{$user->last_name}}</h6>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="collectDocumentSec">
                            <div class="docTitle">
                                <h5>Gesammelte Dokumente: </h5>
                            </div>
                            <div class="docListOur">
                                <ul>
                                    @foreach($documentFiles as $document)
                                        <li>
                                            <p><i class="fa-solid fa-file-lines"></i>{{ $document->original_file_name }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                                @if(count($documentFiles) > 10)
                                    <div class="openDocBtn">
                                        <button class="btn btnOpen"><i class="bi bi-caret-down-fill"></i></button>
                                    </div>
                                @endif
                            </div>
                            <div class="endbtnSection">
                                <a href="{{route('tender.add', ['id' => $tender->id])}}" class="btn btnCom"><span><img src="{{$baseUrl}}images/enter-file.png" alt="enter-file"></span> BEARBEITEN</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="service_provider">
                            <div class="titleBox">
                                <h5>{{ $tender->title }}</h5>
                                <p>{{ $tender->description }}</p>
                            </div>
                            <div class="serviceDetails">
                                <div class="lefDetail">
                                    <h6>Vergabestelle</h6>
                                    <p>{{ $tender->vergabestelle }}</p>
                                    <div class="devliverBox">
                                        <h6>Abgabeform</h6>
                                        <a href="javascript:void(0)" class="btn btnDetails cursor-default">{{ $tender->abgabeform_text }}</a>
                                    </div>
                                </div>
                                <div class="rightMap">
                                    <h6>Ausführungsort</h6>
                                    <div class="mapTextNew">
                                        <p>{{ $tender->place_of_execution }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="awardDetails">
                                <div class="awardtitle">
                                    <h5>Vergabe</h5>
                                </div>
                                <ul>
                                    <li>
                                        <p>Vergabeordnung</p>
                                        <span>{{ $tender->procurement_regulations }}</span>
                                    </li>
                                    <li>
                                        <p>Vergabeverfahren</p>
                                        <span>{{ $tender->procurement_procedures }}</span>
                                    </li>
                                    <li>
                                        <p>Unterteilung in Lose</p>
                                        <span>{{ $tender->is_subdivision_lots ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                    <li>
                                        <p>Nebenangebote zulässig</p>
                                        <span>{{ $tender->is_side_offers_allowed ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                    <li>
                                        <p>Mehrere Hauptangebote zul.</p>
                                        <span>{{ $tender->is_main_offers_allowed ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="fileSection">
                                <div class="accordion" id="accordionExample">
                                    @foreach ($folder_files as $folder_name => $files)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button">
                                                    {{ $folder_name ?? 'Unnamed Folder' }}
                                                </button>
                                            </h2>
                                            <div class="accordion-body">
                                                @foreach ($files as $file)
                                                    <a href="{{ asset($file->file_path) }}" target="_blank" class="d-block mb-2 a-txt">
                                                        <i class="fa-solid fa-file-lines"></i>
                                                        {{ $file->original_file_name ?? 'Untitled File' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(isAdmin())
                                <div class="editBox">
                                    <a href="{{route('tender.add', ['id' => $tender->id])}}" class="btn editBtn">INFOS BEARBEITEN</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection



