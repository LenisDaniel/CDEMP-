<?php
/*
file name : main.inc.php
Programmed by : Luis R. Martinez Rojas
Date : 2005.12.23  Time : 9:59 pm
*/


#*******************************************************************************
# MAIN

if (!isset($_GET['action'])){
	$action = "new";
}else{
	$action = base64_decode($_GET['action']);
} // if 

switch($action){
	
	case "register" :		
		
		if ($objqueue->set_registration(0,$_POST)){
			$_SESSION['YOUR REGISTRATION IS COMPLETED'];
			header("location:display_page.php?tpl=main");
			ext;
		} // if			
		break;
	case "new";
		# DO NOTHING
} // switch


// set service dropdown


$objtemplate->set_content("drop_services", $objqueue->get_services());
$objtemplate->set_content("form_action","display_page.php?tpl=main&action=" . base64_encode("register"));

?>