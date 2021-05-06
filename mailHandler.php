<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'config.php';

function sendMail($recipientEmail, $subject, $body) {
    $mail = new PHPMailer(true);                                  // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 1;                                     // Enable verbose debug output
        $mail->isSMTP();                                          // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                           // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = USERNAME;                               // SMTP username
        $mail->Password = PASSWORD;                               // SMTP password
        $mail->SMTPSecure = 'ssl';                                // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                        // TCP port to connect to

        //Recipients
        $mail->setFrom(USERNAME, 'Vaccine Availability Notifier');
        $mail->addAddress($recipientEmail);                       // Add a recipient

        //Content
        $mail->isHTML(true);                                      // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        echo "Mail has been sent!";
    } catch (Exception $e) {
        echo 'Mail could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>
