<?php
require_once ("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

if(isset($_POST) && $_POST != ""){
    extract($_POST);

    $sqlupdate = "UPDATE appointments SET viewed = 1 WHERE appointment_id = $id";
    if($objmydbcon->set_query($sqlupdate)){
        echo true;
    }else{
        echo false;
    }

}