<?php
/*
file name: validate_user.php
*/
session_start();
ob_start();

//require_once("includes/loadconfig.inc.php");
require_once("includes/class.mydbcon.inc.php");
require_once("includes/class.template.inc.php");
$objmydbcon = new classmydbcon();
$objtemplate = new classTemplate();

if(isset($_POST['remember'])){
  $rm = $_POST['remember']; 
}

function get_permissions($privilege_group){
	global $link;
  global $objmydbcon;

	$sqlquery = "select menu_config_id from privilege_group_permissions where privilege_group = $privilege_group";
	if (!$result = $objmydbcon->get_result_set($sqlquery)){
		return false;
	}else if (mysql_num_rows($result)>0){
		while ($rs = mysql_fetch_row($result)){
			$ary_temp[$rs[0]] = "accept";
		} // while
		return $ary_temp;
	}else{
		return false;
	} //
} // function get_permissions

function register_user_loged_in($cuid){
	//return false;
	global $link;
  global $objmydbcon;

	$login_datetime = date("Y-m-d H:i:s");
	$SID = session_id();
	
	$sqlquery = "Insert Into clients_log (cuid,login_datetime,sid) VALUES ($cuid,'$login_datetime','$SID'); Select last_id = @@identity";
  
	if (!$result = $objmydbcon->get_result_set($sqlquery)){
		return false;
	}else{
		$rs = mysql_fetch_row($result);
		return $rs[0];
	} // if !mysql_query
} // function

function get_user($email,$pwd){
    
    global $link;
    global $lang;
    global $objmydbcon;
    global $objtemplate;
    global $rm;

    $sqlquery = "SELECT * FROM master_users WHERE email = '$email' AND active = 1 AND password = '" . md5($pwd) . "'";
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
              $loged_user['password'] = $pwd;
              $loged_user['role_idx'] = $rs['role_idx'];
              $loged_user['phone_1'] = $rs['phone_1'];
              $loged_user['phone_2'] = $rs['phone_2'];
              $loged_user['city'] = $rs['city'];
              $loged_user['state'] = $rs['state'];
              $loged_user['active'] = $rs['active'];
              
              if($rm == 1){

                 $cookie_name = "email";
                 $cookie_value = $loged_user['email'];

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
          header('location:display_page.php?tpl=incidents');
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


$validate_response = get_user($username,$password);
switch ($validate_response){
    case 1 :
         include("includes/update_cancellations.inc.php");
         header("location:display_page.php?tpl=odstart");
         exit;
    case 0 :
         if ($lang == "sp"){
             $errmsg = "El nombre de usuario o contrase�a esta incorrecto.<BR>Trata de nuevo.";
         }else if ($lang =="en"){
             $errmsg = "User name or password is incorrect.<br>Please try again.";
         }
         break;
    case -1 :
         // User not authorized in this Computer
         if ($lang == "sp"){
             $errmsg = "La computadora utilizada para accesar el sistema no esta autorizada con el usuario especificado. Por favor, contacte al administrador de sistemas de E-Business Sense.";
         }else if ($lang == "en"){
             $errmsg = "Computer used to access this site, is not authorized with specified user.  Please contact ECONET sistem administrator.";
         }
         break;
    case -2 :
         // Computer not Authorized, Cookie was not found
         if ($lang == "sp"){
             $errmsg = "Usuario especificado es valido, sin embargo, la computadora con la que solicita el acceso no esta autorizada.  Por favor, contacte al administrador de sistemas de E-Business Sense.";
         }else if ($lang == "en"){
             $errmsg = "Specified user is valid, however, the Computer used to access this site is not authorized. Please contact E-Business Sense sistem administrator.";
         }
         break;
    case -99 :
         // fatal error
         if ($lang == "sp"){
             $errmsg = "Error Fatal ha ocurrido.";
         }else if ($lang == "en"){
             $errmsg = "Fatal error has ocurred.";
         }
         break;
} // switch


$_SESSION["errmsg"] = $errmsg;
header("location:index.php");
exit();
?>
