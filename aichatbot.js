const API_KEY = "// Replace with your Groq API Key"; // Replace with your Groq API Key
const API_URL = "https://api.groq.com/openai/v1/chat/completions";


const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatBox = document.querySelector(".chat-box");


const createChatLi = (message, className) => {
    const chatLi = document.createElement("li");
    chatLi.classList.add("chat", className);
    
    let chatContent = className === "outgoing" 
        ? `<p></p>` 
        : `<span class="material-icons">smart_toy</span><p></p>`;
    
    chatLi.innerHTML = chatContent;
    chatLi.querySelector("p").textContent = message;
    return chatLi;
}

const generateResponse = async (incomingChatLi) => {
    const messageElement = incomingChatLi.querySelector("p");

    const requestOptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${API_KEY}`
        },
        body: JSON.stringify({
            model: "openai/gpt-oss-120b", 
            messages: [{ role: "user", content: userMessage }],
            stream: false,
            temperature: 1,
            max_tokens: 1024
        })
    }

    try {
        const res = await fetch(API_URL, requestOptions);
        const data = await res.json();
        
        if (data.error) {
           
            const errorMessage = data.error.message || JSON.stringify(data.error);
            messageElement.textContent = "Error: " + errorMessage;
            messageElement.style.color = "#ff4b4b"; 
            console.error("API Error:", data);
        } else {
            
            messageElement.textContent = data.choices[0].message.content.trim();
        }
    } catch (error) {
        messageElement.textContent = "Network error. Please check your connection.";
        messageElement.style.color = "#e94560";
        console.error("Fetch Error:", error);
    }
    
    chatBox.scrollTo(0, chatBox.scrollHeight);
}


const handleChat = () => {
    userMessage = chatInput.value.trim();
    if (!userMessage) return;

    
    chatInput.value = "";
    chatBox.appendChild(createChatLi(userMessage, "outgoing"));
    chatBox.scrollTo(0, chatBox.scrollHeight);

   
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatBox.appendChild(incomingChatLi);
    chatBox.scrollTo(0, chatBox.scrollHeight);

   
    generateResponse(incomingChatLi);
}


const toggleChatbot = () => {
    document.body.classList.toggle("show-chatbot");
}


sendChatBtn.addEventListener("click", handleChat);


chatInput.addEventListener("keydown", (e) => {
    if(e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleChat();
    }
});


window.toggleChatbot = toggleChatbot;