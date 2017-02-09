<!doctype html>
<html>
<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/10/20"><meta name = "description" content = "Idea home page"><meta name = "keywords" content = "idea, share, collaborate, discuss, innovate, network">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
	<title>Idea sharing home page</title>
	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
	<script src="js/AddIdeaTab.js"></script>

</head>
<body>
	<?php include("header.php");
	session_start();
	$num_tabs = 1;
	if(isset($_SESSION['idea_tags']))
		$num_tabs = count($_SESSION['idea_tags'])+1;
	include('ideafeedoptions.php');?>

        </br>
	<div id="idea_feed">

	<?php include('ideatablegen.php');
	?>
  </div>



<script src="js/voteScript"></script>
</body>
</html>
