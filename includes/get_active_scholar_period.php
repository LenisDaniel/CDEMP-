<?php
require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

function get_scholar_period(){
    global $objmydbcon;

    $sqlquery = "SELECT * FROM scholar_period WHERE active = 1";
    $limit_dates = array();

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
        $limit_dates['start'] = $rs['start_date'];
        $limit_dates['end'] = $rs['end_date'];
    }else{
        return 0;
    }
    return $limit_dates;
}