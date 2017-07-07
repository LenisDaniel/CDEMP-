<?php

error_reporting(E_ERROR);
require_once("class.schedule.inc.php");
require_once("get_active_scholar_period.php");
$objschedule = new Schedule();



$objtemplate->set_content("schedules_td", $objschedule->get_all_schedules($tpl_uri = 'manage_schedule'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objschedule->get_schedule($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_schedule&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Schedule Info");
    $objtemplate->set_content("send_button", "Update");
    $objtemplate->set_content("grades_dd", $objschedule->get_grades($objschedule->get_schedule_info('grade_id')));
    $objtemplate->set_content("week_days_dd", $objschedule->get_week_days($objschedule->get_schedule_info('week_day_id')));

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_schedule&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Schedule Item");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("grades_dd", $objschedule->get_grades(0));
    $objtemplate->set_content("week_days_dd", $objschedule->get_week_days(0));

}

//    if(isset($_GET['delete'])){
//        $idx = base64_decode($_GET['delete']);
//        header("location: display_page.php?tpl=manage_admins&id=".base64_encode($idx)."&del=1");
//    }
//
//    if(isset($_GET['del'])){
//
//        $del = $_GET['del'];
//        $id = base64_decode($_GET['id']);
//        $role = 1;
//
//        if($del == 2){
//            $objusers->update_user_active($id, $role);
//        }
//
//    }

if(isset($_GET['cid'])) {

    $id = base64_decode($_GET['cid']);
    $grade = $_POST['grade'];
    $group = $_POST['group'];
    $course = $_POST['course'];
    $week_day = $_POST['week_day'];
    $day_hour = $_POST['day_hour'];
    $teacher = $_POST['teacher'];

    $objschedule->manage_schedule_info('manage_schedule', $id, $grade, $group, $course, $week_day, $day_hour, $teacher);

}