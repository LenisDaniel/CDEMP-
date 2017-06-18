<?php

class Incidents{

    var $incidents_td_info;
    var $incidents_info;
    var $field_info;

    function __construct(){
        $this->incidents_td_info = "";
        $this->incidents_info = array();
        $this->field_info = array();
    }

    function get_all_incidents($role = 0, $tpl_uri = "", $teacher_id = 0){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT dr.daily_record_id, mu.first_name, mu.last_name, mu.second_surname,mgr.grade_descr, mg.group_descr, mc.course_descr, md.day_status_descr, mi.incident_descr, dr.create_date, mi.classification_id 
                     FROM daily_records dr
                     INNER JOIN master_users mu ON mu.idx = dr.student_id
                     INNER JOIN master_grade mgr ON mgr.grade_id = dr.grade_id
                     INNER JOIN master_group mg ON mg.group_id = dr.group_id
                     INNER JOIN master_course mc ON mc.course_id = dr.course_id
                     INNER JOIN master_day_status md ON md.day_status_id = dr.day_status_id
                     INNER JOIN master_incidents mi ON mi.incident_id = dr.incident_id
                     WHERE dr.teacher_id = $teacher_id
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
                $this->incidents_td_info .= "<td>" . $this->incidents_info[9]. "</td>";
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

}