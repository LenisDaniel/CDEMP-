<?php
require_once('class.my_profile.inc.php');
$objprofile = new My_Profile();

$role = $_SESSION['loged_user']['role_idx'];
$idx = $_SESSION['loged_user']['idx'];
$group_id = $objprofile->get_student_group($idx);

if($role == 4){

    $student_info = $objprofile->get_student_info($idx);
    $objtemplate->set_content('student_name', $student_info['name']);
    $objtemplate->set_content('student_address', $student_info['address']);
    $objtemplate->set_content('grade', $student_info['grade']);
    $objtemplate->set_content('group', $student_info['group']);
    $objtemplate->set_content('phone', $student_info['phone_1']);
    $objtemplate->set_content('parent_name', $student_info['parent']);
    $objtemplate->set_content('phone1', $student_info['phone_1']);
    $objtemplate->set_content('phone2', $student_info['phone_2']);
    $objtemplate->set_content('email', $student_info['email']);

    $objtemplate->set_content('schedule_data', $objprofile->get_student_schedule($group_id));

}