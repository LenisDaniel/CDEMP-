<?php

error_reporting(E_ALL);
require_once("class.config_records.inc.php");

$objconfig = new ConfigRecords();

$objtemplate->set_content("courses_td", $objconfig->get_all_config('manage_courses', 'course', 2));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objconfig->get_config($idx, "course");
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_courses&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Course Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("course", $objconfig->get_config_info('course_descr'));

    if($objconfig->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_courses&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Course");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("option1", "checked");

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
    $course = $_POST['course'];
    $active = $_POST['active'];

    $objconfig->manage_config_info('manage_courses', "course", $id, $course, $active, 2);

}