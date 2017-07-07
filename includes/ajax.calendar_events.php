<?php
session_start();
require_once("class.mydbcon.inc.php");
require_once("get_active_scholar_period.php");
$objmydbcon = new classmydbcon;
$idx = $_SESSION['loged_user']['idx'];
$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

extract($_POST);

if($type == 'fetch'){

    $events = array();
    $query = mysqli_query($objmydbcon->get_resource_link(), "SELECT event_id, event_descr, event_date, type_id FROM events WHERE on_calendar = 1 AND created_date >= '$limit_start' AND created_date <= '$limit_end' UNION SELECT appointment_id, appointment_descr, appointment_time, type_id FROM appointments WHERE date_with = $idx AND created_date >= '$limit_start' AND created_date <= '$limit_end' OR created_by = $idx AND created_date >= '$limit_start' AND created_date <= '$limit_end' GROUP BY appointment_descr");

    while($fetch = mysqli_fetch_array($query, MYSQLI_ASSOC)){

        if($fetch['type_id'] == 0){
            $color = "blue";
        }else{
            $color = "green";
        }

        $e = array();
        $e['id'] = $fetch['event_id'];
        $e['title'] = $fetch['event_descr'];
        $e['start'] = $fetch['event_date'];
        $e['type_id'] = $fetch['type_id'];
        $e['backgroundColor'] = $color;

        array_push($events, $e);
    }

    echo json_encode($events);

}

if($type == 'detail'){
    $data = array();
    if($type_id == 1){
        //events
        $sqlquery = "SELECT event_descr, event_date, event_details FROM events WHERE event_id = $event_id";
        if(!$result = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($result) > 0){
            $rs = mysqli_fetch_assoc($result);
            $data['title'] = $rs['event_descr'];
            $data['date'] = $rs['event_date'];
            $data['details'] = strip_tags($rs['event_details']);
        }else{
            echo $data;
        }

        echo json_encode($data);

    }else{
        //appointments
        $sqlquery = "SELECT appointment_descr, appointment_time, appointment_details FROM appointments WHERE appointment_id = $event_id";
        if(!$result = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($result) > 0){
            $rs = mysqli_fetch_assoc($result);
            $data['title'] = $rs['appointment_descr'];
            $data['date'] = $rs['appointment_time'];
            $data['details'] = $rs['appointment_details'];
        }else{
            echo $data;
        }

        echo json_encode($data);

    }

}


if (!function_exists('json_last_error_msg')) {
    function json_last_error_msg() {
        static $ERRORS = array(
            JSON_ERROR_NONE => 'No error',
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );

        $error = json_last_error();
        return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
    }
}