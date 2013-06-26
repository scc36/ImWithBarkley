// I'm with Barkley - AJAX javascript to retrieve member information
function getPhotos(abid) {
	if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else { // IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("myphotos").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","inc/getphotos.php?album="+abid);
	xmlhttp.send();
}