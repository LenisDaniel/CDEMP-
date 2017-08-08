<?php

require_once('get_active_scholar_period.php');
$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

$sqlquery = "SELECT dr.day_status_id, dr.create_date, mc.classification_id FROM daily_records dr JOIN master_incidents mi ON mi.incident_id = dr.incident_id JOIN master_classification mc ON mi.classification_id = mc.classification_id WHERE dr.create_date >= '$limit_start' AND dr.create_date <= '$limit_end'";

$absences = 0;
$mild = 0;
$moderate = 0;
$severe = 0;

if(!$results = $objmydbcon->get_result_set($sqlquery)){
    return false;
}else if(mysqli_num_rows($results) > 0){
    while($rs = mysqli_fetch_assoc($results)){
        extract($rs);

        if($day_status_id == 2){
            $absences++;
        }
        if($classification_id == 2){
            $mild++;
        }else if($classification_id == 3){
            $moderate++;
        }else if($classification_id == 4){
            $severe++;
        }
    }
}else{
    return 0;
}

$objtemplate->set_content("absences", $absences);
$objtemplate->set_content("mild", $mild);
$objtemplate->set_content("moderate", $moderate);
$objtemplate->set_content("severe", $severe);

?>