<?php
session_start();
include "db.php";
if (!file_exists("FPDF/fpdf.php")) {
    die("Error: 'FPDF/fpdf.php' not found");
}
require "FPDF/fpdf.php";
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    exit("Unauthorized Access");
}
$user_id = $_SESSION['user_id'];
$student_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM persons WHERE id='$student_id' AND user_id='$user_id'");
$student = mysqli_fetch_assoc($query);
if (!$student) {
    exit("Student not found!");
}
if (!empty($student['image']) && file_exists("uploads/" . $student['image'])) {
    $image_path = "uploads/" . $student['image'];
} else {
    $image_path = "uploads/default.png";
}
define('FPDF_FONTPATH', 'FPDF/font/'); 
$pdf = new FPDF('P', 'mm', array(55, 88));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetFillColor(0, 139, 139);
$pdf->Rect(0, 0, 55, 15, 'F'); 
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(35, 5, 'STUDENT ID CARD', 0, 1, 'C');
$pdf->Ln(12);
$pdf->Image($image_path, 15, 18, 25, 25);
$pdf->Ln(28);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 4, strtoupper($student['name']), 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 7);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(35, 3, 'ID: ' . $student['id'], 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Dept: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['department'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Semester: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['semester'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 4, 'Phone: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, $student['phone'], 0, 1, 'L');
$pdf->SetFillColor(0, 64, 64);
$pdf->Rect(0, 84, 55, 4, 'F');
$pdf->Output('I', 'ID_Card_' . $student['id'] . '.pdf');
?>
