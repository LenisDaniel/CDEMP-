
var request = false;
try {
  httpXml = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    httpXml = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      httpXml = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      httpXml = false;
    }
  }
}

if (!httpXml){
  alert("You must have an AJAX Capable Browser to navigate through this website.");
} // if

httpXml2 = httpXml;
