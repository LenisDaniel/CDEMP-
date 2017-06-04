<?php

error_reporting(E_ALL);
require_once("class.scholar_period.inc.php");

$objbehavior = new Scholar_Period();

$objtemplate->set_content("scholar_periods_td", $objbehavior->get_all_scholar_period('set_scholar_period'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objbehavior->get_scholar_period($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_scholar_period&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Scholar Period Dates");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("scholar_year", $objbehavior->get_scholar_period_info('scholar_year'));
    $objtemplate->set_content("start_date", $objbehavior->get_scholar_period_info('start_date'));
    $objtemplate->set_content("end_date", $objbehavior->get_scholar_period_info('end_date'));

    if($objbehavior->get_scholar_period_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_scholar_period&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Scholar Period");
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

    $scholar_year = $_POST['scholar_year'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $active = $_POST['active'];

    $objbehavior->manage_scholar_period_info('set_scholar_period', $id, $scholar_year, $start_date, $end_date, $active);

}