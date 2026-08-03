<?php
// সেশন চালু করা (যদি ড্যাশবোর্ডের সাথে কানেক্টেড রাখতে চান)
session_start();

// API Key সেট করুন
$api_key = "YOUR_API_KEY_HERE"; // আপনার Gemini API Key এখানে বসান

$response_text = "";
$user_message = "";

// ইউজার যখন মেসেজ সাবমিট করবে
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['message'])) {
    $user_message = htmlspecialchars($_POST['message']);
    
    // Gemini API URL (Gemini 1.5 Flash মডেলটি দ্রুত এবং ফ্রি)
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;
    
    // API-তে পাঠানোর জন্য ডেটা ফরম্যাট
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $user_message]
                ]
            ]
        ]
    ];
    
    // cURL এর মাধ্যমে API রিকোয়েস্ট পাঠানো
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // রেসপন্স প্রসেস করা
    if ($response) {
        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $response_text = $result['candidates'][0]['content']['parts'][0]['text'];
        } else {
            $response_text = "Sorry, I didn't understand that.";
        }
    } else {
        $response_text = "API get error! Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot</title>
    <!-- আপনার প্রজেক্টের স্টাইলশীট লিংক করা হলো -->
    <link rel="stylesheet" href="style.css"> 
    <style>
        .chat-container { width: 50%; margin: 50px auto; border: 1px solid #ccc; padding: 20px; border-radius: 10px; background: #f9f9f9; }
        .chat-box { max-height: 400px; overflow-y: auto; margin-bottom: 20px; padding: 10px; background: #fff; border-radius: 5px; border: 1px solid #ddd; }
        .user-msg { background: #d1ecf1; color: #0c5460; padding: 8px 12px; border-radius: 15px; margin: 5px 0; text-align: right; }
        .ai-msg { background: #e2e3e5; color: #383d41; padding: 8px 12px; border-radius: 15px; margin: 5px 0; text-align: left; }
        .input-group { display: flex; }
        .input-group input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px 0 0 5px; }
        .input-group button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 0 5px 5px 0; cursor: pointer; }
    </style>
</head>
<body>

<div class="chat-container">
    <h2>AI Chatbot Assistant</h2>
    <hr>

    <div class="theme-box">
    <label><b>🎨 Theme:</b></label>

    <select id="themeSwitcher">
        <option value="">🌙 Dark</option>
        <option value="light">☀ Light</option>
        <option value="matrix">💚 Matrix</option>
        <option value="purple">💜 Purple</option>
    </select>
</div>
    
    <div class="chat-box">
        <!-- যদি ইউজার মেসেজ পাঠিয়ে থাকে তবে তা দেখাবে -->
        <?php if (!empty($user_message)): ?>
            <div class="user-msg"><strong>You:</strong> <?php echo $user_message; ?></div>
        <?php endif; ?>
        
        <!-- AI এর উত্তর দেখাবে -->
        <?php if (!empty($response_text)): ?>
            <div class="ai-msg"><strong>AI:</strong> <?php echo nl2br(htmlspecialchars($response_text)); ?></div>
        <?php else: ?>
            <div class="ai-msg"><strong>AI:</strong> Hello! How can I help you?</div>
        <?php endif; ?>
    </div>
    
    <!-- মেসেজ পাঠানোর ফর্ম -->
    <form action="chatbot.php" method="POST" class="input-group">
        <input type="text" name="message" placeholder="Type your message here..." required autocomplete="off">
        <button type="submit">Send</button>
    </form>
    
    <p style="margin-top: 15px;"><a href="dashboard.php">Back to Dashboard</a></p>
</div>
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