<?php
//error_reporting(E_ALL);
/**
 * Created by PhpStorm.
 * User: Jonathan Flores and Lenis Rivera
 * Date: 5/16/2017
 * Time: 9:31 PM
 */

require_once("class.phpmailer.php");
require_once("class.smtp.php");
require("PHPMailerAutoload.php");

$email = $name = $message = $subject = "";



    $email = $_POST['email'];
    $name = $_POST['name'];
    $message = $_POST['message'];
    $subject = $_POST['subject'];

    $mail = new PHPMailer();
    //$mail->SMTPDebug = 1;
    //$mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'mail.cdemp-pr.com';
    $mail->SMTPAuth = true;
    $mail->Port = 25;
    $mail->Username = 'info@cdemp-pr.com';
    $mail->Password = 'cdemp2017';
    //$mail->SMTPSecure = 'ssl';

    $mail->setFrom('info@cdemp-pr.com', 'CDEMP Response');
    $mail->addAddress($email);


    //$mail->addAddress('lenis.daniel@gmail.com');
    $mail->addReplyTo('info@cdemp-pr.com', 'Information');

    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        header("location: ../display_page.php?tpl=events&msg=1");
    }













