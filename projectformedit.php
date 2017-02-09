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
if(!empty($_POST['motivation_edit'])){
  $insert_data[":motivation"] = clean($_POST['motivation_edit']);
  $insert_keys[] = 'motivation = :motivation';
}
if(!empty($_POST['news_edit'])){
  $insert_data[":news"] = clean($_POST['news_edit']);
  $insert_keys[] = 'news = :news';
}
if(!empty($_POST['goals_edit'])){
  $insert_data[":goals"] = clean($_POST['goals_edit']);
  $insert_keys[] = 'goals = :goals';
}
if(!empty($_POST['obstacles_edit'])){
  $insert_data[":obstacles"] = clean($_POST['obstacles_edit']);
  $insert_keys[] = 'obstacles = :obstacles';
}
if(!empty($_POST['header_1_edit'])){
  $insert_data[":header_1"] = clean($_POST['header_1_edit']);
  $insert_keys[] = 'header_1 = :header_1';
}
if(!empty($_POST['header_2_edit'])){
  $insert_data[":header_2"] = clean($_POST['header_2_edit']);
  $insert_keys[] = 'header_2 = :header_2';
}
if(!empty($_POST['header_3_edit'])){
  $insert_data[":header_3"] = clean($_POST['header_3_edit']);
  $insert_keys[] = 'header_3 = :header_3';
}
if(!empty($_POST['header_4_edit'])){
  $insert_data[":header_4"] = clean($_POST['header_4_edit']);
  $insert_keys[] = 'header_4 = :header_4';
}
if(!empty($_POST['header_5_edit'])){
  $insert_data[":header_5"] = clean($_POST['header_5_edit']);
  $insert_keys[] = 'header_5 = :header_5';
}



$key_list = implode(", ",$insert_keys);
$insert_data[':id'] = clean($_POST['idea_edit_id']);
try{
  $sql_update = "UPDATE ideaects SET $key_list WHERE id=:id";
  $sth = $dbh->prepare($sql_update);
  $sth->execute($insert_data);
} catch(PDOException $e){
  echo 'ERROR: ' . $e->getMessage();
}
if(!empty($_POST['tag_edit'])){
  $tag_str = clean($_POST['tag_edit']);
  $proj_tag_array = explode_filtered(",", $tag_str);
  $proj_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $proj_tag_array);
  $proj_tag_array = array_map('trim',$proj_tag_array);
  foreach($proj_tag_array as $project_tag)
  {
    if($project_tag != "")
    {
			$new_tag_id = insert_get_id($dbh,'tags','name',$project_tag);
			$proj_tags_cols = array('tag_id','project_id');
			$proj_tag_vals = array($new_tag_id,$insert_data[':id']);
			insert_get_id($dbh,'project_tags',$proj_tags_cols,$proj_tag_vals);
		}
	}
  $insert_keys[] = 'tag = :tag';
}
$dbh = null;
?>
