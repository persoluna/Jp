<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

function sendCongratulatoryEmail($user_name, $user_email)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'bb746605@gmail.com'; // SMTP username
        $mail->Password = 'okmy ghvs tebf qnkj'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('bb746605@gmail.com', 'Japanese Vocabulary');
        $mail->addAddress($user_email, $user_name); // Recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Congratulations on Reaching 5000 XP!';

        // Randomly select an image from your set of images
        $imageFiles = glob('xp_images/*.jpg'); // Adjust the path to your image directory
        $randomImage = $imageFiles[array_rand($imageFiles)];

        // Embed the image into the email body
        $mail->AddEmbeddedImage($randomImage, 'random_image', 'image.jpg');
        $mail->Body = "Dear $user_name,<br><br>Congratulations on achieving 5000 XP! We appreciate your dedication to learning Japanese.<br><br>Thank you for being with us!<br><br>Sincerely,<br>The Japanese Vocabulary Team<br><br><img src='cid:random_image'>";

        $mail->send();
        // Optionally, log this action for tracking
        // Log that an email was sent to $user_name with $user_email
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
