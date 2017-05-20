<?php

/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 3:16 PM
 */

class Teachers{

    var $teacher_td_info;
    var $teacher_info;
    var $field_info;

    function __construct(){
        $this->teacher_td_info = "";
        $this->teacher_info = array();
        $this->field_info = array();
    }

    function get_all_teachers(){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT mu.idx, mu.first_name, mu.last_name, mu.second_surname, mu.email, mu.phone_1, mc.name, mu.active, mu.created_date 
                     FROM master_users mu 
                     INNER JOIN master_cities mc on mc.city_id = mu.city
                     WHERE role_idx = 3";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->teacher_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->teacher_info[0]);

                $teacher_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $teacher_td_info .= "<td id='td$i'><a href='display_page.php?tpl=manage_teachers&cat=2&edit=$idu'>" . $this->teacher_info[0]. "</a></td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[1]. " " . $this->teacher_info[2] . " " . $this->teacher_info[3] . "</td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[4]. "</td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[5]. "</td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[6]. "</td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[7]. "</td>";
                $teacher_td_info .= "<td>" . $this->teacher_info[8]. "</td>";
                $teacher_td_info .= "</tr>";

            }

        }else{

            return "No records found!";

        }

        return $teacher_td_info;

    }

    function manage_teacher_info($id = 0, $first_name = "", $last_name = "", $second_surname = "", $password = "", $email = "", $address1 = "", $address2 = "", $phone_1 = "", $phone_2 = "", $cities_dd = "", $states_dd = "", $zipcode = "", $active = 0, $role_idx = 0){
        global $objmydbcon;

//        echo $id . "<br/>";
//        echo $first_name . "<br/>";
//        echo $last_name . "<br/>";
//        echo $second_surname . "<br/>";
//        echo $password . "<br/>";
//        echo $email . "<br/>";
//        echo $address1 . "<br/>";
//        echo $address2 . "<br/>";
//        echo $phone_1 . "<br/>";
//        echo $phone_2 . "<br/>";
//        echo $cities_dd . "<br/>";
//        echo $states_dd . "<br/>";
//        echo $zipcode . "<br/>";
//        echo $active . "<br/>";
//        echo $role_idx . "<br/>";
//
//        exit;

        if(!$active == "1"){
            $active = 0;
        }

        if(strlen($password) != 32){
            $password = md5($password);
        }



        if($id > 0){

            $sqlupdate = "UPDATE master_users SET first_name = '$first_name', last_name = '$last_name', second_surname = '$second_surname', password = '$password', email = '$email',
			address1 = '$address1', address2 = '$address2', phone_1 = '$phone_1', phone_2 = '$phone_2', city = '$cities_dd', state = '$states_dd', zipcode = '$zipcode', active = '$active', role_idx = '$role_idx'
			WHERE idx = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=manage_teachers&cat=2&edit=" . base64_encode($id));
                return true;
            }else{
                return false;
            }

        }else{



            $sqlinsert = "INSERT INTO master_users (first_name, last_name, second_surname, password, email, address1, address2, phone_1, phone_2, zipcode, city, state, active, role_idx) 
			              VALUES('$first_name', '$last_name', '$second_surname', '$password', '$email', '$address1', '$address2', '$phone_1', '$phone_2', '$zipcode', '$cities_dd', '$states_dd', '$active', '$role_idx')";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=manage_teachers&cat=2&edit=" . base64_encode($last_id));
                return true;
            }else{
                return false;
            }
        }

    }

    function get_teacher($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_users WHERE idx = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->teacher_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->teacher_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_teacher_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }


    function get_cities($teacher_city = 0){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_cities";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){

            return false;

        }else if(mysqli_num_rows($results)>0){

            while($rs = mysqli_fetch_assoc($results)){

                $val = $rs['city_id'];
                $disp = $rs['name'];

                if($teacher_city == $val){

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

    function get_state($teacher_state = 0){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_states";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['state_idx'];
                $disp = $rs['state_descr'];

                if($teacher_state == $val){
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


}