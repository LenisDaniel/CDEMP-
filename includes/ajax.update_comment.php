<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST)) {
    extract($_POST);

    if($action == 'delete'){

        $msg_info = explode("-", $comment_info[1]);
        $msg_id = $msg_info[0];
        $user_id = $msg_info[1];

        $sqldelete = "DELETE FROM event_interactions WHERE interaction_id = $msg_id";
        if($objmydbcon->set_query($sqldelete)){
            echo "funciono";
        }else{
            echo "fallo";
        }

    }

    if($action == 'edit'){

        $msg_info = explode("-", $comment_info);
        $msg_id = $msg_info[0];
        $comment = $msg;

        $sqlupdate = "UPDATE event_interactions SET message = '$comment' WHERE interaction_id = $msg_id";

        if($objmydbcon->set_query($sqlupdate)){
            echo $comment;
        }else{
            echo 'fallo';
        }

    }


}