<?php

/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 3:16 PM
 */

class Schedule{

    var $schedule_td_info;
    var $schedule_info;
    var $field_info;

    function __construct(){
        $this->schedule_td_info = "";
        $this->schedule_info = array();
        $this->field_info = array();
    }

    function get_all_schedules($role = 0, $tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT mu.idx, mu.first_name, mu.last_name, mu.second_surname, mu.email, mu.phone_1, mc.name, mu.active, mu.created_date 
                     FROM master_users mu 
                     INNER JOIN master_cities mc on mc.city_id = mu.city
                     WHERE role_idx = $role";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->schedule_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->schedule_info[0]);
                $this->schedule_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->schedule_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=2&edit=$idu'>" . $this->schedule_info[0]. "</a></td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[1]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[2]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[3]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[4]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[5]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[6]. "</td>";
                $this->schedule_td_info .= "</tr>";

            }

        }else{

            return "No records found!";

        }

        return $this->schedule_td_info;

    }

    function manage_user_info($tpl_uri = 0, $id = 0, $grade = "", $group = "", $course = "", $week_day = "", $day_hour = "", $teacher = ""){
        global $objmydbcon;

//        echo $id . "<br/>";
//        echo $first_name . "<br/>";
//        echo $last_name . "<br/>";
//        echo $second_surname . "<br/>";
//        echo $password . "<br/>";
//        echo $email . "<br/>";//
//        exit;

        if($id > 0){

            $sqlupdate = "UPDATE master_users SET first_name = '$first_name', last_name = '$last_name', second_surname = '$second_surname', password = '$password', email = '$email',
			address1 = '$address1', address2 = '$address2', phone_1 = '$phone_1', phone_2 = '$phone_2', city = '$cities_dd', state = '$states_dd', zipcode = '$zipcode', active = '$active', role_idx = '$role_idx',
			parent_1 = '$parent_1', parent_2 = '$parent_2', phone_1_carrier = '$phone_1_carrier', phone_2_carrier = '$phone_2_carrier'
			WHERE idx = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO master_users (first_name, last_name, second_surname, password, email, address1, address2, phone_1, phone_2, zipcode, city, state, active, role_idx, parent_1, parent_2, phone_1_carrier, phone_2_carrier) 
			              VALUES('$first_name', '$last_name', '$second_surname', '$password', '$email', '$address1', '$address2', '$phone_1', '$phone_2', '$zipcode', '$cities_dd', '$states_dd', '$active', '$role_idx', '$parent_1', '$parent_2', '$phone_1_carrier', '$phone_2_carrier')";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_schedule($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM schedules_teacher WHERE schedule_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->schedule_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->schedule_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_schedule_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

    function get_grades($grade_selected = 0){
        global $objmydbcon;
        $grades_dd = "";

        $sqlquery = "SELECT * FROM master_grade";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){

            while($rs = mysqli_fetch_assoc($results)){

                $val = $rs['grade_id'];
                $disp = $rs['grade_descr'];

                if($grade_selected == $val){

                    $sel_option = "selected";

                }else{

                    $sel_option = "";

                }
                $grades_dd .= "<option value='$val' $sel_option>" . $disp . "</option>";
            }
        }else{
            return 0;
        }

        return $grades_dd;
    }

    function get_groups($selected_groups = 0){
        global $objmydbcon;
        $groups_dd = "";

        $sqlquery = "SELECT * FROM master_group";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['group_id'];
                $disp = $rs['group_descr'];

                if($selected_groups == $val){
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

    function get_courses_1($courses_selected = 0){
        global $objmydbcon;
        $courses_dd = "";

        $sqlquery = "SELECT * FROM master_course";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['course_id'];
                $disp = $rs['course_descr'];

                if($courses_selected == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }

                $courses_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";

            }

        }else{
            return 0;
        }
        return $courses_dd;
    }

    function get_week_days($selected_week_day = ""){
        global $objmydbcon;
        $week_days_dd = "";

        $sqlquery = "SELECT * FROM master_week_day";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['week_day_id'];
                $disp = $rs['week_day_descr'];

                if($selected_week_day == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $week_days_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $week_days_dd;
    }

    function get_day_hours($selected_day_hours = ""){
        global $objmydbcon;
        $day_hours_dd = "";

        $sqlquery = "SELECT * FROM master_day_hour";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['day_hour_id'];
                $disp = $rs['day_hour_descr'];

                if($selected_day_hours == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $day_hours_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $day_hours_dd;
    }

}