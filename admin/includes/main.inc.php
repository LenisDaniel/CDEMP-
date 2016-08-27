<?php
/*
file name : digital_signage.inc.php
Programmed by : Luis R. Martinez Jr.
Date : 2015.07.31  Time : 3 26pm
*/

#*******************************************************************************
# MAIN

if (!isset($_SESSION['loged_user']['station_id'])){
	$_SESSION['loged_user']['station_id'] = "003";
} // if 

$curdate = date("Y-m-d");


$objtemplate->set_content("cbo_services",$objqueue->get_services());
$objtemplate->set_content("station_id",$_SESSION['loged_user']['station_id']);


?>