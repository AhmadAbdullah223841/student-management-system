<?php
session_start();

include "db.php";

// OTP Verify না হলে এখানে আসতে পারবে না
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified'])) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";
$email = $_SESSION['reset_email'];

if (isset($_POST['reset'])) {

    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    // Empty Check
    if (empty($password) || empty($confirm)) {

        $message = "<span style='color:red;font-weight:bold;'>All fields are required!</span>";

    }

    // Minimum Password Length
    elseif (strlen($password) < 6) {

        $message = "<span style='color:red;font-weight:bold;'>Password must be at least 6 characters!</span>";

    }

    // Password Match
    elseif ($password !== $confirm) {

        $message = "<span style='color:red;font-weight:bold;'>Passwords do not match!</span>";

    }

    else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = mysqli_query($conn,"
            UPDATE userss
            SET
                password='$hash',
                otp=NULL,
                otp_expire=NULL
            WHERE email='$email'
        ");

        if ($update) {

            // Session Remove
            unset($_SESSION['reset_email']);
            unset($_SESSION['otp_verified']);

            session_regenerate_id(true);

            echo "<script>
                    alert('Password Reset Successfully!');
                    window.location='login.php';
                  </script>";
            exit();

        } else {

            $message = "<span style='color:red;font-weight:bold;'>Failed to reset password!</span>";

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Reset Password</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<h2>🔒 Reset Password</h2>

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

    New Password

    <br><br>

    <input
        type="password"
        name="password"
        placeholder="Enter New Password"
        minlength="6"
        required>

    <br><br>

    Confirm Password

    <br><br>

    <input
        type="password"
        name="confirm_password"
        placeholder="Confirm Password"
        minlength="6"
        required>

    <br><br>

    <button
        type="submit"
        name="reset">

        Reset Password

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