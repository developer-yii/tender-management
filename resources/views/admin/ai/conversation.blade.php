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
                    <h5>Chat GPT</h5>
                </div>
                <div id="chatBox" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: auto;">
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
        const userMsg = `<div><strong>Question:</strong> ${question}</div>`;
        chatBox.innerHTML += userMsg;

        try {
            const botResponse = await getChatGPTResponse(question);
            const botMsg = `<div><strong>Response:</strong> ${botResponse}</div>`;
            chatBox.innerHTML += botMsg;
        } catch (error) {
            const errorMsg = `<div><strong>ChatGPT:</strong> Failed to get a response. Please try again later.</div>`;
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

    // Clear chat history when the page is closed
    // window.addEventListener("beforeunload", () => {
    //     sessionStorage.removeItem("chatHistory");
    // });

    // code 1
    // const chatBox = document.getElementById("chatBox");
    // const newQuestionInput = document.getElementById("newQuestion");
    // const sendButton = document.getElementById("send");
    // const OPENAI_API_KEY = "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";

    // // Retrieve chat history from localStorage
    // function loadChatHistory() {
    //     const history = localStorage.getItem("chatHistory");
    //     if (history) {
    //         chatBox.innerHTML = history;
    //     }
    // }

    // // Save chat history to localStorage
    // function saveChatHistory() {
    //     localStorage.setItem("chatHistory", chatBox.innerHTML);
    // }

    // // Function to get a response from ChatGPT
    // async function getChatGPTResponse(question) {
    //     const response = await fetch("https://api.openai.com/v1/chat/completions", {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //             "Authorization": `Bearer ${OPENAI_API_KEY}`,
    //         },
    //         body: JSON.stringify({
    //             model: "gpt-3.5-turbo",
    //             messages: [{ role: "user", content: question }],
    //             max_tokens: 200,
    //         }),
    //     });

    //     if (!response.ok) {
    //         throw new Error("Failed to fetch response from ChatGPT");
    //     }

    //     const data = await response.json();
    //     return data.choices[0].message.content.trim();
    // }

    // // Add new message to the chatBox and update localStorage
    // async function addMessage(question) {
    //     const userMsg = `<div><strong>You:</strong> ${question}</div>`;
    //     chatBox.innerHTML += userMsg;

    //     try {
    //         const botResponse = await getChatGPTResponse(question);
    //         const botMsg = `<div><strong>ChatGPT:</strong> ${botResponse}</div>`;
    //         chatBox.innerHTML += botMsg;
    //     } catch (error) {
    //         const errorMsg = `<div><strong>ChatGPT:</strong> Failed to get a response. Please try again later.</div>`;
    //         chatBox.innerHTML += errorMsg;
    //         console.error(error);
    //     }

    //     saveChatHistory(); // Save the updated history
    // }

    // // Event listener for sending a new message
    // sendButton.addEventListener("click", () => {
    //     const newQuestion = newQuestionInput.value.trim();
    //     if (newQuestion !== "") {
    //         addMessage(newQuestion);
    //         newQuestionInput.value = ""; // Clear the input box
    //     } else {
    //         alert("Please enter a message!");
    //     }
    // });

    // // Load chat history on page load
    // loadChatHistory();

    // code 2
    // const chatBox = document.getElementById("chatBox");
    // const initialQuestion = sessionStorage.getItem("chatQuestion");

    // // Your OpenAI API key
    // const OPENAI_API_KEY = "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";

    // // Function to get a response from ChatGPT via the OpenAI API
    // async function getChatGPTResponse(question) {
    //     const response = await fetch("https://api.openai.com/v1/chat/completions", {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //             "Authorization": `Bearer ${OPENAI_API_KEY}`,
    //         },
    //         body: JSON.stringify({
    //             model: "gpt-3.5-turbo", // Use the desired model
    //             messages: [{ role: "user", content: question }],
    //             max_tokens: 200,
    //         }),
    //     });

    //     if (!response.ok) {
    //         throw new Error("Failed to fetch response from ChatGPT");
    //     }

    //     const data = await response.json();
    //     return data.choices[0].message.content.trim();
    // }

    // // Add the initial question and response to the chatBox
    // if (initialQuestion) {
    //     const userMsg = `<div><strong>You:</strong> ${initialQuestion}</div>`;
    //     chatBox.innerHTML = userMsg;

    //     // Fetch ChatGPT response for the initial question
    //     getChatGPTResponse(initialQuestion)
    //         .then(botResponse => {
    //             const botMsg = `<div><strong>ChatGPT:</strong> ${botResponse}</div>`;
    //             chatBox.innerHTML += botMsg;
    //         })
    //         .catch(error => {
    //             const errorMsg = `<div><strong>ChatGPT:</strong> Failed to get a response. Please try again later.</div>`;
    //             chatBox.innerHTML += errorMsg;
    //             console.error(error);
    //         });
    // }

    // // Handle new messages
    // document.getElementById("send").addEventListener("click", async function () {
    //     const newQuestion = document.getElementById("newQuestion").value;
    //     if (newQuestion.trim() !== "") {
    //         const userMsg = `<div><strong>You:</strong> ${newQuestion}</div>`;
    //         chatBox.innerHTML += userMsg;

    //         // Fetch ChatGPT response for the new question
    //         try {
    //             const botResponse = await getChatGPTResponse(newQuestion);
    //             const botMsg = `<div><strong>ChatGPT:</strong> ${botResponse}</div>`;
    //             chatBox.innerHTML += botMsg;
    //         } catch (error) {
    //             const errorMsg = `<div><strong>ChatGPT:</strong> Failed to get a response. Please try again later.</div>`;
    //             chatBox.innerHTML += errorMsg;
    //             console.error(error);
    //         }

    //         document.getElementById("newQuestion").value = ""; // Clear the text box
    //     } else {
    //         alert("Please enter a message!");
    //     }
    // });
</script>
@endsection

