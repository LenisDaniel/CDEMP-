<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST) && $_POST['group_id'] != "" && $_POST['teacher_id'] != ""){

    $group_id = $_POST['group_id'];
    $teacher_id = $_POST['teacher_id'];
    $course_id = $_POST['course_id'];

    echo get_courses($group_id, $teacher_id, $course_id);

}

function get_courses($group_id = 0, $teacher_id = 0, $course_id = 0){
    global $objmydbcon;
    $courses_dd = "";

    $courses = get_my_courses($group_id, $teacher_id);
    foreach($courses as $value){
        $course_list .= $value . ",";
    }
    $course_list = substr($course_list, 0, -1);

    $sqlquery = "SELECT * FROM master_course WHERE course_id IN($course_list)";
    $text = "--Select Course--";

    //    if($group_id != "-1"){
//        $sqlquery = "SELECT * FROM schedules_teacher WHERE group_id = $group_id AND teacher_id = $teacher_id";
//        $text = "--Select one Course--";
//    }else{
//        $sqlquery = "SELECT * FROM master_zipcode ORDER BY zip ASC";
//        $text = "--Select one city first--";
//    }

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $courses_dd = "<option value='-1'>" . $text . "</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['course_id'];
            $disp = $rs['course_descr'];

            if($val == $course_id){
                $selected = "selected";
            }else{
                $selected = "";
            }

            $courses_dd .= "<option value='$val' $selected> " .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $courses_dd;

}

function get_my_courses($group_id = 0, $teacher_id = 0){
    global $objmydbcon;

    $courses = array();
    $sqlquery = "SELECT course_id FROM schedules_teacher WHERE group_id = $group_id AND teacher_id = $teacher_id";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        while($rs = mysqli_fetch_assoc($results)){
            extract($rs);
            $courses[] = $course_id;
        }
    }else{
        return 0;
    }

    return $courses;

}