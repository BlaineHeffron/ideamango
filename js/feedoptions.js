<!-- 
function sortchange(sort){
	var xmlhttp; 
	
	try{
		xmlhttp = new XMLHttpRequest();
	} catch (e){
		try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				alert("Your browser broke!");
				return false;
			}
		}
	}

	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4){
			document.getElementById("ideafeed").innerHTML = xmlhttp.responseText;
			$( "#tabs" ).tabs()
			$( "#tabs" ).tabs("refresh");
		}
	}
	xmlhttp.open("GET",'ideatablegen.php?sort='+sort+'&time='+document.getElementById("within").value,true);
	xmlhttp.send();
}
function timechange(time){
	var xmlhttp; 
	
	try{
		xmlhttp = new XMLHttpRequest();
	} catch (e){
		try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				alert("Your browser broke!");
				return false;
			}
		}
	}

	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4  ){
			document.getElementById("ideafeed").innerHTML = xmlhttp.responseText;
			$( "#tabs").tabs();
			$( "#tabs").tabs("refresh");
			
		}
	}
	xmlhttp.open("GET",'ideatablegen.php?sort='+document.getElementById("sort").value+'&time='+time,true);
	xmlhttp.send();
}


-->
