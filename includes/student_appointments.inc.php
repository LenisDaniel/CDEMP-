<?php

error_reporting(E_ERROR);
require_once("class.appointments.inc.php");

$objappointments = new Appointments();

$creator = $_SESSION['loged_user']['idx'];
$role_idx = $_SESSION['loged_user']['role_idx'];

$objtemplate->set_content('appointments_td', $objappointments->get_all_appointments('student_appointments', $role_idx, $creator));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objappointments->get_appointment($idx);

    $objtemplate->set_content("appoint_descr", $objappointments->get_appointment_info('appointment_descr'));
    $objtemplate->set_content("appoint_time", $objappointments->get_appointment_info('appointment_time'));
    $objtemplate->set_content("appoint_place", $objappointments->get_appointment_info('appointment_place'));
    $objtemplate->set_content("appoint_details", $objappointments->get_appointment_info('appointment_details'));

    $objappointments->set_as_viewed($idx);

}