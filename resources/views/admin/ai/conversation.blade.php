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
                <div id="chatBox">
                    <!-- Messages will appear here -->
                </div>
                <div class="typeMsgBox" style="margin-top: 10px;">
                    <textarea id="newQuestion" rows="4" cols="50" placeholder="Type your message..."></textarea><br>
                    <button class="btn btnTelegram" id="send" style="margin-top: 5px;">
                        <img src="{{$baseUrl}}images/send-Icon.png" alt="send-Icon">
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>

    const chatBox = document.getElementById("chatBox");
    const newQuestionInput = document.getElementById("newQuestion");
    const sendButton = document.getElementById("send");
    const OPENAI_API_KEY = "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";

    // Load chat history from sessionStorage
    function loadChatHistory() {
        const history = sessionStorage.getItem("chatHistory");
        if (history) {
            chatBox.innerHTML = history;
        }
    }

    // Save chat history to sessionStorage
    function saveChatHistory() {
        sessionStorage.setItem("chatHistory", chatBox.innerHTML);
    }

    // Fetch response from ChatGPT API
    async function getChatGPTResponse(question) {
        const response = await fetch("https://api.openai.com/v1/chat/completions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${OPENAI_API_KEY}`,
            },
            body: JSON.stringify({
                model: "gpt-3.5-turbo",
                messages: [{ role: "user", content: question }],
                max_tokens: 200,
            }),
        });

        if (!response.ok) {
            throw new Error("Failed to fetch response from ChatGPT");
        }

        const data = await response.json();
        return data.choices[0].message.content.trim();
    }

    // Add a new message to the chat box
    async function addMessage(question) {
        const userMsg = `<div class="questionMsg"><div class="msgText"><strong>Question:</strong> ${question}</div></div>`;
        chatBox.innerHTML += userMsg;

        try {
            const botResponse = await getChatGPTResponse(question);
            const botMsg = `<div><strong>Response:</strong> ${botResponse}</div>`;
            chatBox.innerHTML += botMsg;
        } catch (error) {
            const errorMsg = `<div class="responseMsg"><div class="msgText"><strong>ChatGPT:</strong> Failed to get a response. Please try again later.</div></div>`;
            chatBox.innerHTML += errorMsg;
            console.error(error);
        }

        saveChatHistory(); // Save updated chat history
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the latest message
    }

    // Handle sending a new message
    sendButton.addEventListener("click", () => {
        const newQuestion = newQuestionInput.value.trim();
        if (newQuestion !== "") {
            addMessage(newQuestion);
            newQuestionInput.value = ""; // Clear input field
        } else {
            alert("Please enter a message!");
        }
    });

    // Load initial question (if passed from the previous page)
    const initialQuestion = sessionStorage.getItem("chatQuestion");
    if (initialQuestion) {
        sessionStorage.removeItem("chatQuestion"); // Remove the initial question after use
        sessionStorage.removeItem("chatHistory");
        addMessage(initialQuestion); // Add the initial question to the chat
    }

    // Load chat history on page load
    loadChatHistory();
</script>
@endsection

