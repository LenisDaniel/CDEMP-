<?php
error_reporting(E_ERROR);
require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

class My_Profile
{
    var $schedule_td;

    function __construct()
    {
        $this->schedule_td = "";
    }

    function get_student_info($student_id = 0){
        global $objmydbcon;
        $student_info = array();
        $sqlquery = "SELECT mu.first_name, mu.last_name, mu.second_surname, mgde.grade_descr, mgrp.group_descr, mu.parent_1, mu.phone_1, mu.phone_2, mu.email, mu.address1, mu.address2, mc.name, ms.state_descr, mz.zip 
                     FROM master_users mu
                     JOIN students_groups sg ON  sg.student_id = $student_id
                     JOIN master_group mgrp ON mgrp.group_id = sg.group_id
                     JOIN master_grade mgde ON mgde.grade_id = mgrp.grade_id
                     JOIN master_cities mc ON mc.city_id = mu.city
                     JOIN master_state ms ON ms.state_idx = mu.state
                     JOIN master_zipcode mz ON mz.zip_id = mu.zipcode
                     WHERE mu.idx = $student_id";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_assoc($results)){
                $student_info['name'] = $rs['first_name'] . " " . $rs['last_name'] . " " . $rs['second_surname'];
                $student_info['grade'] = $rs['grade_descr'];
                $student_info['group'] = $rs['group_descr'];
                $student_info['parent'] = $rs['parent_1'];
                $student_info['phone_1'] = $rs['phone_1'];
                $student_info['phone_2'] = $rs['phone_2'];
                $student_info['email'] = $rs['email'];
                $student_info['address'] = $rs['address1'] . " " . $rs['address2']. " " . $rs['name'] . ", " . $rs['state_descr'] . ", " . $rs['zip'];
            }
        }else{
            return 0;
        }

        return $student_info;
    }

    function get_student_group($student_id = 0){
        global $objmydbcon;

        $sqlquery = "SELECT group_id FROM students_groups WHERE student_id = 4";

        if(!$result = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($result) > 0){
            $rs = mysqli_fetch_assoc($result);
        }else{
            return 0;
        }
        return $rs['group_id'];
    }

    function get_student_schedule($group_id = 0){
        global $objmydbcon;

        $sqlquery = "SELECT mw.week_day_descr, md.day_hour_descr, mc.course_descr, mu.first_name, mu.last_name, mu.second_surname, md.day_hour_id 
                     FROM schedules_teacher st
                     JOIN master_week_day mw ON mw.week_day_id = st.week_day_id
                     JOIN master_day_hour md ON md.day_hour_id = st.day_hour_id
                     JOIN master_course mc ON mc.course_id = st.course_id
                     JOIN master_users mu ON mu.idx = st.teacher_id
                     WHERE group_id = $group_id 
                     GROUP BY st.course_id
                     ORDER BY md.day_hour_id;";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_array($results)){

                $this->schedule_td .= "<tr>";
                $this->schedule_td .= "<td>" . $rs[0]. "</td>";
                $this->schedule_td .= "<td>" . $rs[1]. "</td>";
                $this->schedule_td .= "<td>" . $rs[2]. "</td>";
                $this->schedule_td .= "<td>" . $rs[3]. " " . $rs[4] . " " . $rs[5] . "</td>";
                $this->schedule_td .= "</tr>";

            }
        }else{
            return 0;
        }

        return $this->schedule_td;
    }

}