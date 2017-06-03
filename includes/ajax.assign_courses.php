<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST)){
    extract($_POST);

    $course_id = $course_id[0];
    if($_POST['action'] == "insert"){

        $sqlinsert = "INSERT INTO course_teacher(teacher_id, course_id)VALUES($teacher_value, $course_id)";
        if($objmydbcon->set_query($sqlinsert)){
            echo "Course successfully assigned";
        }else{
            echo "Error, try again...";
        }

    }else{

        $sqldelete = "DELETE FROM course_teacher WHERE teacher_id = $teacher_value AND course_id = $course_id";
        if($objmydbcon->set_query($sqldelete)){
            echo "Course successfully removed";
        }else{
            echo "Error, try again...";
        }

    }

}

