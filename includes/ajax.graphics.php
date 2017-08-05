<?php

require_once('class.mydbcon.inc.php');
$objmydbcon = new classmydbcon();

$sqlquery = "SELECT count(day_status_id) AS ausencias FROM daily_records WHERE day_status_id = 2 GROUP BY group_id";
$group_arr = array();

if(!$results = $objmydbcon->get_result_set($sqlquery)){
    return false;
}else if(mysqli_num_rows($results) > 0){
    while($rs = mysqli_fetch_assoc($results)){
        $group_arr[] = $rs['ausencias'];
    }
}else{
    return 0;
}

echo json_encode($group_arr);

