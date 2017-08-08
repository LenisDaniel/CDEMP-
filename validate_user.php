<?php

session_start();
ob_start();

require_once("includes/class.mydbcon.inc.php");
require_once("includes/class.template.inc.php");
$objmydbcon = new classmydbcon();
$objtemplate = new classTemplate();

if(isset($_POST['remember'])){
    $rm = $_POST['remember'];
}



function register_user_loged_in($user_id){

    global $link;
    global $objmydbcon;

    $sqlquery = "INSERT INTO users_log(user_id)VALUES($user_id)";
    if($objmydbcon->set_query($sqlquery)){
        return true;
    }else{
        return false;
    }

}

function get_user($username,$pwd){

    global $link;
    global $lang;
    global $objmydbcon;
    global $objtemplate;
    global $rm;

    $sqlquery = "SELECT * FROM master_users WHERE username = '$username' AND active = 1 AND password = '$pwd'";
    // echo $sqlquery;
    // exit;
    //$sqlquery = "SELECT * FROM users WHERE email = '$email' AND active = 1 AND password = '$pwd'";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result)>0){

        $rs = mysqli_fetch_assoc($result);
        if (isset($rs['first_name'])){
            $loged_user['first_name'] = $rs['first_name'];
            $loged_user['last_name'] = $rs['last_name'];
            $loged_user['second_surname'] = $rs['second_surname'];
            $loged_user['idx'] = $rs['idx'];
            $loged_user['email'] = $email;
            $loged_user['username'] = $username;
            $loged_user['password'] = $pwd;
            $loged_user['role_idx'] = $rs['role_idx'];
            $loged_user['phone_1'] = $rs['phone_1'];
            $loged_user['phone_2'] = $rs['phone_2'];
            $loged_user['city'] = $rs['city'];
            $loged_user['state'] = $rs['state'];
            $loged_user['active'] = $rs['active'];

            if($rm == 1){

                $cookie_name = "username";
                $cookie_value = $loged_user['username'];

                $cookie_name1 = "pass";
                $cookie_value1 = $loged_user['password'];

                setcookie($cookie_name, $cookie_value, time() +(86400 * 30), "/");
                setcookie($cookie_name1, $cookie_value1, time() +(86400 * 30), "/");
            }

        }else{
            $loged_user['name'] = UNKNOWN;
        } // if isset

        mysqli_free_result($result);
        $_SESSION['loged_user'] = $loged_user;
        register_user_loged_in($rs['idx']);
        header('location:display_page.php?tpl=events');
        exit();
    }else{
        return 0;
    } // if !$result
} // function get_user
//***********************************************************************************************************************
// MAIN
if (!isset($_POST["txt_username"]) || !isset($_POST["txt_password"])){

    $errmsg = "Authentication information is required to access our services.";

    $_SESSION['errmsg'] = $errmsg;

    header("location:index.php");
    exit();
}

$username = $_POST["txt_username"];
$password = $_POST["txt_password"];

get_user($username, $password);

$_SESSION["errmsg"] = $errmsg;
header("location:index.php");
exit();
?>
