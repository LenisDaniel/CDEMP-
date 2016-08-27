<?php
/*
file name : digital_signage.inc.php
Programmed by : Luis R. Martinez Jr.
Date : 2015.07.31  Time : 3 26pm
*/

#*******************************************************************************
# MAIN

$curdate = date("Y-m-d");

// GET_LIST PARAMS : STATUS,DATE,SHOWNAME,SHOWSERVICE,SHOWSTATION,SHOWSTATUS

$objtemplate->set_content("serving",$objqueue->get_list('serving',$curdate,true,false,true,false)); 
$objtemplate->set_content("list",$objqueue->get_list('waiting',$curdate,true));

?>