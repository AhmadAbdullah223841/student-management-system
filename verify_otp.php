<?php
session_start();
include "db.php";

// Email Session না থাকলে Forgot Password page এ পাঠাবে
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";
$email = $_SESSION['reset_email'];

if (isset($_POST['verify'])) {

    $otp = trim($_POST['otp']);

    // OTP অবশ্যই ৬ সংখ্যার হতে হবে
    if (!preg_match('/^[0-9]{6}$/', $otp)) {

        $message = "<span style='color:red;font-weight:bold;'>Please enter a valid 6-digit OTP!</span>";

    } else {

        $otp = mysqli_real_escape_string($conn, $otp);

        $sql = mysqli_query($conn, "
            SELECT *
            FROM userss
            WHERE email='$email'
            AND otp='$otp'
            AND otp_expire >= NOW()
        ");

        if (mysqli_num_rows($sql) == 1) {

            // OTP Verify Success
            $_SESSION['otp_verified'] = true;

            header("Location: reset_password.php");
            exit();

        } else {

            $message = "<span style='color:red;font-weight:bold;'>❌ Invalid or Expired OTP!</span>";

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Verify OTP</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<h2>🔐 Verify OTP</h2>

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

    Enter OTP

    <br><br>

    <input
        type="text"
        name="otp"
        maxlength="6"
        pattern="[0-9]{6}"
        placeholder="Enter 6-digit OTP"
        required>

    <br><br>

    <button
        type="submit"
        name="verify">

        Verify OTP

    </button>

</form>

<br>

<a href="forgot_password.php">← Back</a>

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