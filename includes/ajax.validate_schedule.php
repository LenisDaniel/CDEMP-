<?php
require_once("class.mydbcon.inc.php");
require_once("get_active_scholar_period.php");
$objmydbcon = new classmydbcon();

$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

if(isset($_POST) && $_POST != ""){
    extract($_POST);

    $grade_id = $grade;
    $group_id = $group;
    $day_hour_id = $day_hour;
    $day_hour_assigned = "";

    $sqlquery = "SELECT st.grade_id, st.group_id, st.day_hour_id, st.teacher_id, mg.group_descr, mu.first_name, mu.last_name, mdh.day_hour_descr, mwd.week_day_descr 
                 FROM schedules_teacher st 
                 JOIN master_group mg ON mg.group_id = st.group_id
                 JOIN master_users mu ON mu.idx = st.teacher_id
                 JOIN master_day_hour mdh ON mdh.day_hour_id = st.day_hour_id
                 JOIN master_week_day mwd ON mwd.week_day_id = st.week_day_id
                 WHERE st.grade_id = $grade_id AND st.group_id = $group_id AND st.day_hour_id = $day_hour_id 
                 AND st.created_date >= '$limit_start' AND st.created_date <= '$limit_end'";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        while($rs = mysqli_fetch_assoc($results)){
            //encontro algun item
            extract($rs);
            $name = $first_name . " " . $last_name;
            $day_hour_assigned .= "The group $group_descr has classes at $day_hour_descr on $week_day_descr with $name";
            $day_hour_assigned .= "<br>";
            echo $day_hour_assigned;
        }
    }else{
        echo "continue";
    }

}