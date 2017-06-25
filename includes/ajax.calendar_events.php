<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

$con = mysqli_connect('localhost','root','xf6C4FBPqNCMNGRG','u186334426_cdemp');
extract($_POST);

if($type == 'fetch')
{

    $events = array();
    $query = mysqli_query($con, "SELECT * FROM events WHERE on_calendar = 1");

    while($fetch = mysqli_fetch_array($query, MYSQLI_ASSOC)){

        $e = array();
        $e['id'] = $fetch['event_id'];
        $e['title'] = $fetch['event_descr'];
        $e['start'] = $fetch['event_date'];
        $e['allDay'] = false;

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