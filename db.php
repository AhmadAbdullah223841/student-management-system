<?php
// ডেটাবেজ কানেকশন ফাইল
$conn = mysqli_connect(
    "sql111.infinityfree.com", //hostname
    "if0_42379709",//username
    "ohWxZ0bVpFeWZF", //password
    "if0_42379709_student"//Database name
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>