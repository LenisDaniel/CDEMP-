<?php

class Notes{

    var $notes_td_info;
    var $note_info;
    var $field_info;

    function __construct(){
        $this->notes_td_info = "";
        $this->note_info = array();
        $this->field_info = array();
    }

    function get_all_notes($tpl_uri = ""){
        global $objmydbcon;

        $i = 0;
        $sqlquery = "SELECT * 
                     FROM master_notes                      
                     WHERE active = 1";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results) > 0){

            while($this->note_info = mysqli_fetch_array($results)){

                $i++; //para asignarle un id al tr para poder tomar accion sobre el record
                $idu = base64_encode($this->note_info[0]);

                $this->notes_td_info .= "<tr id='tr$i' class='odd gradeX'>";
                $this->notes_td_info .= "<td id='td$i'><a href='display_page.php?tpl=$tpl_uri&cat=6&edit=$idu'>" . $this->note_info[0]. "</a></td>";
                $this->notes_td_info .= "<td>" . $this->note_info[1]. "</td>";
                $this->notes_td_info .= "<td>" . $this->note_info[2]. "</td>";
                $this->notes_td_info .= "<td>Active</td>";
                $this->notes_td_info .= "</tr>";

            }

        }else{
            return "No records found!";
        }
        return $this->notes_td_info;

    }

    function manage_note_info($tpl_uri = 0, $id = 0, $note = "", $note_range = 0, $active = 0){
        global $objmydbcon;

        //        echo $table . "<br>";
        //        echo $field1 . "<br>";
        //        echo $field2 . "<br>";
        //        echo $tpl_uri . "<br>";
        //        echo $descr . "<br>";
        //        echo $id . "<br>";
        //        echo $active . "<br>";//
        //        exit;

        if($active == 1){
            $active = 1;
        }else{
            $active = 0;
        }

        if($id > 0){

            $sqlupdate = "UPDATE master_notes SET note_descr = '$note', note_range = '$note_range', active = '$active' WHERE note_id = $id";

            if($objmydbcon->set_query($sqlupdate)){
                header("location: display_page.php?tpl=$tpl_uri&cat=6");
                return true;
            }else{
                return false;
            }

        }else{

            $sqlinsert = "INSERT INTO master_notes(note_descr, note_range, active)VALUES('$note', $note_range, $active)";

            if($objmydbcon->set_query($sqlinsert)){
                $last_id = $objmydbcon->get_last_id();
                header("location: display_page.php?tpl=$tpl_uri&cat=6");
                return true;
            }else{
                return false;
            }
        }

    }

    function get_note($idx){
        global $objmydbcon;

        $sqlquery = "SELECT * FROM master_notes WHERE note_id = $idx";

        if(!$results = $objmydbcon->get_result_set($sqlquery)){
            return false;
        }else if(mysqli_num_rows($results)>0){
            while($this->note_info = mysqli_fetch_assoc($results)){

                $this->field_info = $this->note_info;

            }
        }else{

            return "No results found";

        }

        return $this->field_info;
    }

    function get_note_info($field){

        if (isset($this->field_info[$field])){

            return $this->field_info[$field];

        }else{

            return false;

        }

    }

}