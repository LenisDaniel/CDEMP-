<?php

require_once("class.mydbcon.inc.php");
require_once ("friendly_date.php");
$objmydbcon = new classmydbcon;

if(isset($_POST)){
    extract($_POST);

    if($event_id != "" && $user_id != "" && $message_text != ""){

        $div_ids = $div_id;

        $sqlquery = "INSERT INTO event_interactions (event_id, user_id, message) VALUES ($event_id, $user_id, '$message_text')";
        if($objmydbcon->set_query($sqlquery)){

            $last_id = $objmydbcon->get_last_id();

            $sqlquery0 = "SELECT ei.interaction_id, ei.event_id AS ev_id, ei.user_id AS uid, ei.message AS msg, ei.created_date AS c_date, m.first_name AS fname, m.last_name AS lname, m.second_surname AS sname FROM event_interactions ei JOIN master_users m ON m.idx = ei.user_id WHERE ei.interaction_id = $last_id AND ei.event_id = $event_id ORDER BY ei.created_date DESC";
            $comments = array();
            if(!$results0 = $objmydbcon->get_result_set($sqlquery0)){
                return false;
            }else if(mysqli_num_rows($results0) > 0){
                while($rs0 = mysqli_fetch_assoc($results0)){
                    extract($rs0);
                    $name0 = $fname . " " . $lname . " " . $sname;
                    $creator = get_event_creator($uid);

                    if($creator == $uid){
                        $conditional_color = 'style="padding-left: 10px;border-left: 3px solid #2399D5;margin-bottom: 30px;"';
                    }else{
                        $conditional_color = 'style="padding-left: 10px;border-left: 3px solid #a21b24;margin-bottom: 30px;"';
                    }
                    $comments[] = '<div class="media"  '.$conditional_color.'>
                                <div id="example_'.$last_id. '-' .$uid.'" class="media-body comment_line">
                                    <h4 class="media-heading">'.$name0.'
                                        <small>'.friendly_date($c_date).'</small>
                                        <i class="fa fa-times pull-right edit_x_'.$div_ids.'" id="delete_'.$last_id. '-' .$uid.'" style="display:none; color: #999999;"></i>
                                        <i class="fa fa-ellipsis-h pull-right edit_points_'.$div_ids.'" id="edit_'.$last_id. '-' .$uid.'" style="display:none; color: #999999;"></i>                                        
                                    </h4>
                                    <span>'.$msg.'</span>
                                </div>
                              </div>';
                }

            }else{
                $comments = "";
            }

            foreach($comments as $value){
                $insert_comments .= $value;
            }

            echo $insert_comments;

        }else{
            echo "fail";
        }

    }

}

function get_event_creator($event_id = 0){
    global $objmydbcon;
    $sqlquery = "SELECT created_by FROM events WHERE event_id = $event_id";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return 0;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['created_by'];
}

