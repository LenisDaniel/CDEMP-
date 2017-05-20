<?php

/**
 * Created by PhpStorm.
 * User: Lenis Rivera
 * Date: 5/19/2017
 * Time: 11:15 PM
 */

require_once ('class.mydbcon.inc.php');
$objmydbcon = new classmydbcon();

class teacher_courses
{

    //Initialize parameters
    var $courses_list;
    var $teachers_list;

    function __construct(){

        $this->courses_list = array();
        $this->teachers_list = array();

    }

    //Aquí creamos el método para traer las listas y presentarlas en el view
    public function get_all_list(){

    }

}