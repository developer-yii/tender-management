@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')

<section class="mainSection">
    <div class="homeSectionPart">
        <div class="referanceDetailsSec">
            <div class="addCommonBtn newAdd">
                <a href="{{ route('reference.index') }}" class="btn btnBack"><i class="bi bi-chevron-double-left"></i> Zurück Zu Allen</a>
                <button class="btn btnAdd edit-reference" data-bs-toggle="modal" data-bs-target="#addReferenceModal" data-id="{{$reference->id}}"><i class="fa-solid fa-plus"></i> Referenz hinzufügen</button>
            </div>
            <div class="refInnerDetail">
                <div class="titleBox">
                    <h5>{{$reference->project_title}}</h5>
                </div>
                <div class="spaceBox"></div>
                <div class="multyTagsBox">
                    <div class="titleBox">
                        <h5>Suche Mitarbeiter nach Tag</h5>
                    </div>
                    <div class="multyBtntag">
                        @foreach($tags as $tag)
                            <a href="{{route('reference.index')}}#{{$tag->name}}" class="btn btnCommonTag">{{$tag->name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.reference-modal')
@endsection

@section('js')
<script>
    var createReferenceUrl = "{{ route('reference.addupdate') }}";
    var getReferenceUrl = "{{ route('reference.detail') }}";
    var referenceListUrl = "{{ route('reference.index') }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/references.js"></script>
<script src="{{ $baseUrl }}custom/js/reference-details.js"></script>
@endsection

