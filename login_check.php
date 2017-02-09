<?php
session_start();
include('mysql_connect.php');
function redirect($url) {
    echo "<script type='text'/javascript'>window.location='" . $url . "';</script>";
}

$escaped_name = mysql_real_escape_string($_POST['username']);
$escaped_pass = mysql_real_escape_string($_POST['password']);

$salt_query = "select salt from users where username = ?";
try{
	  $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $q = $conn->prepare($salt_query);
    $q->execute(array($escaped_name));
    $salt = $q->fetchColumn();
  } catch(PDOException $e) {
	  echo 'ERROR: ' . $e->getMessage();
  }


if(empty($salt)){
  $conn=null;
  redirect('http://localhost/ideas.php');
}


$salted_pass =  $escaped_pass . $salt;

$hashed_pass = hash('sha512', $salted_pass);

$query = "select id from users where username = ? and password = ?; ";
try
{
  $q = $conn->prepare($query);
  $q->execute(array($escaped_name,$hashed_pass));
  $user_id = $q->fetchColumn();
} catch(PDOException $e) {
	  echo 'ERROR: ' . $e->getMessage();
}
$conn=null;
if(empty($user_id))
  redirect('http://localhost/ideas.php');
else{
  $_SESSION['user_id'] = $user_id;
  if(isset($_GET['project_create'])):
    redirect('http://localhost/projectcreate.php');
  elseif(isset($_GET['idea_create'])):
    redirect('http://localhost/ideacreate.php');
  elseif(isset($_GET['box_url'])):
    redirect($_GET['box_url']);
  else:
    redirect('http://localhost/ideas.php');
  endif;
}

?>
