<?php
require_once('class.mydbcon.inc.php');
$objmydbcon = new classmydbcon();

if(isset($_POST) && $_POST['id'] != ""){
    extract($_POST);

    $sqldelete = "DELETE FROM $table WHERE grade_id = $id";
    if($objmydbcon->set_query($sqldelete)){
        echo "done";
    }else{
        echo "fail";
    }

}