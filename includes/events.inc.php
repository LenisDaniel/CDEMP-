<?php
require_once('Mobile_Detect.php');
require_once('get_active_scholar_period.php');
require_once ('friendly_date.php');
$detect = new Mobile_Detect;

$limit_date = get_scholar_period();
$limit_start = $limit_date['start'];
$limit_end = $limit_date['end'];

$user_ids = $_SESSION['loged_user']['idx'];
$objtemplate->set_content('user_id', $user_ids);

if($detect->isMobile()){

    if($_SESSION['loged_user']['role_idx'] == 4){
        $grp = get_student_group($user_ids);
        $conditional = "WHERE e.role_idx = 1 OR e.group_id = $grp AND e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 30";
    }else if($_SESSION['loged_user']['role_idx'] == 3){
        $conditional = "WHERE e.role_idx = 1 OR e.created_by = $user_ids AND e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 30";
    }else{
        $conditional = "WHERE e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 30";
    }
    $objtemplate->set_content('mobile', 1);

}else{

    if($_SESSION['loged_user']['role_idx'] == 4){
        $grp = get_student_group($user_ids);
        $conditional = "WHERE e.role_idx = 1 OR e.group_id = $grp AND e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 0,5";
    }else if($_SESSION['loged_user']['role_idx'] == 3){
        $conditional = "WHERE e.role_idx = 1 OR e.created_by = $user_ids AND e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 0,5";
    }else{
        $conditional = "WHERE e.created_date >= '$limit_start' AND e.created_date <= '$limit_end' AND e.active = 1 ORDER BY e.created_date DESC LIMIT 0,5";
    }
    $objtemplate->set_content('mobile', 0);

}

$i = 1;

$sqlquery = "SELECT e.*, mu.first_name, mu.last_name, mu.second_surname FROM events e JOIN master_users mu ON mu.idx = e.created_by             
             $conditional";

if(!$results = $objmydbcon->get_result_set($sqlquery)){
    return false;
}else if(mysqli_num_rows($results) > 0){
    while($rs = mysqli_fetch_assoc($results)){
        extract($rs);

        $sqlquery0 = "SELECT ei.interaction_id AS interaction, ei.event_id AS ev_id, ei.user_id AS uid, ei.message AS msg, ei.created_date AS c_date, m.first_name AS fname, m.last_name AS lname, m.second_surname AS sname FROM event_interactions ei JOIN master_users m ON m.idx = ei.user_id WHERE ei.event_id = $event_id ORDER BY ei.created_date DESC";
        $comments = array();
        if(!$results0 = $objmydbcon->get_result_set($sqlquery0)){
            return false;
        }else if(mysqli_num_rows($results0) > 0){
            while($rs0 = mysqli_fetch_assoc($results0)){
                extract($rs0);
                $role = get_creator_role($uid);
                $name0 = $fname . " " . $lname . " " . $sname;
                if($created_by == $uid){
                    $conditional_color = 'style="padding-left: 10px;border-left: 3px solid #2399D5;margin-bottom: 30px;"';
                }else{
                    if($role == 1){
                        $conditional_color = 'style="padding-left: 10px;border-left: 3px solid #4bc75e;margin-bottom: 30px;"';
                    }else{
                        $conditional_color = 'style="padding-left: 10px;border-left: 3px solid #a21b24;margin-bottom: 30px;"';
                    }

                }
                $comments[] = '<div class="media"  '.$conditional_color.'>
                                <div id="example_'.$interaction. '-' .$uid.'" class="media-body comment_line">
                                    <h4 class="media-heading">'.$name0.'
                                        <small>'.friendly_date($c_date).'</small>
                                        <i class="fa fa-times pull-right edit_x_'.$i.'" id="delete_'.$interaction. '-' .$uid.'" style="display:none; color: #999999;"></i>
                                        <i class="fa fa-ellipsis-h pull-right edit_points_'.$i.'" id="edit_'.$interaction. '-' .$uid.'" style="display:none; color: #999999;"></i>                                        
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

        $name = $first_name . " " . $last_name . " " . $second_surname;

        $contents .=

            '
            <div class="row">
                <div class="col-lg-8">
                <h1>'.$event_descr.'</h1>
                <p class="lead">
                    by '.$name.'
                </p>
                <hr>
                <p><span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;'.friendly_date($created_date).'</p>
                <hr>
                <div class="fr-view">
                    '.$event_details.'
                </div>
                <hr>
                <div class="well">
                    <h4>Leave a Comment:</h4>                    
                     <div class="form-group">
                         <textarea class="form-control" id="message_text_'.$i.'" rows="3"></textarea>
                         <input type="hidden" id="event_id_'.$i.'" value="'.$event_id.'">
                         <input type="hidden" id="user_id_'.$i.'" value="'.$user_ids.'">
                     </div>
                     <button type="button" id="'.$i.'" class="btn btn-primary comment_number">Submit</button>                    
                </div>
                <hr>
                <div class="comments_div_identification" id="comments_div_'.$i.'">
                '.$insert_comments.'     
                </div> 
                <hr style="height:20px; background-color: #EEEEEE">                        
            </div>
            </div>
            <br>
            <br>
            
            ';
        $i++;
        $insert_comments = "";
    }
}else{
    return "No events found";
}

$objtemplate->set_content('contenido', $contents);
function get_student_group($user_ids){
    global $objmydbcon;
    $sqlquery = "SELECT group_id FROM students_groups WHERE student_id = $user_ids";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return 0;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['group_id'];
}

function get_creator_role($id = 0){
    global $objmydbcon;
    $sqlquery = "SELECT role_idx FROM master_users WHERE idx = $id";
    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return 0;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);
    }else{
        return 0;
    }
    return $rs['role_idx'];
}



