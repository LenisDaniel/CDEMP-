<?php
error_reporting(E_ERROR);
require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST) && $_POST != "" && $_POST['id'] > 0){
    extract($_POST);

    if($action == 1){

        $sqlquery = "SELECT student_id, day_status_id, incident_id FROM daily_records WHERE daily_record_id = $id";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                extract($rs);

            }
        }else{
            return 0;
        }

        echo get_edit_info($student_id, $day_status_id, $incident_id);

    }else{

        $sqlquery = "UPDATE daily_records SET day_status_id = $day_status, incident_id = $incident WHERE daily_record_id = $id";
        if($objmydbcon->set_query($sqlquery)){
            echo "done";
        }else{
            echo "fail";
        }

    }

}

function get_edit_info($student_id = 0, $daily_status_id = 0, $incident_id = 0){
    global $objmydbcon;

    $student_name = "";
    $daily_status_dd = "";
    $incidents_dd = "";

    $sqlquery = "SELECT * FROM master_day_status";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['day_status_id'];
            $disp = $rs['day_status_descr'];

            if($val == $daily_status_id){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $daily_status_dd .= "<option value='$val' $selected>" . $disp . "</option>";
        }
    }else{
        return 0;
    }

    $sqlquery0 = "SELECT * FROM master_incidents";
    if(!$results0 = $objmydbcon->get_result_set($sqlquery0)){
        return false;
    }else if(mysqli_num_rows($results0)>0){
        while($rs0 = mysqli_fetch_assoc($results0)){
            $val = $rs0['incident_id'];
            $disp = $rs0['incident_descr'];

            if($val == $incident_id){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $incidents_dd .= "<option value='$val' $selected>" . $disp . "</option>";
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

    $dropdowns = array($daily_status_dd, $incidents_dd, $student_name);
    return json_encode($dropdowns);

}