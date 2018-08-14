<?php
require_once ('friendly_date.php');
/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 3:16 PM
 */

class Users{

    var $user_td_info;
    var $user_info;
    var $field_info;

    function __construct(){
        $this->user_td_info = "";
        $this->user_info = array();
        $this->field_info = array();
    }

    function get_all_users($role = 0, $tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        if($role == 4){
            $sqlquery = "SELECT mu.idx, mu.first_name, mu.last_name, mu.second_surname, mu.email, mu.phone_1, mc.name, mu.active, mu.created_date, sg.group_id, mg.group_descr
                         FROM master_users mu 
                         INNER JOIN master_cities mc on mc.city_id = mu.city
                         JOIN students_groups sg ON sg.student_id = mu.idx
                         JOIN master_group mg ON mg.group_id = sg.group_id
                         WHERE mu.role_idx = $role AND mu.active = 1";
        }else{
            $sqlquery = "SELECT mu.idx, mu.first_name, mu.last_name, mu.second_surname, mu.email, mu.phone_1, mc.name, mu.active, mu.created_date 
                         FROM master_users mu 
                         INNER JOIN master_cities mc on mc.city_id = mu.city
                         WHERE role_idx = $role AND active = 1";
        }


        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->user_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->user_info[0]);

                $this->user_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->user_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=2&edit=$idu'>" . $this->user_info[0]. "</a></td>";
                $this->user_td_info .= "<td>" . $this->user_info[1]. " " . $this->user_info[2] . " " . $this->user_info[3] . "</td>";
                $this->user_td_info .= "<td>" . $this->user_info[4]. "</td>";
                $this->user_td_info .= "<td>" . $this->user_info[5]. "</td>";
                $this->user_td_info .= "<td>" . $this->user_info[6]. "</td>";
                if($role == 4){
                    $this->user_td_info .= "<td>" . $this->user_info[10]. "</td>";
                    $this->user_td_info .= "<td>Active</td>";
                }else{
                    $this->user_td_info .= "<td>Active</td>";
                }

                $this->user_td_info .= "<td>" . friendly_date($this->user_info[8]). "</td>";
                $this->user_td_info .= "</tr>";

            }

        }else{

            return "No records found!";

        }

        return $this->user_td_info;

    }

    function manage_user_info($tpl_uri = 0, $id = 0, $first_name = "", $last_name = "", $second_surname = "", $username = "", $password = "", $email = "", $address1 = "", $address2 = "", $phone_1 = "", $phone_2 = "", $cities_dd = "", $states_dd = "", $zipcode = "", $active = 0, $role_idx = 0, $parent_1 = "", $parent_2 = "", $phone_1_carrier = "", $phone_2_carrier = ""){
        global $objmydbcon;

        if($active == 1){
            $active = 1;
        }else{
            $active = 0;
        }

        //        if(strlen($password) != 32){
        //            $password = md5($password);
        //        }

        if($id > 0){

            $sqlupdate = "UPDATE master_users SET first_name = '$first_name', last_name = '$last_name', second_surname = '$second_surname', username = '$username', password = '$password', email = '$email',
			address1 = '$address1', address2 = '$address2', phone_1 = '$phone_1', phone_2 = '$phone_2', city = '$cities_dd', state = '$states_dd', zipcode = '$zipcode', active = '$active', role_idx = '$role_idx',
			parent_1 = '$parent_1', parent_2 = '$parent_2', phone_1_carrier = '$phone_1_carrier', phone_2_carrier = '$phone_2_carrier'
			WHERE idx = $id";

            if($objmydbcon->set_query($sqlupdate)){
                if($role_idx == 1 || $role_idx == 2 || $role_idx == 3){
                    header("location: display_page.php?tpl=$tpl_uri&cat=2");
                }else{
                    return $id;
                }
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO master_users (first_name, last_name, second_surname, username, password, email, address1, address2, phone_1, phone_2, zipcode, city, state, active, role_idx, parent_1, parent_2, phone_1_carrier, phone_2_carrier) 
			              VALUES('$first_name', '$last_name', '$second_surname', '$username', '$password', '$email', '$address1', '$address2', '$phone_1', '$phone_2', '$zipcode', '$cities_dd', '$states_dd', '$active', '$role_idx', '$parent_1', '$parent_2', '$phone_1_carrier', '$phone_2_carrier')";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                if($role_idx == 1 || $role_idx == 2 || $role_idx == 3){
                    header("location: display_page.php?tpl=$tpl_uri&cat=2");
                }else{
                    return $last_id;
                }

            }else{
                return false;
            }
        }

    }

    function get_user($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_users WHERE idx = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->user_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->user_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_user_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

    function get_cities($user_city = 0){
        global $objmydbcon;
        $cities_dd = "";

        $sqlquery = "SELECT * FROM master_cities";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){

            while($rs = mysqli_fetch_assoc($results)){

                $val = $rs['city_id'];
                $disp = $rs['name'];

                if($user_city == $val){

                    $sel_option = "selected";

                }else{

                    $sel_option = "";

                }
                $cities_dd .= "<option value='$val' $sel_option>" . $disp . "</option>";
            }
        }else{
            return 0;
        }

        return $cities_dd;
    }

    function get_state($user_state = 0){
        global $objmydbcon;
        $states_dd = "";

        $sqlquery = "SELECT * FROM master_state";


        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['state_id'];
                $disp = $rs['state_descr'];

                if($user_state == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $states_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $states_dd;
    }

    function get_zipcodes($user_zipcode = ""){
        global $objmydbcon;
        $zipcodes_dd = "";

        $sqlquery = "SELECT * FROM master_zipcode ORDER BY zip ASC";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['zip_id'];
                $disp = $rs['zip'];

                if($user_zipcode == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $zipcodes_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $zipcodes_dd;
    }

    function get_carriers($user_carrier = ""){
        global $objmydbcon;
        $carriers_dd = "";

        $sqlquery = "SELECT * FROM mast_carriers";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['idx'];
                $disp = $rs['carrier_descr'];

                if($user_carrier == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $carriers_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $carriers_dd;
    }

}