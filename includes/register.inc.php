<?php
session_start();

	require_once('class.users.inc.php');

	$objusers = new classUsers;

	$type = $_GET['type'];
	$action = "create";
	$_SESSION['phone1'] = $_POST['phone1'];
    $_SESSION['phone2'] = $_POST['phone2'];
    $_SESSION['address1'] = $_POST['address1'];
    $_SESSION['address2'] = $_POST['address2'];
    $_SESSION['town'] = $_POST['town'];
    $_SESSION['country'] = $_POST['country'];
    $_SESSION['zipcode'] = $_POST['zipcode'];   

    if ($type == 1){
    	
    	$objusers->set_users(0, $action, 1, 1);

    }else if($type == 2){
    	
    	$objusers->set_users(0, $action, 2, 0);

    }else{
    	
    	$objusers->set_users(0, $action, 3, 1);

    }

?>