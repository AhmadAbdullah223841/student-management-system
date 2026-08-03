<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>

    <link rel="stylesheet" href="style.css">

    <style>
        .person-photo{
            width:45px;
            height:45px;
            object-fit:cover;
            border-radius:5px;
            border:1px solid #000;
        }

        #search{
            width:350px;
            padding:10px;
            border:2px solid #008b8b;
            border-radius:5px;
            font-size:15px;
            margin-top:15px;
            margin-bottom:20px;
        }

        .top-links a{
            display:inline-block;
            margin-right:10px;
            padding:8px 14px;
            background:#008b8b;
            color:#fff;
            text-decoration:none;
            border-radius:5px;
            font-weight:bold;
        }

        .top-links a:hover{
            background:#006666;
        }
        
        .theme-box {
            margin-top: 15px;
        }
    </style>
</head>

<body>

<h2>🎓 My Students List</h2>

<div class="top-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_student.php">Add Student</a>
    <a href="chatbot.php">AI Chatbot</a>
</div>

<div class="theme-box">
    <label><b>Theme:</b></label>
    <select id="themeSwitcher">
        <option value="">Dark</option>
        <option value="light">Light</option>
        <option value="matrix">Matrix</option>
        <option value="purple">Purple</option>
    </select>
</div>

<div class="theme-box" style="margin-top: 10px;">
    <label><b>Notifications:</b></label>
    <select id="notiSwitcher" onchange="toggleNotification(this.value)">
        <option value="default">Ask/Default</option>
        <option value="granted">Allow</option>
        <option value="denied">Block</option>
    </select>
</div>

<br>

<input
type="text"
id="search"
placeholder="🔍 Search Student by Name...">

<div id="studentTable">
</div>

<div id="chat-circle" onclick="toggleChat()">
    <h4><b>💬</b></h4>
</div>

<div id="chat-box">
    <div id="chat-header">
        <span>Joe AI Assistant</span>
        <strong style="cursor:pointer;" onclick="toggleChat()">X</strong>
    </div>
    
    <div id="chat-logs">
        <div class="msg ai">
            Hello, I'm your AI assistant. How can I help you today?
        </div>
    </div>
    
    <div id="chat-input-area">
        <input
        type="text"
        id="chat-input"
        placeholder="Write a message..."
        onkeypress="checkEnter(event)">
        <button onclick="sendMessage()">
            Send
        </button>
    </div>
</div>

<div id="downloadModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.7); z-index:9999;">
    <div style="background:#1b203a; border: 2px solid #00ffff; width:320px; margin:120px auto; padding:25px; border-radius:10px; text-align:center; color: #fff; box-shadow: 0 0 20px #00ffff;">
        <h3 style="margin-top: 0; color: #00ffff;">Download Student Info</h3>
        
        <select id="downloadFormat" style="width:100%; padding:10px; margin:15px 0; background: #111; color: #fff; border: 1px solid #00ffff; border-radius: 5px;">
            <option value="pdf">PDF</option>
            <option value="csv">CSV</option>
            <option value="json">JSON</option>
        </select>
        
        <button onclick="downloadStudents()" style="background: #00ffff; border: none; padding: 10px 20px; font-weight: bold; cursor: pointer; border-radius: 5px; margin-right: 10px; color: #000;">Download</button>
        <button onclick="closeDownloadModal()" style="background: #333; border: 1px solid #777; color: #fff; padding: 10px 20px; cursor: pointer; border-radius: 5px;">Cancel</button>
    </div>
</div>

<script>
//==========================
// CHATBOT SCRIPTS
//==========================
function toggleChat() {
    var chatBox = document.getElementById("chat-box");
    chatBox.style.display = (chatBox.style.display === "flex") ? "none" : "flex";
}

function checkEnter(event) {
    if(event.key==="Enter") {
        sendMessage();
    }
}

function sendMessage(){
    var inputField=document.getElementById("chat-input");
    var message=inputField.value.trim();
    if(message=="") return;

    var chatLogs=document.getElementById("chat-logs");
    chatLogs.innerHTML += "<div class='msg user'>"+message+"</div>";
    inputField.value="";
    chatLogs.scrollTop=chatLogs.scrollHeight;

    var typingId="typing"+Date.now();
    chatLogs.innerHTML += "<div class='msg ai' id='"+typingId+"'>Thinking...</div>";
    chatLogs.scrollTop=chatLogs.scrollHeight;

    var apiKey="YOUR_API_KEY_HERE"; // এখানে আপনার জেমিনি এপিআই কি বসান

    fetch(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key="+apiKey,
        {
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({
                contents:[
                    {
                        parts:[
                            {
                                text:message
                            }
                        ]
                    }
                ]
            })
        }
    )
    .then(response=>response.json())
    .then(data=>{
        document.getElementById(typingId).remove();
        if(data.candidates){
            chatLogs.innerHTML +=
            "<div class='msg ai'>"+
            data.candidates[0].content.parts[0].text+
            "</div>";
        }
        else{
            chatLogs.innerHTML +=
            "<div class='msg ai'>No response.</div>";
        }
        chatLogs.scrollTop=chatLogs.scrollHeight;
    })
    .catch(()=>{
        document.getElementById(typingId).innerHTML="API Error";
    });
}

//==========================
// LIVE SEARCH SCRIPTS
//==========================
function loadData(page=1){
    let search=document.getElementById("search").value;
    let xhr=new XMLHttpRequest();

    xhr.open(
        "GET",
        "search_students.php?page="+page+"&search="+encodeURIComponent(search),
        true
    );

    xhr.onload=function(){
        document.getElementById("studentTable").innerHTML=this.responseText;
    }
    xhr.send();
}

document.addEventListener("DOMContentLoaded",function(){
    loadData();
    document.getElementById("search").addEventListener("keyup",function(){
        loadData();
    });
});

//==================================
// THEME SWITCHER & DOWNLOAD SCRIPTS
//==================================
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

var currentStudentId = 0;

function openDownloadModal(id){
    currentStudentId = id;
    document.getElementById("downloadModal").style.display = "block";
}

function closeDownloadModal(){
    document.getElementById("downloadModal").style.display = "none";
}

function downloadStudents(){
    let format = document.getElementById("downloadFormat").value;
    if(currentStudentId != 0) {
        window.open("export.php?id=" + currentStudentId + "&format=" + format, "_blank");
        
        // ট্রিগার নোটিফিকেশন: ডাউনলোড সফল হলে পুশ নোটিফিকেশন শো করবে
        showWebNotification("⚡ Download Started", "Student data template requested in " + format.toUpperCase() + " format!");
    }
    closeDownloadModal();
}

//==================================
// NOTIFICATION SYSTEM (ALLOW/BLOCK)
//==================================
const notiSwitcher = document.getElementById("notiSwitcher");

document.addEventListener("DOMContentLoaded", function() {
    let savedNotiStatus = localStorage.getItem("noti_permission") || "default";
    notiSwitcher.value = savedNotiStatus;
    
    if (Notification.permission === "denied") {
        localStorage.setItem("noti_permission", "denied");
        notiSwitcher.value = "denied";
    }
});

function toggleNotification(status) {
    if (status === "granted") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                localStorage.setItem("noti_permission", "granted");
                showWebNotification("System Notification", "Notifications have been successfully enabled! 🎉");
            } else {
                localStorage.setItem("noti_permission", "denied");
                notiSwitcher.value = "denied";
                alert("Notification permission denied by browser.");
            }
        });
    } else if (status === "denied") {
        localStorage.setItem("noti_permission", "denied");
        alert("Notifications have been blocked.");
    } else {
        localStorage.setItem("noti_permission", "default");
    }
}

function showWebNotification(title, message) {
    let allowedStatus = localStorage.getItem("noti_permission");
    if ("Notification" in window && Notification.permission === "granted" && allowedStatus === "granted") {
        new Notification(title, {
            body: message,
            icon: "uploads/default.png"
        });
    }
}

// PHP সেশন থেকে ডাইনামিক নোটিফিকেশন মেসেজ হ্যান্ডেল করা
<?php
if (isset($_SESSION['notify_msg']) && isset($_SESSION['notify_title'])) {
    $title = htmlspecialchars($_SESSION['notify_title']);
    $msg = htmlspecialchars($_SESSION['notify_msg']);
    
    echo "
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            showWebNotification('$title', '$msg');
        }, 800);
    });
    ";
    
    unset($_SESSION['notify_title']);
    unset($_SESSION['notify_msg']);
}
?>
</script>

<style>
/* চ্যাটবট ও মোডালের অতিরিক্ত স্টাইল */
#chat-circle{
    position:fixed;
    bottom:20px;
    right:20px;
    background:#00ffff;
    color:#000;
    width:60px;
    height:60px;
    border-radius:50%;
    text-align:center;
    line-height:60px;
    cursor:pointer;
    font-size:24px;
    font-weight:bold;
    box-shadow:0 0 15px #00ffff;
    z-index:999;
}
#chat-box{
    display:none;
    position:fixed;
    bottom:90px;
    right:20px;
    width:350px;
    height:430px;
    background:#111424;
    border:2px solid #00ffff;
    border-radius:10px;
    box-shadow:0 0 20px #00ffff;
    flex-direction:column;
    z-index:999;
}
#chat-header{
    background:#00ffff;
    color:#000;
    padding:12px;
    font-weight:bold;
    display:flex;
    justify-content:space-between;
}
#chat-logs{
    flex:1;
    padding:15px;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
    gap:12px;
    color:#fff;
}
.msg{
    padding:10px 14px;
    border-radius:10px;
    max-width:80%;
    font-size:14px;
    word-wrap:break-word;
}
.msg.user{
    background:#00ffff;
    color:#000;
    align-self:flex-end;
}
.msg.ai{
    background:#222;
    color:#fff;
    align-self:flex-start;
}
#chat-input-area{
    display:flex;
    padding:10px;
    background:#1a1f38;
    gap:8px;
}
#chat-input{
    flex:1;
    padding:10px;
    background:#111;
    border:1px solid #00ffff;
    color:#fff;
}
#chat-input-area button{
    background:#00ffff;
    border:none;
    padding:10px 18px;
    cursor:pointer;
    font-weight:bold;
    color:#000;
}
</style>

</body>
</html>