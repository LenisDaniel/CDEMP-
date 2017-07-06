<?php
/*
filename : class.mydbcon.inc.php
Programed by : Luis R. Martinez Rojas
Date : 2006.11.27  Time : 1:00 pm
*/

class classmydbcon{

	var $dbhost="192.185.16.243";
	var $db="cdempadm_cdemp";
	var $uname="cdempadm_cdemp";
	var $pwd="cdemp2017";
	var $link="";
	var $error="";

//	 var $dbhost="localhost";
//	 var $db="u186334426_cdemp";
//	 var $uname="root";
//	 var $pwd="xf6C4FBPqNCMNGRG";
//	 var $link="";
//	 var $error="";

//	var $dbhost="localhost";
//	var $db="cdempadm_cdemp";
//	var $uname="cdempadm_cdemp";
//	var $pwd="cdemp2017";
//	var $link="";
//	var $error="";

//    var $dbhost="localhost";
//    var $db="cdemp_test";
//    var $uname="root";
//    var $pwd="xf6C4FBPqNCMNGRG";
//    var $link="";
//    var $error="";


	# class constructor
	function __construct(){
		$this->set_conn_string($this->dbhost,$this->db,$this->uname,$this->pwd);
	} // function classmydbcon

	function set_conn_string($host,$db,$uname,$pwd,$conn_type='normal'){
		if ($this->link = mysqli_connect($host,$uname,$pwd,$db)){

			#return $this->link;
			return true;

		}else{

			return false;
		} // if
	} // function set_conn_string

	function get_resource_link(){

		return $this->link;

	} // function get_conn_string

	function get_result_set($query){

		if (!$result = mysqli_query($this->link,$query)){
			$this->error = mysqli_error($this->link);
			return false;
		}else{
			return $result;
		} // if !result

	} // function get_result_set

	function set_query($query){

		if (!mysqli_query($this->link,$query)){
			echo "Error in query $query<br>" . mysqli_error($this->link);
			return false;
		}else{
			return true;
		} // if !result

	} // function set_query

	function get_error(){
		return $this->error;
	} // if

	function get_last_id(){
		return mysqli_insert_id($this->link);
	} // function

	function get_affected_rows(){
		if (is_resource($this->link)){
			return mysqli_affected_rows($this->link);
		}else{
			return 0;
		} // if
	} // function affected_rows
} // class classmydbcon
?>
