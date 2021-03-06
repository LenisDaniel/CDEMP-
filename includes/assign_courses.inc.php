<?php
/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 8:24 AM
 */

require_once("class.teachers.inc.php");
require_once("class.config_records.inc.php");
require_once("get_active_scholar_period.php");
$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

$objteachers = new Teachers();
$objconfig = new ConfigRecords();

if(isset($_GET['edit']) && $_GET['edit'] > 0){

    $idx = $_GET['edit'];
    $objtemplate->set_content("teachers_dd", $objteachers->get_teachers($idx));
    $objtemplate->set_content("teacher_id", $idx);

    $course_ids = $objteachers->get_teacher_selected_courses($idx, $limit_start, $limit_end);
    $objtemplate->set_content("courses_dd", $objconfig->get_courses($course_ids));

}else{

    $objtemplate->set_content("teachers_dd", $objteachers->get_teachers(0));
    $objtemplate->set_content("courses_dd", $objconfig->get_courses(0));

}
$objtemplate->set_content("form_title", "Select a Teacher and assign courses");



