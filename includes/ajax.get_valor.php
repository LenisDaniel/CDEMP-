<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST)){
    extract($_POST);



    $sqlquery = "SELECT max_punctuation FROM grade_identifier WHERE grade_identifier_id = $id";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }elseif(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
        extract($rs);
        echo $max_punctuation;
    }else{
        return "";
    }
}

