<?php

error_reporting(E_ERROR);
require_once("class.config_records.inc.php");

$objconfig = new ConfigRecords();

$objtemplate->set_content("groups_td", $objconfig->get_all_config('manage_groups', 'group', 2));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objconfig->get_config($idx, "group");
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_groups&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Group Info");
    $objtemplate->set_content("send_button", "Update");
    $objtemplate->set_content("grades_dd", $objconfig->get_grades($objconfig->get_config_info('grade_id')));
    $objtemplate->set_content("group", $objconfig->get_config_info('group_descr'));

    if($objconfig->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_groups&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Group");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("grades_dd", $objconfig->get_grades(0));
    $objtemplate->set_content("option1", "checked");
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
    $group = $_POST['group'];
    $grade = $_POST['grade'];
    $active = $_POST['active'];

    $objconfig->manage_config_info('manage_groups', "group", $id, $group, $active, 2, $grade);

}