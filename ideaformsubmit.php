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


$idea_title = clean($_POST['title']);
$idea_short_desc = clean($_POST['short_desc']);
if(!empty($_POST['detailed_desc']))
	$idea_desc = clean($_POST['detailed_desc']);
else
	$idea_desc = $idea_short_desc;
if(!isset($_SESSION["user_id"]))
	$idea_author_id = 0;
else
	$idea_author_id = $_SESSION["user_id"];
if(!empty($_POST['purpose']))
	$idea_motivation = clean($_POST['motivation']);
else
	$idea_motivation = null;
if(!empty($_POST["city1"]))
	$has_location = 1;
else
	$has_location = 0;
if(!empty($_POST["idea_pic"]))
	$pic_url = clean($_POST["idea_pic"]);
else
	$pic_url = null;

$idea_fields = array('creation_date','author_id','title','short_desc','detailed_desc','purpose','has_location','pic','manager_id');
$idea_values = array('NOW()',$idea_author_id,$idea_title,$idea_short_desc,$idea_desc,$idea_motivation,$has_location,$pic_url,$idea_author_id);
$new_idea_id = insert_get_id($dbh,'ideas',$idea_fields,$idea_values); //Inserts the new idea into ideas and gets  the new idea id.





$j = 1;
$new_location_id = array();
//The following loops through all locations and stores the data in their proper tables.
while(!empty($_POST["city$j"])){
	//Get city, region, country data for first location and store region and country into respective tables and retrieve their Ids.
	$index = $j-1;
	$idea_city = clean($_POST["city$j"]);
	$idea_region = clean($_POST["region$j"]);
	$idea_country = clean($_POST["country$j"]);
	$new_region_id = insert_get_id($dbh,'regions','name',$idea_region);
	$new_country_id = insert_get_id($dbh,'countries','name',$idea_country);

	//Get city data and store with region and country id's into location table. Retrieve location id.
	$loc_values = array($idea_city,$new_country_id,$new_region_id);
	$loc_columns = array('city','country_id','region_id');
	$new_location_id[] = insert_get_id($dbh,'locations',$loc_columns,$loc_values);

	//Store location id along with idea id in idea_locations table.
	$idea_location_columns = array('location_id','idea_id');
	$idea_location_values = array($new_location_id[$index],$new_idea_id);
	$new_idea_loc_id = insert_get_id($dbh,'idea_locations',$idea_location_columns,$idea_location_values);
	$j++;
}



if(!empty($_POST['idea_tags']))
{
	$user_tag_str = $_POST['idea_tags'];
	$idea_tag_array = explode_filtered(",", $user_tag_str);
	$idea_tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $idea_tag_array);
	$idea_tag_array = array_map('trim',$idea_tag_array);
  foreach($idea_tag_array as $idea_tag)
  {
    if($idea_tag != "")
    {
			$new_tag_id = insert_get_id($dbh,'tags','name',$idea_tag);
			$idea_tags_cols = array('tag_id','idea_id');
			$idea_tag_vals = array($new_tag_id,$new_idea_id);
			insert_get_id($dbh,'idea_tags',$idea_tags_cols,$idea_tag_vals);
		}
	}

}


$dbh = null;
header("Location: http://localhost/ideas.php");
?>
