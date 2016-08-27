<?php
/* file name: common_functions.inc.php
     Programmed by Luis R. Martinez Rojas
     10.18.2002
*/

function fngetsearch(){
     $tempstr = "<form method='POST' action='searchengine.php' onsubmit='return TestField(this)'>\n".
                "<div align='center'><center><table border='0' cellpadding='3' cellspacing='1'>\n".
                "<tr>\n".
                "<td><input type='text' name='txtSearch' size='13' class='searchbox'></td>\n".
                "<td><input type='submit' value='Ve' name='btn_submit' class='search_btn'></td>\n".
                "</tr>\n".
                "</form>\n".
                "</table>\n".
                "</center></div>\n";
     return $tempstr;
} // function fngetsearch

function fnreporterr($err){
     $mailhost = "mail.prcinternet.net";
     $recipient = "ebusiness@prcinternet.net";
     $subject = "Program error detected in virtual store dvdentucasa.com";
     echo $subject . "<br>" . $err;
}
global $search_form;
$search_form = fngetsearch();

function formatcurrency($num){
		if (! is_numeric($num) || $num ==""){
			 return "$0.00";
		}else{
					//return sprintf('$' . "%01.2f", $num);
					return "$" . number_format($num,2,'.',',');
		}
} // functoin formatcurrency

function strip_quotes($string){
	if (strstr($string,"'")){
		$string = str_replace("'","''",$string);
	} // if strstr
	return $string;
} // function strip_quotes

function magic_quotes($string,$search_string=false){
    // THIS FUNCTION REPLACES SIGLE QUOTES WITH DOUBLE SINGLE QUOTES
    // TO AVOID PROBLEMS WITH DATA ENTRY TO DATABASES.
    // ALSO STRIPS BACK SLASH CARARCTER (\)
    
    $temp = str_replace("\\","",$string);
    if ($search_string == true){
    	$temp = str_replace("\"","",$temp);
    	$temp = str_replace("'","",$temp);
	}else{
		$temp = str_replace("'","''",$string);
    } // if
    return $temp;
} // function magic_quotes

function sanitize($var){
	$invalid_chars = "<,>,/,\\,#,%,(,)";
	$tmp = $var;
	$ary_inv_chars = explode(",",$invalid_chars);
	
	if (is_array($tmp)){
		while(list($key,$value) = each($tmp)){
			foreach($ary_inv_chars as $inv_char){
				if (stristr($tmp,$inv_char)){
        			$tmp = str_replace($inv_char,"",$tmp);
        		} // if
			} // for
		} // while
	}else{
        foreach($ary_inv_chars as $inv_char){
			if (stristr($tmp,$inv_char)){
        		$tmp = str_replace($inv_char,"",$tmp);
        	} // if
		} // for

	} // if is_array
	
	
	return $tmp;
} // function

?>
