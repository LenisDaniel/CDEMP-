<?php
error_reporting(E_ERROR);
require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST) && $_POST != "" && $_POST['id'] > 0){
    extract($_POST);

    if($action == 1){

        $sqlquery = "SELECT student_id, note_identifier, punctuation, note_id FROM trans_notes WHERE trans_notes_id = $id";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $student_id = $rs['student_id'];
                $note_identifier = $rs['note_identifier'];
                $punctuation = $rs['punctuation'];
                $note_id = $rs['note_id'];
            }
        }else{
            return 0;
        }

        echo get_grade_info($student_id, $note_identifier, $punctuation, $note_id);

    }else{

        $punctuations = $punctuation_1 . "-" . $valor_1;

        if($grade_1 == "A"){
            $note = 1;
        }elseif($grade_1 == "B"){
            $note = 2;
        }elseif($grade_1 == "C"){
            $note = 3;
        }elseif($grade_1 == "D"){
            $note = 4;
        }elseif($grade_1 == "F"){
            $note = 5;
        }




        $sqlquery = "UPDATE trans_notes SET punctuation = '$punctuations', note_id = $note WHERE trans_notes_id = $id";
        if($objmydbcon->set_query($sqlquery)){
            echo "done";
        }else{
            echo "fail";
        }

    }

}

function get_grade_info($student_id = 0, $note_identifier = 0, $punctuation = "", $note_id = ""){
    global $objmydbcon;

    $student_name = "";
    $grade_descr = "";

    $sqlquery = "SELECT * FROM grade_identifier WHERE grade_identifier_id = $note_identifier";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $note_identifier = $rs['identifier_name'];
        }
    }else{
        return 0;
    }

    $sqlquery0 = "SELECT * FROM master_notes WHERE note_id = $note_id";
    if(!$results0 = $objmydbcon->get_result_set($sqlquery0)){
        return false;
    }else if(mysqli_num_rows($results0)>0){
        while($rs0 = mysqli_fetch_assoc($results0)){
           $grade_descr = $rs0['note_descr'];
        }
    }else{
        return 0;
    }

    $sqlquery1 = "SELECT first_name, last_name, second_surname FROM master_users WHERE idx = $student_id";
    if(!$results1 = $objmydbcon->get_result_set($sqlquery1)){
        return false;
    }else if(mysqli_num_rows($results1)>0){
        $rs1 = mysqli_fetch_assoc($results1);

        $student_name = $rs1['first_name'] . " " . $rs1['last_name'] . " " . $rs1['second_surname'];
    }else{
        return 0;
    }

    $punctuation = explode("-", $punctuation);
    $points = $punctuation[0];
    $valor = $punctuation[1];

    $grade_edit_info = array($student_name, $note_identifier, $points, $valor, $grade_descr);
    return json_encode($grade_edit_info);

}