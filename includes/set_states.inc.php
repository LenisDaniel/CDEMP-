<?php

error_reporting(E_ERROR);
require_once("class.config_records.inc.php");

$objconfig = new ConfigRecords();
$objtemplate->set_content("states_td", $objconfig->get_all_config('set_states', 'state', 6));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objconfig->get_config($idx, "state");
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_states&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update States Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("state", $objconfig->get_config_info('state_descr'));

    if($objconfig->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_states&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New State");
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
    $state = $_POST['state'];
    $active = $_POST['active'];

    $objconfig->manage_config_info('set_states', "state", $id, $state, $active,6);

}