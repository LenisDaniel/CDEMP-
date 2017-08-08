<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

if(isset($_POST)){
    extract($_POST);

    $sqlquery = "SELECT idx FROM master_users WHERE username = '$username'";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        echo 1;
    }else{
        echo 0;
    }

}