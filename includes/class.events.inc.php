<?php
require_once ("friendly_date.php");
require_once('get_active_scholar_period.php');

$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

class Events{

    var $events_td_info;
    var $event_info;
    var $field_info;

    function __construct(){
        $this->events_td_info = "";
        $this->event_info = array();
        $this->field_info = array();
    }

    function get_all_events($tpl_uri = "", $created_by = 0, $role = 0){
        global $objmydbcon;
        global $limit_date;
        global $limit_start;
        global $limit_end;

        $i = 0;
        if($role == 1){
            $conditional = "OR role_idx = 1 AND created_date >= '$limit_start' AND created_date <= '$limit_end'";
            $sqlquery = "SELECT event_id, group_id, course_id, event_descr, active, created_date
                         FROM events                         
                         WHERE active = 1 AND created_by = $created_by AND created_date >= '$limit_start' AND created_date <= '$limit_end' $conditional";

        }else{

            $conditional = "AND created_date >= '$limit_start' AND created_date <= '$limit_end'";
            $sqlquery = "SELECT e.event_id, mg.group_descr, mc.course_descr, e.event_descr, e.active, e.created_date
                         FROM events e
                         JOIN master_group mg ON mg.group_id = e.group_id
                         JOIN master_course mc ON mc.course_id = e.course_id
                         WHERE e.active = 1 AND e.created_by = $created_by $conditional";

        }

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->event_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->event_info[0]);

                $this->events_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->events_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=3&edit=$idu'>" . $this->event_info[0]. "</a></td>";
                $this->events_td_info .= "<td>" . $this->event_info[1]. "</td>";
                $this->events_td_info .= "<td>" . $this->event_info[2]. "</td>";
                $this->events_td_info .= "<td>" . $this->event_info[3]. "</td>";
                $this->events_td_info .= "<td>Active</td>";
                $this->events_td_info .= "<td>" . friendly_date($this->event_info[5]) . "</td>";
                $this->events_td_info .= "</tr>";

            }

        }else{
            return "No records found!";
        }
        return $this->events_td_info;

    }

    function manage_event_info($tpl_uri = "", $id = 0, $created_by = 0, $role_idx = 0, $group_id = 0, $course_id = 0, $event_descr = "", $event_details = "", $event_date = "", $on_calendar = 0, $active = 0){
        global $objmydbcon;

        if($active == 1){
            $active = 1;
        }else{
            $active = 0;
        }

        //echo $event_date;
        //exit;

        if($id > 0){

            $sqlupdate = "UPDATE events SET group_id = '$group_id', course_id = '$course_id', event_descr = '$event_descr', event_details = '$event_details', event_date = '$event_date', active = '$active' WHERE event_id = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=4");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO events(created_by, role_idx, group_id, course_id, event_descr, event_details, event_date, on_calendar, active)VALUES($created_by, $role_idx, $group_id, $course_id, '$event_descr', '$event_details', '$event_date', $on_calendar, $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=4");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_event($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM events WHERE event_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->event_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->event_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_event_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

    function get_groups($group_id = 0, $teacher_id = 0){
        global $objmydbcon;

        $groups_dd = "";
        $groups = $this->get_my_groups($teacher_id);

        foreach($groups as $value){
            $groups_list .= $value . ",";
        }
        $groups_list = substr($groups_list, 0, -1);

        $sqlquery = "SELECT * FROM master_group WHERE group_id IN($groups_list)";
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

    function get_my_groups($teacher_id = 0){
        global $objmydbcon;
        $groups = array();

        $sqlquery = "SELECT DISTINCT group_id FROM schedules_teacher WHERE teacher_id = $teacher_id";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_assoc($results)){
                extract($rs);
                $groups[] = $group_id;
            }
        }else{
            return 0;
        }

        return $groups;

    }



}