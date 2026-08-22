<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION['user']) || !isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include "db.php";
$message = "";
if(isset($_POST['add'])){
    // SQL Injection থেকে বাঁচতে ডেটা এস্কেপ করা হলো
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : '';
    $user_id = $_SESSION['user_id'];

    // ছবির ফাইল হ্যান্ডেল করা
    $image_name = $_FILES['student_image']['name'];
    $image_tmp = $_FILES['student_image']['tmp_name'];
    $image_size = $_FILES['student_image']['size'];
    $image_error = $_FILES['student_image']['error'];

    $unique_image_name = "default.png"; 
    $upload_ok = true; 

    if (!empty($image_name)) {
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // সাইজ লিমিট ৩০০ মেগাবাইট (300 * 1024 * 1024 bytes)
        if ($image_size > 314572800) {
            $message = "<span style='color: red; font-weight: bold;'>Error: File size must be less than 300 MB!</span>";
            $upload_ok = false;
        }
        elseif ($image_error !== 0) {
            $message = "<span style='color: red; font-weight: bold;'>Error uploading your file! Code: $image_error</span>";
            $upload_ok = false;
        }
        
        if ($upload_ok) {
            $clean_name = str_replace(' ', '_', $name);
            $unique_image_name = time() . "_" . $clean_name . "." . $image_ext; 
            $upload_path = "uploads/" . $unique_image_name;
            
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            if (!move_uploaded_file($image_tmp, $upload_path)) {
                $message = "<span style='color: red; font-weight: bold;'>Error: Failed to move uploaded file!</span>";
                $upload_ok = false;
            }
        }
    }

    if ($upload_ok) {
        // টেবিলের নাম 'persons' করা হলো আপনার XAMPP অনুযায়ী
        $sql = "INSERT INTO persons (user_id, name, email, phone, department, semester, gender, image)
                VALUES ('$user_id', '$name', '$email', '$phone', '$department', '$semester', '$gender', '$unique_image_name')";

        if(mysqli_query($conn, $sql)){
            $message = "<span style='color: green; font-weight: bold;'>Person Added Successfully!</span>";
            $_SESSION['notify_msg'] = "New student added successfully";
            $_SESSION['notify_title'] = "Student Added";
        } else {
            $message = "<span style='color: red; font-weight: bold;'>Failed to Add: " . mysqli_error($conn) . "</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .radio { margin-top: 5px; }
    </style>
</head>
<body>

<h2>Add New Person</h2>
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

<form method="POST" enctype="multipart/form-data">
    Name <br>
    <input type="text" name="name" required><br><br>
    Email <br>
    <input type="email" name="email" required><br><br> 
    Phone <br>
    <input type="text" name="phone" required><br><br>
    Department <br>
    <input type="text" name="department" required><br><br>
    Semester <br>
    <input type="text" name="semester" required><br><br>
    
    File/Photo (Max 300MB, Any Format) <br>
    <input type="file" name="student_image" accept="*/*"><br><br>

    Gender <br>
    <div class="radio">
        <input type="radio" name="gender" value="Male" required> Male
        <input type="radio" name="gender" value="Female" required> Female
    </div>
    <br><br>
    <button type="submit" name="add">Add Person</button>
</form>

<br>
<a href="dashboard.php">Dashboard</a> |
<a href="view_student.php">View List</a>

</body>
</html>
