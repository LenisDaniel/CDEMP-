<?php

class Scholar_Period{

    var $scholar_period_td_info;
    var $scholar_period_info;
    var $field_info;

    function __construct(){
        $this->scholar_period_td_info = "";
        $this->scholar_period_info = array();
        $this->field_info = array();
    }

    function get_all_scholar_period($tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT * 
                     FROM scholar_period                      
                     WHERE active = 1";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->scholar_period_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->scholar_period_info[0]);

                $this->scholar_period_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->scholar_period_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=6&edit=$idu'>" . $this->scholar_period_info[0]. "</a></td>";
                $this->scholar_period_td_info .= "<td>" . $this->scholar_period_info[1]. "</td>";
                $this->scholar_period_td_info .= "<td>" . $this->scholar_period_info[2]. "</td>";
                $this->scholar_period_td_info .= "<td>" . $this->scholar_period_info[3]. "</td>";
                $this->scholar_period_td_info .= "<td>" . $this->scholar_period_info[4]. "</td>";
                $this->scholar_period_td_info .= "</tr>";

            }

        }else{
            return "No records found!";
        }
        return $this->scholar_period_td_info;

    }

    function manage_scholar_period_info($tpl_uri = 0, $id = 0, $scholar_year= "", $start_date = "", $end_date = 0, $active = 0){
        global $objmydbcon;

        //        echo $table . "<br>";
        //        echo $field1 . "<br>";
        //        echo $field2 . "<br>";
        //        echo $tpl_uri . "<br>";
        //        echo $descr . "<br>";
        //        echo $id . "<br>";
        //        echo $active . "<br>";
        //        exit;

        if(!$active == "1"){
            $active = 0;
        }

        if($id > 0){

            $sqlupdate = "UPDATE scholar_period SET scholar_year = '$scholar_year', start_date = '$start_date', end_date = '$end_date', active = '$active' WHERE scholar_period_id = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=6");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO scholar_period(scholar_year, start_date, end_date, active)VALUES('$scholar_year', '$start_date', '$end_date', $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=6");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_scholar_period($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM scholar_period WHERE scholar_period_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->scholar_period_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->scholar_period_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_scholar_period_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

}