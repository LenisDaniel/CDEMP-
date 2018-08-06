<?php

error_reporting(E_ERROR);
require_once ("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();



//if(isset($_GET['grade_id']) && isset($_GET['group_id']) && isset($_GET['course_id'])){
if(isset($_GET['filter'])){

    $grade_id = $_POST['sel_grade'];
    $group_id = $_POST['sel_group'];;
    $course_id = $_POST['sel_course'];;
    $teacher_id = $_POST['sel_teacher'];;
    $header = array();
    $header_columns = "";
    $body_rows = "";

    $students = get_students_list($group_id);

    $i = 0;
    foreach($students as $value){
        $i++;

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

    $objtemplate->set_content("grades_dd", get_filter_grades($grade_id));
    $objtemplate->set_content("groups_dd", get_filter_groups($group_id));
    $objtemplate->set_content("courses_dd", get_filter_courses($course_id));
    $objtemplate->set_content("teachers_dd", get_filter_teachers($teacher_id));


}else{
    $objtemplate->set_content('grades_dd', get_filter_grades(0));
    $objtemplate->set_content('groups_dd', get_filter_groups(0));
    $objtemplate->set_content('courses_dd', get_filter_courses(0));
    $objtemplate->set_content('teachers_dd', get_filter_teachers(0));
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

function get_filter_grades($grade_id = 0){
    global $objmydbcon;

    $grades_dd = "";
    $sqlquery = "SELECT * FROM master_grade";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['grade_id'];
            $disp = $rs['grade_descr'];

            if($grade_id == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }

            $grades_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
    }else{
        return 0;
    }

    return $grades_dd;
}

function get_filter_groups($group_id = 0){
    global $objmydbcon;

    $groups_dd = "";
    $sqlquery = "SELECT * FROM master_group";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['group_id'];
            $disp = $rs['group_descr'];

            if($group_id == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }

            $groups_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
    }else{
        return 0;
    }


    return $groups_dd;
}

function get_filter_courses($course_id = 0){
    global $objmydbcon;

    $courses_dd = "";
    $sqlquery = "SELECT * FROM master_course ORDER BY course_descr ASC";
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

function get_filter_teachers($teacher_id = 0){
    global $objmydbcon;

    $teachers_dd = "";
    $sqlquery = "SELECT * FROM master_users WHERE role_idx = 3";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['idx'];
            $disp = $rs['first_name'] . " " . $rs['last_name'] . " " . $rs['second_surname'];

            if($teacher_id == $val){
                $sel_option = "selected";
            }else{
                $sel_option = "";
            }

            $teachers_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $teachers_dd;
}