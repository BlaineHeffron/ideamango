<?php session_start();
if(isset($_SESSION['user_id'])){
  include('projectcreate.html');
}
else
  include('login_page.php?project_create=1');
?>
