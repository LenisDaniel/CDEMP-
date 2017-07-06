<?php

/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 7/1/2017
 * Time: 7:06 AM
 */

class Appointments{

    var $appoint_td_info;
    var $appoint_info;
    var $field_info;

    function __construct(){
        $this->appoint_td_info = "";
        $this->appoint_info = array();
        $this->field_info = array();
    }

    function get_all_appointments($tpl_uri = "", $role = 0, $creator = 0){
        global $objmydbcon;

        $i = 0;
        if($role == 1 || $role == 2){
            $cat = 3;
        }else if($role == 4){
            $cat = 5;
        }else{
            $cat = 4;
        }


        $sqlquery = "SELECT a.*, mu.first_name, mu.last_name, mu.second_surname FROM appointments a JOIN master_users mu ON mu.idx = a.date_with WHERE date_with = $creator OR created_by = $creator";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->appoint_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->appoint_info[0]);

                if($creator == $this->appoint_info[2]){
                    $day_color = "warning";
                }else{
                    $day_color = "primary";
                }

                $this->appoint_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                if($role == 4){
                    $this->appoint_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=$cat&edit=$idu'><span class='label label-$day_color'>" . $this->appoint_info[0]. "</span></a></td>";
                }else{
                    $this->appoint_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=$cat&edit=$idu'><span class='label label-$day_color'>" . $this->appoint_info[0]. "</span></a></td>";
                }
                $this->appoint_td_info .= "<td>" . $this->appoint_info[12]. ' ' . $this->appoint_info[13]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[3]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[4]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[5]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[6]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[7]. "</td>";
                $this->appoint_td_info .= "<td>" . $this->appoint_info[8]. "</td>";
                $this->appoint_td_info .= "</tr>";

            }

        }else{
            return "";
        }
        return $this->appoint_td_info;

    }


    function manage_appoint_info($tpl_uri = "", $id = 0, $appoint_descr = "", $appoint_time = "", $appoint_place = "", $appoint_details = "", $users_list = "", $unique_number = 0, $creator = 0, $active = 0){
        global $objmydbcon;

        if($id <= 0){
            $unique_number = $this->get_max_number() + 1;
        }

        if($active != 1){
            $active = 0;
        }

        if(strpos($users_list, ',')){

            $list = explode(',', $users_list);

            foreach($list as $value){
                $this->insert_appointments($tpl_uri,$id, $value, $appoint_descr, $appoint_time, $appoint_place, $appoint_details, 0, $unique_number, $creator, $active);
            }

        }else{
            $this->insert_appointments($tpl_uri, $id, $users_list, $appoint_descr, $appoint_time, $appoint_place, $appoint_details, 0, $unique_number, $creator, $active);
        }

    }

    function insert_appointments($tpl_uri = "", $id = 0, $users_list = 0, $appoint_descr = "", $appoint_time = "", $appoint_place = "", $appoint_details = "", $viewed = 0, $unique_number = 0, $creator = 0, $active = 0){
        global $objmydbcon;

        if($id > 0){

            $sqlupdate = "UPDATE appointments SET appointment_descr = '$appoint_descr', appointment_time = '$appoint_time', appointment_place = '$appoint_place', appointment_details = '$appoint_details', active = $active WHERE unique_number = $unique_number";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=4");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO appointments(unique_number, date_with, appointment_descr, appointment_time, appointment_place, appointment_details, viewed, created_by, active)VALUES($unique_number,'$users_list', '$appoint_descr', '$appoint_time', '$appoint_place', '$appoint_details', $viewed, $creator, $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=4");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_appointment($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM appointments WHERE appointment_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->appoint_info = mysqli_fetch_assoc($results)){
                $this->field_info = $this->appoint_info;
            }
        }else{
            return "No results found";
        }

        return $this->field_info;
    }

    function get_appointment_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{
            return false;
        }
    }


    function get_users_type(){
        global $objmydbcon;

        $users_type = "";

        $sqlquery = "SELECT * FROM master_roles";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['role_idx'];
                $disp = $rs['role_descr'];

                $users_type .= "<option value='$val'>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $users_type;
    }

    function get_groups(){
        global $objmydbcon;

        $groups_dd = "";

        $sqlquery = "SELECT * FROM master_group";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['group_id'];
                $disp = $rs['group_descr'];

                $groups_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $groups_dd;
    }

    function get_max_number(){
        global $objmydbcon;

        $sqlquery = "SELECT max(unique_number) AS higher FROM appointments";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            $rs = mysqli_fetch_assoc($results);
        }else{
            return 0;
        }
        return $rs['higher'];

    }

    function set_as_viewed($idx = 0){
        global $objmydbcon;
        $sqlupdate = "UPDATE appointments SET viewed = 1 WHERE appointment_id = $idx";
        if($objmydbcon->set_query($sqlupdate)){
            return true;
        }else{
            return false;
        }
    }

}