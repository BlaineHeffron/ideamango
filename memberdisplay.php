
<?php include('mysql_connect.php');
include('data_sanitizers.php');

if(!is_null($_GET['member_proj_id'])){
  $member_sql = "SELECT u.id, u.username, u.pic FROM project_members as pm
                 JOIN users as u on u.id = pm.user_id
                 WHERE pm.project_id = ?";

  $member_proj_id = clean($_GET['member_proj_id']);
  $member_project_title = clean($_GET['member_proj_title']);
  $member_url = "<a href='http://localhost/project/$member_proj_id'>$member_project_title</a>";

  try{
      $conn = new PDO($dsn, $user, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $q = $conn->prepare($member_sql);
      $q->execute(array($member_project_id));
      $member_data = $q->fetchAll();
  } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
  }
}
$member_count = count($member_data);
?>
<span id="member_count"><?php echo $member_count;?> members  <?php echo $member_url?></span>
<?php
$i = 0; //member counter
if($_GET['display'] == 'full'){
  foreach($member_data as $member){
    echo "<a href='http://localhost/user/{$member['u.id']}' data-label='{$member['u.username']}' class='member_pic' id='member_$i'>
      <img class='member_pic' src='{$member['u.pic']}'></a>";
    $i++;
    if($i>28)
      break;
  }
}
else{
  shuffle($member_data);
  foreach($member_data as $member){
    echo "<a href='http://localhost/user/{$member['u.id']}' data-label='{$member['u.username']}' class='member_pic' id='member_$i'>
      <img class='member_pic' src='{$member['u.pic']}'></a>";
    $i++;
    if($i>4)
      break;
  }
}
?>
