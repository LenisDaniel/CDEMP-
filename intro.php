<?php
/* file name : display_page.php
	programmed by :  Luis R. Martinez
   date : 02.27.2003
*/

set_time_limit(0); // SET TIME OUT TO UNLIMITED (0)

require_once("includes/common_functions.inc.php");
require_once("includes/load_config.inc.php");
require_once("includes/disclaimer.inc.php");

// IF IE IS LESS THAN VERSION 9, BUY A NEW COMPUTER
/*$IE6 = preg_match('MSIE 6') , $_SERVER['HTTP_USER_AGENT']) ? true : false;
$IE7 = preg_match('MSIE 7') , $_SERVER['HTTP_USER_AGENT']) ? true : false;
$IE8 = preg_match('MSIE 8') , $_SERVER['HTTP_USER_AGENT']) ? true : false;
$IE9 = preg_match('MSIE 9') , $_SERVER['HTTP_USER_AGENT']) ? true : false;


if (($IE6 == 1) || ($IE7 == 1) || ($IE8 == 1 ) || ($IE9 == 1)) {
   header("location:old_browser.html");
   exit;
   
}*/// if

//****************************************************************************************************************************************
// INSTANTIATE TEMPLATE CLASS
$objtemplate = new classTemplate;


//****************************************************************************************************************************************
// INSTANTIATE STATISTICS CLASS
//$objstats = new classStatistics;

//if (is_object($objclient)){
	// REGISTER THIS VISIT IN ACTIVITY_STATS
//	$objstats->set_visit($objclient->get_client_id());
//} // if 


//****************************************************************************************************************************************
// INSTANTIATE BROWSER CLASS
//$objbrowser = new classBrowser;
//$objtemplate->set_content("browser",$objbrowser->display());


//****************************************************************************************************************************************
// GET TINY SHOPPING CART

//$objtemplate->set_content("tinycart",$objcart->get_tinycart());

//****************************************************************************************************************************************
// CHECK FOR ERR MSG
if (($_SESSION["errmsg"])){
    $objtemplate->set_content("errmsg",$_SESSION['errmsg']);
    unset($_SESSION['errmsg']);
} // if session_is_registered("errmsg")
//****************************************************************************************************************************************
// OPEN CONTENT PAGE
#$content = $objtemplate->open("templates_en/valid_form.tem.htm");
//****************************************************************************************************************************************
$tpl = $objtemplate->open("templates_sp/template_00.tem.htm",1);
//require_once("includes/index.inc.php");


//****************************************************************************************************************************************
$objtemplate->set_content("html_title",$_SESSION['storeconfig']['store_name'] . " " . $_SESSION['storeconfig']['store_title']);
$objtemplate->set_content("meta_descr",$_SESSION['storeconfig']['meta_descr']);
$objtemplate->set_content("meta_keywords",$_SESSION['storeconfig']['meta_keywords']);
//$objtemplate->set_content("welcome",$objclient->get_welcome());
$objtemplate->set_content("content",$objtemplate->parsedata($content));
$objtemplate->set_content("disclaimer",$disclaimer);
$objtemplate->set_content("today_date",$today_date);
$objtemplate->set_content("form_search","display_page.php?tpl=items&lookby=" . base64_encode('search'));
//$objtemplate->set_content("motion_gallery",get_new_arrivals(15,true,"featureditems"));
$objtemplate->parsedata($tpl,1);
?>