<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . "/PHPMAILER/src/PHPMailer.php";
require_once __DIR__ . "/PHPMAILER/src/SMTP.php";
require_once __DIR__ . "/PHPMAILER/src/Exception.php";

function sendOTP($email, $otp)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        // Gmail
        $mail->Username = "personkd63@gmail.com";

        // Gmail App Password
        $mail->Password = "njqmvtfsfwgccjsx";

        // Encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
        $mail->setFrom(
            "personkd63@gmail.com",
            "Student Management System"
        );

        // Receiver
        $mail->addAddress($email);

        // Mail
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";

        $mail->Subject = "Password Reset OTP";

        $mail->Body = "
            <h2>Password Reset</h2>
            <p>Your OTP is:</p>
            <h1 style='color:#008b8b;'>$otp</h1>
            <p>This OTP is valid for 2 minutes.</p>
        ";

        $mail->AltBody = "Your OTP is: $otp";

        $mail->send();

        return true;

    } catch (Exception $e) {

        echo $mail->ErrorInfo;
        return false;

    }

}

?>