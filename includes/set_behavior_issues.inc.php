<?php

error_reporting(E_ALL);
require_once("class.behavior_issues.inc.php");

$objbehavior = new Behaviors();

$objtemplate->set_content("behaviors_td", $objbehavior->get_all_behaviors('set_behavior_issues'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objbehavior->get_behavior($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_behavior_issues&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Behavior Issues Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("behavior", $objbehavior->get_behavior_info('incident_descr'));
    $objtemplate->set_content("classification_dd", $objbehavior->get_classifications($objbehavior->get_behavior_info('classification_id')));

    if($objbehavior->get_behavior_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_behavior_issues&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Behavior Issue");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("option1", "checked");
    $objtemplate->set_content("classification_dd", $objbehavior->get_classifications());
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
    $behavior = $_POST['behavior'];
    $classification_dd = $_POST['classification_dd'];
    $active = $_POST['active'];

    $objbehavior->manage_behavior_info('set_behavior_issues', $id, $behavior, $classification_dd, $active);

}