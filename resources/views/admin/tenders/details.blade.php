@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Zart Details ')
@section('content')
    <section class="mainSection">
        <div class="homeSectionPart">
            <div class="mainTenderDetails">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="propertySec">
                            <div class="imgbox">
                                <img src="{{ getTenderMainImage($tender) }}" alt="{{$tender->tender_name}}">
                            </div>
                            <div class="textDetails">
                                <div class="propName">
                                    <h6>{{ $tender->tender_name }}</h6>
                                </div>
                                <ul>
                                    <li>
                                        <p class="gray">Ausführungszeitraum</p>
                                        <p>{{ formatDate($tender->period_from, 'm/Y') }} bis {{ formatDate($tender->period_to, 'm/Y') }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Ablauf Angebotsfrist</p>
                                        <p>{{ formatDate($tender->offer_period_expiration, 'd.m.Y | h:i') ?? 'No expiration set' }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->offer_period_expiration) }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Bindefrist</p>
                                        <p>{{ formatDate($tender->binding_period, 'd.m.Y') ?? '' }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->binding_period) }}</p>
                                    </li>
                                    <li>
                                        <p class="gray">Bewerberfragen bis</p>
                                        <p>{{ formatDate($tender->question_ask_last_date, 'd.m.Y | h:i') }}</p>
                                        <p>{{ getRemainingDaysMessage($tender->question_ask_last_date) }}</p>
                                    </li>
                                </ul>
                                <div class="ourThreeBox">
                                    <div class="firstBox box-box">
                                        <p>BIS ZUM ENDE DES ANGEBOTFRIST</p>
                                        <h6>{{ getRemainingDays($tender->offer_period_expiration) }}</h6>
                                        <span>Tage</span>
                                    </div>
                                    <div class="secondBox box-box">
                                        <h6>STATUS</h6>
                                        <img src="{{ $tender->tenderStatus ? $tender->tenderStatus->getIconUrl() : '' }}" alt="{{ $tender->tenderStatus ? $tender->tenderStatus->title : 'status' }}">
                                        <p>{{ $tender->tenderStatus ? $tender->tenderStatus->title : 'Unknown' }}</p>
                                    </div>
                                    <div class="thirdBox box-box">
                                        <p>VERANTWORTLICHE PERSON</p>
                                        @foreach($tender->users as $user)
                                            <div class="newBox m-b-10">
                                                <img src="{{ $user->getProfilePicUrl() }}" alt="{{ $user->first_name}}">
                                                <h6>{{$user->first_name}} {{$user->last_name}}</h6>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="collectDocumentSec">
                            <div class="docTitle">
                                <h5>Gesammelte Dokumente: </h5>
                            </div>
                            <div class="docListOur">
                                <ul>
                                    @foreach($documentFiles as $document)
                                        <li>
                                            <p>
                                                <a href="{{ $document->getFilePathUrl() }}" target="_blank" class="d-block mb-2"><i class="fa-solid fa-file-lines"></i>{{ $document->original_file_name }}</a>
                                                <img src="{{$baseUrl}}images/chatgpt.png" class="chat-gpt" data-file-path="{{ $document->getFilePathUrl() }}" data-chat-box-id="chatBox-{{ $document->id }}">
                                                <div class="chatGptBox" id="chatBox-{{ $document->id }}" style="display: none;">
                                                    <!-- Messages will appear here -->
                                                </div>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                                @if(count($documentFiles) > 10)
                                    <div class="openDocBtn">
                                        <button class="btn btnOpen"><i class="bi bi-caret-down-fill"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="service_provider">
                            <div class="titleBox">
                                <h5>{{ $tender->title }}</h5>
                                <p>{{ $tender->description }}</p>
                            </div>
                            <div class="serviceDetails">
                                <div class="lefDetail">
                                    <h6>Vergabestelle</h6>
                                    <p>{{ $tender->vergabestelle }}</p>
                                    <div class="devliverBox">
                                        <h6>Abgabeform</h6>
                                        <a href="javascript:void(0)" class="btn btnDetails cursor-default">
                                            {{ isset($tender->abgabeformValue) ? $tender->abgabeformValue->name : 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                                <div class="rightMap">
                                    <h6>Ausführungsort</h6>
                                    <div class="mapTextNew">
                                        <p>{{ $tender->place_of_execution }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="awardDetails">
                                <div class="awardtitle">
                                    <h5>Vergabe</h5>
                                </div>
                                <ul>
                                    <li>
                                        <p>Vergabeordnung</p>
                                        <span>{{ $tender->procurement_regulations }}</span>
                                    </li>
                                    <li>
                                        <p>Vergabeverfahren</p>
                                        <span>{{ $tender->procurement_procedures }}</span>
                                    </li>
                                    <li>
                                        <p>Unterteilung in Lose</p>
                                        <span>{{ $tender->is_subdivision_lots ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                    <li>
                                        <p>Nebenangebote zulässig</p>
                                        <span>{{ $tender->is_side_offers_allowed ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                    <li>
                                        <p>Mehrere Hauptangebote zul.</p>
                                        <span>{{ $tender->is_main_offers_allowed ? 'Yeah' : 'Nein' }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="fileSection">
                                <div class="accordion" id="accordionExample">
                                    @foreach ($folder_files as $folder_name => $files)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button cursor-default" type="button">
                                                    {{ $folder_name ?? 'Unnamed Folder' }}
                                                </button>
                                            </h2>
                                            <div class="accordion-body">
                                                @foreach ($files as $file)
                                                    <div class="colAppsBox">
                                                        <a href="{{ $file->getFilePathUrl() }}" target="_blank" class="d-block mb-2">
                                                            <i class="fa-solid fa-file-lines"></i>
                                                            {{ $file->original_file_name ?? 'Untitled File' }}
                                                        </a>
                                                        <img src="{{$baseUrl}}images/chatgpt.png" class="chat-gpt" data-file-path="{{ $file->getFilePathUrl() }}" data-chat-box-id="chatBox-{{ $file->id }}">
                                                    </div>
                                                    <div class="chatGptBox" id="chatBox-{{ $file->id }}" style="display: none;">
                                                        <!-- Messages will appear here -->
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(isAdmin())
                                <div class="editBox">
                                    <a href="{{route('tender.add', ['id' => $tender->id])}}" class="btn editBtn">INFOS BEARBEITEN</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/4.0.2/tesseract.min.js"></script>
<script>
    const OPENAI_API_KEY = "{{ $openaiApiKey }}";
    const MAX_FILE_CONTENT_LENGTH = 10000; // Maximum characters for file content
    const MAX_FILE_SIZE_MB = 1; // Maximum file size in MB

    document.querySelectorAll(".chat-gpt").forEach((image) => {
        image.addEventListener("click", async function () {
            const filePath = image.getAttribute("data-file-path");
            const question = "Fassen Sie den wichtigsten Teil dieses Dokuments zusammen";
            const chatBoxId = image.getAttribute("data-chat-box-id");
            const chatBox = document.getElementById(chatBoxId);
            chatBox.style.display = "block";


            // Fetch the file content asynchronously
            const fileDetails = {
                fileName: filePath.split('/').pop(), // Extract file name from the path
                fileContent: await handleFileUpload(filePath, question), // Fetch the file content
            };

            // Call the function to send the message
            await addMessage(question, fileDetails, chatBox);
        });
    });


    function truncateContent(content, limit) {
        return content.length > limit ? content.substring(0, limit) + "... [Content truncated]" : content;
    }

    // Adjusting the file upload handler to handle file paths (URLs)
    async function handleFileUpload(filePath, question) {
        try {
            // Fetch the file as a Blob
            const response = await fetch(filePath);
            const fileBlob = await response.blob();

            // Create a FileReader to read the file content
            const reader = new FileReader();

            return new Promise((resolve, reject) => {
                reader.onload = async (event) => {
                    let fileContent = event.target.result;

                    // If the file is a PDF, extract text from it
                    if (filePath.endsWith(".pdf")) {
                        fileContent = await extractTextFromPDF(fileBlob);
                    } else if (filePath.endsWith(".docx")) {
                        fileContent = await extractTextFromDOCX(fileBlob);
                    } else if (filePath.endsWith(".txt")) {
                        fileContent = await file.text();
                    } else if (filePath.endsWith(".csv")) {
                        fileContent = await extractTextFromCSV(fileBlob);
                    } else if (filePath.endsWith(".xls") || filePath.endsWith(".xlsx")) {
                        fileContent = await extractTextFromExcel(fileBlob);
                    } else if (file.type.startsWith("image/")) {
                        fileContent = await extractTextFromImage(fileBlob);
                    }

                    const truncatedContent = truncateContent(fileContent, MAX_FILE_CONTENT_LENGTH);
                    resolve(truncatedContent);  // Resolve the promise with the truncated content
                };

                reader.onerror = (error) => {
                    reject(new Error(`File read error: ${error.message}`));
                };

                // Read the file as text
                reader.readAsText(fileBlob);
            });
        } catch (error) {
            console.error("Error in file upload:", error);
            return ""; // Return empty content on error
        }
    }

    // Function to extract text from PDF (using pdf.js)
    async function extractTextFromPDF(file) {
        const pdf = await pdfjsLib.getDocument(URL.createObjectURL(file)).promise;
        let text = '';
        for (let i = 1; i <= pdf.numPages; i++) {
            const page = await pdf.getPage(i);
            const content = await page.getTextContent();
            text += content.items.map(item => item.str).join(' ') + '\n';
        }
        return text;
    }

    // Function to extract text from DOCX (using mammoth.js or similar)
    async function extractTextFromDOCX(file) {
        const arrayBuffer = await file.arrayBuffer();
        const result = await mammoth.extractRawText({ arrayBuffer });
        return result.value;
    }

    async function extractTextFromCSV(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const csvText = event.target.result;
                const parsedData = Papa.parse(csvText, { header: true });
                resolve(JSON.stringify(parsedData.data, null, 2)); // Convert to readable JSON
            };
            reader.onerror = reject;
            reader.readAsText(file);
        });
    }


    async function extractTextFromExcel(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: "array" });
                let text = "";

                workbook.SheetNames.forEach(sheetName => {
                    const sheet = workbook.Sheets[sheetName];
                    text += XLSX.utils.sheet_to_csv(sheet) + "\n";
                });

                resolve(text);
            };
            reader.onerror = reject;
            reader.readAsArrayBuffer(file);
        });
    }

    async function extractTextFromImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = async (event) => {
                const imageData = event.target.result;
                const { data: { text } } = await Tesseract.recognize(imageData, "eng");
                resolve(text);
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    // ChatGPT integration (same as the original code you provided)
    async function addMessage(question, fileDetails = null, chatBox) {
        let userMsg = `<div class="questionMsg"><div class="msgText"><strong>Frage:</strong> ${question}</div></div>`;

        if (fileDetails) {
            userMsg += `<div class="questionMsg"><div class="msgText"><strong>Hochgeladene Datei:</strong> ${fileDetails.fileName}</div></div>`;
        }

        chatBox.innerHTML += userMsg;

        try {
            let combinedInput = `User Question:\n${question}\n\nDocument Content:\n${fileDetails ? fileDetails.fileContent : ""}`;

            // Truncate content if too long
            combinedInput = truncateContentToFitTokens(combinedInput);

            const botResponse = await getChatGPTResponse(combinedInput);
            const botMsg = `<div class="responseMsg"><div class="msgText"><strong>Antwort:</strong> ${botResponse}</div></div>`;
            chatBox.innerHTML += botMsg;
        } catch (error) {
            const errorMsg = `<div class="responseMsg"><div class="msgText"><strong>Fehler:</strong> ${error.message}</div></div>`;
            chatBox.innerHTML += errorMsg;
            console.error(error);
        }

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function getChatGPTResponse(inputText) {
        const response = await fetch("https://api.openai.com/v1/chat/completions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${OPENAI_API_KEY}`,
            },
            body: JSON.stringify({
                model: "gpt-3.5-turbo",
                messages: [{ role: "user", content: inputText }],
                max_tokens: 500,
            }),
        });

        if (!response.ok) {
            const errorDetails = await response.text();
            throw new Error(`HTTP Error: ${response.status} - ${errorDetails}`);
        }

        const data = await response.json();
        return data.choices[0].message.content.trim();
    }

    function truncateContentToFitTokens(content) {
        const maxContentLength = 4000; // You can adjust this based on token count limitations
        const truncatedContent = content.length > maxContentLength
            ? content.substring(0, maxContentLength) + "... [Content truncated]"
            : content;
        return truncatedContent;
    }

</script>
@endsection



