<?php

/**
 * Created by PhpStorm.
 * User: Jonathan Flores
 * Date: 5/16/2017
 * Time: 9:31 PM
 */
// variables
$_name_error = $email_error = $subject_error = "";
$name = $email = $message = $subject = $success = "";


//if(isset($_POST['name'])){
//    echo "<pre>";
//    print_r($_POST);
//    echo "</pre>";
//    exit;
//}


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
if(empty($_POST["email"])) {
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
    $to = $_POST['email'];
    $subject = $_POST['subject'];
// email send
    if (mail($to, $subject, $message)) {
        $success = "Message sent, thank you for contacting us";
        echo $success;
        $name = $email = $subject = $message = "";
    }else{
        echo "Fallo";
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



