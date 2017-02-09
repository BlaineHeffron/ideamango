<!doctype html>
<html>
<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/12/21"><meta name = "description" content = "Project home page"><meta name = "keywords" content = "project, collaborate, share, create, innovate, network">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
	<title>Project home page</title>
	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
	<script src="js/AddProjectTab.js"></script>

</head>
<body>
	<?php include("header.php");
	session_start();
	$num_tabs = 1;
	if(isset($_SESSION['project_tags']))
		$num_tabs = count($_SESSION['project_tags'])+1;
	include('ProjectFeedOptions.php');?>
  <span id="create_project">
    <?php if(isset($_SESSION['user_id'])){?>
      <h3>Want to share your project? <a href="http://localhost/projectcreate.html">Setup your project's page here!</a></h3>
    <?php }
      else{?>
      <h3>Want to share your project? <a href="http://localhost/login.php">Login to create your own project page.</a></h3>
    <?php }?>
</br>
	<div id="project_feed">

	<?php include('ProjectTableGen.php');
	?>
  </div>



<script src="js/voteScript"></script>
</body>
</html>
