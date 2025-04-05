// const chatBox = document.getElementById("chatBox");
// const newQuestionInput = document.getElementById("question");
// const fileInput = document.getElementById("fileInput");
// const sendButton = document.getElementById("send");

// const MAX_FILE_CONTENT_LENGTH = 10000; // Maximum characters for file content
// const MAX_FILE_SIZE_MB = 1; // Maximum file size in MB

// document.getElementById('fileInput').addEventListener('change', function() {
//     var fileText = document.getElementById('fileText');
//     if (this.files.length > 0) {
//         fileText.textContent = this.files[0].name; // Display the selected file name
//     }
// });

// // Clear previous chat history on page refresh
// sessionStorage.removeItem("chatHistory");


// // Load chat history from sessionStorage
// function loadChatHistory() {
//     const history = sessionStorage.getItem("chatHistory");
//     if (history) {
//         chatBox.innerHTML = history;
//     }
// }

// // Save chat history to sessionStorage
// function saveChatHistory() {
//     sessionStorage.setItem("chatHistory", chatBox.innerHTML);
// }

// function truncateContent(content, limit) {
//     return content.length > limit ? content.substring(0, limit) + "... [Content truncated]" : content;
// }

// // Fetch response from ChatGPT API
// async function getChatGPTResponse(inputText) {
//     const response = await fetch("https://api.openai.com/v1/chat/completions", {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/json",
//             "Authorization": `Bearer ${OPENAI_API_KEY}`,
//         },
//         body: JSON.stringify({
//             model: "gpt-3.5-turbo",
//             messages: [{ role: "user", content: inputText }],
//             max_tokens: 500,
//         }),
//     });

//     if (!response.ok) {
//         const errorDetails = await response.text();
//         throw new Error(`HTTP Error: ${response.status} - ${errorDetails}`);
//     }
//     const data = await response.json();
//     return data.choices[0].message.content.trim();
// }

// // Add a new message to the chat box
// async function addMessage(question, fileDetails = null) {
//     let userMsg = `<div class="questionMsg"><div class="msgText"><strong>Question:</strong> ${question}</div></div>`;

//     if (fileDetails) {
//         userMsg += `<div class="questionMsg"><div class="msgText"><strong>Uploaded File:</strong> ${fileDetails.fileName}</div></div>`;
//     }

//     chatBox.innerHTML += userMsg;

//     try {
//         let combinedInput = `User Question:\n${question}\n\nDocument Content:\n${fileDetails ? fileDetails.fileContent : ""}`;

//         const botResponse = await getChatGPTResponse(combinedInput);
//         const botMsg = `<div class="responseMsg"><div class="msgText"><strong>Response:</strong> ${botResponse}</div></div>`;
//         chatBox.innerHTML += botMsg;
//     } catch (error) {
//         const errorMsg = `<div class="responseMsg"><div class="msgText"><strong>Error:</strong> ${error.message}</div></div>`;
//         chatBox.innerHTML += errorMsg;
//         console.error(error);
//     }

//     saveChatHistory();
//     chatBox.scrollTop = chatBox.scrollHeight;
// }

// // Handle sending a new message
// sendButton.addEventListener("click", () => {
//     const newQuestion = newQuestionInput.value.trim();
//     const selectedFile = fileInput.files[0];
//     const fileText = document.getElementById("fileText");

//     if (newQuestion === "" && !selectedFile) {
//         alert("Please enter a message or upload a file!");
//         return;
//     }

//     if (selectedFile) {
//         handleFileUpload(selectedFile, newQuestion);
//     } else {
//         addMessage(newQuestion);
//     }

//     newQuestionInput.value = ""; // Clear input field
//     fileInput.value = ""; // Clear file input
//     fileText.textContent = "";
// });

// // Handle file upload
// function handleFileUpload(file, question) {
//     if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
//         alert("The file is too large. Please upload a file smaller than 1 MB.");
//         return;
//     }

//     const reader = new FileReader();

//     reader.onload = async (event) => {
//         let fileContent = event.target.result;

//         // If the file is a PDF, extract text from it
//         if (file.name.endsWith(".pdf")) {
//             fileContent = await extractTextFromPDF(file);
//         } else if (file.name.endsWith(".docx")) {
//             fileContent = await extractTextFromDOCX(file);
//         } else if (file.name.endsWith(".txt")) {
//             fileContent = await file.text();
//         } else if (file.name.endsWith(".csv")) {
//             fileContent = await extractTextFromCSV(file);
//         } else if (file.name.endsWith(".xls") || file.name.endsWith(".xlsx")) {
//             fileContent = await extractTextFromExcel(file);
//         } else if (file.type.startsWith("image/")) {
//             fileContent = await extractTextFromImage(file);
//         }

//         const truncatedContent = truncateContent(fileContent, MAX_FILE_CONTENT_LENGTH);
//         const fileDetails = {
//             fileName: file.name,
//             fileContent: truncatedContent,
//         };

//         addMessage(question || "No question provided", fileDetails);
//     };

//     reader.onerror = (error) => {
//         const errorMsg = `<div class="responseMsg"><strong>Error:</strong> Unable to read file: ${error.message}</div>`;
//         chatBox.innerHTML += errorMsg;
//         console.error("File read error:", error);
//     };

//     // Read the file as text
//     reader.readAsText(file);
// }

// // Function to extract text from PDF (using pdf.js)
// async function extractTextFromPDF(file) {
//     const pdf = await pdfjsLib.getDocument(URL.createObjectURL(file)).promise;
//     let text = '';
//     for (let i = 1; i <= pdf.numPages; i++) {
//         const page = await pdf.getPage(i);
//         const content = await page.getTextContent();
//         text += content.items.map(item => item.str).join(' ') + '\n';
//     }
//     return text;
// }

// // Function to extract text from DOCX (using mammoth.js or similar)
// async function extractTextFromDOCX(file) {
//     const arrayBuffer = await file.arrayBuffer();
//     const result = await mammoth.extractRawText({ arrayBuffer });
//     return result.value;
// }

// async function extractTextFromCSV(file) {
//     return new Promise((resolve, reject) => {
//         const reader = new FileReader();
//         reader.onload = (event) => {
//             const csvText = event.target.result;
//             const parsedData = Papa.parse(csvText, { header: true });
//             resolve(JSON.stringify(parsedData.data, null, 2)); // Convert to readable JSON
//         };
//         reader.onerror = reject;
//         reader.readAsText(file);
//     });
// }


// async function extractTextFromExcel(file) {
//     return new Promise((resolve, reject) => {
//         const reader = new FileReader();
//         reader.onload = (event) => {
//             const data = new Uint8Array(event.target.result);
//             const workbook = XLSX.read(data, { type: "array" });
//             let text = "";

//             workbook.SheetNames.forEach(sheetName => {
//                 const sheet = workbook.Sheets[sheetName];
//                 text += XLSX.utils.sheet_to_csv(sheet) + "\n";
//             });

//             resolve(text);
//         };
//         reader.onerror = reject;
//         reader.readAsArrayBuffer(file);
//     });
// }

// async function extractTextFromImage(file) {
//     return new Promise((resolve, reject) => {
//         const reader = new FileReader();
//         reader.onload = async (event) => {
//             const imageData = event.target.result;
//             const { data: { text } } = await Tesseract.recognize(imageData, "eng");
//             resolve(text);
//         };
//         reader.onerror = reject;
//         reader.readAsDataURL(file);
//     });
// }

// // Load initial question (if passed from the previous page)
// const initialQuestion = sessionStorage.getItem("chatQuestion");
// if (initialQuestion) {
//     sessionStorage.removeItem("chatQuestion");
//     addMessage(initialQuestion);
// }

// // Load chat history on page load
// loadChatHistory();


const chatBox = document.getElementById("chatBox");
const newQuestionInput = document.getElementById("question");
const fileInput = document.getElementById("fileInput");
const sendButton = document.getElementById("send");

const MAX_FILE_CONTENT_LENGTH = 10000; // Zeichenbegrenzung
const MAX_FILE_SIZE_MB = 1; // Dateigröße in MB

document.getElementById('fileInput').addEventListener('change', function () {
    var fileText = document.getElementById('fileText');
    if (this.files.length > 0) {
        fileText.textContent = this.files[0].name;
    }
});

// Chatverlauf beim Laden löschen
sessionStorage.removeItem("chatHistory");

function loadChatHistory() {
    const history = sessionStorage.getItem("chatHistory");
    if (history) {
        chatBox.innerHTML = history;
    }
}

function saveChatHistory() {
    sessionStorage.setItem("chatHistory", chatBox.innerHTML);
}

function truncateContent(content, limit) {
    return content.length > limit ? content.substring(0, limit) + "... [Inhalt wurde gekürzt]" : content;
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
        throw new Error(`HTTP Fehler: ${response.status} - ${errorDetails}`);
    }
    const data = await response.json();
    return data.choices[0].message.content.trim();
}

async function addMessage(question, fileDetails = null) {
    let userMsg = `<div class="questionMsg"><div class="msgText"><strong>Frage:</strong> ${question}</div></div>`;

    if (fileDetails) {
        userMsg += `<div class="questionMsg"><div class="msgText"><strong>Hochgeladene Datei:</strong> ${fileDetails.fileName}</div></div>`;
    }

    chatBox.innerHTML += userMsg;

    try {
        const germanInput = `Bitte beantworte die folgende Frage auf Deutsch.\n\nFrage:\n${question}\n\nDokumentinhalt:\n${fileDetails ? fileDetails.fileContent : ""}`;
        const botResponse = await getChatGPTResponse(germanInput);
        const botMsg = `<div class="responseMsg"><div class="msgText"><strong>Antwort:</strong> ${botResponse}</div></div>`;
        chatBox.innerHTML += botMsg;
    } catch (error) {
        const errorMsg = `<div class="responseMsg"><div class="msgText"><strong>Fehler:</strong> ${error.message}</div></div>`;
        chatBox.innerHTML += errorMsg;
        console.error(error);
    }

    saveChatHistory();
    chatBox.scrollTop = chatBox.scrollHeight;
}

sendButton.addEventListener("click", () => {
    const newQuestion = newQuestionInput.value.trim();
    const selectedFile = fileInput.files[0];
    const fileText = document.getElementById("fileText");

    if (newQuestion === "" && !selectedFile) {
        alert("Bitte eine Nachricht eingeben oder eine Datei hochladen!");
        return;
    }

    if (selectedFile) {
        handleFileUpload(selectedFile, newQuestion);
    } else {
        addMessage(newQuestion);
    }

    newQuestionInput.value = "";
    fileInput.value = "";
    fileText.textContent = "";
});

function handleFileUpload(file, question) {
    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
        alert("Die Datei ist zu groß. Bitte laden Sie eine Datei unter 1 MB hoch.");
        return;
    }

    const reader = new FileReader();

    reader.onload = async (event) => {
        let fileContent = event.target.result;

        if (file.name.endsWith(".pdf")) {
            fileContent = await extractTextFromPDF(file);
        } else if (file.name.endsWith(".docx")) {
            fileContent = await extractTextFromDOCX(file);
        } else if (file.name.endsWith(".txt")) {
            fileContent = await file.text();
        } else if (file.name.endsWith(".csv")) {
            fileContent = await extractTextFromCSV(file);
        } else if (file.name.endsWith(".xls") || file.name.endsWith(".xlsx")) {
            fileContent = await extractTextFromExcel(file);
        } else if (file.type.startsWith("image/")) {
            fileContent = await extractTextFromImage(file);
        }

        const truncatedContent = truncateContent(fileContent, MAX_FILE_CONTENT_LENGTH);
        const fileDetails = {
            fileName: file.name,
            fileContent: truncatedContent,
        };

        addMessage(question || "Keine Frage eingegeben", fileDetails);
    };

    reader.onerror = (error) => {
        const errorMsg = `<div class="responseMsg"><strong>Fehler:</strong> Datei konnte nicht gelesen werden: ${error.message}</div>`;
        chatBox.innerHTML += errorMsg;
        console.error("Dateifehler:", error);
    };

    reader.readAsText(file);
}

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
            resolve(JSON.stringify(parsedData.data, null, 2));
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
            const { data: { text } } = await Tesseract.recognize(imageData, "deu"); // OCR auf Deutsch
            resolve(text);
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

const initialQuestion = sessionStorage.getItem("chatQuestion");
if (initialQuestion) {
    sessionStorage.removeItem("chatQuestion");
    addMessage(initialQuestion);
}

loadChatHistory();
