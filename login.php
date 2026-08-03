<?php
session_start();

// =========================
// Already Logged In
// =========================
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// =========================
// Disable Browser Cache
// =========================
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";

$error_msg = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM userss WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            // Session
            $_SESSION['user'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];

            // Security
            session_regenerate_id(true);

            header("Location: dashboard.php");
            exit();

        }else{

            $error_msg = "Wrong Password!";

        }

    }else{

        $error_msg = "No user found with this email!";

    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link rel="stylesheet" href="style.css">

    <style>

        .error{
            color:#ff4a4a;
            font-weight:bold;
            text-align:center;
            margin-bottom:15px;
        }

    </style>

</head>

<body>

<div class="theme-box">
    <label><b>Theme:</b></label>

    <select id="themeSwitcher">
        <option value="">Dark</option>
        <option value="light">Light</option>
        <option value="matrix">Matrix</option>
        <option value="purple">Purple</option>
    </select>
</div>

<div class="form-container">

<h2>Login</h2>

<?php
if(!empty($error_msg)){
    echo "<div class='error'>$error_msg</div>";
}
?>

<form method="POST">

Email

<br>

<input
type="email"
name="email"
required>

<br><br>

Password

<br>

<input
type="password"
name="password"
required>

<br><br>

<button
type="submit"
name="login">

Login

</button>

<p style="text-align:center;margin-top:15px;">

<a href="forgot_password.php"
style="color:#00ffff;font-weight:bold;text-decoration:none;">

Forgot Password?

</a>

</p>

</form>

<br>

<p style="text-align:center;">

Don't have an account?

<a href="register.php"
style="color:#00ffff;">

Register

</a>

</p>

<p style="text-align:center;margin-top:10px;">

<a href="chatbot.php"
style="color:#1abc9c;font-weight:bold;text-decoration:none;">

🤖 Talk to AI Chatbot

</a>

</p>

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