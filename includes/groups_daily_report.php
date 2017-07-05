<?php

require_once("class.mydbcon.inc.php");
require_once("class.phpmailer.php");
$objmydbcon = new classmydbcon();
$message = "";
$date = date("d-M-Y");
$today_date = date("Y-m-d");

$sqlquery = "SELECT group_id, group_descr FROM master_group";

if(!$results = $objmydbcon->get_result_set($sqlquery)){
    return false;
}else if(mysqli_num_rows($results) > 0){
    while($rs = mysqli_fetch_assoc($results)){
        extract($rs);

        $message .= "<strong><h2>Group $group_descr on $date </h2></strong>";

        $sqlquery1 = "SELECT course_id FROM schedules_teacher WHERE group_id = $group_id";
        if(!$results1 = $objmydbcon->get_result_set($sqlquery1)){
            return false;
        }else if(mysqli_num_rows($results1) > 0){
            while($rs1 = mysqli_fetch_assoc($results1)){
                extract($rs1);

                $course_name = get_course_name($course_id);
                $absences['total'] = get_absences($course_id, $today_date)['total'];
                $absences['list'] = get_absences($course_id, $today_date)['list'];

                $tardiness['total'] = get_delays($course_id, $today_date)['total'];
                $tardiness['list'] = get_delays($course_id, $today_date)['list'];

                $behavior['total'] = get_behaviors($course_id, $today_date)['total'];
                $behavior['list'] = get_behaviors($course_id, $today_date)['list'];

                $message .= "<strong><h2>Course $course_name</h2></strong>
                             <strong><h3>Absences total: " . $absences['total'] . "</h3></strong>
                             <strong>Absence student list: </strong> <br>
                             " . $absences['list'] . "<br><br>
                             <strong><h3>Tardiness total: " . $tardiness['total'] . "</h3></strong>
                             <strong>Tardiness student list: </strong><br>
                             " . $tardiness['list'] . "<br><br>
                             <strong><h3>Behaviors total: " . $behavior['total'] . "</h3></strong>
                             <strong>Behavior student list: </strong><br><br>
                             " . $behavior['list'] . "<br><br>";
            }
        }else{
            //do nothing
        }
    }
    send_email($message);
}else{
    return 0;
}

function get_absences($course_id, $today_date){
    global $objmydbcon;

    $sqlquery = "SELECT student_id FROM daily_records WHERE day_status_id = 2 AND course_id = $course_id AND create_date LIKE '%$today_date%'";
    $absences = array();
    $student_list = "";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        $absences['total'] = mysqli_num_rows($results);
        while($rs = mysqli_fetch_assoc($results)){
            $student_list .= get_students_name($rs['student_id']) . "<br>";
        }
        $absences['list'] = $student_list;
    }else{
        return 0;
    }
    return $absences;

}

function get_delays($course_id, $today_date){
    global $objmydbcon;

    $sqlquery = "SELECT student_id FROM daily_records WHERE day_status_id = 3 AND course_id = $course_id AND create_date LIKE '%$today_date%'";
    $tardiness = array();
    $student_list = "";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        $tardiness['total'] = mysqli_num_rows($results);
        while($rs = mysqli_fetch_assoc($results)){
            $student_list .= get_students_name($rs['student_id']) . "<br>";
        }
        $tardiness['list'] = $student_list;
    }else{
        return 0;
    }
    return $tardiness;
}

function get_behaviors($course_id, $today_date){
    global $objmydbcon;

    $sqlquery = "SELECT student_id FROM daily_records WHERE incident_id <> 1 AND course_id = $course_id AND create_date LIKE '%$today_date%'";
    $behavior = array();
    $student_list = "";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        $behavior['total'] = mysqli_num_rows($results);
        while($rs = mysqli_fetch_assoc($results)){
            $behavior['total'] = mysqli_num_rows($results);
            $student_list .= get_students_name($rs['student_id']) . "<br>";
        }
        $behavior['list'] = $student_list;
    }else{
        return 0;
    }
    return $behavior;
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

function get_course_name($id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT course_descr FROM master_course WHERE course_id = $id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results) > 0 ){
        $rs = mysqli_fetch_assoc($results);
    }else{
        return 0;
    }
    return $rs['course_descr'];
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

function send_email($message = ""){

    $recipients = get_administrators_emails();

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
    $mail->setFrom('info@cdemp-pr.com', 'CDEMP Daily Report');

    foreach($recipients as $value){
        $mail->addAddress($value);
    }

    //$mail->addAddress('lenis.daniel@gmail.com');
    $mail->addReplyTo('info@cdemp-pr.com', 'Information');
    $mail->isHTML(true);
    $mail->Subject = "Daily Group Report for Administration";
    $mail->Body    = $message;
    $mail->AltBody = $message;

    if(!$mail->send()) {
        //do nothing
    } else {
        echo "envie";
    }

}