<?php
 /*
 file name: loadconfig.inc.php
 programmed by : Luis R. Martinez Rojas
 Date : 01.12.2003
 */
// session_cache_expire(1);
// session_start();
require_once("version.inc.php");

function get_date($lang,$months,$wdays){
	$ary_months = explode(",",$months);
	$ary_wdays = explode(",",$wdays);
	$td = getdate();
	
	switch ($lang){
	    case "en" :
	        return $ary_wdays[$td['wday']] . ", " . $ary_months[$td['mon']-1]." ". $td['mday'] . ", " . $td['year'];
			break;
		case "sp" :
		    return $ary_wdays[$td['wday']] . " " . $td['mday'] . " de " . $ary_months[$td['mon']-1]." de " . $td['year'];
			break;
	} // switch
} // function get_date

$provider = "";

if (!isset($_SESSION['portalconfig'])){
    $_SESSION['portalconfig']['valid_domain'] = "localhost:8080/cdemp-/";//"bmjent.ebusiness-sense.com";
    $_SESSION['portalconfig']['root_path'] = "/cdemp-/";
} // if !isset session portalconfig

if (!isset($_SESSION['lang'])){
    // set default language
    $lang = "en";
    $_SESSION['lang'] = $lang;
}else{
    $lang = $_SESSION['lang'];
} // if !isset lang

switch ($lang){
    case "en" :
         define("SEP","from");
         define("LOCATION","Location");
         define("PROVIDER_ACCN","[Provider Account]");
         define("ADMIN_ACCN","[Admin Account]");
         define("USER_ACCN","[User Account]");
         define("GUEST","Guest");
         define("UNKNOWN","Unknown");
         define("SELECT_OPTION","Select an Option");
         define("ALL_OPTIONS","All");
         define("VALUES_NOT_FOUND","Values Not Found");
         define("YES","Yes");
         define("ADDRESS_TYPE_PHYSICAL","Physical");
         define("ADDRESS_TYPE_POSTAL","Postal");
         define("EMPTY_RECORD_SET","No values Found");
         define("NO","No");
		 define("NOTSYNCRONIZED","** Not Syncronized yet");
		 define("TITLE_VERSION","Version");
   		 define("SYNCHRONIZATION_SUCCEED","Synchronization Succeed");
   		 define("SYNCHRO_FAIL","Synchronization has failed.");
   		 define("SYNCHRO_FILE_NOT_FOUND","Synchronization file was not found");
		 define("STORE","Store");
		 define("NET_SALES","Net Sales");
		 define("GROSS_SALES","Gross Sales");
		 define("LAST_POLLED","Last Polled");
		 define("FROM","From");
		 define("TO","To");
   		 if (!isset($_SESSION['today_date_en'])){
			$months = "January,February,March,April,May,June,July,August,September,October,November,December";
		 	$wdays = "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
		 	$today_date = get_date("en",$months,$wdays);
		 	$_SESSION['today_date_en'] = $today_date;
		 }else{
		 	$today_date = $_SESSION['today_date_en'];
		 } // if !isset
         break;
    case "sp" :
         define("SEP","de la");
         define("LOCATION","Localidad");
         define("PROVIDER_ACCN","[Cuenta de Proveedor]");
         define("ADMIN_ACCN","[Cuenta Administrador]");
         define("USER_ACCN","[Cuenta de Usuario]");
         define("GUEST","Invitado");
         define("UNKNOWN","Desconocido");
         define("SELECT_OPTION","Seleccione una Opción");
         define("ALL_OPTIONS","Todos");
         define("VALUES_NOT_FOUND","Valores no encontrados");
         define("YES","Sí");
         define("ADDRESS_TYPE_PHYSICAL","Física");
         define("ADDRESS_TYPE_POSTAL","Postal");
         define("EMPTY_RECORD_SET","No records encontrados");
         define("NO","No");
         define("NOTSYNCRONIZED","** No ha Sincronizado");
         define("TITLE_VERSION","Versión");
         define("SYNCHRONIZATION_SUCCEED","Sincronización fue exitosa.");
   		 define("SYNCHRO_FAIL","Sincronizació falló.");
   		 define("SYNCHRO_FILE_NOT_FOUND","Archivo de sincronización no fue encontrado");
		 define("STORE","Tienda");
		 define("NET_SALES","Ventas Neta");
		 define("GROSS_SALES","Ventas Bruta");
		 define("LAST_POLLED","Ultima Interrogación");
		 define("FROM","Desde");
		 define("TO","Hasta");
		 if (!isset($_SESSION['today_date_sp'])){
			$months = "Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Ago,Septiembre,Octubre,Noviembre,Diciembre";
		 	$wdays = "Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado";
		 	$today_date = get_date("sp",$months,$wdays);
		 	$_SESSION['today_date_sp'] = $today_date;
		 }else{
		 	$today_date = $_SESSION['today_date_sp'];
		 } // if !isset
			
		 break;
} // switch
$qs = "";
if (isset($_POST)){
	$qs = "&vars=";
	while (list($key,$value) = each($_POST)){
		$qs .= "$key:$value|";
	} // while
	
	$qs = substr($qs,0,strlen($qs)-1);
} // if isset $_POST

// SWAP LANGUAGE MECHANISM
if ($lang == "sp"){
    $change_lang = "<a href='change_lang.php?lang=en$qs'>English Version</a>";
}else if ($lang == "en"){
    $change_lang = "<a href='change_lang.php?lang=sp$qs'>Versión en español</a>";
} // if $lang ==

if (!isset($_SESSION['loged_user'])){
    $contact = GUEST;
}else{
    $contact = $_SESSION['loged_user']['contact'];
} // if !iset loged_user

if (isset($_SESSION['loged_user']['privilege_level'])){
	$privilege_level = $_SESSION['loged_user']['privilege_level'];
}else{
	$privilege_level = 0;
}// if isset

$software_version = TITLE_VERSION . " : " . VERSION;

?>
