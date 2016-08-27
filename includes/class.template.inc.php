<?php
/*file name: template.inc.php
Programmed by : Luis Martinez Rojas
Sept. 10, 2002
*/
class classTemplate{
     var $content;
     function classTemplate(){
            // CLASS CONSTRUCTOR
            $content = array();
     }

     function set_content($key,$value,$append=false){
            if ($append == true){
                   if (isset($this->content[$key])){
                          $this->content[$key] .= $value;
                   }else{
                          $this->content[$key] = $value;
                   }
            }else{
                   $this->content[$key] = $value;
            } // if $append...
     } // function set_content

     function get_content($key){
            if (isset($this->content[$key])){
                   return $this->content[$key];
            }else{
                   return false;
            }
     } // function get_content

     function getcolsnum($tpl){
            if ($tpl == ""){
                   return false;
            }
            $pos = strpos($tpl,"TEMPLATECOLS:")  + strlen("TEMPLATECOLS:");
            $colsnum = (integer)chop(substr($tpl,$pos,2));
            return $colsnum;
     } // getcolsnum

     function unset_content($key){
            if (isset($this->content[$key])){
                   unset($this->content[$key]);
            }else{
                   return false;
            } // if
     } // function reset_content
     
     function unset_all(){
        unset($this->content);
     } // function unset_all

     function parsedata($datablock,$display=false,$rules=""){
            /* PROTO CLASS OPERATION parsedata
            parsedata(datablock,[string rules],[bool display])
            DESCRIPTION:
            This template must contain special tags within curly braces like "{content}".
            this class operation will match and replace such special tags with their equivalent named variables.
            PARAMETERS DESCR:
            datablock: scalar variable, required.  This is an opened template loaded in a variable with the class operation open.
            //
            rules: string, optional. Delimited by ">>" and "::", this rules overrides the automatic detection, match and replacement for the
            special tags and their correspondent variable content.  If specified, Tag replacement will occur following the rules string.  If
            not specified, automatic tag replacement using variables names method will assumed.
            //
            display: boolean, optional.  Once all Template's tags were replaced, if display = true then Template is displayed, otherwise, the
            resulting "replaced template" will be returned.  If not specified, false is assumed by default.
            */
            if (!isset($datablock)){
                   // NOT VALID DATABLOCK
                   return false;
            }
            if (!$rules == ""){
                   $aryrules = explode("::",$rules);
                   foreach($aryrules as $rule){
                          $aryrule = explode(">>",$rule);

                          if (stristr($datablock,"<!--" . $aryrule[0] . "-->")!= false){
                                 $datablock = str_replace(strtolower("<!--" . $aryrule[0] . "-->"),$aryrule[1],$datablock);
                          }else{
                                 $datablock = str_replace(strtolower($aryrule[0]),$aryrule[1],$datablock);
                          }
                   }// foreach
            }else{
                   $charpos = 0;
                   $flgstart = false;
                   while ($charpos <= strlen($datablock)){
                          if ($flgstart == false){
                                 $startpos = strpos($datablock,"{",$charpos);
                                 if ($startpos == false) break;
                                 $charpos = $startpos;
                                 $flgstart = true;
                          }else{
                                 $endpos = strpos($datablock,"}",$startpos+1);
                                 $charpos = $endpos;
                                 $flgstart = false;
                                 $tag_name = substr($datablock,$startpos,($endpos-$startpos)+1);
                                 
                                 if (!strstr($tag_name,"\n") && !strstr($tag_name,' ')){
                                    $array[]= $tag_name;
                                 } // if
                                 
                          } // if $flgstart
                   } // while
                   
                   if (isset($array)){
                          foreach($array as $tag){
                                 $key = str_replace("{","",$tag);
                                 $key = str_replace("}","",$key);

                                 if(isset($this->content[$key])){
                                        $datablock = str_replace($tag,$this->content[$key],$datablock);
                                 }else{
                                        if(!strstr($tag," ")){
                                               $datablock = str_replace($tag,"",$datablock);
                                        } // if !strstr($value...
                                 } // if isset($$tempvar

                          } // foreach $array...
                   } // if is_array
            } // if $rules
            if ($display == true || $display == 1){
                   echo $datablock;
                   return true;
            }else{
                   return $datablock;
            }
     } // function parsedata

     function getblock(&$datablock,$blockname){
            // INITIALIZE LOCAL VARIABLES
            $flgstartblock = false;
            $flgstart = false;
            $flgblocknamereplace = false;
            $var = "";
            //
            if (!isset($datablock)){
                   return false;
            } // if $datablock
            $arydata = explode("\n",$datablock);
            $maxelement = count($arydata)-1;

            for($i=0;$i<=$maxelement;$i++){
                   if (stristr($arydata[$i],"<!--START")!=false && stristr($arydata[$i],$blockname)!=false){
                          $flgstartblock = true;
                   } // if stristr($arydata[$i]...
                   if (stristr($arydata[$i],"<!--END")!=false && stristr($arydata[$i],$blockname)!=false){
                          $flgstartblock = false;
                   } // if stristr$arydata[$i]...
                   if ($flgstartblock == true){
                          if ($flgstart == false){
                                 $flgstart  = true;
                          }else{
                                 if ($flgblocknamereplace == false){
                                        $var .= $arydata[$i];
                                        $arydata[$i]= "{" . strtolower($blockname) . "}";
                                        $flgblocknamereplace = true;
                                 }else{
                                        $var .= $arydata[$i];
                                        unset($arydata[$i]);
                                 } //if $flgblocknamereplace...
                          } // if $flgstart
                   } // if $flgstartblock...
            } // for $i
            $datablock = implode($arydata,"\n");
            return $var;
     } // function getblock

		 function stripblock(&$datablock,$blockname){
            // INITIALIZE LOCAL VARIABLES
            $flgstartblock = false;
            $flgstart = false;
            $flgblocknamereplace = false;
            $var = "";
            //
            if (!isset($datablock)){
                   return false;
            } // if $datablock
            $arydata = explode("\n",$datablock);
            $maxelement = count($arydata)-1;

            for($i=0;$i<=$maxelement;$i++){
                   if (stristr($arydata[$i],"<!--START")!=false && stristr($arydata[$i],$blockname)!=false){
                          $flgstartblock = true;
                   } // if stristr($arydata[$i]...
                   if (stristr($arydata[$i],"<!--END")!=false && stristr($arydata[$i],$blockname)!=false){
                          $flgstartblock = false;
                   } // if stristr$arydata[$i]...
                   if ($flgstartblock == true){
                          if ($flgstart == false){
                                 $flgstart  = true;
                          }else{
                                 if ($flgblocknamereplace == false){
                                        $var .= $arydata[$i];
                                        $arydata[$i]= "<!--BLOCK STRIPED:" . strtolower($blockname) . "-->";
                                        $flgblocknamereplace = true;
                                 }else{
                                        $var .= $arydata[$i];
                                        unset($arydata[$i]);
                                 } //if $flgblocknamereplace...
                          } // if $flgstart
                   } // if $flgstartblock...
            } // for $i
            $datablock = implode($arydata,"\n");
            return true;
     } // function stripblock
		 
     function open($template,$type=0){
            $datafile="";
            global $lang;
            if (file_exists($template) || stristr($template,"http://")){
                   $datafile = implode(file($template),"");            
            }else{
                   #$datafile = "Template : $template not found.";
                   $datafile = implode(file("templates_$lang/not_available.tem.htm"),"");
                   return $datafile;
            } // if file_exists
            if ($type ==1){
                   return $datafile;
            }else{
                   $datafile = str_replace("<html>","",$datafile);$datafile = str_replace("<HTML>","",$datafile);
                   $datafile = str_replace("</html>","",$datafile);$datafile = str_replace("</HTML>","",$datafile);
                   $datafile = str_replace("<head>","",$datafile);$datafile = str_replace("<HEAD>","",$datafile);
                   $datafile = str_replace("</head>","",$datafile);$datafile = str_replace("</HEAD>","",$datafile);
                   $datafile = str_replace("<title>","",$datafile);$datafile = str_replace("<TITLE>","",$datafile);
                   $datafile = str_replace("</title>","",$datafile);$datafile = str_replace("</TITLE>","",$datafile);
                   $datafile = str_replace("<body>","",$datafile);$datafile = str_replace("<BODY>","",$datafile);
                   $datafile = str_replace("</body>","",$datafile);$datafile = str_replace("</BODY>","",$datafile);
                   return $datafile;
            } // if $type

     } // function open

} // class objTemplate

?>
