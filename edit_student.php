<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Request!");
}

$id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

// Student Data Load
$stmt = mysqli_prepare($conn, "SELECT * FROM persons WHERE id=? AND user_id=?");
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Student Not Found!");
}

$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // আগের ছবি ধরে রাখা
    $image = $row['image'];

    // নতুন ছবি বা ফাইল আপলোড হলে
    if (!empty($_FILES['student_image']['name'])) {

        $ext = strtolower(pathinfo($_FILES['student_image']['name'], PATHINFO_EXTENSION));

        // সাইজ লিমিট ৩০০ মেগাবাইট করা হলো (300 * 1024 * 1024 = 314572800 bytes)
        if ($_FILES['student_image']['size'] <= 314572800) {

            // পুরনো ছবি/ফাইল যদি default.png না হয় এবং সার্ভারে থাকে তবে ডিলিট করবে
            if ($image != "default.png" && file_exists("uploads/" . $image)) {
                unlink("uploads/" . $image);
            }

            // নামের স্পেস রিমুভ করে ইউনিক ফাইল নেম তৈরি
            $clean_name = str_replace(' ', '_', $name);
            $image = time() . "_" . $clean_name . "." . $ext;

            move_uploaded_file(
                $_FILES['student_image']['tmp_name'],
                "uploads/" . $image
            );

        } else {
            die("File must be under 300MB.");
        }
    }

    $stmt = mysqli_prepare($conn,
        "UPDATE persons
        SET
        name=?,
        email=?,
        phone=?,
        department=?,
        semester=?,
        gender=?,
        image=?
        WHERE id=? AND user_id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssii",
        $name,
        $email,
        $phone,
        $department,
        $semester,
        $gender,
        $image,
        $id,
        $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: view_student.php");
        exit();
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Edit Student</h2>

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
    <input type="text" name="name"
        value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>

    Email <br>
    <input type="email" name="email"
        value="<?php echo htmlspecialchars($row['email']); ?>" required><br><br>

    Phone <br>
    <input type="text" name="phone"
        value="<?php echo htmlspecialchars($row['phone']); ?>" required><br><br>

    Department <br>
    <input type="text" name="department"
        value="<?php echo htmlspecialchars($row['department']); ?>" required><br><br>

    Semester <br>
    <input type="text" name="semester"
        value="<?php echo htmlspecialchars($row['semester']); ?>" required><br><br>

    Current Image / File <br>
    <?php 
    // ফাইলটি যদি ছবি হয় তবে দেখাবে, অন্য ফরম্যাট হলে জাস্ট নাম দেখাবে
    $ext_check = strtolower(pathinfo($row['image'], PATHINFO_EXTENSION));
    if(in_array($ext_check, ['jpg', 'jpeg', 'png', 'gif'])): 
    ?>
    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="120" style="border-radius:4px; border: 1px solid #000000;"><p style="color: gray;">📎 <?php echo htmlspecialchars($row['image']); ?></p>
    <?php endif; ?>

    Change Image / File <br>
    <input type="file" name="student_image" accept="*/*"><br><br>

    Gender <br>

    <input
        type="radio"
        name="gender"
        value="Male"
        <?php if($row['gender']=="Male") echo "checked"; ?>
        required> Male

    <input
        type="radio"
        name="gender"
        value="Female"
        <?php if($row['gender']=="Female") echo "checked"; ?>
        required> Female

    <br><br>

    <button type="submit" name="update">
        Update Student
    </button>

</form>

<br>

<a href="view_student.php">Back</a>

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