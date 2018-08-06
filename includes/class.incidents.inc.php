<?php
error_reporting(E_ERROR);
require_once("class.mydbcon.inc.php");
require_once ("friendly_date.php");
$objmydbcon = new classmydbcon();

class Incidents{

    var $incidents_td_info;
    var $incidents_info;
    var $field_info;

    function __construct(){
        $this->incidents_td_info = "";
        $this->incidents_info = array();
        $this->field_info = array();
    }

    function get_all_incidents($role = 0, $tpl_uri = "", $id = 0, $start = "", $end = "", $grade = 0, $group = 0, $course = 0){
        global $objmydbcon;

        $conditional = "";
        $conditinal1 = "";

        if($role == 4){
            $conditinal1 = "WHERE dr.student_id = $id";
        }else{
            $conditinal1 = "WHERE dr.teacher_id = $id";
        }

        $field = array('dr.create_date', 'dr.create_date', 'dr.grade_id', 'dr.group_id', 'dr.course_id');
        $fields = array($start, $end, $grade, $group, $course);
        for($i = 0; $i < 5; $i++){

            if(!is_numeric($fields[$i])){
                if($fields[$i] != ""){
                    if($i == 0){
                        $conditional .= " AND $field[$i] >= '$fields[$i]'";
                    }else{
                        $conditional .= " AND $field[$i] <= '$fields[$i]'";
                    }
                }else{
                    $conditional .= "";
                }
            }else{
                if($fields[$i] > 0){
                    $conditional .= " AND $field[$i] = $fields[$i]";
                }else{
                    $conditional .= "";
                }
            }
        }

        $i = 0;
        $sqlquery = "SELECT dr.daily_record_id, mu.first_name, mu.last_name, mu.second_surname,mgr.grade_descr, mg.group_descr, mc.course_descr, md.day_status_descr, mi.incident_descr, dr.create_date, mi.classification_id 
                     FROM daily_records dr
                     INNER JOIN master_users mu ON mu.idx = dr.student_id
                     INNER JOIN master_grade mgr ON mgr.grade_id = dr.grade_id
                     INNER JOIN master_group mg ON mg.group_id = dr.group_id
                     INNER JOIN master_course mc ON mc.course_id = dr.course_id
                     INNER JOIN master_day_status md ON md.day_status_id = dr.day_status_id
                     INNER JOIN master_incidents mi ON mi.incident_id = dr.incident_id
                     $conditinal1
                     $conditional
                     ORDER BY dr.create_date DESC";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($this->incidents_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->incidents_info[0]);
                $day_color = $this->get_day_color($this->incidents_info[7]);
                $incident_color = $this->get_incident_color($this->incidents_info[10]);

                $this->incidents_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->incidents_td_info .= "<td id='td$i' class='edit_td'><a href='#'>" . $this->incidents_info[0]. "</a></td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[1]. " " . $this->incidents_info[2] . " " . $this->incidents_info[3] . "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[4]. "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[5]. "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[6]. "</td>";
                $this->incidents_td_info .= "<td><span class='label label-$day_color'>" . $this->incidents_info[7] . "</span></td>";
                $this->incidents_td_info .= "<td><span class='label label-$incident_color'>" . $this->incidents_info[8] . "</span></td>";
                $this->incidents_td_info .= "<td>" . friendly_date($this->incidents_info[9]) . "</td>";
                $this->incidents_td_info .= "</tr>";

            }
        }else{
            return "No records found!";
        }

        return $this->incidents_td_info;
    }

    function get_all_incidents_global($role = 0, $tpl_uri = "", $id = 0, $start = "", $end = "", $grade = 0, $group = 0, $course = 0){
        global $objmydbcon;

        $conditional = "";
        $field = array('dr.create_date', 'dr.create_date', 'dr.grade_id', 'dr.group_id', 'dr.course_id');
        $fields = array($start, $end, $grade, $group, $course);
        for($i = 0; $i < 5; $i++){

            if(!is_numeric($fields[$i])){
                if($fields[$i] != ""){
                    if($i == 0){
                        $conditional .= " AND $field[$i] >= '$fields[$i]'";
                    }else{
                        $conditional .= " AND $field[$i] <= '$fields[$i]'";
                    }
                }else{
                    $conditional .= "";
                }
            }else{
                if($fields[$i] > 0){
                    $conditional .= " AND $field[$i] = $fields[$i]";
                }else{
                    $conditional .= "";
                }
            }
        }

        $i = 0;
        $sqlquery = "SELECT dr.daily_record_id, mu.first_name, mu.last_name, mu.second_surname, mu2.first_name, mu2.last_name, mu2.second_surname,mgr.grade_descr, mg.group_descr, mc.course_descr, md.day_status_descr, mi.incident_descr, dr.create_date, mi.classification_id 
                     FROM daily_records dr
                     INNER JOIN master_users mu ON mu.idx = dr.student_id
                     INNER JOIN master_users mu2 ON mu2.idx = dr.teacher_id
                     INNER JOIN master_grade mgr ON mgr.grade_id = dr.grade_id
                     INNER JOIN master_group mg ON mg.group_id = dr.group_id
                     INNER JOIN master_course mc ON mc.course_id = dr.course_id
                     INNER JOIN master_day_status md ON md.day_status_id = dr.day_status_id
                     INNER JOIN master_incidents mi ON mi.incident_id = dr.incident_id                     
                     $conditional
                     ORDER BY dr.create_date DESC";

//        echo $sqlquery;
//        exit;

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($this->incidents_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->incidents_info[0]);
                $day_color = $this->get_day_color($this->incidents_info[10]);
                $incident_color = $this->get_incident_color($this->incidents_info[13]);

                $this->incidents_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->incidents_td_info .= "<td id='td$i' class='edit_td'><a href='#'>" . $this->incidents_info[0]. "</a></td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[1]. " " . $this->incidents_info[2] . " " . $this->incidents_info[3] . "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[4]. " " . $this->incidents_info[5] . " " . $this->incidents_info[6] . "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[7]. "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[8]. "</td>";
                $this->incidents_td_info .= "<td>" . $this->incidents_info[9]. "</td>";
                $this->incidents_td_info .= "<td><span class='label label-$day_color'>" . $this->incidents_info[10] . "</span></td>";
                $this->incidents_td_info .= "<td><span class='label label-$incident_color'>" . $this->incidents_info[11] . "</span></td>";
                $this->incidents_td_info .= "<td>" . friendly_date($this->incidents_info[12]). "</td>";
                $this->incidents_td_info .= "</tr>";

            }
        }else{
            return "No records found!";
        }

        return $this->incidents_td_info;
    }

    function get_day_color($day_status = ""){
        switch($day_status){
            case "Present":
                $label_color = "success";
            break;
            case "Absent":
            case "Class Cut":
                $label_color = "danger";
                break;
            case "Tardiness":
                $label_color = "warning";
                break;
        }
        return $label_color;
    }

    function get_incident_color($incident = 0){
        switch($incident){
            case "2":
                $label_color = "primary";
                break;
            case "3":
                $label_color = "warning";
                break;
            case "4":
                $label_color = "danger";
                break;
            default:
                $label_color = "default";
                break;
        }
        return $label_color;
    }

    function get_filter_grades($grade_id = 0, $id = 0){
        global $objmydbcon;

        $grade_list = $this->get_teacher_schedule($id)[0];
        $grades_dd = "";
        if($id == 0){
            $sqlquery = "SELECT * FROM master_grade";
        }else{
            $sqlquery = "SELECT * FROM master_grade WHERE grade_id IN($grade_list)";
        }

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['grade_id'];
                $disp = $rs['grade_descr'];

                if($grade_id == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }

                $grades_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }

        return $grades_dd;
    }

    function get_filter_groups($group_id = 0, $id = 0){
        global $objmydbcon;

        $group_list = $this->get_teacher_schedule($id)[1];
        $groups_dd = "";
        if($id == 0){
            $sqlquery = "SELECT * FROM master_group";
        }else{
            $sqlquery = "SELECT * FROM master_group WHERE group_id IN($group_list)";
        }

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['group_id'];
                $disp = $rs['group_descr'];

                if($group_id == $val){
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

    function get_filter_courses($course_id = 0, $id = 0){
        global $objmydbcon;

        $course_list = $this->get_teacher_schedule($id)[2];
        $courses_dd = "";

        if($id == 0){
            $sqlquery = "SELECT * FROM master_course ORDER BY course_descr ASC";
        }else{
            $sqlquery = "SELECT * FROM master_course WHERE course_id IN($course_list) ORDER BY course_descr ASC";
        }

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['course_id'];
                $disp = $rs['course_descr'];

                if($course_id == $val){
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


    function get_filter_courses_student($course_id = 0, $id = 0){
        global $objmydbcon;

        $courses_dd = "";
        $group = $this->get_student_group1($id);
        $courses = $this->get_student_courses($group);

        $sqlquery = "SELECT * FROM master_course WHERE course_id IN($courses) ORDER BY course_descr ASC";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['course_id'];
                $disp = $rs['course_descr'];

                if($course_id == $val){
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

    function get_teacher_schedule($teacher_id = 0){
        global $objmydbcon;

        $grade = "";
        $group = "";
        $course = "";

        $sqlquery = "SELECT grade_id, group_id, course_id FROM schedules_teacher WHERE teacher_id = $teacher_id";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){

                $grade .= $rs['grade_id'] . ",";
                $group .= $rs['group_id'] . ",";
                $course .= $rs['course_id'] . ",";
            }
        }else{
            return 0;
        }

        return array( substr($grade, 0, -1), substr($group, 0, -1), substr($course, 0, -1) );
    }


    function get_student_group1($student_id = 0){
        global $objmydbcon;

        $sqlquery = "SELECT group_id FROM students_groups WHERE student_id = $student_id";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }elseif(mysqli_num_rows($results) > 0 ){
            while($rs = mysqli_fetch_assoc ($results)){
                $student_group = $rs['group_id'];
            }
        }else{
            return 0;
        }
        return $student_group;

    }

    function get_student_courses($group = 0){
        global $objmydbcon;

        $course = "";
        $sqlquery = "SELECT course_id FROM schedules_teacher WHERE group_id = $group";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $course .= $rs['course_id'] . ",";
            }
        }else{
            return 0;
        }

        return substr($course, 0, -1);

    }


}