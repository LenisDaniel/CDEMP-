<?php

error_reporting(E_ERROR);
require_once("class.events.inc.php");

$objevents = new Events();

$created_by = $_SESSION['loged_user']['idx'];
$role_idx = $_SESSION['loged_user']['role_idx'];

$objtemplate->set_content('events_td', $objevents->get_all_events('teacher_events', $created_by, $role_idx));


if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objevents->get_event($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=teacher_events&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Event Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("groups_dd", $objevents->get_groups($objevents->get_event_info('group_id'), $created_by));
    $objtemplate->set_content("event_descr", $objevents->get_event_info('event_descr'));
    $objtemplate->set_content("event_details", $objevents->get_event_info('event_details'));
    $objtemplate->set_content("teacher_id", $created_by);
    $objtemplate->set_content("course_id", $objevents->get_event_info('course_id'));

    if($objevents->get_event_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=teacher_events&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create Event");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("teacher_id", $created_by);
    $objtemplate->set_content("option1", "checked");
    $objtemplate->set_content("groups_dd", $objevents->get_groups(0, $created_by));
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
    $group = $_POST['group'];
    $course = $_POST['course'];
    $event_descr = $_POST['event_descr'];
    $event_details = $_POST['event_details'];
    $active = $_POST['active'];

    $objevents->manage_event_info('teacher_events', $id, $created_by, $role_idx, $group, $course, $event_descr, $event_details, 0, $active);

}