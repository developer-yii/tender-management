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
                @if ($references->isNotEmpty())
                    @foreach($tags as $tag)
                        @if($tag->references->isNotEmpty())
                            <div class="referancePart" id="{{ $tag->name }}">
                                <div class="titleBox">
                                    <h5>{{ $tag->name }}</h5>
                                </div>
                                <div class="projectType">
                                    @foreach ($tag->references as $reference)
                                        @include('admin.reference.reference_card_partial', ['reference' => $reference])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if($referencesWithoutTags->isNotEmpty())
                        <div class="referancePart" id="no_tags">
                            <div class="titleBox">
                                <h5>References Without Tags</h5>
                            </div>
                            <div class="projectType">
                                @foreach ($referencesWithoutTags as $reference)
                                    @include('admin.reference.reference_card_partial', ['reference' => $reference])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="referancePart" id="no_tags">
                        <div class="titleBox">
                            <h5>No Data Found</h5>
                        </div>
                    </div>
                @endif

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
<script src="{{ $baseUrl }}custom/js/reference.js"></script>
@endsection

