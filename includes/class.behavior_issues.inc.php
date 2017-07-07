<?php

class Behaviors{

    var $behavior_td_info;
    var $behavior_info;
    var $field_info;

    function __construct(){
        $this->behavior_td_info = "";
        $this->behavior_info = array();
        $this->field_info = array();
    }

    function get_all_behaviors($tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT * 
                     FROM master_incidents                      
                     WHERE active = 1";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->behavior_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->behavior_info[0]);

                $this->behavior_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->behavior_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=6&edit=$idu'>" . $this->behavior_info[0]. "</a></td>";
                $this->behavior_td_info .= "<td>" . $this->behavior_info[1]. "</td>";
                $this->behavior_td_info .= "<td>" . $this->behavior_info[2]. "</td>";
                $this->behavior_td_info .= "<td>" . $this->behavior_info[3]. "</td>";
                $this->behavior_td_info .= "</tr>";

            }

        }else{
            return "No records found!";
        }
        return $this->behavior_td_info;

    }

    function manage_behavior_info($tpl_uri = 0, $id = 0, $behavior = "", $classification_dd = 0, $active = 0){
        global $objmydbcon;

//        echo $table . "<br>";
//        echo $field1 . "<br>";
//        echo $field2 . "<br>";
//        echo $tpl_uri . "<br>";
//        echo $descr . "<br>";
//        echo $id . "<br>";
//        echo $active . "<br>";//
//        exit;

        if($active != 1){
            $active = 0;
        }

        if($id > 0){

            $sqlupdate = "UPDATE master_incidents SET incident_descr = '$behavior', classification_id = $classification_dd, active = '$active' WHERE incident_id = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=2&edit=" . base64_encode($id));
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO master_incidents(incident_descr, classification_id, active)VALUES('$behavior', $classification_dd, $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=2&edit=" . base64_encode($last_id));
                return true;
            }else{
                return false;
            }
        }

    }

    function get_behavior($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_incidents WHERE incident_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->behavior_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->behavior_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_behavior_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

    function get_classifications($selected_classification = 0){
        global $objmydbcon;
        $classifications_dd = "";

        $sqlquery = "SELECT * FROM master_classification";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($rs = mysqli_fetch_assoc($results)){
                $val = $rs['classification_id'];
                $disp = $rs['classification_descr'];

                if($selected_classification == $val){
                    $sel_option = "selected";
                }else{
                    $sel_option = "";
                }
                $classifications_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";
            }
        }else{
            return 0;
        }
        return $classifications_dd;
    }

}