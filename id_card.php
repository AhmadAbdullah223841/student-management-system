<?php
session_start();
include "db.php";

// ১. 📁 FPDF ফোল্ডারের ভেতর থেকে ফাইলটি রিকোয়ার করা
if (!file_exists("FPDF/fpdf.php")) {
    die("Error: 'FPDF/fpdf.php' খুঁজে পাওয়া যায়নি! ফাইলটি সঠিক ফোল্ডারে আছে কিনা চেক করুন।");
}
require "FPDF/fpdf.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    exit("Unauthorized Access");
}

$user_id = $_SESSION['user_id'];
$student_id = mysqli_real_escape_string($conn, $_GET['id']);

// স্টুডেন্টের ডেটা তুলে আনা
$query = mysqli_query($conn, "SELECT * FROM persons WHERE id='$student_id' AND user_id='$user_id'");
$student = mysqli_fetch_assoc($query);

if (!$student) {
    exit("Student not found!");
}

// ছবির পাথ চেক করা
if (!empty($student['image']) && file_exists("uploads/" . $student['image'])) {
    $image_path = "uploads/" . $student['image'];
} else {
    $image_path = "uploads/default.png";
}

// ২. 🪪 আইডি কার্ড তৈরি এবং FPDF-কে ফন্ট ফোল্ডারের পাথ চিনিয়ে দেওয়া
define('FPDF_FONTPATH', 'FPDF/font/'); 
$pdf = new FPDF('P', 'mm', array(55, 88));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

// 🎨 ব্যাকগ্রাউন্ড বর্ডার ও হেডার ডিজাইন
$pdf->SetFillColor(0, 139, 139); // গাঢ় টিল কালার
$pdf->Rect(0, 0, 55, 15, 'F'); 

// হেডার টেক্সট (পুরো ৫৫ মিমি জুড়ে সেন্টার করা হয়েছে)
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(35, 5, 'STUDENT ID CARD', 0, 1, 'C');

$pdf->Ln(12); // স্পেসিং

// 🖼️ স্টুডেন্টের ছবি বসানো
$pdf->Image($image_path, 15, 18, 25, 25);

$pdf->Ln(28); // ছবির নিচে স্পেসিং

// 📋 স্টুডেন্টের তথ্যাদি (টেক্সট কালার আবার কালো করা হলো)
$pdf->SetTextColor(0, 0, 0);

// নাম
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 4, strtoupper($student['name']), 0, 1, 'C');
$pdf->Ln(2);

// আইডি নাম্বার
$pdf->SetFont('Arial', '', 7);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(35, 3, 'ID: ' . $student['id'], 0, 1, 'C');
$pdf->Ln(2);

// ডিপার্টমেন্ট ও সেমিস্টার
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Dept: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['department'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Semester: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['semester'], 0, 1, 'L');

// ফোন নম্বর
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Phone: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['phone'], 0, 1, 'L');

// 🎨 ফুটার ডিজাইন
$pdf->SetFillColor(0, 64, 64);
$pdf->Rect(0, 84, 55, 4, 'F');

// পিডিএফ আউটপুট
$pdf->Output('I', 'ID_Card_' . $student['id'] . '.pdf');
?>