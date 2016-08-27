<?php
/* file name : display_page.php
	programmed by :  Luis R. Martinez
   date : 02.27.2003
*/
set_time_limit(0); // SET TIME OUT TO UNLIMITED (0)
session_start();

require_once("../includes/class.template.inc.php");
require_once("../includes/common_functions.inc.php");
require_once("../includes/class.queue.inc.php");
require_once("../includes/class.mydbcon.inc.php");


#*******************************************************************************
# MAIN
$lang = "en";

// INSTANTIATE DATABASECON CLASS
if (!$objmydbcon = new classmydbcon){
	echo "Error connecting to DB";
	exit;
} // if

// INSTANTIATE TEMPLATE CLASS
$objtemplate = new classTemplate;

// INSTANTIATE QUEUE CLASS
$objqueue = new classQueue;


#*******************************************************************************
// CHECK FOR ERR MSG
if (isset($_SESSION['errmsg'])){
    $objtemplate->set_content("errmsg",$_SESSION['errmsg']);
    unset($_SESSION['errmsg']);
} // if session_is_registered("errmsg")

#*******************************************************************************
// OPEN CONTENT PAGE
if (!isset($_GET["tpl"])){
	// set default page
	$_GET['tpl'] = 'main';
} // if isset

$tpl = sanitize($_GET['tpl']);

$objtemplate->set_content("tpl",$tpl);

$content = $objtemplate->open("templates_$lang/".$tpl.".tem.htm");



if (file_exists("includes/" . $tpl . ".inc.php")){
	include("includes/" . $tpl . ".inc.php");
} // if

//****************************************************************************************************************************************
if ($main_tpl ==""){
	$tpl = $objtemplate->open("templates_$lang/template_00.tem.htm",1);
}else{
	$tpl = $objtemplate->open("templates_$lang/$main_tpl.tem.htm",1);
} // if
//****************************************************************************************************************************************

$meta_tags = "<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
			 <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />";

$objtemplate->set_content("html_title","1STBANK - QUEUE SYSTEM");
$objtemplate->set_content("meta_tags","$meta_tags");
$objtemplate->set_content("meta_descr","");
$objtemplate->set_content("meta_keywords","");
$objtemplate->set_content("content",$objtemplate->parsedata($content));
$objtemplate->set_content("disclaimer",$disclaimer);
$objtemplate->parsedata($tpl,1);

?>
