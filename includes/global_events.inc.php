<?php

error_reporting(E_ERROR);
require_once("class.events.inc.php");

$objevents = new Events();

$created_by = $_SESSION['loged_user']['idx'];
$role_idx = $_SESSION['loged_user']['role_idx'];

$objtemplate->set_content('events_td', $objevents->get_all_events('global_events', $created_by, $role_idx));


if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objevents->get_event($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=global_events&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Event Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("event_descr", $objevents->get_event_info('event_descr'));
    $objtemplate->set_content("event_details", $objevents->get_event_info('event_details'));
    $app_date = explode(" ", $objevents->get_event_info('event_date'));
    $objtemplate->set_content("event_date", $app_date[0]);
    $objtemplate->set_content("event_time", $app_date[1]);
    $objtemplate->set_content("admin_id", $created_by);

    if($objevents->get_event_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=global_events&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create Event");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("admin_id", $created_by);
    $objtemplate->set_content("option1", "checked");
}


if(isset($_GET['cid'])) {

    $id = base64_decode($_GET['cid']);
    $group = $_POST['group'];
    $course = $_POST['course'];
    $event_descr = $_POST['event_descr'];
    $event_details = $_POST['event_details'];

//    echo "<pre>";
//    print_r($_POST);
//    echo "</pre>";
//    exit;

    if(substr($_POST['event_time'], -2) == 'am'){
        $event_t = $_POST['event_date'] . " " . substr($_POST['event_time'], 0, -2);
    }else{
        $event_t = $_POST['event_date'] . " " . convert_hour($_POST['event_time']);
    }



    $event_date = $event_t;
    $active = $_POST['active'];

    $objevents->manage_event_info('global_events', $id, $created_by, $role_idx, $group, $course, $event_descr, $event_details, $event_date, 1, $active);

}

function convert_hour($comp_hour){

    switch ($comp_hour) {

        case '12:00pm':
            $comp_hour = "12:00";
            break;

        case '12:30pm':
            $comp_hour = "12:30";
            break;

        case '1:00pm':
            $comp_hour = "13:00";
            break;

        case '1:30pm':
            $comp_hour = "13:30";
            break;

        case '2:00pm':
            $comp_hour = "14:00";
            break;

        case '2:30pm':
            $comp_hour = "14:30";
            break;

        case '3:00pm':
            $comp_hour = "15:00";
            break;

        case '3:30pm':
            $comp_hour = "15:30";
            break;

        case '4:00pm':

            $comp_hour = "16:00";
            break;

        case '4:30pm':

            $comp_hour = "16:30";
            break;

        case '5:00pm':
            $comp_hour = "17:00";
            break;

        case '5:30pm':
            $comp_hour = "17:30";
            break;

        case '6:00pm':
            $comp_hour = "18:00";
            break;

        case '6:30pm':
            $comp_hour = "18:30";
            break;

        case '7:00pm':
            $comp_hour = "19:00";
            break;

        case '7:30pm':
            $comp_hour = "19:30";
            break;

        case '8:00pm':
            $comp_hour = "20:00";
            break;

        case '8:30pm':
            $comp_hour = "20:30";
            break;

        case '9:00pm':
            $comp_hour = "21:00";
            break;

        case '9:30pm':
            $comp_hour = "21:30";
            break;

        case '10:00pm':
            $comp_hour = "22:00";
            break;

        case '10:30pm':
            $comp_hour = "22:30";
            break;

        case '11:00pm':
            $comp_hour = "23:00";
            break;

        case '11:30pm':
            $comp_hour = "23:30";
            break;

    }

    return $comp_hour;

}
