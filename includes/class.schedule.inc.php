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

    function get_all_schedules($tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT st.schedule_id, mgr.grade_descr, mgro.group_descr, mc.course_descr, wd.week_day_descr,
                     dh.day_hour_descr, mu.first_name, mu.last_name, mu.second_surname, st.created_date, sp.start_date, sp.end_date
                     FROM schedules_teacher st
                     INNER JOIN master_grade mgr ON mgr.grade_id = st.grade_id
                     INNER JOIN master_group mgro ON mgro.group_id = st.group_id
                     INNER JOIN master_course mc ON mc.course_id = st.course_id
                     INNER JOIN master_week_day wd ON wd.week_day_id = st.week_day_id
                     INNER JOIN master_day_hour dh ON dh.day_hour_id = st.day_hour_id
                     INNER JOIN master_users mu ON mu.idx = st.teacher_id
                     INNER JOIN scholar_period sp ON sp.active = 1
                     WHERE st.created_date > sp.start_date AND st.created_date < sp.end_date";


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
                $this->schedule_td_info .= "<td>" . $this->schedule_info[6] . " " .  $this->schedule_info[7] . " " .  $this->schedule_info[8]. "</td>";
                $this->schedule_td_info .= "<td>" . $this->schedule_info[9]. "</td>";
                $this->schedule_td_info .= "</tr>";

            }

        }else{

            return "No records found!";

        }

        return $this->schedule_td_info;

    }

    function manage_schedule_info($tpl_uri = 0, $id = 0, $grade = "", $group = "", $course = "", $week_day = "", $day_hour = "", $teacher = ""){
        global $objmydbcon;

        if($id > 0){

            $sqlupdate = "UPDATE schedules_teacher SET grade_id = '$grade', group_id = '$group', course_id = '$course', 
                          week_day_id = '$week_day', day_hour_id = '$day_hour', teacher_id = '$teacher'			
			              WHERE schedule_id = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO schedules_teacher (grade_id, group_id, course_id, week_day_id, day_hour_id, teacher_id) 
			              VALUES('$grade', '$group', '$course', '$week_day', '$day_hour', '$teacher')";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
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

}