
document.getElementById("submit").addEventListener("click", function () {
    const question = document.getElementById("question").value;
    if (question.trim() !== "") {
        // localStorage.setItem("chatQuestion", question);
        sessionStorage.setItem("chatQuestion", question);
        window.location.href = redirectUrl;
    } else {
        alert("Please enter a question!");
    }
});


