<?php

error_reporting(E_ALL);
require_once("class.notes.inc.php");

$objbehavior = new Notes();

$objtemplate->set_content("notes_td", $objbehavior->get_all_notes('set_notes'));

if(isset($_GET['edit'])){

    $idx = base64_decode($_GET['edit']);
    $objbehavior->get_note($idx);
    $objtemplate->set_content("form_action", "display_page.php?tpl=set_notes&cid=".base64_encode($idx));
    $objtemplate->set_content("form_title", "Update Notes Info");
    $objtemplate->set_content("send_button", "Update");

    $objtemplate->set_content("note", $objbehavior->get_note_info('note_descr'));
    $objtemplate->set_content("note_range", $objbehavior->get_note_info('note_range'));

    if($objbehavior->get_note_info('active') == 1){
        $objtemplate->set_content("option1", "checked");
    }else{
        $objtemplate->set_content("option2", "checked");
    }

}else{

    $objtemplate->set_content("form_action", "display_page.php?tpl=set_notes&cid=".base64_encode(0));
    $objtemplate->set_content("form_title", "Create New Note");
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
    $note = $_POST['note'];

    $note_range = $_POST['note_range'];
    $active = $_POST['active'];

    $objbehavior->manage_note_info('set_notes', $id, $note, $note_range, $active);

}