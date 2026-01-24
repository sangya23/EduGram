/* ================= CONFIGURATION ================= */
const API_KEY = addYourGroqApiKeyHere; // Replace with your Groq API Key
const API_URL = "https://api.groq.com/openai/v1/chat/completions";

/* ================= DOM ELEMENTS ================= */
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatBox = document.querySelector(".chat-box");

/* ================= FUNCTIONS ================= */

// Function to create a chat message <li>
const createChatLi = (message, className) => {
    const chatLi = document.createElement("li");
    chatLi.classList.add("chat", className);
    
    // Add icon for incoming messages
    let chatContent = className === "outgoing" 
        ? `<p></p>` 
        : `<span class="material-icons">smart_toy</span><p></p>`;
    
    chatLi.innerHTML = chatContent;
    chatLi.querySelector("p").textContent = message;
    return chatLi;
}

// Function to fetch response from Groq API
const generateResponse = async (incomingChatLi) => {
    const messageElement = incomingChatLi.querySelector("p");

    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${API_KEY}`
        },
        body: JSON.stringify({
            model: "openai/gpt-oss-120b", // The model from your Groq playground
            messages: [{ role: "user", content: userMessage }],
            stream: false,
            temperature: 1,
            max_tokens: 1024
        })
    }

    // Send Request
    try {
        const res = await fetch(API_URL, requestOptions);
        const data = await res.json();
        
        if (data.error) {
            // Handle errors safely
            const errorMessage = data.error.message || JSON.stringify(data.error);
            messageElement.textContent = "Error: " + errorMessage;
            messageElement.style.color = "#ff4b4b"; 
            console.error("API Error:", data);
        } else {
            // Success: Get the message content
            messageElement.textContent = data.choices[0].message.content.trim();
        }
    } catch (error) {
        messageElement.textContent = "Network error. Please check your connection.";
        messageElement.style.color = "#e94560";
        console.error("Fetch Error:", error);
    }
    
    chatBox.scrollTo(0, chatBox.scrollHeight);
}

// Main logic to handle user input
const handleChat = () => {
    userMessage = chatInput.value.trim();
    if (!userMessage) return;

    // Append User Message
    chatInput.value = "";
    chatBox.appendChild(createChatLi(userMessage, "outgoing"));
    chatBox.scrollTo(0, chatBox.scrollHeight);

    // Append "Thinking..." Bubble
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatBox.appendChild(incomingChatLi);
    chatBox.scrollTo(0, chatBox.scrollHeight);

    // Call API
    generateResponse(incomingChatLi);
}

/* ================= TOGGLE LOGIC (FIXED) ================= */
// This makes the chat button work
const toggleChatbot = () => {
    document.body.classList.toggle("show-chatbot");
}

/* ================= LISTENERS ================= */
sendChatBtn.addEventListener("click", handleChat);

// Allow pressing "Enter" to send
chatInput.addEventListener("keydown", (e) => {
    if(e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleChat();
    }
});

// Expose toggleChatbot to the HTML onclick attribute
window.toggleChatbot = toggleChatbot;