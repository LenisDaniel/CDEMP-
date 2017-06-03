<?php

class ConfigRecords
{

    var $config_td_info;
    var $config_info;
    var $field_info;

    function __construct(){
        $this->config_td_info = "";
        $this->config_info = array();
        $this->field_info = array();
    }

    function get_all_config($tpl_uri = "", $table_name = ""){
        global $objmydbcon;

        $table = "master_" . $table_name;

        $i = 0;
        $sqlquery = "SELECT * 
                     FROM $table                      
                     WHERE active = 1";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->config_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->config_info[0]);

                $this->config_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->config_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=2&edit=$idu'>" . $this->config_info[0]. "</a></td>";
                $this->config_td_info .= "<td>" . $this->config_info[1]. "</td>";
                $this->config_td_info .= "<td>" . $this->config_info[2]. "</td>";
                $this->config_td_info .= "</tr>";

            }

        }else{
            return "No records found!";
        }
        return $this->config_td_info;

    }

    function manage_config_info($tpl_uri = 0, $table_name = "", $id = 0, $descr = "", $active = ""){
        global $objmydbcon;

        $table = "master_" . $table_name;
        $field1 = $table_name . "_descr";
        $field2 = $table_name . "_id";

//        echo $table . "<br>";
//        echo $field1 . "<br>";
//        echo $field2 . "<br>";
//        echo $tpl_uri . "<br>";
//        echo $descr . "<br>";
//        echo $id . "<br>";
//        echo $active . "<br>";
//
//        exit;

        if(!$active == "1"){
            $active = 0;
        }

        if($id > 0){

            $sqlupdate = "UPDATE $table SET $field1 = '$descr', active = '$active' WHERE $field2 = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO $table ($field1, active)VALUES('$descr', $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=2");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_config($idx, $table_name){
        global $objmydbcon;

        $table = "master_" . $table_name;
        $field1 = $table_name . "_id";

        $sqlquery = "SELECT * FROM $table WHERE $field1 = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->config_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->config_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_config_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

    function get_courses($courses_selected = 0){
        global $objmydbcon;
        $counses_dd = "";

        $sqlquery = "SELECT * FROM master_course";
        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['course_id'];
                $disp = $rs['course_descr'];

                if($courses_selected == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $counses_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $counses_dd;
    }

}