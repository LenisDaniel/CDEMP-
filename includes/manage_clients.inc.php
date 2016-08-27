<?php
require_once("class.users.inc.php");
require_once("class.template.inc.php");
$objmydbcon = new classmydbcon;
$objtemplate = new classTemplate;
$objusers = new classUsers;

//Aqui cargamos todos los clientes en la tabla.
$objtemplate->set_content("man_clients_td", $objusers->get_all_clients(2));

//Aqui cargamos los datos del cliente si viene id por Get, si no pues el cliente puede crear uno nuevo.
if(isset($_GET['edit'])){
	$idx = base64_decode($_GET['edit']);
	$objusers->get_client($idx);
	$objtemplate->set_content("action", "includes/manage_clients.inc.php?cid=".base64_encode($idx));
	$objtemplate->set_content("name", $objusers->get_info('name'));
	$objtemplate->set_content("username", $objusers->get_info('username'));
	$objtemplate->set_content("email", $objusers->get_info('contact_email'));
	$objtemplate->set_content("phone1", $objusers->get_info('tel1'));
	$objtemplate->set_content("phone2", $objusers->get_info('tel2'));
	$objtemplate->set_content("address1", $objusers->get_info('address1'));
	$objtemplate->set_content("address2", $objusers->get_info('address2'));
	$objtemplate->set_content("town", $objusers->get_info('town'));
	$objtemplate->set_content("country", $objusers->get_info('country'));
	$objtemplate->set_content("zipcode", $objusers->get_info('zipcode'));
	$objtemplate->set_content("display", "display:none");
	$objtemplate->set_content("passrequired", "");

	$objtemplate->set_content("man_clients_member_type_dropdown", $objusers->get_members_type($objusers->get_info('member')));

	if($objusers->get_info('active') == 1){
		$objtemplate->set_content("checked", "checked='checked'");
	}else{
		$objtemplate->set_content("checked", "");
	}

	$objtemplate->set_content("submit", "Update");
	$objtemplate->set_content("title", "Update Client Info");

}else{

	$objtemplate->set_content("action", "includes/manage_clients.inc.php?cid=".base64_encode(0));
	$objtemplate->set_content("man_clients_member_type_dropdown", $objusers->get_members_type($objusers->get_info('member')));
	$objtemplate->set_content("submit", "Create");
	$objtemplate->set_content("display", "");
	$objtemplate->set_content("passrequired", "required");
	$objtemplate->set_content("title", "Create New Client");

}

if(isset($_GET['cid'])){
	$id = base64_decode($_GET['cid']);
	$clientname = $_POST['man_clients_name'];
	$user = $_POST['man_clients_username'];
	$password = md5($_POST['man_clients_password']);
	$email = $_POST['man_clients_email'];
	$phone1 = $_POST['man_clients_phone1'];
	$phone2 = $_POST['man_clients_phone2'];
	$addr1 = $_POST['man_clients_address1'];
	$addr2 = $_POST['man_clients_address2'];
	$town = $_POST['man_clients_town'];
	$country = $_POST['man_clients_country'];
	$zip = $_POST['man_clients_zipcode'];
	$memtype = $_POST['man_clients_member'];
	$active = $_POST['man_clients_active'];
	$role = 2;

	$objusers->manage_client_info($id, $clientname, $user, $password, $email, $phone1, $phone2, $addr1, $addr2, $town, $country, $zip, $role, $memtype, $active);

}

?>