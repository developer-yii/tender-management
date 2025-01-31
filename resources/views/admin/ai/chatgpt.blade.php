@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="aitools_mainSec chatBoxCustom">
            <div class="toolsInner">
                <div class="aiTitle">
                    <h5>Chat GPT</h5>
                </div>
                <div id="chatBox" class="chatGptBox">
                    <!-- Messages will appear here -->
                </div>
                <div class="typeMsgBox" style="margin-top: 10px;">
                    <textarea id="question" rows="6" cols="50" placeholder="Chat starten..."></textarea><br>
                    <div class="filerImpotbtn">
                        <div id="fileText"></div>
                        <input type="file" class="fileBtn" id="fileInput">
                        <i class="fa-solid fa-paperclip"></i>
                    </div>
                    <div class="sendBtn">
                        <button class="btn btnTelegram" id="send"><img src="{{$baseUrl}}images/send-Icon.png" alt="send-Icon"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    const OPENAI_API_KEY = "{{ $openaiApiKey }}";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/4.0.2/tesseract.min.js"></script>
<script src="{{ $baseUrl }}custom/js/chatgpt.js"></script>
@endsection

