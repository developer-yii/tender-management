@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Referenzen ')
@section('content')
    <section class="mainSection">
        <div class="homeSectionPart">
            <div class="referanceMainSec">
                <div class="addCommonBtn">
                    <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addReferenceModal" ><i class="fa-solid fa-plus"></i> Referenz hinzufügen</button>
                </div>
                @foreach($tags as $tag)
                    @if($tag->references->isNotEmpty())
                        <div class="referancePart" id="{{ $tag->name }}">
                            <div class="titleBox">
                                <h5>{{ $tag->name }}</h5>
                            </div>
                            <div class="projectType">
                                @foreach ($tag->references as $reference)
                                    <div class="projectDetailsBox">
                                        <h6>Projekt: {{ $reference->project_title }}</h6>
                                        <div class="projectBox">
                                            <p>Umfang:</p>
                                            <pre class="pre">{{ $reference->scope }}</pre>
                                            <p class="date">Leistungszeitraum:
                                                <span>{{formatDateRange($reference->start_date, $reference->end_date)}}</span>
                                            </p>
                                            <a href="{{ route('reference.details', [$reference->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="referancePart" id="no_tags">
                    <div class="titleBox">
                        <h5>References Without Tags</h5>
                    </div>
                    <div class="projectType">
                        @foreach ($referencesWithoutTags as $reference)
                            <div class="projectDetailsBox">
                                <h6>Projekt: {{ $reference->project_title }}</h6>
                                <div class="projectBox">
                                    <p>Umfang:</p>
                                    <pre>{{ $reference->scope }}</pre>
                                    <p class="date">Leistungszeitraum: <span>{{ $reference->start_date }}–{{ $reference->end_date }}</span></p>
                                    <a href="{{ route('reference.details', [$reference->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                                </div>
                            </div>
                        @endforeach
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
    var referenceListUrl = "{{ route('reference.index') }}";
</script>
<script src="{{ $baseUrl }}custom/js/common.js"></script>
<script src="{{ $baseUrl }}custom/js/references.js"></script>
@endsection

