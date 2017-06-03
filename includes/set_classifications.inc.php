<?php

error_reporting(E_ALL);
require_once("class.config_records.inc.php");

$objbehavior = new ConfigRecords();

$objtemplate->set_content("classifications_td", $objbehavior->get_all_config('set_classifications', 'classification'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objbehavior->get_config($idx, "classification");
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_classifications&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Classification Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("classification", $objbehavior->get_config_info('classification_descr'));

    if($objbehavior->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_classifications&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Classification");
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
    $classification = $_POST['classification'];
    $active = $_POST['active'];

    $objbehavior->manage_config_info('set_classifications', "classification", $id, $classification, $active);

}