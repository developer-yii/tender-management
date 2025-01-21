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
                <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{$fileUrl}}" width="100%" style="min-height: 1000px;"></iframe>
                {{-- <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=https://dev2.amcodr.co/tender-management/public/storage/certificates/certificate1/67811f2837400_1736515368.docx" width="100%" style="min-height: 1000px;"></iframe> --}}
            </div>
        </div>
    </div>
</section>
@endsection


