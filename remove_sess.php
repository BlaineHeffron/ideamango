<?php session_start();
include('data_sanitizers.php');

if(isset($_POST['idea_tag_index'])){
  $index = clean($_POST['idea_tag_index']);
  unset($_SESSION['idea_tags'][$index]);
  $_SESSION['idea_tags'] = array_values($_SESSION['idea_tags']);
}
else{
  $index = clean($_POST['project_tag_index']);
  unset($_SESSION['project_tags'][$index]);
  $_SESSION['project_tags'] = array_values($_SESSION['project_tags']);
}
?>
