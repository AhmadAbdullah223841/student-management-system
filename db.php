<?php
// ডেটাবেজ কানেকশন ফাইল
$conn = mysqli_connect(
    "sql111.infinityfree.com",
    "if0_42379709",
    "ohWxZ0bVpFeWZF",
    "if0_42379709_student"
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>