<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon();

function get_appointment_notifications($id, $role){
    global $objmydbcon;

    $sqlquery = "SELECT * FROM appointments WHERE date_with = $id ORDER BY created_date DESC LIMIT 20";
    $appointment_uri = get_appointment_uri($role);
    $notication_items = "";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        $badge_qty = get_new_appointments_count($id);
        if(get_new_appointments_count($id) == 1){
            $qty = "You have " . get_new_appointments_count($id) . " notification";
        }else{
            $qty = "You have " . get_new_appointments_count($id) . " notifications";
        }
        while($rs = mysqli_fetch_assoc($results)){
            extract($rs);

            if($viewed == 1){
                $appointment_label = "default";
                $appointment_icon = "eye";
            }else{
                $appointment_label = "warning";
                $appointment_icon = "bell";
            }

            $notication_items .=
                '<li class="appointments_items" id="'.$appointment_id.'">
	                <a href="display_page.php?tpl='.$appointment_uri.'&edit='.base64_encode($appointment_id).'&viewed=1">
		            <span class="label label-sm label-icon label-'.$appointment_label.'">
		                <i class="fa fa-'.$appointment_icon.'"></i>
		            </span>
			            '.$appointment_descr.'
                    <span class="time">'.date('M-d', strtotime($appointment_time)).'</span>
	                </a>	                
                </li>';
        }

    }else{
        $badge_qty = 0;
    }

    return array($badge_qty, $qty, $notication_items);

}

function get_appointment_uri($role = 0){
    if($role == 1 || $role == 2){
        $uri = "global_appointments&cat=3";
    }else if($role == 3){
        $uri = "teacher_appointments&cat=4";
    }else{
        $uri = "student_appointments&cat=5";
    }
    return $uri;
}

function get_new_appointments_count($id){
    global $objmydbcon;

    $sqlquery = "SELECT count(*) AS qty FROM appointments WHERE date_with = $id AND viewed = 0";

    if(!$result = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($result) > 0){
        $rs = mysqli_fetch_assoc($result);

    }else{
        return 0;
    }
    return $rs['qty'];
}


/* *
 * You have 0 new notifications
 *
 *<li>
	<a href="#">
		<span class="label label-sm label-icon label-success">
		    <i class="fa fa-plus"></i>
		</span>
		New user registered.
        <span class="time">Just now </span>
	</a>
  </li>
  <li>
	<a href="#">
		<span class="label label-sm label-icon label-danger">
			<i class="fa fa-bolt"></i>
		</span>
		Server #12 overloaded.
        <span class="time">15 mins </span>
	</a>
  </li>
 */