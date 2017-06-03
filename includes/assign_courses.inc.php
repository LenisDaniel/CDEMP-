<?php
/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/20/2017
 * Time: 8:24 AM
 */

require_once("class.users.inc.php");
require_once("class.config_records.inc.php");
$objusers = new Users();
$objconfig = new ConfigRecords();

if(isset($_GET['edit']) && $_GET['edit'] > 0){

    $idx = $_GET['edit'];
    $objtemplate->set_content("teachers_dd", $objusers->get_teachers($idx));

}

$objtemplate->set_content("teachers_dd", $objusers->get_teachers(0));
$objtemplate->set_content("courses_dd", $objconfig->get_courses(0));

