<!--
    //For Numeric only checking
	function isNumeric(event) {
		var KeyTyped = String.fromCharCode(event.keyCode);
		if(parseInt(KeyTyped.toString()) != KeyTyped.toString()) {
			return false;
		} else {
			return true;
		} // if
	 }  // function fnIsNumeric
	//******************************************************************************************
	// Validate currency
	function isCurrency (event) {
		sString = String.fromCharCode(event.keyCode);
 		return RegExp(/^-?\$?[0-9\,.]+(\.\d{2})?$/).test(String(sString).replace(/^\s+|\s+$/g, ""));
	} // if isCurrency
	//******************************************************************************************
	// Validate float
	function isFloat (event) {
		sString = String.fromCharCode(event.keyCode);
 		return RegExp(/^-?\$?[0-9\.]+(\.\d{2})?$/).test(String(sString).replace(/^\s+|\s+$/g, ""));
	} // if isCurrency
    //******************************************************************************************
	// Validate float
	function isPercent (event) {
		sString = String.fromCharCode(event.keyCode);
 		return RegExp(/^-?\$?[0-9\.]+(\.\d{2})?$/).test(String(sString).replace(/^\s+|\s+$/g, ""));
	} // if isCurrency
    //******************************************************************************************
	function isDate(theField){
		var validformat=/^\d{2}\/\d{2}\/\d{4}$/ //Basic check for format validity
		var returnval=false;
		if (!validformat.test(theField.value)){
			alert("Invalid Date Format. Please correct and submit again.\nDate Format (mm/dd/yyyy).");
		}else{
			//Detailed check for valid date ranges
			var monthfield=theField.value.split("/")[0];
			var dayfield=theField.value.split("/")[1];
			var yearfield=theField.value.split("/")[2];
			var dayobj = new Date(yearfield, monthfield-1, dayfield);
			if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield)){
				alert("Invalid Date.\n Please correct and submit again.\nDate Format (mm/dd/yyyy).");
			}else{
				returnval=true;
			} // if
		} // if !validformart
		if (returnval==false){
			theField.select();
		} // if
		return returnval;
	} // function isDate
	//******************************************************************************************
	// for auto tabbing
	function autotab(theField,theForm,nextField){
		if (theField.value.length == theField.size){
			nextField.focus();
		} // if
	} // function autotab
    //******************************************************************************************
	// calendar open
	function OpenCalendar(fieldName,theForm){
		//theForm = document.form_register_asset;
		theField = theForm.elements[fieldName];
		form_opener = theForm.name;
		
		window.open("calendar.php?fieldName=" + fieldName + "&form_opener=" + form_opener,"calendar","width=250,height=200,scrollbars=no,left=260,top=340");
	}// function OpenCalendar
    //******************************************************************************************
	// calendar reset
	function EraseField(fieldName,theForm){
		//theForm = document.form_register_asset;
		theForm.elements[fieldName].value = "";
	} // function EraseField
	//******************************************************************************************
	// show or hidden division blocks
	function toggleVisibility(id){
		if (document.getElementById(id).style.display == 'none'){
			// show / expanded
			document.getElementById(id).style.display = 'block';
			document.getElementById("img_"+id).src = "images/icon_contract.gif";
		}else{
			// hiden / collapsed
			document.getElementById(id).style.display = 'none';
			document.getElementById("img_"+id).src = "images/icon_expand.gif";
		} // if
	} // function toggleVisibility

	
	function formatCurrency(theField) {
		num = theField.value;
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num)){
			num = "0";
			alert("This field required to be numeric");
			theField.select();
			return;
		} // if
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10)
		cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+
		num.substring(num.length-(4*i+3));
		//return (((sign)?'':'-') + num + '.' + cents);
		theField.value = (((sign)?'':'-') + "$" + num + '.' + cents);
	} // function formatCurrency

	function clean_currency(value){
		temp = str_replace("$","",value);
		temp = str_replace(",","",temp);
		return temp;
	} // function clean_currencies

	function str_replace(search,replace,haystack){
		temp = "" + haystack; // temporary holder

		while (temp.indexOf(search)>-1) {
			pos= temp.indexOf(search);
			temp = "" + (temp.substring(0, pos) + replace +
			temp.substring((pos + search.length), temp.length));
		} // while
		return temp;
	} // function replaceChars
//-->
