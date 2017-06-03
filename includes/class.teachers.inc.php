<?php
/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 3:16 PM
 */

require_once("class.users.inc.php");

class Teachers extends Users {



    function get_teachers($teacher_selected = ""){
        global $objmydbcon;
        $teachers_dd = "";

        $sqlquery = "SELECT * FROM master_users WHERE role_idx = 3";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['idx'];
                $disp = $rs['first_name'] . " " . $rs['last_name'] . " " . $rs['second_surname'];

                if($teacher_selected == $val){

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

    function get_teacher_selected_courses($teacher_id){
        global $objmydbcon;
        $course_id = array();
        $i = 0;

        $sqlquery = "SELECT course_id FROM course_teacher WHERE teacher_id = $teacher_id";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return "Connection Problems";
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_row($results)){

                $course_id[] = $rs[0];
            }

        }else{
            return "No records found";
        }

        return $course_id;

    }

}