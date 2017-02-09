<?php session_start()?>

<!doctype html>
<html>
<head><meta charset="utf-8"><meta name ="author" content = "Blaine"><meta name = "revised" content = "2012/12/21"><meta name = "description" content = "user login"><meta name = "keywords" content = "user, login">
        <link rel="stylesheet" type="text/css" href="css/mango.css">
	<title>User login page</title>
</head>
<body>
<?php
if(isset($_SESSION['user_id'])){
  $login_id = $_SESSION['user_id'];
  echo '<p>You are already logged in, <a href="http://localhost/user/'.$login_id.'">Visit your homepage.</a></p>';
}
else{
  if(isset($_GET['project_create'])){
    echo '<p>You must login in order to create a project. If you do not have an account, <a href="http://localhost/usercreate.php"> create one here.</a></p>';?>
    <div id="login_form">
      <h5>Login</h5>
      <div id="login_warning">
        <p id="warn"></p>
      </div>
      <form action="login_check.php" method="post">
        Username: <input type="text" name="username"/>
        Password: <input type="text" name="password"/>
        <input type="hidden" name="project_create" value="1">
        <input type="submit" value="Submit">
      </form>
    </div>

<?php
  }
  elseif(isset($_GET['idea_create'])){
    echo '<p>You must login in order to share your idea. If you do not have an account, <a href="http://localhost/usercreate.php"> create one here.</a></p>';

