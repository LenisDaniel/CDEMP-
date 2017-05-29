<?php
    error_reporting(E_ALL);
    /**
     * Created by PhpStorm.
     * File: manage_students.inc.php
     * User: Christian J. Negron Garcia
     * Date: 5/20/2017
     * Time: 11:31 PM
     */

    require_once("class.students.inc.php");
    $objstudents = new Students();
    
    $objtemplate->set_content("students_td", $objstudents->get_all_users(4, "manage_students"));
    
    if(isset($_GET['edit'])){
    
        $idx = base64_decode($_GET['edit']);
        $objstudents->get_user($idx);
        $objtemplate->set_content("form_action", "display_page.php?tpl=manage_students&cid=".base64_encode($idx));
        $objtemplate->set_content("form_title", "Update Student Info");
        $objtemplate->set_content("send_button", "Update");
    
        //aqui llenamos el formulario con la info de la base de datos
        $objtemplate->set_content("first_name", $objstudents->get_user_info('first_name'));
        $objtemplate->set_content("last_name", $objstudents->get_user_info('last_name'));
        $objtemplate->set_content("second_surname", $objstudents->get_user_info('second_surname'));
        $objtemplate->set_content("email", $objstudents->get_user_info('email'));
        $objtemplate->set_content("password", $objstudents->get_user_info('password'));
        $objtemplate->set_content("address1", $objstudents->get_user_info('address1'));
        $objtemplate->set_content("address2", $objstudents->get_user_info('address2'));
        $objtemplate->set_content("cities_dd", $objstudents->get_cities($objstudents->get_user_info('city')));
        $objtemplate->set_content("states_dd", $objstudents->get_state($objstudents->get_user_info('state')));
        $objtemplate->set_content("parent_1", $objstudents->get_user_info('parent_1'));
        $objtemplate->set_content("parent_2", $objstudents->get_user_info('parent_2'));
        $objtemplate->set_content("phone_1", $objstudents->get_user_info('phone_1'));
        $objtemplate->set_content("phone_2", $objstudents->get_user_info('phone_2'));
        $objtemplate->set_content("zipcodes_dd", $objstudents->get_zipcodes($objstudents->get_user_info('zipcode')));
        $objtemplate->set_content("carriers_dd", $objstudents->get_carriers($objstudents->get_user_info('phone_1_carrier')));

        if($objstudents->get_user_info('active') == 1){
            $objtemplate->set_content("option1", "checked");
        }else{
            $objtemplate->set_content("option2", "checked");
        }
    
    }else{

        $objtemplate->set_content("form_action", "display_page.php?tpl=manage_students&cid=".base64_encode(0));
        $objtemplate->set_content("cities_dd", $objstudents->get_cities(0));
        $objtemplate->set_content("states_dd", $objstudents->get_state(0));
        $objtemplate->set_content("zipcodes_dd", $objstudents->get_zipcodes(0));
        $objtemplate->set_content("carriers_dd", $objstudents->get_carriers(0));

        $objtemplate->set_content("form_title", "Create New Student");
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
//            $objstudents->update_user_active($id, $role);
//        }
//
//    }
    
    if(isset($_GET['cid'])){

//        print_r($_POST);
//        exit;
    
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
        $role_idx = 4;

        $parent_1 = $_POST['parent_1'];
        $phone_1_carrier = $_POST['phone_1_carrier'];
        $parent_2 = $_POST['parent_2'];
        $phone_2_carrier = $_POST['phone_2_carrier'];
    
        $objstudents->manage_user_info('manage_students', $id, $first_name, $last_name, $second_surname, $password, $email, $address1, $address2, $phone_1, $phone_2, $cities_dd, $states_dd, $zipcode, $active, $role_idx, $parent_1, $parent_2, $phone_1_carrier, $phone_2_carrier);
    
    }


?>