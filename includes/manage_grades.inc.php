<?php

error_reporting(E_ERROR);
require_once("class.config_records.inc.php");

$objconfig = new ConfigRecords();

$objtemplate->set_content("grades_td", $objconfig->get_all_config('manage_grades', 'grade', 2));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objconfig->get_config($idx, "grade");
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_grades&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Grade Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("grade", $objconfig->get_config_info('grade_descr'));
    $objtemplate->set_content("grade_idx", $idx);

    if($objconfig->get_config_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_grades&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Grade");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("option1", "checked");

}



if(isset($_GET['cid'])) {

    $id = base64_decode($_GET['cid']);
    $grade = $_POST['grade'];
    $active = $_POST['active'];

    $objconfig->manage_config_info('manage_grades', "grade", $id, $grade, $active, 2);

}