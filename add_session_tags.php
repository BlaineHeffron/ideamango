<?php
//sanitize the tag string here and use the + delimiters to make an array, store as $tagString array. $host = "localhost"; Host Name
$port = '3306'; //Default MySQL Port
$dbname = "ideabase"; //Database Name
$user = "root"; //MySQL Username
$pass = "du7%E)49Rq;2v"; //MySQL Password


$dsn = "mysql:host=$host;port=$port;dbname=$dbname";
$table = "ideas";
$numentries = 10;
function createquery($table,$sort,$numentries,$numdays,$tags)
{
	$tagstring = '';
	foreach($tags as $tag){
		$tagstring .= "tags LIKE '%" . $tag . "%' AND ";
	}
	$tagstring = substr($tagstring,0,-5);
	if(strlen($tagstring)>1){
		$sqlquery = "SELECT id, creationdate, author, title, LEFT($table.desc,50) short_desc, proj, likes, views, tags, pic from $table WHERE creationdate > DATE_SUB(CURDATE(), INTERVAL $numdays DAY) AND ({$tagstring}) ORDER BY $sort LIMIT $numentries";
	}
	else{
		$sqlquery = "SELECT id, creationdate, author, title, LEFT($table.desc,50) short_desc, proj, likes, views, tags, pic from $table WHERE creationdate > DATE_SUB(CURDATE(), INTERVAL $numdays DAY) ORDER BY $sort LIMIT $numentries";
	}

	return $sqlquery;


}

function explode_filtered_empty($var) {
  if ($var == "")
    return(false);
  return(true);
}
function clean($elem) //This function cleans user input for the get variables.
{
    if(!is_array($elem))
        $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
    else
        foreach ($elem as $key => $value)
            $elem[$key] = $this->clean($value);
	return $elem;
}
function explode_filtered($delimiter, $str) {
  $parts = explode($delimiter, $str);
  return(array_filter($parts, "explode_filtered_empty"));
}
$usertagstr = clean($_GET['tagstr']);
$tagarray = explode_filtered("+", $usertagstr);
$tagarray = preg_replace("/[^A-Za-z0-9]/", "", $tagarray);

if(empty($_GET['sort'])) //Test if the get variables are specified in the url, if not defaults to sort by likes within past week.
	$sort = 'likes';
else
       	$sort = clean($_GET['sort']);
if(empty($_GET['time']))
	$numdays = 1;
else
	$numdays = clean($_GET['time']);
session_start();
$_SESSION['tags'][] = $tagarray;


$numtabs = count($tags)+1;

try {
	  $conn = new PDO($dsn, $user, $pass);
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}

$sql = createquery($table,$sort,$numentries,$numdays,$tagarray);
$data = $conn->query($sql);
$i=1;
echo '<ol>';
foreach($data as $row){
	echo '<li id="idea'.$i.'"><span class="vote"></span><span class="ideapic"><img src="data:image/jpeg;base64,' . base64_encode( $row['pic'] ). '" /></span><div class = "ideacontent"><h1>'.$row['title'].'</h1><p>'.$row['short_desc'].'</p></div><span class="addbutton"></span><span class="turnintoproj"></span><span class="buttons"></span></li>';
	$i++;
}


echo '</ol>';


$conn = null;



?>

