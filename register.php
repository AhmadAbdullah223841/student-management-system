<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";

$message = "";

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Password Hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Email already exists?
    $check = mysqli_prepare($conn, "SELECT id FROM userss WHERE email=?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if(mysqli_stmt_num_rows($check) > 0){

        $message = "<span style='color:red;font-weight:bold;'>Email already exists!</span>";

    }else{

        mysqli_stmt_close($check);

        $insert = mysqli_prepare($conn, "INSERT INTO userss (name, email, password) VALUES (?, ?, ?)");

        mysqli_stmt_bind_param($insert, "sss", $name, $email, $hashed_password);

        if(mysqli_stmt_execute($insert)){

            header("Location: login.php");
            exit();

        }else{

            $message = "<span style='color:red;font-weight:bold;'>Registration Failed! ".mysqli_error($conn)."</span>";

        }

        mysqli_stmt_close($insert);

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="form-container">

<h2>Register</h2>

<?php
if($message!=""){
    echo "<p style='text-align:center;'>".$message."</p>";
}
?>

<form method="POST">

<label>Name</label><br>
<input type="text" name="name" required>

<br><br>

<label>Email</label><br>
<input type="email" name="email" required>

<br><br>

<label>Password</label><br>
<input type="password" name="password" required>

<br><br>

<button type="submit" name="register">
Register
</button>

</form>

<br>

<p style="text-align:center;">
Already have an account?
<a href="login.php" style="color:#00ffff;">
Login
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