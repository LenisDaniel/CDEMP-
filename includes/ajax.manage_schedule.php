<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

if(isset($_POST)){
    extract($_POST);

    if($action == "get_groups" && $grade > 0){

        echo get_groups($grade);

    }else if($action == "get_day_hours" && $grade > 0 && $group > 0 && $week_day > 0){

        echo get_day_hours($grade, $group, $week_day);

    }else if($action == "get_courses" && $group > 0){

        echo get_courses($group, $only);

    }else if($action == "get_teachers" && $week_day > 0 && $day_hour > 0 && $course > 0){

        echo get_teachers($week_day, $day_hour, $course, $only);
    }

}

function get_groups($grade = 0){
    global $objmydbcon;

    $sqlquery = "SELECT * FROM master_group WHERE grade_id = $grade";
    $text = "--Select Group--";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $grades_dd = "<option value='-1'>" . $text . "</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['group_id'];
            $disp = $rs['group_descr'];

            $grades_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $grades_dd;

}

function get_day_hours($grade = 0, $group = 0, $week_day = 0){
    global $objmydbcon;

    $sqlquery_schedule = "SELECT day_hour_id FROM schedules_teacher WHERE grade_id = $grade AND group_id = $group AND week_day_id = $week_day";
    if(!$results0 = $objmydbcon->get_result_set($sqlquery_schedule)){
        return false;
    }else if(mysqli_num_rows($results0)>0){
        while($rs0 = mysqli_fetch_assoc($results0)){
            extract($rs0);
            $values .= $day_hour_id . ",";
            $filter = 1;
        }
    }else{
        $filter = 0;
    }

    $value_list = substr($values, 0, -1);
    if($filter != 0){
        $conditional = "WHERE day_hour_id NOT IN(" .$value_list. ")";
    }else{
        $conditional = "";
    }

    $sqlquery = "SELECT * FROM master_day_hour $conditional";

    $text = "--Select Day Hour--";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $day_hours_dd = "<option value='-1'>" . $text . "</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['day_hour_id'];
            $disp = $rs['day_hour_descr'];

            $day_hours_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $day_hours_dd;

}

function get_courses($group = 0, $only = 0){
    global $objmydbcon;

    $sqlquery_schedule = "SELECT course_id FROM schedules_teacher WHERE group_id = $group";
    if(!$results0 = $objmydbcon->get_result_set($sqlquery_schedule)){
        return false;
    }else if(mysqli_num_rows($results0)>0){
        while($rs0 = mysqli_fetch_assoc($results0)){
            extract($rs0);
            $values .= $course_id . ",";
            $filter = 1;
        }
    }else{
        $filter = 0;
    }

    $value_list = substr($values, 0, -1);
    if($filter != 0){
        if($only == 1){
            $conditional = "WHERE course_id IN(" .$value_list. ")";
        }else{
            $conditional = "WHERE course_id NOT IN(" .$value_list. ")";
        }

    }else{
        $conditional = "";
    }

    $sqlquery = "SELECT * FROM master_course $conditional";

    $text = "--Select Course--";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $courses_dd = "<option value='-1'>" . $text . "</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['course_id'];
            $disp = $rs['course_descr'];

            $courses_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $courses_dd;

}

function get_teachers($week_day = 0, $day_hour = 0, $course = 0, $only = 0){
    global $objmydbcon;
    if($only == 1){
        $sqlquery_schedule = "SELECT teacher_id FROM schedules_teacher WHERE course_id = $course";
    }else{
        $sqlquery_schedule = "SELECT teacher_id FROM schedules_teacher WHERE week_day_id = $week_day AND day_hour_id = $day_hour";
    }

    if(!$results0 = $objmydbcon->get_result_set($sqlquery_schedule)){
        return false;
    }else if(mysqli_num_rows($results0)>0){
        while($rs0 = mysqli_fetch_assoc($results0)){
            extract($rs0);
            $values .= $teacher_id . ",";
            $filter = 1;
        }
    }else{
        $filter = 0;
    }

    $value_list = substr($values, 0, -1);

    if($filter != 0){
        if($only == 1){
            $conditional = "WHERE teacher_id IN(" .$value_list. ") AND course_id = $course";
        }else{
            $conditional = "WHERE teacher_id NOT IN(" .$value_list. ") AND course_id = $course";
        }


    }else{
        $conditional = "WHERE course_id = $course";
    }

    $sqlquery_course_teacher = "SELECT * FROM course_teacher $conditional";

    if(!$results1 = $objmydbcon->get_result_set($sqlquery_course_teacher)){
        return false;
    }else if(mysqli_num_rows($results1)>0){
        while($rs1 = mysqli_fetch_assoc($results1)){
            $values1 .= $rs1['teacher_id'] . ",";
        }
    }else{
        $filter1 = "No teachers for this course";
    }

    $value_list1 = substr($values1, 0, -1);

    $sqlquery = "SELECT * FROM master_users WHERE idx IN($value_list1) AND role_idx = 3";
    $teachers_dd = "";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        $teachers_dd = "<option value='-1'>--No teacher available for this course--</option>";
    }else if(mysqli_num_rows($results) > 0){
        $teachers_dd = "<option value='-1'>--Select Teacher--</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['idx'];
            $disp = $rs['first_name'] . " " . $rs['last_name'] . " " . $rs['second_surname'];
            $teachers_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        $teachers_dd = "<option value='-1'>--No teacher available for this course--</option>";
    }
    return $teachers_dd;

}