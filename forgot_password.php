<?php
session_start();

include "db.php";
require "mailer.php";

$message = "";

if (isset($_POST['send_otp'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check = mysqli_query($conn, "SELECT * FROM userss WHERE email='$email'");

    if (mysqli_num_rows($check) == 1) {

        // ৬ সংখ্যার OTP
        $otp = rand(100000, 999999);

        // ৫ মিনিটের জন্য OTP Valid
        $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        // Database Update
        mysqli_query($conn, "
            UPDATE userss
            SET
                otp='$otp',
                otp_expire='$expire'
            WHERE email='$email'
        ");

        // Email Send
        if (sendOTP($email, $otp)) {

            // Email Session এ রাখলাম
            $_SESSION['reset_email'] = $email;

            header("Location: verify_otp.php");
            exit();

        } else {

            $message = "<span style='color:red;font-weight:bold;'>Failed to send OTP!</span>";

        }

    } else {

        $message = "<span style='color:red;font-weight:bold;'>Email not found!</span>";

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Forgot Password</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<h2>Forgot Password</h2>

<p><?php echo $message; ?></p>

<div class="theme-box">
    <label><b>🎨 Theme:</b></label>

    <select id="themeSwitcher">
        <option value="">🌙 Dark</option>
        <option value="light">☀ Light</option>
        <option value="matrix">💚 Matrix</option>
        <option value="purple">💜 Purple</option>
    </select>
</div>

<form method="POST">

Email

<br><br>

<input
type="email"
name="email"
required>

<br><br>

<button
type="submit"
name="send_otp">

Send OTP

</button>

</form>

<br>

<a href="login.php">← Back to Login</a>

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