@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="aitools_mainSec">
            <div class="toolsInner">
                <div class="aiTitle">
                    <h5>Notebook LM</h5>
                </div>
                <div class="typeMsgBox">
                    <textarea name="" id="" placeholder="Chat starten..."></textarea>
                    <button class="btn btnTelegram"><img src="{{$baseUrl}}images/send-Icon.png" alt="send-Icon"></button>
                </div>
            </div>
            <div class="toolsInner">
                <div class="aiTitle">
                    <h5>Chat GPT</h5>
                </div>
                <div class="typeMsgBox">
                    <textarea id="question" rows="6" cols="50" placeholder="Chat starten..."></textarea><br>
                    <button class="btn btnTelegram" id="submit"><img src="{{$baseUrl}}images/send-Icon.png" alt="send-Icon"></button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    var redirectUrl = "{{ route('ai.conversation') }}";
</script>
<script src="{{ $baseUrl }}custom/js/chatgpt.js"></script>
@endsection

