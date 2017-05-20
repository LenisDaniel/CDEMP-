<?php
/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/19/2017
 * Time: 11:11 PM
 */

require_once ('class.template.inc.php');
require_once ('class.mydbcon.inc.php');

$objmydbcon = new classmydbcon();
$objtemplate = new classTemplate();

$objtemplate->set_content('title', 'Aquí presentaremos el plugin de las listas.');