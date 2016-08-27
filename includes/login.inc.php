<?php
require_once('class.template.inc.php');
$objtemplate = new classTemplate;
echo date("y");
exit;

$objtemplate->set_content('year', '2014');
?>