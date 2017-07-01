<?php
session_start();
require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;
$idx = $_SESSION['loged_user']['idx'];

extract($_POST);
if($type == 'fetch')
{

    $events = array();
    $query = mysqli_query($objmydbcon->get_resource_link(), "SELECT event_id, event_descr, event_date, type_id FROM events WHERE on_calendar = 1 UNION SELECT appointment_id, appointment_descr, appointment_time, type_id FROM appointments WHERE date_with = $idx OR created_by = $idx GROUP BY appointment_descr");

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