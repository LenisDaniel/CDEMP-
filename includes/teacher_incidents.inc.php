<?php
error_reporting(E_ERROR);
require_once("class.incidents.inc.php");
$objincidents = new Incidents();


$objtemplate->set_content("students_incidents", $objincidents->get_all_incidents(3,"teacher_incidents", $_SESSION['loged_user']['idx']));



