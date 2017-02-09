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


$proj_title = clean($_POST['title']);
$proj_short_desc = clean($_POST['short_desc']);
if(!empty($_POST['detailed_desc']))
	$proj_desc = clean($_POST['detailed_desc']);
else
	$proj_desc = $proj_short_desc;
if(!isset($_SESSION["user_id"]))
	$proj_author_id = 0;
else
	$proj_author_id = $_SESSION["user_id"];
if(!empty($_POST['motivation']))
	$proj_motivation = clean($_POST['motivation']);
else
	$proj_motivation = null;
$j=1;
$has_job=0;
$has_money=0;
while(!empty($_POST["job_title$j"])){
	$has_job=1;
	if(!empty($_POST["money_job$j"]))
		$has_money=1;
	$j++;
}
if(!empty($_POST["city1"]))
	$has_location = 1;
else
	$has_location = 0;
if(!empty($_POST["proj_pic"]))
	$pic_url = clean($_POST["proj_pic"]);
else
	$pic_url = null;

$proj_fields = array('creation_date','author_id','title','short_desc','detailed_desc','motivation','has_location','bool_jobs','bool_money','pic','manager_id');
$proj_values = array('NOW()',$proj_author_id,$proj_title,$proj_short_desc,$proj_desc,$proj_motivation,$has_location,$has_job,$has_money,$pic_url,$proj_author_id);
$new_project_id = insert_get_id($dbh,'projects',$proj_fields,$proj_values); //Inserts the new project into projects and gets  the new project id.





$j = 1;
$new_location_id = array();
//The following loops through all locations and stores the data in their proper tables.
while(!empty($_POST["city$j"])){
	//Get city, region, country data for first location and store region and country into respective tables and retrieve their Ids.
	$index = $j-1;
	$proj_city = clean($_POST["city$j"]);
	$proj_region = clean($_POST["region$j"]);
	$proj_country = clean($_POST["country$j"]);
	$new_region_id = insert_get_id($dbh,'regions','name',$proj_region);
	$new_country_id = insert_get_id($dbh,'countries','name',$proj_country);

	//Get city data and store with region and country id's into location table. Retrieve location id.
	$loc_values = array($proj_city,$new_country_id,$new_region_id);
	$loc_columns = array('city','country_id','region_id');
	$new_location_id[] = insert_get_id($dbh,'locations',$loc_columns,$loc_values);

	//Store location id along with project id in project_locations table.
	$proj_location_columns = array('location_id','project_id');
	$proj_location_values = array($new_location_id[$index],$new_project_id);
	$new_proj_loc_id = insert_get_id($dbh,'project_locations',$proj_location_columns,$proj_location_values);
	$j++;
}



if(!empty($_POST['project_tags']))
{
	$user_tag_str = $_POST['project_tags'];
	$proj_tag_array = explode_filtered(",", $user_tag_str);
	$proj_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $proj_tag_array);
	$proj_tag_array = array_map('trim',$proj_tag_array);
  foreach($proj_tag_array as $project_tag)
  {
    if($project_tag != "")
    {
			$new_tag_id = insert_get_id($dbh,'tags','name',$project_tag);
			$proj_tags_cols = array('tag_id','project_id');
			$proj_tag_vals = array($new_tag_id,$new_project_id);
			insert_get_id($dbh,'project_tags',$proj_tags_cols,$proj_tag_vals);
		}
	}

}
$k = 1;
while(!empty($_POST["job_title$k"]))
{
	$proj_job_title = clean($_POST["job_title$k"]);
	$proj_job_desc = clean($_POST["job_desc$k"]);
	$bool_paid = !empty($_POST["money_job$k"]);
	$job_vals = array($proj_job_title,$proj_job_desc,$bool_paid,$proj_author_id,$new_project_id);
	$job_cols = array('title','description','bool_paid','contact_id','proj_id');
	$job_id = insert_get_id($dbh,'jobs',$job_cols,$job_vals);

	$user_job_tag_str = $_POST["job_tags$k"];
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

	}
	$job_loc_cols= array('location_id','job_id');
  for($n=0;$n<count($new_location_id);$n++)
  {
		$x = $n+1;
    if(isset($_POST["job_{$k}_loc_$x"]))
    {
			$job_loc_vals = array($new_location_id[$n],$job_id);
			insert_get_id($dbh,'job_locations',$job_loc_cols,$job_loc_vals);
		}
	}
	$k++;
}
$dbh = null;
header("Location: http://localhost/ideas.php");
?>
