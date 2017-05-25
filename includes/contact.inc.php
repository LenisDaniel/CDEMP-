<?php

/**
 * Created by PhpStorm.
 * User: Jonathan Flores and Lenis Rivera
 * Date: 5/16/2017
 * Time: 9:31 PM
 */


require_once("includes/class.phpmailer.php");
require_once("includes/class.smtp.php");
require("includes/PHPMailerAutoload.php");


$email = $_POST['email'];
$subject = $_POST['subject'];
$name = $_POST['name'];
$message = $_POST['message'];





$mail = new PHPMailer();
//$mail->SMTPDebug = 1;
$mail->isSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Port = 25;
$mail->Username = 'lenis.daniel@gmail.com';
$mail->Password = 'playaysol2584';
$mail->SMTPSecure = 'ssl';


$mail->setFrom('info@lenisrivera.info', 'Prueba ICY-ATP');

$mail->addAddress($email);


//$mail->addAddress('lenis.daniel@gmail.com');
$mail->addReplyTo('info@lenisrivera.info', 'Information');

//$mail->addAttachment('pdfs/filename_'.$id.'.pdf', 'plan_transicion.pdf'); // Add attachments

$mail->isHTML(true);

$mail->Subject = $subject;
$mail->Body    = $message;
$mail->AltBody = $message;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    header("location: complete.php");
    //echo 'Message has been sent';
}







