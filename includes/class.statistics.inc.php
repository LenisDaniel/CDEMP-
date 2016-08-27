<?php
/*
filename : class.statisctics.inc.php
Programmed by : Luis R. Martinez Rojas
Date: 2006.08.12  Time : 3:16 pm

DESCRIPTION :
THIS CLASS IS FOR REGISTER VISIT STATISTICS
IT REQUIRES THE DB HANDLER REFERENCED BY VARIABLE [$link]

CLASS CONSTRUCTOR WILL GET ALL THE INFORMATION REQUIRED TO BE STORED,
INCLUDING PROTOCOL, URL, GET PARAMS, POST PARAMS, CLIENT ID AND TIME STAMP

METHOD [set_visit] WILL PERFORM THE ACTUAL STATISTIC STORAGE
*/

class classStatistics {
	var $protocol = "";
	var $host = "";
	var $path = "";
	var $query = "";
	var $post = "";
	var $sessid = "";
	var $client_id = 0;
	var $referer = "";
	var $time_stamp="";
	var $ip_address="";
	
	// CLASS CONSTRUCTOR
	function classStatistics(){
	
        $this->time_stamp = date("Y-m-d H:i:s");

		// GATHER CLIENT_ID FROM COOKIE
		if (isset($_COOKIE['client_id']['id'])){
			$this->client_id = base64_decode($_COOKIE['client_id']['id']);
		} // if

		// GATHER URL PARTS
		// PROTOCOL
		if ($_SERVER['SERVER_PORT_SECURE']){
			$this->protocol = 'https://';
		}else{
			$this->protocol = 'http://';
		} // if
		
		// HOST
        $this->host = $_SERVER['HTTP_HOST'];
		// PATH
		$this->path = $_SERVER['PHP_SELF'];
		
		// GATHER REFERER
		if (isset($_SERVER['HTTP_REFERER'])){
			$this->referer = $_SERVER['HTTP_REFERER'];
		} // if
		
		// GATHER IP ADDRESS
		if (isset($_SERVER['REMOTE_ADDR'])){
			$this->ip_address = $_SERVER['REMOTE_ADDR'];
		} // IF
        
		// GATHER GET VARS
		if (isset($_GET)){
			while (list($key,$value) = each($_GET)){
				$this->query .= "&$key=$value";
			} // while
		} // if
    
		// GATHER POST VARS
		// EXCLUDE checkout_step2 module
		if (!$_GET['tpl'] == 'checkout_step2'){
			if (isset($_POST)){
				while (list($key,$value) = each($_POST)){
					$this->post .= "&$key=$value";
				} // while
			} // if
		} // if

		// GATHER SESSION ID
		if (isset($_COOKIE['PHPSESSID'])){
			$this->sessid = $_COOKIE['PHPSESSID'];
		} // if

	} // function classStatistics
	
	function set_visit($client_id=0){
		global $objmydbcon;
		if ($client_id != 0){
			$this->client_id = $client_id;
		} // if
		// CONSTRUCT QUERY
		$sqlquery = "INSERT INTO activity_stats
					 SET client_id=" . $this->client_id . ",
					 url='" . $this->protocol . $this->host . $this->path . "',
					 ip_address='" . $this->ip_address . "',
					 get_vars='" . $this->query . "',
					 post_vars='" . $this->post . "',
					 referer='" . $this->referer . "',
					 time_stamp='" . $this->time_stamp . "',
					 sessid='" . $this->sessid . "'";
		if ($objmydbcon->set_query($sqlquery)){
			return true;
		}else{
			return false;
		} // if
	} // function set_visit
	
	function get_item_descr($item_number){
		global $objmydbcon;
		$sqlquery = "SELECT type,title,image_url
		            FROM products
		            WHERE item_number='$item_number'";
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			return "Product Unknown";
		}else if (mysql_num_rows($result)>0){
			$link_start = "";
			$link_end = "";
			$rs = mysql_fetch_assoc($result);
			if (isset($rs["image_url"]) ){
                $img_dir = "../imagelib/";
                $prod_pic = $rs["image_url"];
				// default values
				$size = 175;
                $window_width=175;
                $window_height=200;
				if ($d = @getimagesize($img_dir . $prod_pic)){
					$size = $d[0] ; // based on its width
					$window_width = $d[0];
					$window_height = $d[1];
					$link_start = "<a href=\"javascript:;\" onclick=\"var winpicture=window.open('../show_prod_pic.php?img_dir=" . base64_encode("imagelib/") . "&prod_pic=" . base64_encode($prod_pic) . "&size=" . base64_encode($size) .">','Picture','width=$window_width,height=$window_height')\">";
                	$link_end = "</a>";
				} // if
            } // if isset image_url
			return $link_start . $rs['type'] . " " . $rs['title'] . $link_end;
		}else{
			return "Product Unknown";
		} // if
	} // function get_item_descr
	
	function get_top_ten_viewed_prods($limit=5){
		global $objmydbcon;
		$sqlquery = "select count(*) as hits,get_vars,time_stamp
					FROM activity_stats
					WHERE get_vars like '%itemdetail%'
					GROUP BY get_vars
					ORDER BY hits DESC,time_stamp DESC
					LIMIT 0,$limit";
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if (mysql_num_rows($result)>0){
			$tempstr ="<table width='100%' cellpadding='2' cellspacing='1' border='0'>\n
					   <tr><td colspan='2' style=\"background-image: url('images/backg_diagonal_gray_lines.gif'); background-repeat: repeat-x; border: 1px solid rgb(192,192,192)\"><b>Item #</b></td>
					   <td style=\"background-image: url('images/backg_diagonal_gray_lines.gif'); background-repeat: repeat-x; border: 1px solid rgb(192,192,192)\"><b>Producto</b></td>
					   <td style=\"background-image: url('images/backg_diagonal_gray_lines.gif'); background-repeat: repeat-x; border: 1px solid rgb(192,192,192)\"><b>Hits</b></td>
					   </tr>\n";
			$rowcnt = 1;
			while ($rs = mysql_fetch_assoc($result)){
				extract($rs);
				$ary_temp = explode('&',$get_vars);
				$ary_temp2 = explode('=',$ary_temp[2]);
				$item_number=base64_decode($ary_temp2[1]);
				$tempstr .="<tr>
				            <td class='regtxt'>$rowcnt.</td>
				            <td class='regtxt'><b>$item_number</b></td>
				            <td class='regtxt'>" . $this->get_item_descr($item_number) . "</td>
                            <td class='regtxt' align='right'><b>" . number_format($hits,0,'',',') . "</b></td>
							</tr>\n
							<tr><td colspan='4' style=\"background-repeat: repeat-x; background-image: url('images/bkg_green_dots.gif')\"><img src='../images/clear.gif' width='5' height'1' border='0'></td></tr>\n";
				$rowcnt++;
			} // while
			$tempstr .="</table>\n";
			return $tempstr;
		}else{
			return false;
		}// if
	} // function
	
	function get_total_visits(){
		global $objmydbcon;
		// GET UNIQUE VISITS
		$sqlquery = "SELECT COUNT(DISTINCT sessid)
					 FROM activity_stats";
		$result = $objmydbcon->get_result_set($sqlquery);
		$rs = mysql_fetch_row($result);
		$unique_visits = $rs[0];
		// GET TOTAL HITS
		$sqlquery = "SELECT COUNT(*)
					 FROM activity_stats";
		$result = $objmydbcon->get_result_set($sqlquery);
		$rs = mysql_fetch_row($result);
		$total_hits = $rs[0];
		unset($result,$rs);
		$tempstr = "<table width='100%' cellpadding='2' cellspacing='1' border='0'>\n
		            <tr><td class='regtxt'>Visitas Unicas</td>
					<td class='regtxt' align='right'><b>" . number_format($unique_visits,0,'',',') . "</b></td></tr>\n
					<tr><td colspan='2' style=\"background-repeat: repeat-x; background-image: url('images/bkg_green_dots.gif')\"><img src='../images/clear.gif' width='5' height'1' border='0'></td></tr>\n
					<tr><td class='regtxt'>Hits (Page views)</td>
					<td class='regtxt' align='right'><b>" . number_format($total_hits,0,'',',') . "</b></td></tr>\n
					</table>\n";
		return $tempstr;
	} // function get_total_visits
	
	function get_total_registered_clients(){
		global $objmydbcon;
		// GET UNIQUE VISITS
		$sqlquery = "SELECT COUNT(*) AS cnt,IF (active=1,'Activos','Inactivos') AS active_status
					 FROM clients
					 GROUP BY active";
		if (!$result = $objmydbcon->get_result_set($sqlquery)){
			return false;
		}else if (mysql_num_rows($result)>0){
			$tempstr = "<table width='100%' cellpadding='2' cellspacing='1' border='0'>\n";
			$total_clients = 0;
			while($rs = mysql_fetch_assoc($result)){
				extract($rs);
		        $tempstr .= "<tr><td class='regtxt'>$active_status</td>
							 <td class='regtxt' align='right'><b>" . number_format($cnt,0,'',',') . "</b></td></tr>\n
							 <tr><td colspan='2' style=\"background-repeat: repeat-x; background-image: url('images/bkg_green_dots.gif')\"><img src='../images/clear.gif' width='5' height'1' border='0'></td></tr>\n";
				$total_client +=$cnt;
			} // while
			// present total clients
			$tempstr .="<tr><td class='regtxt'><b>Total</b>
			            <td class='regtxt' align='right'><b>" . number_format($total_client,0,'',',') . "</b></td>
			            </tr>\n
			            </table>\n";
			return $tempstr;
		}else{
			return false;
		} // if
	} // function get_total_visits
} // class statistics
?>
