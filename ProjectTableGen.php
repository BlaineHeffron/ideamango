<?php
include('mysql_connect.php');
include('data_sanitizers.php');

session_start();
$table = 'projects';
$num_entries = 10;



//This function will create a query that selects $num_entries rows from $table selecting for $tags (an array of tags) in tags field and selecting rows created within $num_days days of the request date and orders by $sort (likes, views, datecreated)
function project_feed_query($table,$sort,$num_entries,$num_days,$tags)
{
  $tag_string = '';
  $col_str = '';
  if($tags[0] != '')
  {
    foreach($tags as $tag)
    {
      $tag_string .= "t.name = '" . $tag . "' AND ";
    }
    $tag_string = substr($tag_string,0,-5);
  }
  if($tags != '')
    $sql_query = "SELECT DISTINCT(p.id), u.username, u.id, p.creation_date, p.title, p.short_desc, p.pic, p.likes, p.views, p.id, t.name, p.has_location
    FROM $table AS p
    JOIN users as u ON u.id = p.manager_id
    JOIN project_tags as pt ON pt.project_id = p.id
    JOIN tags as t ON t.id = pt.tag_id
    WHERE p.creation_date > DATE_SUB(CURDATE(), INTERVAL $num_days DAY) AND ({$tag_string})
    ORDER BY p.$sort
    LIMIT $num_entries";
	else
		$sqlquery = "SELECT u.username, u.id, p.creation_date, p.title, p.short_desc, p.pic, p.likes, p.views, p.id, p.has_location
    FROM $table AS p
    JOIN users as u ON u.id = p.manager_id
    WHERE p.creation_date > DATE_SUB(CURDATE(), INTERVAL $num_days DAY)
    ORDER BY p.$sort
    LIMIT $num_entries";

	return $sqlquery;
}

if(empty($_GET['sort'])) //Test if the get variables are specified in the url, if not defaults to sort by likes within past week.
	$sort = 'likes';
else
 	$sort = clean($_GET['sort']);
if(empty($_GET['time']))
	$num_days = 30;
else
	$num_days = clean($_GET['time']);


if(!empty($_GET['tag_str'])){
	$user_tag_str = $_GET['tag_str'];
	$tag_array = explode_filtered("+", $user_tag_str);
	$tag_array = preg_replace("/[^A-Za-z0-9?!\s]/","", $tag_array);
	$tag_array = array_map('trim',$tag_array);
	$_SESSION['project_tags'][] = $tag_array;
}
if(!empty($_GET['insert_bool']))
	$insert_bool = 1;
else
	$insert_bool = 0;


if(!isset($_SESSION['project_tags']))
	$tags = '';
else{
	$tags = $_SESSION['project_tags'];
	$num_tabs = count($tags)+1;
}

try {
	  $conn = new PDO($dsn, $user, $pass);
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}
if($insert_bool === 1){
	$sql = project_feed_query($table,$sort,$num_entries,$num_days,$tag_array);
	try{
    $data = $conn->query($sql);
		echo '<ol>';
    foreach($data as $row){
			include('ProjectBar.php');
		}

		echo '</ol>';
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
	$conn = null;

}
else{
	$j=0;
	echo '<div id="tabs">
		<ul id = "tab_list">
		  <li><a href="#tabs-1">All</a><span class="ui-icon ui-icon-close">Remove Tab</span></li>';
	if($tags != ''){
		while($j<($num_tabs-1)){
			$tag_head = '';
			if(is_array($tags[$j])){
				foreach($tags[$j] as $tag_name){
					$tag_head.=$tag_name . ' + ';
				}
				$tag_head = substr($tag_head,0,-3);
			}
			else
				$tag_head = $tags[$j];
			echo '<li><a href="#tabs-'.($j+2).'">'.$tag_head.'</a><span class="ui-icon ui-icon-close">Remove Tab</span></li>';
			$j++;
		}
		echo '</ul>
			 <div id ="tabs-1">';
		$sql = project_feed_query($table,$sort,$num_entries,$num_days,'');
		try{
			echo '<ol>';
			$data = $conn->query($sql);
	  foreach($data as $row){
			include('ProjectBar.php');
		}

			echo '</ol>';
		} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
		}

		echo '</div>';

		$j = 0;

		while($j<($num_tabs-1)){
			$i = 1;
			$sql = project_feed_query($table,$sort,$num_entries,$num_days,$tags[$j]);
			try{
				$data = $conn->query($sql);
				echo '<div id="tabs-'.($j+2).'">
					<ol>';
		    foreach($data as $row){
			    include('ProjectBar.php');
		    }

				echo '</ol>';
			} catch(PDOException $e) {
				echo 'ERROR: ' . $e->getMessage();
			}
			echo '</div>';
			$j++;
		}
	}
	else{
		echo '</ul><div id="tabs-1"><ol>';
		$sql = project_feed_query($table,$sort,$num_entries,$num_days,'');
		$i =1;
		try{
			$data = $conn->query($sql);
		  foreach($data as $row){
			  include('ProjectBar.php');
		  }

			echo '</ol>';
		}catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		echo '</div>';
	}

	echo '</div>';

	$conn = null;
}
?>
