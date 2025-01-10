document.getElementById('submit').addEventListener('click', async () => {
  const question = document.getElementById('question').value;
  const responseDiv = document.getElementById('response');

  if (!question.trim()) {
    responseDiv.innerText = "Please enter a question.";
    return;
  }

  responseDiv.innerText = "Loading...";

  try {
    const response = await fetch("https://api.openai.com/v1/chat/completions", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer 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`, // Replace with your OpenAI API key
      },
      body: JSON.stringify({
        model: "gpt-3.5-turbo", // Specify the model you want to use
        messages: [{ role: "user", content: question }],
        max_tokens: 10000,
      }),
    });

    if (!response.ok) {
      throw new Error(`Error: ${response.statusText}`);
    }

    const data = await response.json();
    const reply = data.choices[0]?.message?.content || "No response from ChatGPT.";
    responseDiv.innerText = reply;
  } catch (error) {
    responseDiv.innerText = `Error: ${error.message}`;
  }
});
