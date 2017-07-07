<?php

error_reporting(E_ERROR);
require_once ("class.mydbcon.inc.php");
require_once ("class.template.inc.php");
require_once ("class.phpmailer.php");
require_once ("class.smtp.php");

$objmydbcon = new classmydbcon();
$objtemplate = new classTemplate();
date_default_timezone_set("America/Puerto_Rico");

if(isset($_GET['grade_id']) && isset($_GET['group_id']) && isset($_GET['course_id'])){

    $grade_id = $_GET['grade_id'];
    $group_id = $_GET['group_id'];
    $course_id = $_GET['course_id'];
    $teacher_id = $_SESSION['loged_user']['idx'];
    $header = array();
    $header_columns = "";
    $body_rows = "";

    $students = get_students_list($group_id);

    $i = 0;
    foreach($students as $value){
            $i++;
            $student_td_info .= "<tr class='odd gradeX'>";
            $student_td_info .= "<td id='td_$i'><input type='hidden' id='student_id_$i' name='student_id_$i' value='$value'>" . $value . "</td>";
            $student_td_info .= "<td id='tdn_$i'>" . get_student_name($value) . "</td>";
            $student_td_info .= "<td>" . get_day_status(0, $i) . "</td>";
            $student_td_info .= "<td>" . get_incidents(0, $i) . "</td>";
            $student_td_info .= "<td class='action-buttons'><button type='button' class='btn btn-success' onclick='insert_grade($i)'><i class='fa fa-pencil-square-o'></i></button></td>";
            $student_td_info .= "</tr>";

            $body_rows .= "<tr class='odd gradeX'>";
            $body_rows .= "<td id='tds_$i'><input type='hidden' id='student_id_$i' name='student_id_$i' value='$value'>" . $value . "</td>";
            $body_rows .= "<td>" . get_student_name($value) . "</td>";

            //Ciclo que busque nota en cada examen creado y cree el td
            if($grade_idx = create_header($grade_id, $group_id, $course_id)){
                $insert_header = 1;

                for($j = 0; $j < count($grade_idx); $j++){
                    $idx = $grade_idx[$j][0];
                    $sqlquery = "SELECT tn.note_id, tn.trans_notes_id, mn.note_descr FROM trans_notes tn INNER JOIN master_notes mn ON mn.note_id = tn.note_id WHERE student_id = $value AND note_identifier = $idx";

                    if($result = $objmydbcon->get_result_set($sqlquery)){
                        if(mysqli_num_rows($result) > 0){
                            $rs = mysqli_fetch_assoc($result);
                            extract($rs);

                            $body_rows .= "<td id='$trans_notes_id' class='grade_selector'>" . $note_descr . "</td>";

                        }else{
                            $body_rows .= "<td>Not Assigned</td>";
                        }
                    }else{
                        return 0;
                    }

                }

                //Abajo se hace el promedio total
                $body_rows .= "<td>" .get_percentage($grade_id, $group_id, $course_id, $teacher_id, $value). "</td>";
                $body_rows .= "</tr>";
            }

    }

    $objtemplate->set_content('students_list', $student_td_info);
    $objtemplate->set_content('grade', $grade_id);
    $objtemplate->set_content('group', $group_id);
    $objtemplate->set_content('course', $course_id);
    $objtemplate->set_content('teacher', $teacher_id);

    //Se crea el header de las notas que estan creadas e la base de datos
    if($insert_header == 1){
        $header = create_header($grade_id, $group_id, $course_id);
        for($i = 0; $i < count($header); $i++){
            $header_columns .= "<th>".$header[$i][1]."</th>";
        }
        $header_columns .= "<th>Total Percentage</th>";
        $objtemplate->set_content('header_columns', $header_columns);
    }

    //Aquí creamos el contenido de la tabla
    $objtemplate->set_content('students_grades', $body_rows);

}

$objtemplate->set_content('grade_identifiers_dd', get_grade_identifiers($grade_id, $group_id, $course_id, $teacher_id));

if(isset($_POST) && $_POST['form_action1'] == 1 && $_POST != ""){

    $grade_id = $_POST['grade'];
    $group_id = $_POST['group'];
    $course_id = $_POST['course'];
    $teacher_id = $_POST['teacher'];
    $forget_date = $_POST['day_status_date'];
    $counter = 0;

    for($i = 0; $i < count($_POST); $i++){
        if(array_key_exists("student_id_$i", $_POST)){
            $counter++;
        }
    }

    if(validate_today_info($grade_id, $group_id, $course_id, $teacher_id, $forget_date)){

        header('location: ../display_page.php?tpl=my_courses&cat=4&process=3');

    }else{

        for($i = 1; $i <= $counter; $i++){

            $student_id = $_POST['student_id_'.$i];
            $day_status_id = $_POST['day_status_'.$i];
            $incident_id = $_POST['incidents_'.$i];

            if($forget_date != ""){

                $sqlinsert = "INSERT INTO daily_records(teacher_id, student_id, grade_id, group_id, course_id, day_status_id, incident_id, create_date)
                          VALUES ($teacher_id, $student_id, $grade_id, $group_id, $course_id, $day_status_id, $incident_id, '$forget_date')";

            }else{

                $sqlinsert = "INSERT INTO daily_records(teacher_id, student_id, grade_id, group_id, course_id, day_status_id, incident_id)
                          VALUES ($teacher_id, $student_id, $grade_id, $group_id, $course_id, $day_status_id, $incident_id)";

            }

            if($objmydbcon->set_query($sqlinsert)){
                //Send Email & sms to parent
                $contact_info = get_student_contact_info($student_id);

                if($day_status_id == 2 || $incident_id != 1){
                    send_daily_email($contact_info, $student_id, $course_id, $teacher_id, $day_status_id, $incident_id);
                }

            }else{
                $flag = 1;
                header('location: ../display_page.php?tpl=my_courses&cat=4&process=2');
            }

            if($flag == 0){
                header('location: ../display_page.php?tpl=my_courses&cat=4&process=1');
            }

        }

    }

}

if(isset($_POST) && $_POST['form_action2'] == 2 && $_POST['punctuation'] != "" && $_POST['valor'] != "" && $_POST['txt_grade'] != ""){
    extract($_POST);

    $punctuation_value = $punctuation . "-" . $valor;

    if($txt_grade == "A"){
        $grade_id = 1;
    }elseif($txt_grade == "B"){
        $grade_id = 2;
    }elseif($txt_grade == "C"){
        $grade_id = 3;
    }elseif($txt_grade == "D"){
        $grade_id = 4;
    }elseif($txt_grade == "F"){
        $grade_id = 5;
    }

    $sqlinsert0 = "INSERT INTO trans_notes(grade_id, group_id, course_id, teacher_id, student_id, note_identifier, punctuation, note_id)VALUES($grade0, $group0, $course0, $teacher0, $student0, '$grade_identifiers', '$punctuation_value', $grade_id)";
    if($objmydbcon->set_query($sqlinsert0)){
        header('location: ../display_page.php?tpl=course_details&grade_id='.$grade0.'&group_id='.$group0.'&course_id='.$course0.'&grade_insert=1');
    }else{
        header('location: ../display_page.php?tpl=course_details&grade_id='.$grade0.'&group_id='.$group0.'&course_id='.$course0.'&grade_insert=2');
    }

//    echo "grado " . $grade0 . "<br>";
//    echo "group " . $group0 . "<br>";
//    echo "course " . $course0 . "<br>";
//    echo "teacher " . $teacher0 . "<br>";
//    echo "student " . $student0 . "<br>";
//
//    echo "grade identifier " . $grade_identifiers . "<br>";
//    echo "punctuation " . $punctuation . "<br>";
//    echo "valor " . $valor . "<br>";
//    echo "grade " . $txt_grade . "<br>";
//
//    exit;

}

function get_students_list($group_id = 0){
    global $objmydbcon;
    $student_list = array();

    $sqlquery = "SELECT student_id FROM students_groups WHERE group_id = $group_id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results) > 0 ){
        while($rs = mysqli_fetch_assoc ($results)){

            $student_list[] = $rs['student_id'];

        }
    }else{
        return 0;
    }
    return $student_list;

}

function get_student_name($id){
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

function get_incidents($incident = 0, $position = 0){
    global $objmydbcon;
    $incidents_dd = "";

    $sqlquery = "SELECT * FROM master_incidents";
    $incidents_dd = "<select id='incidents_$position' name='incidents_$position'>";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['incident_id'];
            $disp = $rs['incident_descr'];

            if($incident == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }
            $incidents_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
        $incidents_dd .= "</select>";
    }else{
        return 0;
    }
    return $incidents_dd;
}

function get_day_status($day_status = 0, $position = 0){
    global $objmydbcon;
    $day_status_dd = "";

    $sqlquery = "SELECT * FROM master_day_status";
    $day_status_dd = "<select id='day_status_$position' name='day_status_$position'>";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['day_status_id'];
            $disp = $rs['day_status_descr'];

            if($day_status == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }
            $day_status_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
        $day_status_dd .= "</select>";
    }else{
        return 0;
    }
    return $day_status_dd;
}

function get_grade_identifiers($grade_id = 0, $group_id = 0, $course_id = 0, $teacher_id = 0){
    global $objmydbcon;
    $identifiers_dd = "";

    $sqlquery = "SELECT * FROM grade_identifier WHERE grade_id = $grade_id AND group_id = $group_id AND course_id = $course_id AND teacher_id = $teacher_id";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['grade_identifier_id'];
            $disp = $rs['identifier_name'];

            $identifiers_dd .= "<option value='$val'>" .$disp . "</option>";
        }

    }else{
        return 0;
    }
    return $identifiers_dd;
}


function validate_today_info($grade_id = 0, $group_id = 0, $course_id = 0, $teacher_id = 0, $forget_date = ""){
    global $objmydbcon;

    $today_date = date('Y-m-d');

    if($forget_date != ""){
        $verify_date = $forget_date;
    }else{
        $verify_date = $today_date;
    }


    $sqlquery = "SELECT daily_record_id FROM daily_records WHERE grade_id = $grade_id AND group_id = $group_id AND course_id = $course_id AND teacher_id = $teacher_id AND create_date LIKE '%$verify_date%'";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return "Connection Problems";
    }elseif(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }

}

//echo "<pre>";
//print_r(create_header());
//echo "</pre>";
//exit;

function create_header($grade = 0, $group = 0, $course = 0){
    global $objmydbcon;

    $identifiers = array();
    $identifiers_id = array();
    $sqlquery = "SELECT grade_identifier_id, identifier_name FROM grade_identifier WHERE grade_id = $grade AND group_id = $group AND course_id = $course";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($result)){
        while($rs = mysqli_fetch_assoc($result)){
            $identifiers[] = array_values($rs);
        }
    }else{
        return false;
    }

    return $identifiers;

}

function get_percentage($grade_id = 0, $group_id = 0, $course_id = 0, $teacher_id = 0, $value = 0){
    global $objmydbcon;

    $sqlquery = "SELECT punctuation FROM trans_notes WHERE grade_id = $grade_id AND group_id = $group_id AND course_id = $course_id AND teacher_id = $teacher_id AND student_id = $value";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results)){
        while($rs = mysqli_fetch_assoc($results)){
            extract($rs);
            $punctuation_total = explode("-", $punctuation);

            $points = $points + $punctuation_total[0];
            $max_points = $max_points + $punctuation_total[1];

        }
        $percentage = ($points / $max_points) * 100;
    }else{
        return "";
    }

    return number_format($percentage, 2);

}

function get_student_contact_info($student_id = 0){
    global $objmydbcon;
    $contact_info = array();
    $sqlquery = "SELECT email, phone_1, phone_1_carrier, phone_2, phone_2_carrier FROM master_users WHERE idx = $student_id";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
        $contact_info['email'] = $rs['email'];
        $contact_info['phone_1'] = $rs['phone_1'];
        $contact_info['phone_1_carrier'] = $rs['phone_1_carrier'];
        $contact_info['phone_2'] = $rs['phone_2'];
        $contact_info['phone_2_carrier'] = $rs['phone_2_carrier'];
    }else{
        return 0;
    }

    return $contact_info;

}

function send_daily_email($contact_info = "", $student_id = 0, $course_id = 0, $teacher_id = 0, $day_status_id = 0, $incident_id = 0){

    if($contact_info['phone_1_carrier'] != "" || $contact_info['phone_1_carrier'] > 1){
        $carrier_string_1 = get_carrier($contact_info['phone_1_carrier']);
        $sms_1 = str_replace("-","", $contact_info['phone_1']) . $carrier_string_1;
    }
    if($contact_info['phone_2_carrier'] != "" || $contact_info['phone_2_carrier'] > 1){
        $carrier_string_2 = get_carrier($contact_info['phone_2_carrier']);
        $sms_2 = str_replace("-","", $contact_info['phone_2']) . $carrier_string_2;
    }

    $directions = array($contact_info['email'], $sms_1, $sms_2);
    $recipients = array();

    for($i = 0; $i < 3; $i++){
        if($directions[$i] != ""){
            $recipients[] = $directions[$i];
        }
    }

    $student_name = get_student_name($student_id);
    $teacher_name = get_student_name($teacher_id);
    $course_name = get_course_name($course_id);
    $day_status_name = get_status_name($day_status_id);
    $incident_id = get_incident_name($incident_id);
    $date = date("d-M-Y");

    if($day_status_id == 2){

        $message  = "Date: $date </br>";
        $message .= "Student: $student_name </br>";
        $message .= "Teacher: $teacher_name </br>";
        $message .= "Course: $course_name </br>";
        $message .= "Day Status: $day_status_name </br>";

        $message_1  = "Date: $date \n";
        $message_1 .= "Student: $student_name \n";
        $message_1 .= "Teacher: $teacher_name \n";
        $message_1 .= "Course: $course_name \n";
        $message_1 .= "Day Status: $day_status_name \n";

    }else{

        $message  = "Date: $date </br>";
        $message .= "Student: $student_name </br>";
        $message .= "Teacher: $teacher_name </br>";
        $message .= "Course: $course_name </br>";
        $message .= "Day Status: $day_status_name </br>";
        $message .= "Incidents: $incident_id </br>";

        $message_1  = "Date: $date \n";
        $message_1 .= "Student: $student_name \n";
        $message_1 .= "Teacher: $teacher_name \n";
        $message_1 .= "Course: $course_name \n";
        $message_1 .= "Day Status: $day_status_name \n";
        $message_1 .= "Incidents: $incident_id \n";

    }



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

    $mail->Subject = "Student daily status report";
    $mail->Body    = $message;
    $mail->AltBody = $message_1;

    if(!$mail->send()) {
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        //header("location: ../display_page.php?tpl=events&msg=1");
    }

}

function get_carrier($carrier_id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT email_string FROM mast_carriers WHERE idx = $carrier_id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }

    return $rs['email_string'];
}

function get_course_name($course_id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT course_descr FROM master_course WHERE course_id = $course_id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['course_descr'];
}

function get_status_name($day_status_id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT day_status_descr FROM master_day_status WHERE day_status_id = $day_status_id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['day_status_descr'];
}

function get_incident_name($incident_id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT incident_descr FROM master_incidents WHERE incident_id = $incident_id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['incident_descr'];
}
