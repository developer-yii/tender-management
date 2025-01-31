@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Vorlage ')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
</script>
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Vorlage hinzufügen</button>
        </div>
        @if ($templetes->isNotEmpty())
            <div class="templetsSecMain">
                @foreach ($templetes as $templete)
                    <div class="tepmletBox">
                        <div class="titleBox">
                            <h5>{{ $templete->title }}</h5>
                        </div>
                        <div class="chartImg">
                            @if($templete->isPdf())
                            <canvas id="preview-{{$templete->id}}" data-url="{{ $templete->getTempleteFileUrl() }}" style="height: 180px; width: 50%;"></canvas>
                            @elseif($templete->isImage())
                                <img src="{{ $templete->getTempleteFileUrl() }}" alt="Image preview" style="height: 150px; width: 100%; object-fit: cover;">
                            @else
                                <img src="{{ $baseUrl }}images/no-preview.png" alt="No preview available" style="">
                            @endif
                        </div>
                        <div class="templeBtn">
                            <button class="btn btnDetails download-btn" data-url="{{ $templete->getTempleteFileUrl() }}">
                                HERUNTERLADEN
                            </button>
                            <a href="{{ route('templete.details', [$templete->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="document_confirmSec">
                <div class="insurance_confirm">
                    <div class="titleBox">
                        <h5>{{ trans('message.data not found') }}</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
@section('modal')
    @include('include.templete-modal')
@endsection
@section('js')
<script>
    var createUrl = "{{ route('templete.addupdate') }}";
    var listUrl = "{{ route('templete.index') }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/templetes.js"></script>
@endsection

