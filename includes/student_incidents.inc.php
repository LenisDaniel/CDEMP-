<?php
//error_reporting(E_ERROR);
session_start();
require_once("class.incidents.inc.php");
$objincidents = new Incidents();
$id = $_SESSION['loged_user']['idx'];


if(isset($_GET['filter'])){

    $start = $_POST['from_date'];
    $end = $_POST['to_date'];
    $course = $_POST['sel_course'];

    $objtemplate->set_content("students_incidents", $objincidents->get_all_incidents(4,"student_incidents", $id, $start, $end,0, 0, $course));
    $objtemplate->set_content("courses_dd", $objincidents->get_filter_courses_student($course, $id));

}else{

    $objtemplate->set_content("students_incidents", $objincidents->get_all_incidents(4,"student_incidents", $id, "", "", 0, 0, 0));
    $objtemplate->set_content("courses_dd", $objincidents->get_filter_courses_student(0, $id));

}