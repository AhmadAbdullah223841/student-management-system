<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">
    <h1>🎓 Student Management System</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
    <div class="theme-box">

    <label><b>Theme :</b></label>

    <select id="themeSwitcher">

    <option value="dark">Dark</option>

    <option value="light">Light</option>

    <option value="matrix">Matrix</option>

    <option value="purple">Purple</option>

</select>

</div>
    <br>
    <a href="add_student.php"><button>Add Student</button></a>
    <br><br>
    <a href="view_student.php"><button>View Students</button></a>
    <br><br>
    <!-- এখানে আপনার চাওয়া চ্যাটবট লিংক বাটনটি যুক্ত করা হলো -->
    <a href="chatbot.php"><button style="background-color: #00ffff; color: #000; font-weight: bold;">🤖 AI Chatbot Page</button></a>
    <br><br>
    <a href="logout.php"><button>Logout</button></a>
</div>

<!-- পপ-আপ চ্যাট বাবল বাটন -->
<div id="chat-circle" onclick="toggleChat()"><h4><b>💬</b></h4></div>

<!-- চ্যাট বক্স উইন্ডো -->
<div id="chat-box">
    <div id="chat-header">
        <span>Joe AI Assistant</span>
        <strong style="cursor:pointer;" onclick="toggleChat()">X</strong>
    </div>
    <div id="chat-logs">
        <div class="msg ai">Hello, I'm your AI assistant. How can I help you today?</div>
    </div>
    <div id="chat-input-area">
        <input type="text" id="chat-input" placeholder="Write a message..." onkeypress="checkEnter(event)">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<style>
    #chat-circle {
        position: fixed; bottom: 20px; right: 20px;
        background: #00ffff; color: #000; width: 60px; height: 60px;
        border-radius: 50%; text-align: center; line-height: 60px;
        font-size: 24px; cursor: pointer; font-weight: bold;
        box-shadow: 0 0 15px #00ffff; z-index: 999;
    }
    #chat-box {
        display: none; position: fixed; bottom: 90px; right: 20px;
        width: 350px; height: 430px; background: #111424;
        border: 2px solid #00ffff; border-radius: 10px;
        box-shadow: 0 0 20px #00ffff; flex-direction: column; z-index: 999;
    }
    #chat-header {
        background: #00ffff; color: #000; padding: 12px;
        font-weight: bold; display: flex; justify-content: space-between;
    }
    #chat-logs {
        flex: 1; padding: 15px; overflow-y: auto; color: #fff;
        display: flex; flex-direction: column; gap: 12px;
    }
    .msg {
        padding: 10px 14px; border-radius: 10px; max-width: 80%;
        font-size: 14px; line-height: 1.4; word-wrap: break-word;
    }
    .msg.user {
        background: #00ffff; color: #000; align-self: flex-end;
        border-radius: 10px 10px 0 10px;
    }
    .msg.ai {
        background: #222; color: #fff; align-self: flex-start;
        border: 1px solid #333; border-radius: 10px 10px 10px 0;
    }
    #chat-input-area {
        display: flex; padding: 10px; background: #1a1f38;
        border-top: 1px solid #333; gap: 8px; align-items: center;
    }
    #chat-input {
        flex: 1; background: #111; border: 1px solid #00ffff;
        color: #fff; padding: 10px; border-radius: 4px;
        height: 40px; box-sizing: border-box; font-size: 14px;
    }
    #chat-input-area button {
        background: #00ffff; border: none; padding: 0 18px;
        cursor: pointer; font-weight: bold; border-radius: 4px;
        height: 40px; color: #000; font-size: 14px; display: flex; align-items: center; justify-content: center;
    }
</style>

<script>
    function toggleChat() {
        var chatBox = document.getElementById("chat-box");
        chatBox.style.display = (chatBox.style.display === "flex") ? "none" : "flex";
    }

    function checkEnter(event) {
        if (event.key === "Enter") sendMessage();
    }

    function sendMessage() {
        var inputField = document.getElementById("chat-input");
        var message = inputField.value.trim();
        if (message === "") return;

        var chatLogs = document.getElementById("chat-logs");
        chatLogs.innerHTML += `<div class="msg user">${message}</div>`;
        inputField.value = "";
        chatLogs.scrollTop = chatLogs.scrollHeight;

        var typingId = "typing-" + Date.now();
        chatLogs.innerHTML += `<div class="msg ai" id="${typingId}">Thinking...</div>`;
        chatLogs.scrollTop = chatLogs.scrollHeight;

        // আপনার জেমিনি এপিআই কি এখানে বসান
        var apiKey = "YOUR_API_KEY_HERE"; // এখানে আপনার জেমিনি এপিআই কি বসান
        var url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" + apiKey;

        var data = {
            "contents": [{"parts": [{"text": message}]}]
        };

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('API Error!');
            return response.json();
        })
        .then(resData => {
            var typingElement = document.getElementById(typingId);
            if(typingElement) typingElement.remove();
            
            if (resData.candidates && resData.candidates[0].content.parts[0].text) {
                var aiReply = resData.candidates[0].content.parts[0].text;
                chatLogs.innerHTML += `<div class="msg ai">${aiReply}</div>`;
            } else {
                chatLogs.innerHTML += `<div class="msg ai">No answer, bro!</div>`;
            }
            chatLogs.scrollTop = chatLogs.scrollHeight;
        })
        .catch(error => {
            var typingElement = document.getElementById(typingId);
            if(typingElement) typingElement.innerText = "Error: " + error.message;
        });
    }
</script>
<script>

const switcher=document.getElementById("themeSwitcher");

const savedTheme=localStorage.getItem("theme");

if(savedTheme){

document.body.className=savedTheme;

switcher.value=savedTheme;

}

switcher.addEventListener("change",function(){

document.body.className=this.value;

localStorage.setItem("theme",this.value);

});

</script>
</body>
</html>