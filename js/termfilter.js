<!--
function addTagTab(tagString) {
	"use strict";
	var xmlhttp1;
//	var xmlhttp2;
	if(tagString === "") {
		return;
	}
	try {
		xmlhttp1 = new XMLHttpRequest();
//		xmlhttp2 = new XMLHttpRequest();
	} catch (e) {
		try {
			xmlhttp1 = new ActiveXObject("Msxml2.XMLHTTP");
//			xmlhttp2 = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
//				xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Browser error");
				return false;
			}
		}
	}
	xmlhttp1.onreadystatechange = function() {
		if(xmlhttp1.readyState == 4){
		/*	if(numTabs != 2){
				var tabList = document.getElementById("tablist").innerHTML;
				var tabContent = document.getElementById("tabcontent").innerHTML;
				document.getElementById("tablist").innerHTML = tabList.concat("<li><a href=\"#tabs-"+nextTabNum+"\">"+tagstring+"</a></li>");
				document.getElementById("tabcontent").innerHTML = tabContent.concat(xmlhttp2.responseText);
			}
			else{*/

				document.getElementById("ideafeed").innerHTML = xmlhttp1.responseText;
		//	}
		}
	}
//	if(numtabs == 2){
	xmlhttp1.open("GET",'ideatablegen.php?tagstr='+tagString+'&?sort='+document.getElementById("sort").value+'&time='+document.getElementById("within").value,true);
	xmlhttp1.send();
//	}

/*	else{	
		xmlhttp2.open("GET",'add_session_tags.php?tagStr='+tagString+'&?sort='+document.getElementById("sort").value+'&time='+document.getElementById("within").value,true);
		xmlhttp2.send();
	}*/

}
-->
