<?php include('mysql_connect.php');
include('data_sanitizers.php');
include('insert_get_id.php');
try {
	  $dbh = new PDO($dsn, $user, $pass);
	  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);


if(!empty($_POST['title_edit'])){
  $insert_data[":title"] = clean($_POST['title_edit']);
  $insert_keys[] = 'title = :title';
}
if(!empty($_POST['short_desc_edit'])){
  $insert_data[":short_desc"] = clean($_POST['short_desc_edit']);
  $insert_keys[] = 'short_desc = :short_desc';
}
if(!empty($_POST['detailed_desc_edit'])){
  $insert_data[":detailed_desc"] = clean($_POST['detailed_desc_edit']);
  $insert_keys[] = 'detailed_desc = :detailed_desc';
}
if(!empty($_POST['purpose_edit'])){
  $insert_data[":motivation"] = clean($_POST['motivation_edit']);
  $insert_keys[] = 'motivation = :motivation';
}
if(!empty($_POST['header_1_edit'])){
  $insert_data[":header_1"] = clean($_POST['header_1_edit']);
  $insert_keys[] = 'header_1 = :header_1';
}
if(!empty($_POST['header_2_edit'])){
  $insert_data[":header_2"] = clean($_POST['header_2_edit']);
  $insert_keys[] = 'header_2 = :header_2';
}

$key_list = implode(", ",$insert_keys);
$insert_data[':id'] = clean($_POST['idea_edit_id']);
try{
  $sql_update = "UPDATE ideas SET $key_list WHERE id=:id";
  $sth = $dbh->prepare($sql_update);
  $sth->execute($insert_data);
} catch(PDOException $e){
  echo 'ERROR: ' . $e->getMessage();
}
if(!empty($_POST['tag_edit'])){
  $tag_str = clean($_POST['tag_edit']);
  $idea_tag_array = explode_filtered(",", $tag_str);
  $idea_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $idea_tag_array);
  $idea_tag_array = array_map('trim',$idea_tag_array);
  foreach($idea_tag_array as $idea_tag)
  {
    if($idea_tag != "")
    {
			$new_tag_id = insert_get_id($dbh,'tags','name',$idea_tag);
			$idea_tags_cols = array('tag_id','idea_id');
			$idea_tag_vals = array($new_tag_id,$insert_data[':id']);
			insert_get_id($dbh,'idea_tags',$idea_tags_cols,$idea_tag_vals);
		}
	}
}

$dbh = null;
?>
