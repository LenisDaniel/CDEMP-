
<?php
error_reporting(E_ERROR);

require_once("includes/class.users.inc.php");
$objusers = new Users;

if(isset($_GET['id'])){
	if(isset($_GET['active'])){
		$id= $_GET['id'];
		if($_GET['active'] == 'activate'){
		$objusers->updateMemberActive($id, 1);
		}
	}
}
$test = "";
$test2 = "";
//*****************************************************************************************************************************************
// main
//require_once("includes/loadconfig.inc.php");
require_once("includes/class.template.inc.php");
require_once("includes/disclaimer.inc.php");
//****************************************************************************************************************************************
// INSTANTIATE TEMPLATE CLASS
$objtemplate = new classTemplate;
//****************************************************************************************************************************************
// CHECK FOR ERR MSG
if (isset($_SESSION['errmsg'])){
	$objtemplate->set_content("errmsg",$_SESSION['errmsg']);
    unset($_SESSION['errmsg']);
} // if session_is_registered("errmsg")

//****************************************************************************************************************************************
// OPEN LOGIN FORM
$content = $objtemplate->open("templates_en/login.tem.htm");
$vdisclaimer = "";
//****************************************************************************************************************************************
if(isset($_COOKIE['username'])){
	$objtemplate->set_content("username", $_COOKIE['username']);
}
if(isset($_COOKIE['pass'])){
	$objtemplate->set_content("pass", $_COOKIE['pass']);
}

//$objtemplate->set_content("change_lang",$change_lang);
//$objtemplate->set_content("software_version",$software_version);
//$objtemplate->set_content("today_date",$today_date);
$objtemplate->set_content("form_action","validate_user.php");
$objtemplate->set_content("dinamic_year", date("Y"));
$objtemplate->parsedata($content,1);
session_destroy();

?>
