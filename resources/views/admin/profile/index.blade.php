@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="employsSection">
            <div class="row">
                <p class="myProfile">Mein Profil</p>
                <div class="col-lg-5">
                    <div class="profiledetails">
                        <div class="mainSec">
                            <div class="user-img">
                                <img src="{{ isAdmin() ? $user->getAdminProfilePicUrl() :$user->getProfilePicUrl() }}" alt="{{$user->first_name}}">
                            </div>
                            <div class="textSec">
                                <div class="topText">
                                    <h6>{{$user->first_name}} {{$user->last_name}}</h6>
                                    <span>{{$user->email}}</span>
                                </div>
                                <div class="bottomText">
                                    <p>{{$user->description}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="btncenterSec">
                            @if(isEmployee())
                                <button class="btn btnCom" id="previewBtn">
                                    <span><img src="{{$baseUrl}}images/eye-icon.png" alt="eye-icon"></span> LEBENSLAUF VORSCHAU
                                </button>

                                @foreach (['cv' => 'LEBENSLAUF', 'document' => 'DOKUMENT'] as $type => $label)
                                    <button class="btn btnCom" id="download{{ ucfirst($type) }}">
                                        <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> {{ $label }} HERUNTERLADEN
                                        <a id="download-{{ $type }}" href="{{ $user->{$type . '_url'} }}" download style="display:none;"></a>
                                    </button>
                                @endforeach
                            @endif

                                <a href="{{route('profile.edit')}}" class="btn btnGray">
                                    INFOS BEARBEITEN
                                </a>
                        </div>

                        @if(isEmployee() && $user->tags->isNotEmpty())
                            <div class="multyBnt">
                                @foreach($user->tags as $userTag)
                                    <button class="btn btnCommonTag cursor-default">{{ $userTag->name }}</button>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
                @if(isEmployee())
                    <div class="col-lg-7">
                        <div class="markusTender">
                            <div class="tenderAll">
                                <div class="titleBox">
                                    <h5>{{$user->first_name}} {{$user->last_name}} Ausschreibungen:</h5>
                                </div>

                                <ul>
                                    @if($user->tenders->isNotEmpty())
                                    @foreach($user->tenders as $userTender)
                                        <li>
                                            <div class="statusIcon">
                                                <img src="{{ $userTender->tenderStatus->getIconUrl() }}" alt="{{ $userTender->tenderStatus->title }}">
                                            </div>
                                            <div class="text">
                                                <p>{{$userTender->tender_name}}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                    @else
                                        <li>
                                            <div class="statusIcon"></div>
                                            <div class="text">
                                                <p>- No Tender Assign</p>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.preview-modal')
@endsection
@section('js')
<script>
    var createUrl = "{{ route('employee.addupdate') }}";
    var listUrl = "{{ route('employee.index') }}";
    var fileUrl = "{{ $user->getCvUrl() }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/employees.js"></script>
@endsection

