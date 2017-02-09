<?php include('mysql_connect.php');
include('data_sanitizers.php');

if(is_null($_GET['subs_proj_id'])){
  $subscriber_sql = "SELECT u.id, u.username, u.pic FROM idea_subscribers as is
                     JOIN users as u on u.id = is.user_id
                     WHERE is.idea_id = ?";

  $subs_id = clean($_GET['subs_idea_id']);
  $subs_idea_title = clean($_GET['subs_idea_title']);
  $subs_url = "<a href='http://localhost/idea/$subs_id'>$subs_idea_title</a>";

  try{
      $conn = new PDO($dsn, $user, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $q = $conn->prepare($subscriber_sql);
      $q->execute(array($subs_id));
      $subscriber_data = $q->fetchAll();
  } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
  }
}
else{
  $subscriber_sql = "SELECT u.id, u.username FROM project_subscribers as ps
                     JOIN users as u on u.id = ps.user_id
                     WHERE ps.project_id = ?";

  $subs_id = clean($_GET['subs_proj_id']);
  $subs_proj_title = clean($_GET['subs_proj_title']);
  $subs_url = "<a href='http://localhost/project/$subs_id'>$subs_proj_title</a>";
  try{
      $conn = new PDO($dsn, $user, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $q = $conn->prepare($subscriber_sql);
      $q->execute(array($subs_id));
      $subscriber_data = $q->fetchAll();
  } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
  }
}
$subscriber_count = count($subscriber_data);
?>
<span id="subscriber_count">There are <?php echo $subscriber_count;?> people subscribed to <?php echo $subs_url?></span>
<?php
$i = 0; //subscriber counter
if($_GET['display'] == 'full'){
  foreach($subscriber_data as $subscriber){
    echo "<a href='http://localhost/user/{$subscriber['u.id']}' data-label='{$subscriber['u.username']}' class='subscriber_pic' id='subscriber_$i'>
      <img class='subscriber_pic' src='{$subscriber['u.pic']}'></a>";
    $i++;
    if($i>28)
      break;
  }
}
else{
  shuffle($subscriber_data);
  foreach($subscriber_data as $subscriber){
    echo "<a href='http://localhost/user/{$subscriber['u.id']}' data-label='{$subscriber['u.username']}' class='subscriber_pic' id='subscriber_$i'>
      <img class='subscriber_pic' src='{$subscriber['u.pic']}'></a>";
    $i++;
    if($i>4)
      break;
  }
}
?>
