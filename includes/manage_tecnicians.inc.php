<?php

error_reporting(E_ALL);
require_once("class.tecnicians.inc.php");

$objtecnicians = new Tecnicians();

$objtemplate->set_content("technician_td", $objtecnicians->get_all_users(2, 'manage_technicians'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objtecnicians->get_user($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_technicians&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Technician Info");
    $objtemplate->set_content("send_button", "Update");

    //aqui llenamos el formulario con la info de la base de datos
    $objtemplate->set_content("first_name", $objtecnicians->get_user_info('first_name'));
    $objtemplate->set_content("last_name", $objtecnicians->get_user_info('last_name'));
    $objtemplate->set_content("second_surname", $objtecnicians->get_user_info('second_surname'));
    $objtemplate->set_content("email", $objtecnicians->get_user_info('email'));
    $objtemplate->set_content("password", $objtecnicians->get_user_info('password'));
    $objtemplate->set_content("address1", $objtecnicians->get_user_info('address1'));
    $objtemplate->set_content("address2", $objtecnicians->get_user_info('address2'));
    $objtemplate->set_content("cities_dd", $objtecnicians->get_cities($objtecnicians->get_user_info('city')));
    $objtemplate->set_content("states_dd", $objtecnicians->get_state($objtecnicians->get_user_info('state')));
    $objtemplate->set_content("phone_1", $objtecnicians->get_user_info('phone_1'));
    $objtemplate->set_content("phone_2", $objtecnicians->get_user_info('phone_2'));
    $objtemplate->set_content("zipcodes_dd", $objtecnicians->get_zipcodes($objtecnicians->get_user_info('zipcode')));

    if($objtecnicians->get_user_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_technicians&cid=".base64_encode(0));
    $objtemplate->set_content("cities_dd", $objtecnicians->get_cities(0));
    $objtemplate->set_content("states_dd", $objtecnicians->get_state(0));
    $objtemplate->set_content("zipcodes_dd", $objtecnicians->get_zipcodes(0));
    $objtemplate->set_content("form_title", "Create New Technician");
    $objtemplate->set_content("send_button", "Submit");
    $objtemplate->set_content("option1", "checked");

}

//    if(isset($_GET['delete'])){
//        $idx = base64_decode($_GET['delete']);
//        header("location: display_page.php?tpl=manage_admins&id=".base64_encode($idx)."&del=1");
//    }
//
//    if(isset($_GET['del'])){
//
//        $del = $_GET['del'];
//        $id = base64_decode($_GET['id']);
//        $role = 1;
//
//        if($del == 2){
//            $objusers->update_user_active($id, $role);
//        }
//
//    }

if(isset($_GET['cid'])){

    $id = base64_decode($_GET['cid']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $second_surname = $_POST['second_surname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $phone_1 = $_POST['phone_1'];
    $phone_2 = $_POST['phone_2'];
    $cities_dd = $_POST['city'];
    $states_dd = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $active = $_POST['active'];
    $role_idx = 2;

    //$objtecnicians->manage_user_info('manage_technicians', $id, $first_name, $last_name, $second_surname, $password, $email, $address1, $address2, $phone_1, $phone_2, $cities_dd, $states_dd, $zipcode, $active, $role_idx);

}
