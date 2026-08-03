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

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    // প্রথমে ছবির নাম বের করি
    $stmt = mysqli_prepare($conn, "SELECT image FROM persons WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

        // default.png ছাড়া অন্য ছবি হলে delete করবে
        if (!empty($row['image']) && $row['image'] != "default.png") {

            $imagePath = "uploads/" . $row['image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        mysqli_stmt_close($stmt);

        // Database থেকে Student Delete
        $stmt = mysqli_prepare($conn, "DELETE FROM persons WHERE id=? AND user_id=?");
        mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $_SESSION['notify_msg'] = "Student record removed from system.";
            $_SESSION['notify_title'] = "Student Deleted";
            header("Location: view_student.php");
            exit();
        } else {
            echo "Delete Failed: " . mysqli_error($conn);
        }

    } else {
        echo "Student not found!";
    }

} else {
    echo "Invalid Request!";
}

mysqli_close($conn);
?>