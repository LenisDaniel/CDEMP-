<?php
/*
filename : contact.inc.php
Programmed by : Luis R. Martinez Rojas
Date : 2008.04.01  Time : 1:45 pm
*/



//******************************************************************************
// MAIN
//******************************************************************************


//$slide_list = $obj_cardata->getSlides();
//$offers_list = $obj_cardata->getOffers(true);


#print_r($offers_list);
#exit;

if ($slide_list[0]['idx']>0) {
	
	$slide_data = "";
	for ($i=0;$i<count($slide_list);$i++) {
		if ($slide_list[$i]['style']==2) {
			$caption_style = "v2";
		} else {
			$caption_style = "";
		} //end if
		if ($slide_list[$i]['color']=="wht") {
			$color_style = " white";
		} else {
			$color_style = "";
		} //end if
		
		$slide_data .= "<li>";
		$slide_data .= "<img src='banners/".$slide_list[$i]['img']."'/>";
		$slide_data .= "<div class='caption-holder".$caption_style . $color_style."'>";
		$slide_data .= "<div class='caption-head".$caption_style . $color_style."'>";
		$slide_data .= "<p>".utf8_encode($slide_list[$i]['title'])."</p>";
		$slide_data .= "</div>";
		
		if (trim($slide_list[$i]['descr'])!="") {
			$slide_data .= "<div class='caption-desc".$caption_style . $color_style."'>";
			$slide_data .= utf8_encode($slide_list[$i]['descr']);
			$slide_data .= "</div>";
		} //end if
		$slide_data .= "</div>";
		$slide_data .= "</li>";
		//*** SET GRID VIEW
		
	} //end for
	
	$objtemplate->set_content("slide_list",$slide_data);
	
} //end if

if ($offers_list[0]['idx']>0) {
	$offers_data = "";
	for ($i=0;$i<count($offers_list);$i++) {
		$offers_data .= "<li>";
		$offers_data .= "<div class='ch-item'>";
		$offers_data .= "<div class='ch-item-border'>";
		$offers_data .= "<img class='ch-img' src='images/offers/".$offers_list[$i]['img']."'/>";
		$offers_data .= "</div>";
		$offers_data .= "<div class='ch-info-wrap'>";
		$offers_data .= "<div class='ch-info'>";
		$offers_data .= "<div class='ch-info-front'>";
		$offers_data .= "</div>";
		$offers_data .= "<div class='ch-info-back'>";
		$offers_data .= "<a href='display_page.php?tpl=offer-detail&idx=".base64_encode($offers_list[$i]['idx'])."'>";
		$offers_data .= "<h3>Ver Oferta</h3>";
		$offers_data .= "<p><i class='fa fa-search-plus'>&ensp;</i></p>";
		$offers_data .= "</a>";
		$offers_data .= "</div>";
		$offers_data .= "</div>";
		$offers_data .= "</div>";
		$offers_data .= "</div>";
		$offers_data .= "<div class='ch-item-details'>";
		$offers_data .= "<h1>".$offers_list[$i]['title']."</h1>";
		$offers_data .= "<p>".$offers_list[$i]['teaser']."</p>";
		$offers_data .= "</div>";
		$offers_data .= "<div class='share-options'>";
		$offers_data .= "<p>Comparte esta oferta</p>";
		$offers_data .= "<div class='addthis_sharing_toolbox offer-share-options' data-url='display_page.php?tpl=offer-detail&idx=".base64_encode($offers_list[$i]['idx'])."' data-title='".$offers_list[$i]['title']."'>";
		$offers_data .= "</div>";
		$offers_data .= "</div>";
		$offers_data .= "</li>";
	} //end for
	$objtemplate->set_content("offers_list",$offers_data);
	
} //end if


# USE CUSTOM TEMPLATE FOR THIS PAGE
#$main_tpl = "template_02";




?>
