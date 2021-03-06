<?php

error_reporting(E_ERROR);
require_once("class.appointments.inc.php");
require_once("get_active_scholar_period.php");
$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

$objappointments = new Appointments();

$creator = $_SESSION['loged_user']['idx'];
$role_idx = $_SESSION['loged_user']['role_idx'];

$objtemplate->set_content('appointments_td', $objappointments->get_all_appointments('teacher_appointments', $role_idx, $creator, $limit_start, $limit_end));


if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objappointments->get_appointment($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=teacher_appointments&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Appointment Info");
    $objtemplate->set_content("send_button", "Update");
    $objtemplate->set_content("appoint_descr", $objappointments->get_appointment_info('appointment_descr'));
    $app_date = explode(" ", $objappointments->get_appointment_info('appointment_time'));
    $objtemplate->set_content("appoint_date", $app_date[0]);
    $objtemplate->set_content("appoint_time", $app_date[1]);
    $objtemplate->set_content("appoint_place", $objappointments->get_appointment_info('appointment_place'));
    $objtemplate->set_content("appoint_details", $objappointments->get_appointment_info('appointment_details'));
    $objtemplate->set_content("unique_number", $objappointments->get_appointment_info('unique_number'));
    $objtemplate->set_content("users_type", "style='display:none'");
    $objtemplate->set_content("user_list", "style='display:none'");

    if($objappointments->get_appointment_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

    //$objappointments->set_as_viewed($idx);

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=teacher_appointments&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create Appointment");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("option1", "checked");
    $objtemplate->set_content("users_type_dd", $objappointments->get_users_type());
    $objtemplate->set_content("groups_dd", $objappointments->get_groups());

}




if(isset($_GET['cid'])) {

    $users_list = "";

    if($id <= 0){
        for($i = 0; $i < count($_POST); $i++){

            if(array_key_exists("chk_$i", $_POST)) {

                if($_POST['chk_'.$i] == 'on'){
                    $users_list .= $_POST['txt_name_'.$i] . ",";
                }

            }

        }
        if($users_list == ""){
            header("location: display_page.php?tpl=global_appointments&cat=3&no_users=1");
        }
    }

    if(substr($_POST['appoint_time'], -2) == 'am'){
        $appointment_t = $_POST['appoint_date'] . " " . substr($_POST['appoint_time'], 0, -2);
    }else{
        $appointment_t = $_POST['appoint_date'] . " " . convert_hour($_POST['appoint_time']);
    }

    $id = base64_decode($_GET['cid']);
    $appoint_descr = $_POST['appoint_descr'];
    $appoint_time = $appointment_t;
    $appoint_place = $_POST['appoint_place'];
    $appoint_details = $_POST['appoint_details'];
    $unique_number = $_POST['unique_number'];
    $list = substr($users_list, 0, -1);
    $active = $_POST['active'];

    $objappointments->manage_appoint_info('teacher_appointments', $id, $appoint_descr, $appoint_time, $appoint_place, $appoint_details, $list, $unique_number, $creator, $active);

}