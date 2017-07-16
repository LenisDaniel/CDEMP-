<?php
require_once('class.mydbcon.inc.php');
$objmydbcon = new classmydbcon();

if(isset($_POST)){

    if($_POST['id'] == "1"){

        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $path = "../images/";

        if(move_uploaded_file($_FILES['file']['tmp_name'] , $path.$file_name))
        {
            echo '{"link": "http://localhost:8080/CDEMP-/images/'.$file_name.'"}';
        }
        else
        {
            echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
        }

    }else{

        $file_type = $_FILES['file_param']['type'];
        $file_size = $_FILES['file_param']['size'];
        $file_tmp_name = $_FILES['file_param']['tmp_name'];
        $file_name = $_FILES['file_param']['name'];
        $path = "../files/";

        if(move_uploaded_file($_FILES['file_param']['tmp_name'] , $path.$file_name))
        {
            echo '{"link": "http://localhost:8080/CDEMP-/files/'.$file_name.'"}';
        }
        else
        {
            echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
        }

    }


}