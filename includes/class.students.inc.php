<?php

/**
 * Created by PhpStorm.
 * File: class.students.inc.php
 * User: Christian J. Negron Garcia
 * Date: 5/20/2017
 * Time: 11:38 PM
 */

require_once("class.users.inc.php");

class Students extends Users {

    function assign_group($group = 0, $id = 0){
        global $objmydbcon;

        $sqlinsert = "INSERT INTO students_groups(student_id, group_id)VALUES($id, $group)";
        if($objmydbcon->set_query($sqlinsert)){
            header("location: display_page.php?tpl=manage_students&cat=2");
        }else{
            return false;
        }
    }

    function get_groups($grade = 0){
        global $objmydbcon;
        $groups_dd = "";
        $sqlquery = "SELECT * FROM master_group";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){

            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['group_id'];
                $disp = $rs['group_descr'];

                if($grade == $val){
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

    function get_group_info($idx = 0){
        global $objmydbcon;
        $sqlquery = "SELECT group_id FROM students_groups WHERE student_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            $rs = mysqli_fetch_assoc($results);
            $group_id = $rs['group_id'];
        }else{
            return 0;
        }
        return $group_id;

    }

}
