<?php
session_start();

require_once('class.mydbcon.inc.php');
$objmydbcon = new classmydbcon;

class classUsers{
	var $client_info;
	var $client_td_info;
	var $ex;
	var $idx;
	var $type;
	var $action;
	var $role;
	var $active;	

	function classUsers(){
		$this->client_info = array();	    
		$this->client_td_info = array();
		$this->ex = array();	    
		
	}

	//En esta funcion de la clase se decide como trabajar el nuevo usuario en la insercion o update en la tabla usuarios.
	function set_users($idx = 0, $action = "", $role = 0, $active = 0){
		global $objmydbcon;
		
		$username = $_SESSION['username'];
		$password = md5($_SESSION['password']);
		$name = $_SESSION['name'];
		$email = $_SESSION['email'];
		$member = $_SESSION['member'];
		$phone1 = $_SESSION['phone1'];
		$phone2 = $_SESSION['phone2'];
		$address1 = $_SESSION['address1'];
		$address2 = $_SESSION['address2'];
		$town = $_SESSION['town'];
		$country = $_SESSION['country'];
		$zipcode = $_SESSION['zipcode'];
		
		//Switch para trabajar la base de datos segun la accion
		switch ($action) {
			case 'create':

				$sqlinsert = "INSERT INTO users (username, password, name, contact_email, role, member, active, tel1, tel2, address1, 
							  address2, town, country, zipcode) 
							  VALUES ('$username', '$password', '$name', '$email', $role, '$member', $active, '$phone1', '$phone2', '$address1', 
							  '$address2', '$town', '$country', '$zipcode')";							  

				if(!$objmydbcon->set_query($sqlinsert)){
					return false;
				}else{		
					$id = $objmydbcon->get_last_id();
					//En este metodo de la clase se envia el email al adminstrador(tiene que tener el id para que el valla directo al casito y lo active)
					$this->adminEmail($id, "lenis.daniel@gmail.com", $name, $username);
					$this->userEmail($id, $email, $name, $username);
					return true;
				}
				
				break;
			
			case 'update':				

				$sqlupdate = "";

				break;

			case 'delete':				

				$sqldetele = "";

				break;
		}
	}

	//En esta funcion obtenemos todos los usuarios con role de cliente para el manejo de los Administradores.
	function get_all_clients($role){
		global $objmydbcon;

		$i = 0;
		$sqlquery = "SELECT u.idx, u.name, u.contact_email, u.tel1, mmt.mem_type_descr, u.active FROM users u 
		INNER JOIN mast_member_type mmt ON mmt.idx = u.member
		WHERE u.role = $role";
		if(!$results = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if(mysqli_num_rows($results)>0){
			while($this->client_info = mysqli_fetch_array($results)){
				$i++; //para asignarle un id al tr para poder tomar accion sobre el record				
				$clients_td_info .= "<tr id='tr$i'>";
				$clients_td_info .= "<td id='td$i'>" . $this->client_info[0]. "</td>";
				$clients_td_info .= "<td>" . $this->client_info[1]. "</td>";
				$clients_td_info .= "<td>" . $this->client_info[2]. "</td>";
				$clients_td_info .= "<td>" . $this->client_info[3]. "</td>";
				$clients_td_info .= "<td>" . $this->client_info[4]. "</td>";
				$clients_td_info .= "<td>" . $this->client_info[5]. "</td>";
				$clients_td_info .= "<td class='action-buttons'>
									 <button class='btn btn-theme03' onclick='user_id_number($i);'><i class='fa fa-pencil-square-o'></i></button>&nbsp&nbsp
									 <button class='btn btn-theme04'><i class='fa fa-times'></i>
									 </button>
									 </td>";              
				$clients_td_info .= "</tr>";														
			}			
		}else{
			return 0;
		}
		//funcion devuelve los datos que llenan la tabla de manage clients
		return $clients_td_info;				
	}

	function get_client($idx){
		global $objmydbcon;
		$sqlquery = "SELECT * FROM users WHERE idx = $idx";
		if(!$results = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if(mysqli_num_rows($results)>0){
			while($this->client_info = mysqli_fetch_assoc($results)){
				//echo $this->client_info;
				$this->ex = $this->client_info;
				
			}
		}else{
			return 0;
		}
		
		return $this->ex;
	}

	//funcion para obtener los datos de las funciones por campo
	function get_info($field){
		global $objmydbcon;
		global $objusers;
		global $ex;

		if (isset($this->ex[$field])){	
			
            return $this->ex[$field];

        }else{

          	return false;

        } 
	}

	function get_members_type($client_type){
		global $objmydbcon;
		
		$sqlquery = "SELECT * FROM mast_member_type";
		if(!$results = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if(mysqli_num_rows($results)>0){
			while($rs = mysqli_fetch_assoc($results)){
				$val = $rs['idx'];
				$disp = $rs['mem_type_descr'];				

				if($client_type == $val){
					$sel_option = "selected";
				}else{
					$sel_option = "";
				}
				$mem_dd .= "<option value='$val' $sel_option>" .$disp . "</option>";								
			}						
		}else{
			return 0;
		}
		return $mem_dd;	
	}

	
	//funcion para insertar o actualizar el registro del cliente
	function manage_client_info($id, $cname, $user, $password, $email, $phone1, $phone2, $addr1, $addr2, $town, $country, $zip, $role, $memtype, $active){
		global $objmydbcon;

		if($active == "on"){
			$active = 1;
		}else{
			$active = 0;
		}		

		if($id > 0){

			$sqlupdate = "UPDATE users SET name = '$cname', username = '$user', password = '$password', contact_email = '$email', tel1 = '$phone1',
			tel2 = '$phone2', address1 = '$addr1', address2 = '$addr2', town = '$town', country = '$country', zipcode = '$zip', role = '$role', member = '$memtype',
			active = '$active' WHERE idx = $id";

			if($objmydbcon->set_query($sqlupdate)){
				header("location: ../display_page.php?tpl=manage_clients&edit=".base64_encode($id));
				return true;
			}else{
				return false;
			}

		}else{

			$sqlinsert = "INSERT INTO users (name, username, password, contact_email, tel1, tel2, address1, address2, town, country, zipcode, role, member, active) 
			VALUES('$cname', '$user', '$password', '$email', '$phone1', '$phone2', '$addr1', '$addr2', '$town', '$country', '$zip', '$role', '$memtype', '$active')";
			
			if($objmydbcon->set_query($sqlinsert)){
				$last_id = $objmydbcon->get_last_id();
				header("location: ../display_page.php?tpl=manage_clients&edit=".base64_encode($last_id));
				return true;
			}else{
				return false;
			}
		}

	}	

	//Esta funcion envia notificaciones(emails) a los administradores. En formato html y en texto plano
	function adminEmail($id = 0, $email = "", $name = "", $username = ""){

		$uniqueid= uniqid('np');	    

	    //Headers
		$headers = "From: ebusiness@prcinternet.net \r\n";
	   	$headers .= "X-Sender: Okie Dokie at Home \r\n";
	    $headers .= "Cc: lenis.daniel@outlook.com \r\n";
	   	$headers .= "X-Mailer: PHP/" . phpversion(). "\r\n"; // mailer
	   	$headers .= "X-Priority: 0\n"; // Urgent message!
	   	$headers .= "Return-Path: lenis.daniel@gmail.com\n";  // Return path for errors
	    $headers .= "Content-Type: multipart/alternative; boundary=$uniqueid". "\r\n". // Mime type
	    $recipient = $email; // Este Email tiene que ser el del Administrador
	    $subject = "Membership Application Received Succesfully";
	    $activate = "<a href='http://ebusiness-sense.com/okiedokie?id=$id&active=activate'>Click to Activate your Account.</a>\n\n";
	    $msg = "";

	    //Email en formato Texto
	    $msg .= "\r\n\r\n--" . $uniqueid. "\r\n";
	    $msg .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
	    $msg .= "New client request.";
		$msg .= "Applicant Name: " . $name . "\r\n";
	    $msg .= "Your usernme is: " . $username . "\r\n";
	    $msg .= "Email: " . $email . "\r\n";
	    $msg .= "Link: " . $activate;

	    //Email en formato HTML
	    $msg .= "\r\n\r\n--" . $uniqueid. "\r\n";
	    $msg .= "Content-type: text/html;charset=utf-8\r\n\r\n";
	    $msg .= "New client request." . "<br/>";
		$msg .= "Applicant Name: " . $name . "<br/>";
	    $msg .= "Your username is: " . $username . "<br/>";
	    $msg .= "Email: " . $email . "<br/>";
	    $msg .= "Link: " . $activate;

	    $msg .= "\r\n\r\n--" . $uniqueid. "--";		

	    //Se presenta Alert de Envio exitoso y se redirecciona a la pagina principal
	    if (@mail($recipient, $subject, $msg, $headers)){	        
	    	return true;
	    }else{
	        echo "Error al enviar mensaje";
	    	return false;
	    } // if mail

	}
	//Esta funcion envia notificaciones(emails) a los usuarios. En formato html y en texto plano
	function userEmail($id = 0, $email = "", $name = "", $username = ""){
		$uniqueid= uniqid('np');	    

	    //Headers
		$headers = "From: ebusiness@prcinternet.net \r\n";
	   	$headers .= "X-Sender: Okie Dokie at Home \r\n";
	    $headers .= "Cc: lenis.daniel@outlook.com \r\n";
	   	$headers .= "X-Mailer: PHP/" . phpversion(). "\r\n"; // mailer
	   	$headers .= "X-Priority: 0\n"; // Urgent message!
	   	$headers .= "Return-Path: lenis.daniel@gmail.com\n";  // Return path for errors
	    $headers .= "Content-Type: multipart/alternative; boundary=$uniqueid". "\r\n". // Mime type
	    $recipient = $email; // Este Email tiene que ser el del Administrador
	    $subject = "Membership Application Received Succesfully";
	    // $activate = "<a href='http://ebusiness-sense.com/okiedokie?id=$id&active=activate'>Click to Activate your Account.</a>\n\n";
	    $msg = "";

	    //Email en formato Texto
	    $msg .= "\r\n\r\n--" . $uniqueid. "\r\n";
	    $msg .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
	    $msg .= "Thanks for your client request, if approved, we will contact you soon.";
		$msg .= "Applicant Name: " . $name . "\r\n";
	    $msg .= "Your usernme is: " . $username . "\r\n";
	    $msg .= "Email: " . $email . "\r\n";
	    
	    //Email en formato HTML
	    $msg .= "\r\n\r\n--" . $uniqueid. "\r\n";
	    $msg .= "Content-type: text/html;charset=utf-8\r\n\r\n";
	    $msg .= "Thanks for your client request, if approved, we will contact you soon." . "<br/>";
		$msg .= "Applicant Name: " . $name . "<br/>";
	    $msg .= "Your username is: " . $username . "<br/>";
	    $msg .= "Email: " . $email . "<br/>";
	    
	    $msg .= "\r\n\r\n--" . $uniqueid. "--";		

	    //Se presenta Alert de Envio exitoso y se redirecciona a la pagina principal
	    if (@mail($recipient, $subject, $msg, $headers)){
	        echo "<script language='javascript'>";	        
	        echo "setTimeout('location.href=\"http://ebusiness-sense.com/okiedokie?send=1\"')";//aqui le puedo añadir tiempo
	        echo "</script>";
	    	return true;
	    }else{
	        echo "Error al enviar mensaje";
	    	return false;
	    } // if mail
	}

	//Esta funcion no se va a usar porque el que activa al usuario es el administrador.
	function updateMemberActive($id = 0, $active = 0){
		global $objmydbcon;
		$sqlupdate = "UPDATE users SET active = $active WHERE idx = $id";		
		if(!$objmydbcon->set_query($sqlupdate)){
			return false;
		}else{	
	    	return true;
		}
	}

}

?>