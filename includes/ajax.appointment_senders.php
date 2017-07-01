<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;
$get_admins = 0;
$users_td = "";
$i = 0;
if(isset($_POST)){
    extract($_POST);

    if($get_admins == 1){

        $sqlquery = "SELECT * FROM master_users WHERE role_idx = $user_type";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_assoc($results)){
                $users_td .= '<tr>
                              <td><input type="checkbox" class="checkbox" name="chk_'.$i.'"></td>
                              <td id="chk_'.$i.'"><input type="hidden" name="txt_name_'.$i.'" value="'.$rs['idx'].'">'.$rs['first_name']. " " . $rs['last_name'] .  '</td>
                              </tr>';
                $i++;
            }
        }else{
            return 0;
        }


    }else{

        $s_list = get_group_students($group_id);

        $sqlquery = "SELECT * FROM master_users WHERE role_idx = $user_type AND idx IN($s_list)";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){
            while($rs = mysqli_fetch_assoc($results)){
                $users_td .= '<tr>
                              <td><input type="checkbox" class="checkbox" name="chk_'.$i.'"></td>
                              <td id="chk_'.$i.'"><input type="hidden" name="txt_name_'.$i.'" value="'.$rs['idx'].'">'.$rs['first_name'].'</td>
                              </tr>';
                $i++;
            }
        }else{
            return 0;
        }

    }
    echo $users_td;

}


function get_group_students($group_id  = 0){
    global $objmydbcon;
    $student_list = "";
    $sqlquery = "SELECT student_id FROM students_groups WHERE group_id = $group_id";
    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){
        while($rs = mysqli_fetch_assoc($results)){
            $student_list .= $rs['student_id'] . ",";
        }
    }else{
        return 0;
    }
    return substr($student_list, 0, -1);
}