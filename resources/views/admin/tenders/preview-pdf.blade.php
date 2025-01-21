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
                <button class="btn btnAdd"><span><img src="{{$baseUrl}}images/downloads-Folder.png" alt="downloads-Folder"></span> WORD HERUNTERLADEN</button>
            </div>
            <div class="previewBox">
                <iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url={{$fileUrl}}" width="100%" height ="1000px">
                </iframe>

                <iframe src="{{$fileUrl}}" width="100%" height ="1000px"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection


