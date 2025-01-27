<section class="mainSection">
    <div class="homeSectionPart">
        <div class="aitools_mainSec">
            <!-- Notebook LM Section -->
            <div class="toolsInner">
                <div class="aiTitle">
                    <h5>Notebook LM</h5>
                </div>
                <div id="notebookChatBox" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: auto;">
                    <!-- Messages will appear here -->
                </div>
                <div class="typeMsgBox" style="margin-top: 10px;">
                    <textarea id="notebookQuestion" rows="4" cols="50" placeholder="Chat starten..."></textarea><br>
                    <button class="btn btnTelegram" id="notebookSend" style="margin-top: 5px;">
                        <img src="{{$baseUrl}}images/send-Icon.png" alt="send-Icon">
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const notebookChatBox = document.getElementById("notebookChatBox");
    const notebookQuestionInput = document.getElementById("notebookQuestion");
    const notebookSendButton = document.getElementById("notebookSend");

    const NOTEBOOK_API_KEY = "your-api-key-here";  // Replace with actual API key for Notebook LM

    // Load chat history from sessionStorage
    function loadNotebookHistory() {
        const history = sessionStorage.getItem("notebookChatHistory");
        if (history) {
            notebookChatBox.innerHTML = history;
        }
    }

    // Save chat history to sessionStorage
    function saveNotebookHistory() {
        sessionStorage.setItem("notebookChatHistory", notebookChatBox.innerHTML);
    }

    // Fetch response from Notebook LM API
    async function getNotebookLMResponse(question) {
        const response = await fetch("https://api.notebooklm.com/v1/chat/completions", { // Replace with the actual Notebook LM API URL
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${NOTEBOOK_API_KEY}`,
            },
            body: JSON.stringify({
                model: "notebook-lm", // Replace with the correct model name for Notebook LM
                messages: [{ role: "user", content: question }],
                max_tokens: 200,
            }),
        });

        if (!response.ok) {
            throw new Error("Failed to fetch response from Notebook LM");
        }

        const data = await response.json();
        return data.choices[0].message.content.trim();
    }

    // Add a new message to the chat box
    async function addNotebookMessage(question) {
        const userMsg = `<div><strong>You:</strong> ${question}</div>`;
        notebookChatBox.innerHTML += userMsg;

        try {
            const botResponse = await getNotebookLMResponse(question);
            const botMsg = `<div><strong>Notebook LM:</strong> ${botResponse}</div>`;
            notebookChatBox.innerHTML += botMsg;
        } catch (error) {
            const errorMsg = `<div><strong>Notebook LM:</strong> Failed to get a response. Please try again later.</div>`;
            notebookChatBox.innerHTML += errorMsg;
            console.error(error);
        }

        saveNotebookHistory(); // Save updated chat history
        notebookChatBox.scrollTop = notebookChatBox.scrollHeight; // Scroll to the latest message
    }

    // Handle sending a new message
    notebookSendButton.addEventListener("click", () => {
        const newQuestion = notebookQuestionInput.value.trim();
        if (newQuestion !== "") {
            addNotebookMessage(newQuestion);
            notebookQuestionInput.value = ""; // Clear input field
        } else {
            alert("Please enter a message!");
        }
    });

    // Load chat history on page load
    loadNotebookHistory();

    // Clear the session history when the page is unloaded or refreshed
    window.addEventListener("beforeunload", () => {
        sessionStorage.removeItem("notebookChatHistory");
    });
</script>
