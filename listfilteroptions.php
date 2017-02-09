echo '<script language="javascript" type="text/javascript">
<!-- 
function filterchange(sort,time){
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
		if(xmlhttp.readyState == 4 & xmlhttp.status==200){
			document.getElementById("ideafeed").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ideatablegen.php?sort="+sort+"&time="+time,true);
	xmlhttp.send();

-->
</script>'
