@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Zertifizierungen ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addCertificateModal"><i class="fa-solid fa-plus"></i> Zertifizierung hinzufügen</button>
        </div>
        @foreach ($categoriesWithCertificates as $category => $certificates)
            <div class="certificationsMain">
                <div class="titleBox">
                    <h5>{{ $category }}</h5> <!-- Display the category name -->
                </div>
                <div class="ceritesBgBox">
                    @foreach ($certificates as $certificate)
                        <div class="comBox">
                            <div class="topBox">
                                <div class="leftsec">
                                    <h6>{{ $certificate->title }}</h6>
                                    <p>{{ $certificate->description }}</p>
                                </div>
                                <div class="rightSec">
                                    <img src="{{ $certificate->getLogoUrl() }}" alt="{{ $certificate->title }}">
                                </div>
                            </div>
                            <div class="endBox">
                                <p>Gültig vom {{ formatDateToGerman($certificate->valid_from_date) }} bis {{ formatDateToGerman($certificate->valid_to_date) }}</p>
                                <a href="{{ route('certificate.details', [$certificate->id]) }}" class="btn btnDetails">
                                    DETAILS ANSEHEN
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{-- @foreach ($categories as $category)
            @if ($category->certificates->isNotEmpty())
                <div class="certificationsMain">
                    <div class="titleBox">
                        <h5>{{$category->name}}</h5>
                    </div>
                    <div class="ceritesBgBox">
                        @foreach ($category->certificates as $certificate)
                            <div class="comBox">
                                <div class="topBox">
                                    <div class="leftsec">
                                        <h6>{{$certificate->title}}</h6>
                                        <p>{{ $certificate->description }}</p>
                                    </div>
                                    <div class="rightSec">
                                        <img src="{{$certificate->getLogoUrl()}}" alt="{{$certificate->title}}">
                                    </div>
                                </div>
                                <div class="endBox">
                                    <p>Gültig vom {{ $certificate->valid_from_date }} bis {{ $certificate->valid_to_date }}</p>
                                    <a href="{{route('certificate.details', [$certificate->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach --}}
    </div>
</section>
@endsection

@section('modal')
    @include('include.certificate-modal')
@endsection

@section('js')
    <script>
        var createCertificateUrl = "{{ route('certificate.addupdate') }}";
        var getCertificateUrl = "{{ route('certificate.detail') }}";
        var certificateListUrl = "{{ route('certificate.index') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/common.js"></script>
    <script src="{{ $baseUrl }}custom/js/certificates.js"></script>
@endsection


