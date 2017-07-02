<?php
/**
 * Created by PhpStorm.
 * User: Jonathan Flores
 * Date: 6/3/2017
 * Time: 12:16 AM
 */

error_reporting(E_ERROR);
require_once("class.config_records.inc.php");

$objconfig = new ConfigRecords();

$objtemplate->set_content("week_days_td", $objconfig->get_all_config('set_week_days', 'week_day', 6));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objconfig->get_config($idx, "week_day");
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_week_days&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Week Day Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("week_day", $objconfig->get_config_info('week_day_descr'));

    if($objconfig->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_week_days&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Week Day");
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
    $week_day = $_POST['week_day'];
    $active = $_POST['active'];

    $objconfig->manage_config_info('set_week_days', "week_day", $id, $week_day, $active, 6);
}


