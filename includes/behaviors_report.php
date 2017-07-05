<?php
//error_reporting(E_ALL);
require_once("class.mydbcon.inc.php");
require_once("class.phpmailer.php");
$objmydbcon = new classmydbcon();

$date = date("d-M-Y");
$today_date = date("Y-m-d");
$monday_date = date("Y-m-d", strtotime($today_date . "-4 days"));

$today_date_1 = date("d-M-Y");
$monday_date_1 = date("d-M-Y", strtotime($today_date . "-4 days"));

$absent = "";
$tardiness = "";
$mild = "";
$moderate = "";
$severe = "";

$sqlquery = "SELECT idx FROM master_users WHERE role_idx = 4";

if(!$results = $objmydbcon->get_result_set($sqlquery)){
    return false;
}else if(mysqli_num_rows($results) > 0){
    while($rs = mysqli_fetch_assoc($results)){
        extract($rs);

        if(get_absent_cases($idx, $monday_date, $today_date) > 1){
            $absent .= get_students_name($idx) . "<br>";
        }

        if(get_tardiness_cases($idx, $monday_date, $today_date) > 1){
            $tardiness .= get_students_name($idx) . "<br>";
        }

        if(get_mild_cases($idx, $monday_date, $today_date) > 1){
            $mild .= get_students_name($idx) . "<br>";
        }

        if(get_moderate_cases($idx, $monday_date, $today_date) > 1){
            $moderate .= get_students_name($idx) . "<br>";
        }

        if(get_severe_cases($idx, $monday_date, $today_date) > 1){
            $severe .= get_students_name($idx) . "<br>";
        }

    }

    send_friday_email($monday_date_1, $today_date_1, $absent, $tardiness, $mild, $moderate, $severe);

}else{
    return 0;
}

function get_students_name($id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT first_name, last_name, second_surname FROM master_users WHERE idx = $id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results) > 0 ){
        $rs = mysqli_fetch_assoc($results);
        extract($rs);
        $name = $first_name . " " . $last_name;
    }else{
        return 0;
    }
    return $name;
}

function get_absent_cases($id = 0, $monday_date = "", $today_date = ""){
    global $objmydbcon;

    $sqlquery = "SELECT count(day_status_id) AS num FROM daily_records WHERE student_id = $id AND day_status_id = 3 AND create_date >= '$monday_date' AND create_date <= '$today_date'";
    $rs = "";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['num'];
}

function get_tardiness_cases($id = 0, $monday_date = "", $today_date = ""){
    global $objmydbcon;

    $sqlquery = "SELECT count(day_status_id) AS num FROM daily_records where student_id = $id and day_status_id = 2 AND create_date >= '$monday_date' AND create_date <= '$today_date'";
    $rs = "";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['num'];
}

function get_mild_cases($id = 0, $monday_date = "", $today_date = ""){
    global $objmydbcon;

    $mild_behaviors = get_behavior_per_classification(2);
    $rs = "";
    $sqlquery = "SELECT count(incident_id) AS num FROM daily_records where student_id = $id and incident_id IN($mild_behaviors) AND create_date >= '$monday_date' AND create_date <= '$today_date'";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['num'];
}

function get_moderate_cases($id = 0, $monday_date = "", $today_date = ""){
    global $objmydbcon;

    $moderate_behaviors = get_behavior_per_classification(3);

    $sqlquery = "SELECT count(incident_id) AS num FROM daily_records where student_id = $id and incident_id IN($moderate_behaviors) AND create_date >= '$monday_date' AND create_date <= '$today_date'";
    $rs = "";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['num'];
}

function get_severe_cases($id = 0, $monday_date = "", $today_date = ""){
    global $objmydbcon;

    $severe_behaviors = get_behavior_per_classification(4);
    $rs = "";
    $sqlquery = "SELECT count(incident_id) AS num FROM daily_records where student_id = $id and incident_id IN($severe_behaviors) AND create_date >= '$monday_date' AND create_date <= '$today_date'";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['num'];
}

function get_behavior_per_classification($id = 0){
    global $objmydbcon;

    $behaviors = "";
    $sqlquery = "SELECT incident_id FROM master_incidents WHERE classification_id = $id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        while($rs = mysqli_fetch_assoc($result)){
            $behaviors .= $rs['incident_id'] . ',';
        }
    }else{
        return 0;
    }
    return substr($behaviors, 0, -1);
}

function get_administrators_emails(){
    global $objmydbcon;

    $sqlquery = "SELECT email FROM master_users WHERE role_idx IN(1,2)";
    $email_list = array();
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        while($rs = mysqli_fetch_assoc($results)){
            $email_list[] = $rs['email'];
        }
    }else{
        return 0;
    }
    return $email_list;
}

function send_friday_email($monday_date_1 = "", $today_date_1 = "", $absent = "", $tardiness = "", $mild = "", $moderate = "", $severe = ""){

    $recipients = get_administrators_emails();

    $lists = array($absent, $tardiness, $mild, $moderate, $severe);
    for($i = 0; $i < count($lists); $i++){
        if($lists[$i] == ""){
            $lists[$i] = "No student found";
        }
    }

    $message  = "<strong><h2>Report From $monday_date_1 To $today_date_1 </h2></strong>";
    $message .= "<strong><h3>List of student with more than 5 absences</h3></strong>";
    $message .= "$lists[0] <br>";
    $message .= "<strong><h3>List of student with more than 5 delays</h3></strong>";
    $message .= "$lists[1] <br>";
    $message .= "<strong><h3>List of student with more than 5 mild behaviors</h3></strong>";
    $message .= "$lists[2] <br>";
    $message .= "<strong><h3>List of student with more than 5 moderate behaviors</h3></strong>";
    $message .= "$lists[3] <br>";
    $message .= "<strong><h3>List of student with more than 5 severe behaviors</h3></strong>";
    $message .= "$lists[4]";

    $mail = new PHPMailer();
    //$mail->SMTPDebug = 1;
    //$mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'mail.smtp2go.com';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = 'lenis.daniel@gmail.com';
    $mail->Password = '2bdrNC0hQ2hw';
    $mail->SMTPSecure = 'ssl';
    $mail->setFrom('info@cdemp-pr.com', 'CDEMP Fridays Report');

    foreach($recipients as $value){
        $mail->addAddress($value);
    }

    //$mail->addAddress('lenis.daniel@gmail.com');
    $mail->addReplyTo('info@cdemp-pr.com', 'Information');
    $mail->isHTML(true);
    $mail->Subject = "Weekly Absences and Behaviors Report for Administration";
    $mail->Body    = $message;
    $mail->AltBody = $message;

    if(!$mail->send()) {
        //do nothing
    } else {
        echo "envie";
    }

}


