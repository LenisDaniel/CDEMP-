<?php

/*
class : class.queue.inc.php
Programmed by : Luis R. Martinez Rojas
Date :2016.1.14  Time : 8:07 am
*/

class classQueue{
	
	function _construct(){
		# constructor	
	} // CONSTRUCTOR
	
	function get_service_name($service_id){
		global $objmydbcon;
		
		$sqlquery = "SELECT descr
					 FROM mast_services
					 WHERE service_id=$service_id";
	
					 
		if (!$result= $objmydbcon->get_result_set($sqlquery)){
			return "NOT DEFINED";
		}else if (mysqli_num_rows($result)>0){
			$rs = mysqli_fetch_row($result);
			return $rs[0];
		}else{
			return "UNDEFINED";
		} // if
	} // function get_service_name
	
	
	
	function get_list($status,$curdate,$showname=false,$showservice=false,$showstation=false,$showstatus=false){
		global $objmydbcon;
		
		
		
		$sqlquery = "SELECT * 
					 FROM  trn_registrations
					 WHERE trans_date='$curdate'
					 AND status='$status'
					 AND active=1
					 ORDER BY service_id ASC, sequential ASC";
		#echo $sqlquery;
		
		$showname_col = "";
		$showservice_col = "";
		$showstation_col = "";
		$showstatus_col = "";
		
		if ($showname){
			$showname_col = "<td><span>NAME</span></td>";
		} // if 
		
		if ($showservice){
			$showservice_col = "<td><span>SERVICIO / DEPARTAMENTO</span></td>";
		} // if
		
		if ($showstation){
			$showstation_col = "<td><span>ESTACION</span></td>";
		} // if 
		
		if ($showstatus){
			$showstatus_col = "<td><span>ESTATUS</span></td>";
		} // if 
		
		$tempstr = "<table class='table'>".
					"<thead>".
					"<tr>".
					"<td><p>TURNO</p></td>".
					$showname_col.
					$showservice_col.
					$showstation_col.
					$showstatus_col.
					"</tr>".
					"</thead>".
					"<tbody>";
					
					 
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			echo "Error in query $sqlquery<Br>" . $objmydbcon->get_error();
			exit;
		}else if (mysqli_num_rows($result)>0){
			
			while ($rs = mysqli_fetch_assoc($result)){
				extract($rs);
				
				$namecol = "";
				$servicecol = "";
				$stationcol = "";
				$statuscol = "";
				
				if ($showname){
					$namecol = "<td><span>" . strtoupper($name) . "</span></td>";	
				} // if 
				
				if ($showservice){
					$servicecol = "<td><span>" . $this->get_service_name(strtoupper($service_id)) . "</span></td>";
				} // if 
				
				if ($showstation){
					$stationcol = "<td><span>#$station_id</span></td>";
				} // if 
				
				if ($showstatus){
					$statuscol = "<td><span>" . strtoupper($status) . "</span></td>";
				} // if 
				
				$tempstr .= "<tr>".
							"<td><span>" . str_pad($sequential,4,0,0) . "</span></td>".
							$namecol.		
							$servicecol.
							$stationcol.
							$statuscol.
							"</tr>";
			} // while
		}else{
			$tempstr .= "<tr><td colspan='5'><span>NO RECORDS FOUND</span></td></tr>";
		} // if
		
		return $tempstr ."</tbody></table>";								 						
		
	} // functin get_list
	
	function set_ajax_notification($idx){
		global $objmydbcon;
		
		$sqlquery = "UPDATE trn_registrations
					 SET has_been_called=1
					 WHERE idx = $idx";		
		
		if ($objmydbcon->set_query($sqlquery)){
			return 1;
		}else{
			return 0;
		} // if 
		
	} // function set_ajax_notification	
	
	function get_next_ajax($curdate,$service_id,$station_id){
		global $objmydbcon;
		
		$sqlquery = "SELECT idx FROM trn_registrations
					 WHERE trans_date='$curdate'
					 AND service_id=$service_id
					 AND status='waiting'
					 ORDER BY sequential ASC
					 LIMIT 0,1";
		
		
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			echo "Error in query $sqlquery<Br>" . $objmydbcon->get_error();
			exit;
		}else if (mysqli_num_rows($result)>0){
			$rs = mysqli_fetch_row($result);	
			
			# NOTIFY NEXT QUEUE
			$this->set_notify_next_queue($curdate,$service_id);		
			
			return $rs[0];
		}else{
			return false;
		} // if 
	} // function get_next_ajax
	
	function set_new_status($status,$idx){
		global $objmydbcon;
		
		$curtimestamp = date("Y-m-d H:i:s");
		
		$sqlquery = "UPDATE trn_registrations
					 SET status='$status',trans_end_timestamp='$curtimestamp'
					 WHERE idx=$idx";
					 
		if ($objmydbcon->set_query($sqlquery)){
			return 1;
		}else{
			return 0;
		} // if 
	} // function
	 
	
	function set_next_ajax($curdate,$service_id,$station_id){
		global $objmydbcon;
		
		if (!$idx = $this->get_next_ajax($curdate,$service_id,$station_id)){
			return false;
		} // if 
		$curtimestamp = date("Y-m-d H:i:s");
		
		$sqlquery = "UPDATE trn_registrations
					 SET status='serving',station_id=$station_id,trans_start_timestamp='$curtimestamp'
					 WHERE idx=$idx";
					 
		if ($objmydbcon->set_query($sqlquery)){
			return $idx;
		}else{
			return 0;
		} // if 			 
		
	} // function set_next_ajax
	
	function get_list_ajax($status,$curdate){
		global $objmydbcon;
		
		
		
		$sqlquery = "SELECT * 
					 FROM  trn_registrations
					 WHERE trans_date='$curdate'
					 AND status='$status'
					 AND active=1
					 ORDER BY service_id ASC, sequential ASC";
		#echo $sqlquery;
							 
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			echo "Error in query $sqlquery<Br>" . $objmydbcon->get_error();
			exit;
		}else if (mysqli_num_rows($result)>0){
			
			while ($rs = mysqli_fetch_assoc($result)){
				extract($rs);
				
				$ary_temp['idx'][] = $idx;
				$ary_temp['sequential'][] = str_pad($sequential,4,0,0);
				$ary_temp['name'][] = strtoupper($name);	
				$ary_temp['service'][] = $this->get_service_name(strtoupper($service_id));
				$ary_temp['station'][] = str_pad($station_id,3,0,0);
				$ary_temp['status'][] = strtoupper($status);
				$ary_temp['has_been_called'][] = $has_been_called;
				 
				
				
			} // while
		}else{
			$ary_temp['status'] = "NO RECORDS FOUND";
		} // if
		
		return $ary_temp;
		
	} // function get_list_ajax
	
	function set_sequential($service_id,$date,$sequential,$init=false){
		global $objmydbcon;
		
		if ($init== true){
			$sqlcommand = "INSERT INTO";
			$sqlconditional = "";
		}else{
			$sqlcommand = "UPDATE";
			$sqlconditional = "WHERE service_id=$service_id AND trans_date='$date'";
		} // if 
	
		
		
		$sqlquery = "$sqlcommand trn_numbering
					 SET trans_date='$date',
					 service_id=$service_id,
					 sequential=$sequential,
					 active=1
					 $sqlconditional";
					
		if ($objmydbcon->set_query($sqlquery)){
			return true;
		}else{
			echo "Error in query $sqlquery <br>" . $objmydbcon->get_error();
			exit;
		} // if 
		
	} // function 
	
	function get_sequential($service_id,$date){
		global $objmydbcon;
		
		$sqlquery = "SELECT sequential 
					 FROM trn_numbering 
					 WHERE service_id=$service_id
					 AND trans_date='$date'
					 AND active=1";
		
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			echo "Error in query $sqlquery <br>" . $objmydbcon->get_error();
			exit;
		}else if (mysqli_num_rows($result)>0){
			$rs = mysqli_fetch_row($result);
			$sequential = $rs[0] + 1;
			if ($this->set_sequential($service_id,$date,$sequential)){
				return $sequential;
			}else{
				return false;
			} // if 
		}else{
			# NO RECORD FOUND
			$sequential = 100;
			if ($this->set_sequential($service_id,$date,$sequential,true)){
				return $sequential;
			}else{
				return false;
			} // if 
		} // if 
			
		
	} // function get_sequential
		
	
	function get_services(){
		global $objmydbcon;
				
		//make the selectbox
		$tempstr = "<select id='cbo_servicess' name='cbo_servicess' class='selectpicker span2' data-style='btn-selectbox' style='width:400px'>";
				   
					
			
		$sqlquery = "SELECT * 
					 FROM mast_services
					 WHERE active=1";
					 			
		
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if (mysqli_num_rows($result)>0){
			if (mysqli_num_rows($result)>1){
				$tempstr .="<option selected disabled>SELECT A SERVICE</option>";
			} // if 
				
			
			while ($rs = mysqli_fetch_assoc($result)){
				extract($rs);				
				$tempstr .= "<option value='". $service_id ."'>$descr</option>\n";
			}// while				
		}else{
			$tempstr .="<option value='-1'>NO RECORDS FOUND</option>";		
		} // if
		
		$tempstr .="</select>";
		
		

		return $tempstr; 
	} // function get_services
	
	function set_registration($id,$array){
		global $objmydbcon;
		
		extract($array);
		
		if ($id == 0){
			$sqlcommand = "INSERT INTO";
			$sqlconditional = "";
		}else if ($id >0){
			$sqlcommand = "UPDATE";
			$sqlconditional = "WHERE id=$id";
		} // if 
		
		$curdate = date("Y-m-d");
		$timestamp = date("Y-m-d H:m:s");
		
		if (!$sequential = $this->get_sequential($cbo_servicess,$curdate)){
			return false;
		} // if 
		
		$sqlquery = "$sqlcommand trn_registrations
					 SET sequential='$sequential', 
					 name='$txt_name',
					 mobile='$txt_mobile',
					 service_id = '$cbo_servicess',
					 notification_type = '$opt_notification_type',
					 station_id = 1,
					 active=1,
					 trans_date='$curdate',
					 trans_timestamp='$timestamp'";
	
			
	
		if ($objmydbcon->set_query($sqlquery)){
			# CHECK IF USER ASK FOR SMS
			
			if ($opt_notification_type == "sms"){
				# send sms via email
				$msg = "SU TURNO ES EL NUMERO " . str_pad($sequential,3,0,0) . " PARA EL SERVICIO DE : " . $this->get_service_name($cbo_servicess);
				$mobile = str_replace("(","",$txt_mobile);
				$mobile = str_replace(")","",$mobile);
				$mobile = str_replace("-","",$mobile);				
				$mobile = str_replace(" ","",$mobile);
				$this->set_sms($mobile . "@mobile.mycingular.net",$msg);
			}  // if
			
			return true;
		}else{
			return false;
		} // if 
		
		
	} // function set_register
	
	function set_remove_registration(){
		global $objmydbcon;
		
	} // function 
	
	function set_notify_next_queue($curdate,$service_id){
		global $objmydbcon;
		
		$sqlquery = "SELECT sequential,mobile,notification_type FROM trn_registrations
					 WHERE trans_date='$curdate'
					 AND service_id=$service_id
					 AND status='waiting'					 
					 ORDER BY sequential ASC
					 LIMIT 2,1";
		
		#echo $sqlquery;
		
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			
			echo "Error in query $sqlquery<Br>" . $objmydbcon->get_error();
			exit;
		}else if (mysqli_num_rows($result)>0){
			$rs = mysqli_fetch_assoc($result);
			extract($rs);
			if ($notification_type == "sms"){
				# send sms via email
				$msg = "SU TURNO " . str_pad($sequential,3,0,0) . " PARA EL SERVICIO DE : " . $this->get_service_name($service_id) . " ESTA PROXIMO A SER LLAMADO";
				$mobile = str_replace("(","",$mobile);
				$mobile = str_replace(")","",$mobile);
				$mobile = str_replace("-","",$mobile);				
				$mobile = str_replace(" ","",$mobile);
				$this->set_sms($mobile . "@mobile.mycingular.net",$msg);
			}  // if
			return true;
		} // if 
	} // funciton set_notify_nex_queue
	
	function set_sms($email,$msg){
		# CONFIGURE EMAIL
		$fromname = "First Bank";
		$Email_From = "ebusiness@prcinternet.net";		

		$headers = "From: $fromname <$Email_From>\n";
   		$headers .= "X-Sender: <$Email_From>\n";
   		$headers .= "X-Mailer: PHP/" . phpversion(). "\n"; // mailer
   		$headers .= "X-Priority: 0\n"; // Urgent message!
   		$headers .= "Return-Path: <$Email_From>\n";  // Return path for errors
				/*
   				/* If you want to send html mail, uncomment the following line */
   				
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type

   		# and now mail it

		
  	 	if (@mail($email, $subject, $msg, $headers)){
			$cnt++;

		} // if
	} // function set_sms
	
	
	
} // class

?>