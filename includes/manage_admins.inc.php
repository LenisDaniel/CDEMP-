<?php

error_reporting(E_ERROR);
require_once("class.admins.inc.php");

$objadmins = new Administrator();

$objtemplate->set_content("administrator_td", $objadmins->get_all_users($role = 1, $tpl_uri = 'manage_admins'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objadmins->get_user($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_admins&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Administrators Info");
    $objtemplate->set_content("send_button", "Update");

    //aqui llenamos el formulario con la info de la base de datos
    $objtemplate->set_content("first_name", $objadmins->get_user_info('first_name'));
    $objtemplate->set_content("last_name", $objadmins->get_user_info('last_name'));
    $objtemplate->set_content("second_surname", $objadmins->get_user_info('second_surname'));
    $objtemplate->set_content("email", $objadmins->get_user_info('email'));
    $objtemplate->set_content("username", $objadmins->get_user_info('username'));
    $objtemplate->set_content("password", $objadmins->get_user_info('password'));
    $objtemplate->set_content("address1", $objadmins->get_user_info('address1'));
    $objtemplate->set_content("address2", $objadmins->get_user_info('address2'));
    $objtemplate->set_content("cities_dd", $objadmins->get_cities($objadmins->get_user_info('city')));
    $objtemplate->set_content("states_dd", $objadmins->get_state($objadmins->get_user_info('state')));
    $objtemplate->set_content("phone_1", $objadmins->get_user_info('phone_1'));
    $objtemplate->set_content("phone_2", $objadmins->get_user_info('phone_2'));
    $objtemplate->set_content("zipcodes_dd", $objadmins->get_zipcodes($objadmins->get_user_info('zipcode')));

    if($objadmins->get_user_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=manage_admins&cid=".base64_encode(0));
    $objtemplate->set_content("cities_dd", $objadmins->get_cities(0));
    $objtemplate->set_content("states_dd", $objadmins->get_state(0));
    $objtemplate->set_content("zipcodes_dd", $objadmins->get_zipcodes(0));
    $objtemplate->set_content("form_title", "Create New Administrator");
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

if(isset($_GET['cid'])) {

    $id = base64_decode($_GET['cid']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $second_surname = $_POST['second_surname'];
    $username = $_POST['username'];
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
    $role_idx = 1;

    $objadmins->manage_user_info('manage_admins', $id, $first_name, $last_name, $second_surname, $username, $password, $email, $address1, $address2, $phone_1, $phone_2, $cities_dd, $states_dd, $zipcode, $active, $role_idx, $parent_1, $parent_2, 1, 1);
}