<?php

error_reporting(E_ERROR);
require_once ("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();
$student_id = $_SESSION['loged_user']['idx'];




    $header = array();
    $header_columns = "";
    $body_rows = "";

    $students = array($student_id);
    $group_id = get_student_group($student_id)[0];
    $group_descr = get_student_group($student_id)[1];
    $group_courses = get_group_courses($group_id);
    $group_n = explode("-", $group_descr);
    $grade_id = get_grade($group_n[0]);


    $i = 0;
    foreach($students as $value){
        $i++;

        $body_rows .= "<tr class='odd gradeX'>";
        $body_rows .= "<td id='tds_$i'><input type='hidden' id='student_id_$i' name='student_id_$i' value='$value'>" . $value . "</td>";
        $body_rows .= "<td>" . get_student_name($value) . "</td>";

        //Ciclo que busque nota en cada examen creado y cree el td
        if( isset($_GET['filter']) && $_GET['filter'] == 1) {

            $course_id = $_POST['sel_course'];

            if($grade_idx = create_header($grade_id, $group_id, $course_id)){
                $insert_header = 1;

                for($j = 0; $j < count($grade_idx); $j++){

                    $idx = $grade_idx[$j][0];
                    $sqlquery = "SELECT tn.note_id, tn.trans_notes_id, mn.note_descr, tn.teacher_id FROM trans_notes tn INNER JOIN master_notes mn ON mn.note_id = tn.note_id WHERE student_id = $value AND note_identifier = $idx";

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
                    $objtemplate->set_content("courses_dd", get_filter_courses($course_id, $group_courses));
                }

                //Abajo se hace el promedio total
                $body_rows .= "<td>" .get_percentage($grade_id, $group_id, $course_id, $teacher_id, $student_id). "</td>";
                $body_rows .= "</tr>";
            }
        }
    }

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
    $objtemplate->set_content('courses_dd', get_filter_courses(0, $group_courses));

function get_student_group($student_id = 0){
    global $objmydbcon;
    $student_list = array();

    $sqlquery = "SELECT sg.group_id, mg.group_descr FROM students_groups sg JOIN master_group mg ON mg.group_id = sg.group_id WHERE student_id = $student_id";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results) > 0 ){
        while($rs = mysqli_fetch_assoc ($results)){
           $student_group = $rs['group_id'];
           $student_group_descr = $rs['group_descr'];
        }
    }else{
        return 0;
    }
    return array($student_group, $student_group_descr);

}

function get_student_name($id){
    global $objmydbcon;

    $sqlquery = "SELECT first_name, last_name, second_surname FROM master_users WHERE idx = $id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($results) > 0 ){
        $rs = mysqli_fetch_assoc($results);
        extract($rs);
        $name = $first_name . " " . $last_name . " " . $second_surname;
    }else{
        return 0;
    }

    return $name;

}

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

function get_group_courses($group_id = 0){
    global $objmydbcon;

    $courses_list = "";
    $sqlquery = "SELECT course_id FROM schedules_teacher WHERE group_id = $group_id";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){

           $courses_list .= $rs['course_id'] . ",";
        }
    }else{
        return 0;
    }

    return substr($courses_list, 0, -1);
}



function get_filter_courses($course_id = 0, $group_courses = ""){
    global $objmydbcon;

    $courses_dd = "";
    $sqlquery = "SELECT * FROM master_course WHERE course_id IN($group_courses)";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['course_id'];
            $disp = $rs['course_descr'];

            if($course_id == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }

            $courses_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
    }else{
        return 0;
    }

    return $courses_dd;
}

function get_grade($group_id = 0){
    global $objmydbcon;

    $sqlquery = "SELECT grade_id FROM master_grade WHERE master_grade.grade_descr = $group_id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){

            $grade_id = $rs['grade_id'];
        }
    }else{
        return 0;
    }

    return $grade_id;

}


