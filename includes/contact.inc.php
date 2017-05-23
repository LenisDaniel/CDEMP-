<?php
<<<<<<< HEAD
/**
 * Created by PhpStorm.
 * User: Jonathan Flores
 * Date: 5/16/2017
 * Time: 9:31 PM
 */
// variables
$_name_error = $email_error = $subject_error = "";
$name = $email = $message = $subject = $success ="";
//require ("contact.tem.htm");
print_r($_POST);
// validation rules
if($_SERVER["REQUEST METHOD"] == "POST") {
    if(empty($_POST["name"])) {
        $name_error = "Name is required";
    }else{
        $name = test_input($_POST["name"]);
        if(!preg_match("/^[a-zA-Z]*$/", $name)){
            $name_error = "Only letters and white space allowed";
        }
    }
}
// require fields
if(empty($_POST["name"])) {
    $email_error = "Email is required";
}else{
    $email = test_input($_POST["email"]);

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $email_error = "Invalid email format";
    }
}
// if message variable is not with the correct parameters
if(empty($_POST["message"])){
    $message ="";
}else{
    $message = test_input($_POST["message"]);
}
if ($name_error == '' and $email_error == '' and $subject_error == '') {
    $message_body = '';
    unset($_POST['submit']);
    foreach ($_POST as $key => $value) {
        $message_body .= "$key: $value\n";
    }
// sender variables
    $to = "info@cdemp-pr.com";
    $subject = 'contact form submit';
// email send
    if (mail($to, $subject, $message)) {
        $success = "Message sent, thank you for contacting us";
        $name = $email = $subject = $message = "";
    }
// function testing
    function test_input($data)
    {
        $data = trim($data);
        $data = stripssplashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}


=======
///**
// * Created by PhpStorm.
// * User: Jonathan Flores
// * Date: 5/16/2017
// * Time: 9:31 PM
// */
//
//require 'template.inc.php';
//
////if "email" variable is filled out, send email
//  if (isset($_REQUEST['email']))  {
//
//    //Email information
//    $admin_email = "someone@example.com";
//    $email = $_REQUEST['email'];
//    $subject = $_REQUEST['subject'];
//    $comment = $_REQUEST['comment'];
//
//    //send email
//    mail($admin_email, "$subject", $comment, "From:" . $email);
//
//    //Email response
//    echo "Thank you for contacting us!";
//}
//
//  //if "email" variable is not filled out, display the form
//  else  {
//?>
>>>>>>> origin/development
