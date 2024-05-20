<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'inc/PHPMailer.php';
require 'inc/SMTP.php';
require 'inc/Exception.php'; // Ensure Exception class is also included

if ($_POST) {
    // Sanitize and validate input
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Check Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }
    // Check Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Please enter a valid email address.";
    }
    // Check Message
    if (strlen($contact_message) < 15) { // Adjusted the length check based on your error message
        $error['message'] = "Please enter your message. It should have at least 15 characters.";
    }
    // Subject
    if ($subject == '') {
        $subject = "Contact Form Submission";
    }

    if (empty($error)) {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'owentest563@gmail.com';
            $mail->Password = 'aivivi1314';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($email, $name);
            $mail->addAddress('scaresneeze@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $contact_message;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $response = '';
        if (isset($error['name'])) {
            $response .= $error['name'] . "<br /> \n";
        }
        if (isset($error['email'])) {
            $response .= $error['email'] . "<br /> \n";
        }
        if (isset($error['message'])) {
            $response .= $error['message'] . "<br />";
        }
        echo $response;
    }
}
