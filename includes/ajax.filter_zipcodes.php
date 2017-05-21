<?php

require_once("class.mydbcon.inc.php");
$objmydbcon = new classmydbcon;

if(isset($_POST) && $_POST['city_id'] != "" && $_POST['city_id'] != "-1"){

    $city_id = $_POST['city_id'];

    echo get_zipcodes($city_id);

}

function get_zipcodes($city_id = ""){
    global $objmydbcon;
    $zipcodes_dd = "";

    $sqlquery = "SELECT * FROM master_zipcode WHERE id_city = $city_id ORDER BY zip ASC";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results)>0){
        $zipcodes_dd = "<option value='-1'>--Select One Zipcode--</option>";
        while($rs = mysqli_fetch_assoc($results)){
            $val = $rs['zip_id'];
            $disp = $rs['zip'];

            $zipcodes_dd .= "<option value='$val'>" .$disp . "</option>";
        }
    }else{
        return 0;
    }
    return $zipcodes_dd;

}