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
    $objtemplate->set_content("send_button", "Delete Schedule Item");
    $objtemplate->set_content("button_color", "btn red");
    $objtemplate->set_content("grades_dd", $objschedule->get_grades($objschedule->get_schedule_info('grade_id')));
    $objtemplate->set_content("groups_dd", $objschedule->get_groups($objschedule->get_schedule_info('group_id')));
    $objtemplate->set_content("week_days_dd", $objschedule->get_week_days($objschedule->get_schedule_info('week_day_id')));
    $objtemplate->set_content("day_hours_dd", $objschedule->get_day_hours($objschedule->get_schedule_info('day_hour_id')));
    $objtemplate->set_content("courses_dd", $objschedule->get_courses($objschedule->get_schedule_info('course_id')));
    $objtemplate->set_content("teachers_dd", $objschedule->get_teachers($objschedule->get_schedule_info('teacher_id')));

    $objtemplate->set_content("selected_grade", $objschedule->get_schedule_info('grade_id'));
    $objtemplate->set_content("selected_group", $objschedule->get_schedule_info('group_id'));
    $objtemplate->set_content("selected_week_day", $objschedule->get_schedule_info('week_day_id'));
    $objtemplate->set_content("selected_day_hour", $objschedule->get_schedule_info('day_hour_id'));
    $objtemplate->set_content("selected_course", $objschedule->get_schedule_info('course_id'));
    $objtemplate->set_content("selected_teacher", $objschedule->get_schedule_info('teacher_id'));
    $objtemplate->set_content("is_edit", 1);

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_schedule&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Schedule Item");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("button_color", "btn green");
    $objtemplate->set_content("grades_dd", $objschedule->get_grades(0));
    $objtemplate->set_content("week_days_dd", $objschedule->get_week_days(0));
    $objtemplate->set_content("is_edit", 0);

}

if(isset($_GET['cid'])) {

    $id = base64_decode($_GET['cid']);

    if($id > 0){
        $grade = $_POST['selected_grade'];
        $group = $_POST['selected_group'];
        $course = $_POST['selected_course'];
        $week_day = $_POST['selected_week_day'];
        $day_hour = $_POST['selected_day_hour'];
        $teacher = $_POST['selected_teacher'];
    }else{
        $grade = $_POST['grade'];
        $group = $_POST['group'];
        $course = $_POST['course'];
        $week_day = $_POST['week_day'];
        $day_hour = $_POST['day_hour'];
        $teacher = $_POST['teacher'];
    }

    $objschedule->manage_schedule_info('manage_schedule', $id, $grade, $group, $course, $week_day, $day_hour, $teacher);

}