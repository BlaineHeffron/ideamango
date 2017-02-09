<?php include('mysql_connect.php');
try {
	  $dbh = new PDO($dsn, $user, $pass);
	  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

function clean($elem) //This function cleans user input for the get variables.
{
  if(!is_array($elem))
    $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
  else
    foreach ($elem as $key => $value)
      $elem[$key] = $this->clean($value);
  return $elem;
}

function explode_filtered_empty($var) {
  if ($var == "")
    return(false);
  return(true);
}

function explode_filtered($delimiter, $str) {
  $parts = explode($delimiter, $str);
  return(array_filter($parts, "explode_filtered_empty"));
}


function insert_get_id($pdo,$table,$columns,$values){

  if(is_Array($columns))
  {
		$colstr1 = "";
		$colstr2 = "";
		$data = array();
		$i = 0;
    foreach($columns as $column){

			$colstr1 .= "$column, ";
			$colstr2 .= ":$column, ";
			$data[":$column"] = $values[$i];
			$i+=1;
		}
		$colstr1 = substr($colstr1,0,-2);
		$colstr2 = substr($colstr2,0,-2);
	}
  else
  {
		$colstr1 = $columns;
		$colstr2 = ":$columns";
		$data[":$columns"]=$values;
	}
  try
  {
		$str = "INSERT INTO $table ($colstr1) VALUES ($colstr2) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)";
		echo  "<p>$str </p>";
		$sth = $pdo->prepare($str);
		$sth->execute($data);
		return $pdo->lastInsertId();
  }catch(PDOException $e)
  {
		echo '<p>ERROR: ' . $e->getMessage() . '</p>';
		return 0;
	}

}



$job_titles       = clean($_POST['job_title']);
$job_descriptions = clean($_POST['job_desc']);
$job_tags         = clean($_POST['job_tags']);
$contact_id       = clean($_POST['job_contact']);
$proj_id          = clean($_POST['proj_id']);
$job_locs         = clean($_POST['job_loc']);



//First update the existing jobs.
$j = 0; //job counter

if(!empty($_POST['job_id'])){
  $job_ids = clean($_POST['job_id']);
  foreach($job_ids as $id){
    $has_money = !empty($_POST['money_job_'.$j]);
    $job_update_sql = "UPDATE jobs
    SET title=?, description=?, bool_paid=?, proj_id=?, contact_id=?
    WHERE id=?";
    try{
      $q = $dbh->prepare($job_update_sql);
      $q->execute(array($job_titles[$j],$job_descriptions[$j],$has_money,$proj_id,$contact_id));
    } catch(PDOException $e){
      echo 'ERROR: ' . $e->getMessage();
    }

    //Insert tags into database with associated job_id.
    $job_tag_array = explode_filtered(",", $job_tags[$j]);
    $job_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $job_tag_array);
    $job_tag_array = array_map('trim',$job_tag_array);
    foreach($job_tag_array as $job_tag)
    {
      if($job_tag != "")
      {
        $job_tag_id = insert_get_id($dbh,'tags','name',$job_tag);
        $job_tag_columns = array('job_id','tag_id');
        $job_tag_vals = array($id,$job_tag_id);
        insert_get_id($dbh,'job_tags',$job_tag_columns,$job_tag_vals);
      }
    }
    //Insert locations into database with associated location_id.
    $sql_loc_columns = array('job_id','location_id');
    foreach($job_locs[($j+1)] as $loc_id){
      $sql_loc_values = array($id,$loc_id);
      insert_get_id($dbh,'job_locations',$sql_loc_columns,$sql_loc_values);
    }
    $j++;
  }
}

//Now we add the new jobs
while(!empty($job_titles[$j])){
  //Insert the new job information into jobs.
  $has_money = !empty($_POST['money_job_'.$j]);
  $job_sql_inputs = array('title','description','bool_paid','proj_id','contact_id');
  $job_sql_values = array($jobtitles[$j],$job_descriptions[$j],$has_money,$proj_id,$contact_id);
  $new_job_id = insert_get_id($dbh,'jobs',$job_sql_inputs,$job_sql_values);

  //Insert tags into database with associated job_id.
  $job_tag_array = explode_filtered(",", $job_tags[$j]);
  $job_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $job_tag_array);
  $job_tag_array = array_map('trim',$job_tag_array);
  foreach($job_tag_array as $job_tag)
  {
    if($job_tag != "")
    {
      $job_tag_id = insert_get_id($dbh,'tags','name',$job_tag);
      $job_tag_columns = array('job_id','tag_id');
      $job_tag_vals = array($new_job_id,$job_tag_id);
      insert_get_id($dbh,'job_tags',$job_tag_columns,$job_tag_vals);
    }
  }

  //Insert locations into database with associated location_id.
  $sql_loc_columns = array('job_id','location_id');
  foreach($job_locs[($j+1)] as $loc_id){
    $sql_loc_values = array($new_job_id,$loc_id);
    insert_get_id($dbh,'job_locations',$sql_loc_columns,$sql_loc_values);
  }
  $j++;
}
$dbh = null;




















  //Insert the tags into job_tags.
  $job_tag_str = $job_tags[$j];
	$proj_job_tags = explode_filtered(",", $user_job_tag_str);
	$proj_job_tags = preg_replace("/[^A-Za-z0-9?!\s]/","", $proj_job_tags);
	$proj_job_tags = array_map('trim',$proj_job_tags);
  foreach($proj_job_tags as $job_tag)
  {
    if($job_tag != "")
    {
			$job_tag_id = insert_get_id($dbh,'tags','name',$job_tag);
			$job_tag_columns = array('job_id','tag_id');
			$job_tag_vals = array($job_id,$job_tag_id);
			insert_get_id($dbh,'job_tags',$job_tag_columns,$job_tag_id);
		}
