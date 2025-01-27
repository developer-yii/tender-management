@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="previewSec">
            <div class="addCommonBtn">
                <h5>Vorschau</h5>
                <a href="{{ asset('storage/mergedFile/' . $fileUrl) }}" class="btn btnAdd" id="download-pdf" download>
                    <span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="Download Pdf"></span> PDF HERUNTERLADEN
                </a>
            </div>
            <div class="previewBox">
                <iframe src="{{ asset('storage/mergedFile/' . $fileUrl) }}"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection


