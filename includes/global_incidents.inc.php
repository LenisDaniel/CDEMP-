<?php
//error_reporting(E_ERROR);
session_start();
require_once("class.incidents.inc.php");
$objincidents = new Incidents();

$id = $_SESSION['loged_user']['idx'];
$role = $_SESSION['loged_user']['role_idx'];

if(isset($_GET['filter'])){

    $start = $_POST['from_date'];
    $end = $_POST['to_date'];
    $grade = $_POST['sel_grade'];
    $group = $_POST['sel_group'];
    $course = $_POST['sel_course'];

    $objtemplate->set_content("students_incidents_table", $objincidents->get_all_incidents_global($role,"global_incidents", $id, $start, $end, $grade, $group, $course));
    $objtemplate->set_content("grades_ddd", $objincidents->get_filter_grades($grade));
    $objtemplate->set_content("groups_ddd", $objincidents->get_filter_groups($group));
    $objtemplate->set_content("courses_ddd", $objincidents->get_filter_courses($course));



}else{

    $objtemplate->set_content("students_incidents_table", $objincidents->get_all_incidents_global($role,"global_incidents", $id, "", "", 0,0,0));
    $objtemplate->set_content("grades_ddd", $objincidents->get_filter_grades(0));
    $objtemplate->set_content("groups_ddd", $objincidents->get_filter_groups(0));
    $objtemplate->set_content("courses_ddd", $objincidents->get_filter_courses(0));

}