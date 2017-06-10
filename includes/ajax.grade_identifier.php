<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST) && $_POST['action'] == 'insert'){
    if($_POST['grade_name'] != ""){
        extract($_POST);

        echo insert_grade_name($grade, $group, $course, $teacher, $grade_name, $grade_valor);

    }
}else{
    echo get_identifiers();
}

//Functions
function insert_grade_name($grade = 0, $group = 0, $course = 0, $teacher = 0, $grade_name = "", $grade_valor = ""){
    global $objmydbcon;

    $sqlinsert = "INSERT INTO grade_identifier(grade_id, group_id, course_id, teacher_id, identifier_name, max_punctuation)VALUES($grade, $group, $course, $teacher, '$grade_name', $grade_valor)";

    if($objmydbcon->set_query($sqlinsert)){
        return 1;
    }else{
        return 2;
    }

}

function get_identifiers(){
    global $objmydbcon;

    $sqlquery = "SELECT * FROM grade_identifier";
    $text = "--Select One--";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $identifiers_dd = "<option value='-1'>" . $text . "</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['grade_identifier_id'];
            $disp = $rs['identifier_name'];

            $identifiers_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        return $identifiers_dd = "<option value='-1'>" . $text . "</option>";;
    }
    return $identifiers_dd;

}